<?php

/*
 * @Author Amit Pandey
 * D < 5 => 100
 * 5 < D < 10 => 50
 * D > 10 => 20
 * Recently freed => 50
 * Star CG => 40
 * Response Accepted by CG => 30
 * Smart Phone => 20
 * Training Date => 10
 * Flagged => 20
 */

namespace App\Services\Domain;

use App\Models\ORM\Lead;
use App\Models\ORM\LeadVendor;
use App\Models\ORM\Patient;
use App\Models\ORM\Locality;
use App\Models\User;
use App\Models\ORM\PreferredCaregiver;
use App\Models\ORM\CaregiverAutoAttendance;
use App\Models\DTO\LocalityDTO;
use App\Services\Domain\VendorDomainService;
use App\Services\Domain\OperationDomainService;
use GuzzleHttp\Client;
use Carbon\Carbon;

const DTMF_CALL_PWD = 'vishal@jain';

class CaregiverDomainService {

    public function populateCaregivers($leadId) {
        $lead = Lead::find($leadId);
        if ($lead != null) {
            $patient = $lead->patient;
            $patient_gender = $patient->genderItem->id; //id or label
            $patient_shift = $patient->shift->id;
            $localityDto = new LocalityDTO();
            $lead_location = $localityDto->convertToDto($lead->locality)->json->geometry->location; //lat & lng

            $caregivers = User::where('user_type_id', 2)
                            ->where('available', '1')
                            ->where('available_date', '<=', \DB::raw('NOW()'))
                            ->where('gender', $patient_gender)
                            ->where('preferred_shift_id', $patient_shift)
                            ->where('locality_id', '<>', '')
                            ->where('locality_id', '>', 0)
                            ->join('user_vendors', 'user_vendors.user_id', '=', 'users.id')
                            ->join('vendor_availabilities', 'vendor_availabilities.vendor_id', '=', 'user_vendors.id')
                            ->select('users.id', 'name', 'email', 'phone', 'gender', 'locality_id', 'user_vendors.preferred_shift_id', 'has_smart_phone', 'training_attended', 'is_flagged')
                            ->orderBy('name')
                            ->distinct()->get();
            $filtered_cgs = [];
            PreferredCaregiver::where('lead_id', '=', $leadId)->delete();
            foreach ($caregivers as $caregiver) {
                $points = 0.00;
                $custom_json = [];
                $locality = Locality::find($caregiver->locality_id);
                if ($locality != null) {
                    $cg_location = $localityDto->convertToDto($locality)->json->geometry->location; //lat & lng
                    $distance = $this->distanceCalculation($lead_location->lat, $lead_location->lng, $cg_location->lat, $cg_location->lng);
                    if ($distance <= 5.00) {
                        $points += 100;
                    } else if ($distance > 5 && $distance <= 10) {
                        $points += 50;
                    } else if ($distance > 10.00) {
                        $points += 20;
                    }

                    if ($caregiver->is_flagged == '1' || $caregiver->is_flagged == 1) {
                        $points += 20;
                    }

                    if ($caregiver->has_smart_phone == '1' || $caregiver->has_smart_phone == true) {
                        $points += 20;
                    }
                    if ($caregiver->training_attended == '1' || $caregiver->training_attended == true) {
                        $points += 10;
                    }

                    if ($patient->mobility_id && $patient->mobility_id !== null) {
                        $lead_ids = LeadVendor::where('assignee_user_id', $caregiver->id)->get()->pluck('lead_id');
                        if (count($lead_ids) > 0) {
                            $patient_count = Patient::whereIn('lead_id', $lead_ids)->where('mobility_id', $patient->mobility_id)->get()->count();
                            if ($patient_count > 0 && $patient_count <= 3) {
                                $points += 5;
                            } else if ($patient_count > 3 && $patient_count <= 10) {
                                $points += 10;
                            } else if ($patient_count > 10) {
                                $points += 15;
                            }
                        }
                    }
                    //$preferred_caregiver = new PreferredCaregiver;
                    //$preferred_caregiver->user_id = $caregiver->id;
                    //$preferred_caregiver->lead_id = $leadId;
                    //$preferred_caregiver->points = $points;
                    //$preferred_caregiver->status_id = 1;
                    //$preferred_caregiver->save();
                    $custom_json['user_id'] = $caregiver->id;
                    $custom_json['lead_id'] = $leadId;
                    $custom_json['points'] = $points;
                    $custom_json['status_id'] = 1;
                    array_push($filtered_cgs, $custom_json);
                }
            }
            $top_cgs = collect($filtered_cgs)->sortByDesc('points')->take(15);
            //echo $top_cgs[0]['points'];
            //die();
            foreach ($top_cgs as $top_cg) {
                PreferredCaregiver::create(['user_id' => $top_cg['user_id'], 'lead_id' => $top_cg['lead_id'],
                    'points' => $top_cg['points'], 'status_id' => 1]);
            }
            return true;
        }
    }

    public function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 2) {
        // Calculate the distance in degrees
        $degrees = rad2deg(acos((sin(deg2rad($point1_lat)) * sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat)) * cos(deg2rad($point2_lat)) * cos(deg2rad($point1_long - $point2_long)))));

        // Convert the distance in degrees to the chosen unit (kilometres, miles or nautical miles)
        switch ($unit) {
            case 'km':
                $distance = $degrees * 111.13384; // 1 degree = 111.13384 km, based on the average diameter of the Earth (12,735 km)
                break;
            case 'mi':
                $distance = $degrees * 69.05482; // 1 degree = 69.05482 miles, based on the average diameter of the Earth (7,913.1 miles)
                break;
            case 'nmi':
                $distance = $degrees * 59.97662; // 1 degree = 59.97662 nautic miles, based on the average diameter of the Earth (6,876.3 nautical miles)
        }
        return round($distance, $decimals);
    }

    public function getPreferredCaregivers($leadId) {
        $preferred_caregivers = PreferredCaregiver::where('lead_id', $leadId)
                        ->where('status_id', 1)
                        ->orderBy('points', 'desc')
                        ->limit(10)->get();
        return $preferred_caregivers;
    }

    public function getPreferredCaregiversIds($leadId) {
        return PreferredCaregiver::getPreferredCaregiversIds($leadId);
    }

    public function deactiveAutoCaregiver($leadId, $userId) {
        $preferred_caregivers = PreferredCaregiver::where('lead_id', $leadId)
                        ->where('user_id', $userId)
                        ->where('status_id', 1)
                        ->orderBy('points', 'desc')
                        ->limit(1)->get();
        if ($preferred_caregivers && $preferred_caregivers->first()) {
            $preferred_caregiver = $preferred_caregivers->first();
            $preferred_caregiver->status_id = 0;
            $preferred_caregiver->save();
        }
    }

    public function autocallCaregiver() {
        $current_time = Carbon::now()->toTimeString();
        $shift_id = [];
        if ($current_time >= '07:29:00' && $current_time < '08:00:00') {
            //12 hours day
            $shift_id = [1];
            $time_slot = '07:30AM';
        } elseif ($current_time >= '09:59:00' && $current_time < '11:00:00') {
            // 24 hours or both or any shift
            $shift_id = [3, 4, 5];
            $time_slot = '10:00AM';
        } elseif ($current_time >= '18:29:00' && $current_time < '19:00:00') {
            // 12 hours night
            $shift_id = [2];
            $time_slot = '06:30PM';
        }
        if ($shift_id) {
            $vendorDomainService = new VendorDomainService;
            $vendorDeployedStatusMapped = $vendorDomainService->getDeployedVendor();
            foreach ($vendorDeployedStatusMapped as $key => $value) {
                try {
                    if ($value['leads'][0] && $value['leads'][0]->patient()->get()->first()) {
                        $patient_shift_id = $value['leads'][0]->patient()->get()->first()->shift_id;
                        if (in_array($patient_shift_id, $shift_id)) {
                            if ($value['info'] && $value['info']->phone) {
                                $lead_id = $value['leads'][0]->id;
                                $caregiver_name = $value['info']->name;
                                $mobile = $value['info']->phone;
                                $user_id = $value['info']->id;
                                $client = new Client();
                                $res = $client->request('GET', 'http://voice.vrinfosoft.co.in/unified.php?usr=18&pwd=' . DTMF_CALL_PWD . '&type=dtmf&obdid=18&ph=' . $mobile);
                                if ($res) {
                                    $response_id = explode(' ', $res->getBody())[0];
                                    CaregiverAutoAttendance::create(['lead_id' => $lead_id,'user_id' => $user_id, 'caregiver_name' => $caregiver_name,
                                        'mobile' => $mobile, 'status_code' => $res->getStatusCode(), 'reason_phrase' => $res->getReasonPhrase(), 'response' => $res->getBody(), 'time_slot' => $time_slot, 'response_id' => $response_id]);
                                } else {
                                    CaregiverAutoAttendance::create(['lead_id' => $lead_id,'user_id' => $user_id, 'caregiver_name' => $caregiver_name, 'mobile' => $mobile, 'time_slot' => $time_slot, 'reason_phrase' => 'No Response From API']);
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    
                }
            }
        }
    }

    public function autocallCaregiverTesting() {
        $mobiles = ['9650071356', '9650071356', '9650071357'];
        foreach ($mobiles as $mobile) {
            try {
                if ($mobile) {
                    $caregiver_name = 'Amit Pandey';
                    $mobile = $mobile;
                    $user_id = 1;
                    $client = new Client();
                    $res = $client->request('GET', 'http://voice.vrinfosoft.co.in/unified.php?usr=18&pwd=' . DTMF_CALL_PWD . '&type=dtmf&obdid=18&ph=' . $mobile);
                    if ($res) {
                        $response_id = explode(' ', $res->getBody())[0];
                        CaregiverAutoAttendance::create(['user_id' => $user_id, 'caregiver_name' => $caregiver_name,
                            'mobile' => $mobile, 'status_code' => $res->getStatusCode(), 'reason_phrase' => $res->getReasonPhrase(), 'response' => $res->getBody(), 'time_slot' => '09:00AM', 'response_id' => $response_id]);
                        //CaregiverAutoAttendance::create(['user_id' => $user_id,'caregiver_name' => $caregiver_name,'mobile' => $mobile,'time_slot' => $time_slot]);
                    }
                }
            } catch (\Exception $e) {
                
            }
        }
    }

    public function autoAttendanceResponse() {
        $date = Carbon::now()->toDateString();
        $dt = explode('-', $date);
        $attendances = CaregiverAutoAttendance::whereDate('created_at', '=', date('Y-m-d'))->whereNull('dtmf_input')->get();
        foreach ($attendances as $attendance) {
            try {
                if ($attendance->response_id) {
                    $day = $dt[2];
                    $month = $dt[1];
                    $year = $dt[0];
                    $response_id = $attendance->response_id;
                    $client = new Client();
                    $url = 'http://voice.vrinfosoft.co.in/unified_rep.php?usr=18&pwd='.DTMF_CALL_PWD.'&dlr=obd&msgid='. $response_id . '&day=' . $day . '&mn=' . $month . '&yr=' . $year;
                    $res = $client->request('GET', $url);
                    $res_array = explode('dtmf: ', $res->getBody());
                    if (isset($res_array['1'])) {
                        $dtmf_input = trim($res_array['1']);
                        if ($dtmf_input != '<br>') {                         
                            $attendance->dtmf_input = $dtmf_input;
                            $attendance->save();
                            
                            $operationDomainService = new OperationDomainService;                          
                            if($dtmf_input == "1 <br>"){
                                $present = 1;
                                $status = $operationDomainService->submitCGAttendance($attendance->lead_id,$attendance->user_id,$attendance->created_at,$present,0,'',0,'autodialer');
                            }else{
                                $present = 0;
                            }                         
                            //$status = $operationDomainService->submitCGAttendance($attendance->lead_id,$attendance->user_id,$attendance->created_at,$present,0,'',0,'autodialer');
                            
                        }
                    }
                }
            } catch (\Exception $e) {
               echo  $e;
            }
        }
    }

}
