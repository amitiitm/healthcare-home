<?php

namespace App\Http\Controllers\Rest;

use App\Contracts\Rest\ICommonRestContract;
use App\Contracts\Rest\IOperationRestContract;
use App\Contracts\Rest\IUserRestContract;
use App\Contracts\Rest\IVendorRestContract;
use App\Http\Controllers\Controller;
use App\Models\ORM\Gender;
use App\Models\User;
use App\Models\ORM\LocationZone;
use App\Templates\PRResponse;
use App\Templates\SCResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\ORM\PreferredCaregiver;
use App\Services\Domain\CaregiverDomainService;
use App\Models\ORM\CaregiverAutoAttendance;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use Cache;
class VendorRestController extends Controller
{
    protected $vendorRestServices;

    protected $commonRestServices;

    public function __construct( IVendorRestContract $vendorRestServices, ICommonRestContract $commonRestContract)
    {
        $this->vendorRestServices = $vendorRestServices;
        $this->commonRestServices = $commonRestContract;
    }

    public function getVendorGridData(){
        return Response::json(PRResponse::getSuccessResponse('',$this->vendorRestServices->getVendorGridList(0,0)));
    }
    public function getVendorDeployedStatusList(){
        return Response::json(PRResponse::getSuccessResponse('',$this->vendorRestServices->getVendorDeployedStatusList()));
    }
    public function getVendorSuggestionForLead($leadId,Request $request){
        $filter = $request->input('filter');
        $pageNumber = $request->input('pagenumber');
        $pageSize = $request->input('pagesize');
        //filter auto used when called from auto cg assign screen
        if($filter == 'auto'){
           return Response::json(PRResponse::getSuccessResponse('',$this->vendorRestServices->getVendorGridListByLead($leadId))); 
        }else{
            if($pageNumber && $pageNumber > 0){
                $count = $this->vendorRestServices->getVendorCount();
                $response = PRResponse::getSuccessResponse('',$this->vendorRestServices->getVendorGridList($pageNumber,$pageSize));
                //return Response::json(['data' => $response,'count' => $count]); 
                $response->count = $count;
                return Response::json($response);    
            }else{
                $vendors = Cache::remember('vendors_listing', 120, function () {
                    return $this->vendorRestServices->getVendorGridList(0,0);
                });
                //$vendors = $this->vendorRestServices->getVendorGridList(0,0);
                return Response::json(PRResponse::getSuccessResponse('',$vendors));    
            }
        }
    }
    public function addVendor(){
        $userResponse = $this->vendorRestServices->addVendor(Input::all());
        return Response::json($userResponse);
    }
    public function submitVendor(){
        //d(($this->operationRestS.ervice->submitOnlineLead(Input::all())));
        $responseObj = $this->vendorRestServices->submitVendor(Input::all(),Auth::user()->id);
        return Response::json($responseObj);
    }

    public function getVendorDetail($vendorId){
        $responseObj = $this->vendorRestServices->getVendorDetail($vendorId);
        return Response::json(PRResponse::getSuccessResponse("",$responseObj));
    }
    
    public function updateVendorDetail($vendorId){
        $responseObj = $this->vendorRestServices->updateVendorDetail(Input::all(),$vendorId);
        return Response::json($responseObj);
    }

    public function deleteVendorDetail($vendorId){
        $responseObj = $this->vendorRestServices->deleteVendorDetail($vendorId);
        return Response::json($responseObj);
    }

    public function deleteVendorsDetails(){
        $vendorIds = Input::all('vendorIds');
        $responseObj = $this->vendorRestServices->deleteVendorsDetails($vendorIds);
        return Response::json($responseObj);
    }


    public function getVendorAvailabilityOptions(){
        return Response::json(PRResponse::getSuccessResponse("", $this->vendorRestServices->getVendorAvailabilityOptions()));
    }
    public function getVendorAvailabilityMapper(){
        return Response::json(PRResponse::getSuccessResponse("", array(
            'zones'=> LocationZone::get(),
            'shifts'=>$this->commonRestServices->getShifts()
        )));
    }
    public function getVendorDocumentTypes(){
        return Response::json(PRResponse::getSuccessResponse("",$this->vendorRestServices->getVendorDocumentTypes()));
    }
    public function getVendorTrainingReasons(){
        return Response::json(PRResponse::getSuccessResponse("",$this->vendorRestServices->getVendorTrainingReasons()));
    }

    public function addVendorDocument(){
        $prescriptionDto = $this->vendorRestServices->uploadVendorDocument(Input::file('file'), Input::all());
        if($prescriptionDto){
            return Response::json(PRResponse::getSuccessResponse("",$prescriptionDto));
        }
        return Response::json(PRResponse::getErrorResponse("",false));
    }

    public function updateVendorAvailability($vendorId,CaregiverDomainService $caregiverDomainService){
        $leadId = Input::get('lead_id');
        //TODO update all auto CG with vendor ids
        //check algo needs to reloaded after this action 0 for unavailable and 1 for available
        $vendor = $this->vendorRestServices->updateVendorAvailability($vendorId,Input::all());
        if($leadId && $leadId > 0){
            PreferredCaregiver::where('user_id','=',$vendorId)->update(array('status_id' => 0));
            //$caregiverDomainService->populateCaregivers($leadId);
        }
        return Response::json($vendor);
    }

    public function deleteVendorDocument($vendorDocumentId){
        return Response::json($this->vendorRestServices->deleteVendorDocument($vendorDocumentId));
    }

    public function getVendorTaskDetailGrouped($vendorId){
        return Response::json(PRResponse::getSuccessResponse('',$this->vendorRestServices->getVendorTaskDetailGrouped($vendorId)));
    }

    public function getAvailableVendors(){
        return Response::json(PRResponse::getSuccessResponse('',$this->vendorRestServices->getAvailableVendors()));
    }

    public function changeVendorFlag(){
        $input = Input::all();
        $vendorId = $input['vendorId']; 
        $currentFlag = $input['currentFlag'];
        return Response::json($this->vendorRestServices->changeVendorFlag($vendorId, $currentFlag));
    }
    
    public function caregiverAutoAttendance(){
        return view('admin.vendor.caregiver_auto_attendance');
    }
    public function caregiverAutoAttendanceData(){
        $attendances = CaregiverAutoAttendance::getCaregiverAutoAttendanceData();
        return Datatables::of($attendances)
                ->editColumn('caregiver_name', function ($model) {              
                            return '<a href="/vendor/' . $model->user_id .'" class="edit_link" data-value=' . $model->user_id . '>'.$model->caregiver_name.'</a>';
                        }) 
                ->editColumn('created_at', function ($model) {              
                            return Carbon::parse($model->created_at)->format('Y-m-d');
                        })
                ->make(true);
    }
}