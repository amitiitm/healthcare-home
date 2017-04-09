<?php

namespace App\Http\Controllers\API;

use App\Contracts\Rest\ICommonRestContract;
use App\Contracts\Rest\IUserRestContract;
use App\Http\Controllers\Controller;
use App\Models\ORM\Complaints;
use App\Models\ORM\ComplaintsCategories;
use App\Models\ORM\ComplaintsStatus;
use App\Models\ORM\CgTraining;
use App\Models\ORM\CgReplacement;
use App\Models\ORM\ComplaintLogs;
use App\Contracts\Domain\IOperationDomainContract;
use App\Templates\PRResponse;
use App\Templates\SCResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use App\Contracts\Domain\IUserDomainContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\ORM\ComplaintResolutionGroups;
use App\Models\ORM\ComplaintResolutionGroupsMembers;
use App\Models\DTO\UserMinimalDTO;
use App\Contracts\Rest\IVendorRestContract;
use App\Models\ORM\Incentives;

class ComplaintsAPIController extends Controller
{
    //protected $complaintsRestService;

    protected $commonRestServices;
    protected $operationDomainService;
    protected $userDomainService;
    protected $vendorRestServices;

    public function __construct( ICommonRestContract $commonRestContract, IOperationDomainContract $operationDomainService,IUserDomainContract $userDomainService, IVendorRestContract $vendorRestServices)
    {
        //$this->complaintsRestService = $complaintsRestService;
        $this->commonRestServices = $commonRestContract;
        $this->operationDomainService = $operationDomainService;
        $this->userDomainService = $userDomainService;
        $this->vendorRestServices = $vendorRestServices;
    }

    public function getComplaintsCategories($userType){
        if($userType == 'user'){
            $key = 'for_user';
        } else if($userType == 'cg'){
            $key = 'for_cg';
        }
        $data = ComplaintsCategories::where($key, 1)->where('parent_id', 0)->get();

        $dataArray = array();
        if(!empty($data)){
            $count = 0;
            foreach ($data as $row) {
                $dataArray[$count]['id'] = $row->id;
                $dataArray[$count]['name'] = $row->name;

                $dataIn = ComplaintsCategories::select('id','name')->where($key, 1)->where('parent_id', $row->id)->get();

                $countIn = 0;
                if(!empty($dataIn)){

                    foreach ($dataIn as $rowIn) {
                        $dataArray[$count]['sub_categories'][$countIn]['id'] = $rowIn->id;
                        $dataArray[$count]['sub_categories'][$countIn]['name'] = $rowIn->name;

                        $dataIn = ComplaintsCategories::select('id','name')->where($key, 1)->where('parent_id', $rowIn->id)->get();
                        $dataArray[$count]['sub_categories'][$countIn]['sub_categories_childs'] = $dataIn;

                        $countIn++;
                    }
                }

                //$dataArray[$count]['sub_categories'] = $dataIn;

                $count++;
            }
        }

        return Response::json(PRResponse::getSuccessResponse('',$dataArray));
    }

    public function getComplaintSubCategories($categoryId, $userType){
        if($userType == 'user'){
            $key = 'for_user';
        } else if($userType == 'cg'){
            $key = 'for_cg';
        }
        $data = ComplaintsCategories::where($key, 1)->where('parent_id', $categoryId)->get();

        $dataArray = array();
        if(!empty($data)){
            $count = 0;
            foreach ($data as $row) {
                $dataArray[$count]['id'] = $row->id;
                $dataArray[$count]['name'] = $row->name;
                $count++;
            }
        }
        return Response::json(PRResponse::getSuccessResponse('',$dataArray));
    }

    public function postAddComplaintAdmin(){
        $this->postAddComplaint(1);
        return Response::json(PRResponse::getSuccessResponse('','Success'));
    }

    public function postAddComplaintAPICg(){
        $this->postAddComplaint(2);
        return Response::json(PRResponse::getSuccessResponse('','Success'));
    }

    public function postAddComplaintAPICustomer(){
        $this->postAddComplaint(3);
        return Response::json(PRResponse::getSuccessResponse('','Success'));
    }

    public function postAddComplaint($userType=false){
        $complaints = new Complaints;

        if($userType == 1){
            $complaints->user_id = Input::get('userId');
            $userType = Input::get('userType');
            $complaints->employee_id = Auth::user()->id;
        } else {
            $complaints->user_id = Authorizer::getResourceOwnerId();
            $complaints->employee_id = 0;
        }

        $complaints->vendor_id = $this->operationDomainService->getLatestVendorByLead(Input::get('lead_id'))->id;
        
        $complaintCategory = Input::get('category');

        $complaints->lead_id = Input::get('lead_id');
        $complaints->complaint_category = $complaintCategory;
        $complaints->details = Input::get('details');
        $complaints->user_type = $userType;
        $complaints->status_id = 1;
        $complaints->save();

        $complaintId = $complaints->id;

        $complaint_category_label = ComplaintsCategories::where('id', $complaints->complaint_category)->first()->name;

        $userFetched = $this->userDomainService->getUser($complaints->user_id);

        if($userType == 2){
            $message =urlencode("आपकी समस्या हमने नोट कर ली है। इसका समाधान कर के आपको सूचित किया जाएगा। धन्यवाद");
            $user_type_label = 'CG';
        } else if($userType == 3){
            $message =urlencode("Dear Customer, We have registered your complaint. We are working on it and will revert as soon as possible. Thanks, Pramaticare.");
            $user_type_label = 'Customer';
        }

        // send sms
        $smsUrl = env('SMS_URL');
        $url = 'user='.env('SMS_USER');
        $url.= '&pwd='.env('SMS_PASSWORD');
        $url.= '&senderid='.env('SMS_SENDER_ID');
        $url.= '&mobileno='.$userFetched->phone;
        $url.= '&msgtext='.$message;
        $url.= '&smstype=13';
        $url.= '&dnd=1';
        $url.= '&unicode=0';

        $urlToUse =  $smsUrl.$url;
        $ch = curl_init($urlToUse);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);

        $dateRaw = Carbon::now();

        // incentives and deductions //
        $incentiveAmount = 0;
        $incentiveComment = '';

        if($complaintCategory == 79){
            //overlate
            $incentiveAmount = -100;
            $incentiveComment = 'Late Complaint';
        } else if($complaintCategory == 75){
            //absent
            $incentiveAmount = -250;
            $incentiveComment = 'Uninformed Leave';
        }

        if($incentiveAmount != 0){
            $incentive = new Incentives;
            $incentive->user_id = $complaints->vendor_id;
            $incentive->amount = $incentiveAmount;
            $incentive->type = 'deduction';
            $incentive->comment = $incentiveComment;
            $incentive->lead_id = $complaints->lead_id;
            $incentive->is_active = 0;
            $incentive->date = $dateRaw->toDateString();
            $incentive->save();
        }
        
        // ./ incentives and deductions //


        // complaint and replacement flow & Blacklist Flow
        $vendorRawId = $this->vendorRestServices->getVendorIdByUserId($complaints->vendor_id);
        if((int) $this->operationDomainService->getVendorServiceCount($complaints->vendor_id) > 1){
            // old

            //blacklist on 2nd
            $vendorLeadComplaintCount = Complaints::where('lead_id', $complaints->lead_id)->where('vendor_id',$complaints->vendor_id)->count();
            if($vendorLeadComplaintCount > 1){
                //more than 0 complaint i.e. 2nd
                $this->vendorRestServices->updateVendorAvailability($vendorRawId, array('available'=>0,'option'=>array('id'=>4,'slug'=>'Blacklist')));
            }

            //replacement request
            $vendorLeadComplaintCount = Complaints::where('lead_id', $complaints->lead_id)->where('vendor_id',$complaints->vendor_id)->where('complaint_category',$complaintCategory)->count();
            if($vendorLeadComplaintCount > 1){
                
                

                $cgReplacement = new CgReplacement;
                $cgReplacement->employee_id = $complaints->employee_id;
                $cgReplacement->user_id = $complaints->vendor_id;
                $cgReplacement->lead_id = $complaints->lead_id;
                $cgReplacement->complaint_id = $complaintId;
                $cgReplacement->start_date = $dateRaw->toDateString();
                $cgReplacement->replacement_date = $dateRaw->toDateString();
                $cgReplacement->reason = '';
                $cgReplacement->save();

            }

        } else {
            // new -> blacklist
            $this->vendorRestServices->updateVendorAvailability($vendorRawId, array('available'=>0,'option'=>array('id'=>4,'slug'=>'Blacklist')));
        }



        // send mails to employees
        /*$employeesData = ComplaintsCategories::select('users.id','users.name','users.phone','users.email')                
                //->join('complaint_resolution_groups', 'complaint_resolution_groups.id', '=', 'complaint_resolution_groups_members.group_id')
                ->join('complaint_resolution_groups_members', 'complaints_categories.resolution_group_id', '=', 'complaint_resolution_groups_members.group_id')
                ->join('users', 'users.id', '=', 'complaint_resolution_groups_members.user_id')
                ->where('complaints_categories.id', $complaintCategory)
                ->get();

        
        if(!empty($employeesData)){
            $emails = array();

            foreach ($employeesData as $employee) {
                $emails[] = $employee->email;
            }

            //$emails = ['vishal@pramaticare.com', 'mayur@pramaticare.com', 'robin@pramaticare.com', 'sachin@pramaticare.com', 'simran@pramaticare.com', 'himanshu@pramaticare.com'];
            $bccList = [];
            $ccList = [];

            $mailSubject = 'A new complaint has been raised. Complaint # '.$complaintId;
            $data = ['emailTo'=>$emails, 'subject'=>$mailSubject,'emailCCTo'=>$ccList, 'emailBccTo'=>$bccList];

            Mail::queue('emails.complaint_admin_mail', ['complaint'=>$complaints, 'userInfo'=>$userFetched,'complaint_category_label'=>$complaint_category_label,'user_type_label'=>$user_type_label], function ($m) use ($data) {
                $m->from(env('PHPMAILER_FROM_EMAIL'),  env('PHPMAILER_FROM_NAME'));
                $m->to($data['emailTo'])->bcc($data['emailBccTo'])->cc($data['emailCCTo'])->subject($data['subject']);
            });
        }*/

        $emails = ['vishal@pramaticare.com', 'mayur@pramaticare.com', 'robin@pramaticare.com', 'sachin@pramaticare.com', 'simran@pramaticare.com', 'himanshu@pramaticare.com'];
        $bccList = [];
        $ccList = [];

        $mailSubject = 'A new complaint has been raised. Complaint # '.$complaintId;
        $data = ['emailTo'=>$emails, 'subject'=>$mailSubject,'emailCCTo'=>$ccList, 'emailBccTo'=>$bccList];

        Mail::queue('emails.complaint_admin_mail', ['complaint'=>$complaints, 'userInfo'=>$userFetched,'complaint_category_label'=>$complaint_category_label,'user_type_label'=>$user_type_label], function ($m) use ($data) {
            $m->from(env('PHPMAILER_FROM_EMAIL'),  env('PHPMAILER_FROM_NAME'));
            $m->to($data['emailTo'])->bcc($data['emailBccTo'])->cc($data['emailCCTo'])->subject($data['subject']);
        });

        return true;
    }

    public function getAllComplaints(){
        $data = Complaints::select('complaints.id','complaints.lead_id','complaints.details','complaints.created_at','users.name as user_name','phone','email','complaints_categories.name as category_name','complaints_status.label')
                            ->join('users', 'users.id', '=', 'complaints.user_id')
                            ->join('complaints_categories', 'complaints_categories.id', '=', 'complaints.complaint_category')
                            ->join('complaints_status', 'complaints_status.id', '=', 'complaints.status_id')
                            ->orderBy('complaints.id', 'DESC')
                            ->get();

        /*$dataArray = array();
        if(!empty($data)){
            $count = 0;
            foreach ($data as $row) {
                $dataArray[$count]['id'] = $row->id;
                $dataArray[$count]['name'] = $row->name;

                $dataIn = ComplaintsCategories::select('id','name')->where($key, 1)->where('parent_id', $row->id)->get();
                $dataArray[$count]['sub_categories'] = $dataIn;

                $count++;
            }
        }*/

        return Response::json(PRResponse::getSuccessResponse('',$data));
    }

    public function getUserComplaints(){
        $loggedUserId = Auth::user()->id;
        
        $return = array();
        $query = Complaints::select('complaints.id','complaints.lead_id','complaints.vendor_id','complaints.employee_id','complaints.created_at','users.name as user_name','phone','email','complaints_categories.name as category_name','complaints_status.id as complaint_status_id','complaints_status.label','complaint_resolution_groups.group_name')
                            ->join('users', 'users.id', '=', 'complaints.user_id')
                            ->join('complaints_categories', 'complaints_categories.id', '=', 'complaints.complaint_category')
                            ->join('complaints_status', 'complaints_status.id', '=', 'complaints.status_id')
                            ->join('complaint_resolution_groups', 'complaint_resolution_groups.id', '=', 'complaints_categories.resolution_group_id')
                            ->where('complaints.user_type', 3);

        if(!Auth::user()->is_admin){
            $groupIds = $this->getComplaintResolutionMemberGroup($loggedUserId);
            if(empty($groupIds)){
                return Response::json(PRResponse::getSuccessResponse('',$return));
            }

            $query->where(function($query) use ($groupIds){
                foreach ($groupIds as $groupId) {
                    $query->orWhere('complaints_categories.resolution_group_id', $groupId->group_id);
                }
            });
        }
        
        $data = $query->orderBy('complaints.id', 'DESC')->get();
        
        /*$data = Complaints::select('complaints.id','complaints.lead_id','complaints.created_at','users.name as user_name','phone','email','complaints_categories.name as category_name','complaints_status.id as complaint_status_id','complaints_status.label')
                            ->join('users', 'users.id', '=', 'complaints.user_id')
                            ->join('complaints_categories', 'complaints_categories.id', '=', 'complaints.complaint_category')
                            ->join('complaints_status', 'complaints_status.id', '=', 'complaints.status_id')
                            ->where('complaints.user_type', 3)
                            ->where('complaints_categories.resolution_group_id', $group_id)
                            ->orderBy('complaints.id', 'DESC')
                            ->get();*/

        if(!empty($data)){
            $counter = 0;
            $userMinimalDto = new UserMinimalDTO();

            foreach ($data as $complaint) {
                $return[$counter]['id'] = $complaint->id;
                $return[$counter]['user_name'] = $complaint->user_name;
                $return[$counter]['phone'] = $complaint->phone;
                $return[$counter]['lead_id'] = $complaint->lead_id;
                $return[$counter]['category_name'] = $complaint->category_name;
                $return[$counter]['details'] = $complaint->details;
                $dt = Carbon::parse($complaint->created_at);
                $return[$counter]['created_at'] = $dt->toDateTimeString();

                $return[$counter]['label'] = $complaint->label;
                $return[$counter]['complaint_status_id'] = $complaint->complaint_status_id;

                $return[$counter]['resolution_group'] = $complaint->group_name;

                if($complaint->vendor_id != 0){
                    $return[$counter]['cg'] = $userMinimalDto->convertToDto($this->userDomainService->getUser($complaint->vendor_id));
                } else {
                    $return[$counter]['cg'] = $this->operationDomainService->getLatestVendorByLead($complaint->lead_id);
                }

                if($complaint->employee_id != 0){
                    $return[$counter]['employee'] = $userMinimalDto->convertToDto($this->userDomainService->getUser($complaint->employee_id));
                } else {
                    $return[$counter]['employee'] = null;
                }

                $return[$counter]['assignedEmployee'] = $userMinimalDto->convertToDto($this->operationDomainService->getLastEmployeeAssigned($complaint->lead_id));

                $counter++;
            }
        }

        return Response::json(PRResponse::getSuccessResponse('',$return));
    }

    public function getCgComplaints(){
        $data = Complaints::select('complaints.id','complaints.lead_id','complaints.vendor_id','complaints.employee_id','complaints.created_at','users.name as user_name','users.phone','users.email','complaints_categories.name as category_name','complaints_status.id as complaint_status_id','complaints_status.label', 'leads.customer_name','leads.customer_last_name','leads.phone AS customer_phone')
                            ->join('users', 'users.id', '=', 'complaints.user_id')
                            ->join('complaints_categories', 'complaints_categories.id', '=', 'complaints.complaint_category')
                            ->join('complaints_status', 'complaints_status.id', '=', 'complaints.status_id')
                            ->join('leads', 'leads.id', '=', 'complaints.lead_id')
                            ->where('complaints.user_type', 2)
                            ->orderBy('complaints.id', 'DESC')
                            ->get();

        $return = array();
        if(!empty($data)){
            $counter = 0;
            $userMinimalDto = new UserMinimalDTO();

            foreach ($data as $complaint) {
                $return[$counter]['id'] = $complaint->id;
                $return[$counter]['user_name'] = $complaint->user_name;
                $return[$counter]['phone'] = $complaint->phone;
                $return[$counter]['lead_id'] = $complaint->lead_id;
                $return[$counter]['category_name'] = $complaint->category_name;
                $return[$counter]['details'] = $complaint->details;
                $dt = Carbon::parse($complaint->created_at);
                $return[$counter]['created_at'] = $dt->toDateTimeString();
                
                $return[$counter]['label'] = $complaint->label;
                $return[$counter]['complaint_status_id'] = $complaint->complaint_status_id;

                $return[$counter]['customer_name'] = $complaint->customer_name.' '.$complaint->customer_last_name;
                $return[$counter]['customer_phone'] = $complaint->customer_phone;

                if($complaint->employee_id != 0){
                    $return[$counter]['employee'] = $userMinimalDto->convertToDto($this->userDomainService->getUser($complaint->employee_id));
                } else {
                    $return[$counter]['employee'] = null;
                }

                $return[$counter]['assignedEmployee'] = $userMinimalDto->convertToDto($this->operationDomainService->getLastEmployeeAssigned($complaint->lead_id));

                $counter++;
            }
        }

        return Response::json(PRResponse::getSuccessResponse('',$return));
    }

    public function getComplaintsListAPI(){
        $user_id = Authorizer::getResourceOwnerId();
        $inputAll = Input::all();

        $query = Complaints::select('complaints.id','complaints.lead_id','complaints.details','complaints.created_at','users.name as user_name','phone','email','complaints_categories.name as category_name','complaints_status.label');
        $query->join('users', 'users.id', '=', 'complaints.user_id');
        $query->join('complaints_categories', 'complaints_categories.id', '=', 'complaints.complaint_category');
        $query->join('complaints_status', 'complaints_status.id', '=', 'complaints.status_id');
        $query->where('complaints.user_id',$user_id);

        if(isset($inputAll['lead_id'])){
            $query->where('lead_id',$inputAll['lead_id']);
        }

        $query->orderBy('complaints.id', 'DESC');
        
        $data = $query->get();
        return Response::json(PRResponse::getSuccessResponse('',$data));
    }

    public function getComplaintDetailsAPI($complaintId){
        $user_id = Authorizer::getResourceOwnerId();
        
        $query = Complaints::select('complaints.id','complaints.lead_id','complaints.details','complaints.created_at','users.name as user_name','phone','email','complaints_categories.name as category_name','complaints_status.label');
        $query->join('users', 'users.id', '=', 'complaints.user_id');
        $query->join('complaints_categories', 'complaints_categories.id', '=', 'complaints.complaint_category');
        $query->join('complaints_status', 'complaints_status.id', '=', 'complaints.status_id');
        $query->where('complaints.user_id',$user_id);
        $query->where('complaints.id',$complaintId);
        $query->orderBy('complaints.id', 'DESC');
        
        $data = $query->get();
        return Response::json(PRResponse::getSuccessResponse('',$data));
    }

    public function getComplaintStatuses(){
        $data = ComplaintsStatus::get();
        return Response::json(PRResponse::getSuccessResponse('',$data));    
    }

    public function getComplaintDetailed($complaintId,$userType){
        if($userType == 2){
            $data = Complaints::select('complaints.id','complaints.lead_id','complaints.details','complaints.created_at','users.name as user_name','users.phone','users.email','complaints_categories.name as category_name','complaints_status.id as complaint_status_id','complaints_status.label', 'leads.customer_name','leads.customer_last_name','leads.phone AS customer_phone')
                            ->join('users', 'users.id', '=', 'complaints.user_id')
                            ->join('complaints_categories', 'complaints_categories.id', '=', 'complaints.complaint_category')
                            ->join('complaints_status', 'complaints_status.id', '=', 'complaints.status_id')
                            ->join('leads', 'leads.id', '=', 'complaints.lead_id')
                            ->where('complaints.user_type', $userType)
                            ->where('complaints.id',$complaintId)
                            ->orderBy('complaints.id', 'DESC')
                            ->first();
        } else if($userType == 3){
            $data = Complaints::select('complaints.id','complaints.lead_id','complaints.details','complaints.created_at','users.name as user_name','phone','email','complaints_categories.name as category_name','complaints_status.id as complaint_status_id','complaints_status.label')
                            ->join('users', 'users.id', '=', 'complaints.user_id')
                            ->join('complaints_categories', 'complaints_categories.id', '=', 'complaints.complaint_category')
                            ->join('complaints_status', 'complaints_status.id', '=', 'complaints.status_id')
                            ->where('complaints.user_type', $userType)
                            ->where('complaints.id',$complaintId)
                            ->orderBy('complaints.id', 'DESC')
                            ->first();
        } else {
            abort(404);
        }

        $model = array();
        $model['id'] = $data->id;
        $model['user_name'] = $data->user_name;
        $model['phone'] = $data->phone;
        $model['lead_id'] = $data->lead_id;
        $model['category_name'] = $data->category_name;
        $model['details'] = $data->details;
        $dt = Carbon::parse($data->created_at);
        $model['created_at'] = $dt->toDateTimeString();
        $model['status'] = $data->label;
        $model['complaint_status_id'] = $data->complaint_status_id;
        $model['complaint_user_type'] = $data->user_type;

        if($userType == 2){
            $model['complaint_user_label'] = 'CG';
            $model['complaint_to_name'] = $data->customer_name.' '.$data->customer_last_name;
            $model['complaint_to_phone'] = $data->customer_phone;
            $model['complaint_to_type_label'] = 'User';
        } else if($userType == 3){
            $model['complaint_user_label'] = 'Customer';
            $cgInfo = $this->operationDomainService->getLatestVendorByLead($data->lead_id);
            $model['complaint_to_name'] = $cgInfo->name;
            $model['complaint_to_phone'] = $cgInfo->phone;
            $model['complaint_to_type_label'] = 'CG';
        }

        return Response::json(PRResponse::getSuccessResponse('',$model));
    }

    public function changeComplaintStatus(){
        $complaints = new Complaints;
        $complaints->where('id', Input::get('complaintId'))
            ->update(['status_id' => INPUT::get('complaintStatus')]);

        return Response::json(PRResponse::getSuccessResponse('',true));
    }

    public function complaintResolutionCGTraining(){
        $inputAll = Input::all();

        $cgId = $inputAll['cgId'];
        $complaintId = $inputAll['complaintId'];
        $leadId = $inputAll['leadId'];
        $dateRaw = strtotime($inputAll['trainingDate']);
        $trainingDate = Carbon::now();
        $trainingDate->timestamp($dateRaw);

        $cgTraining = new CgTraining;
        $cgTraining->employee_id = Auth::user()->id;
        $cgTraining->user_id = $cgId;
        $cgTraining->lead_id = $leadId;
        $cgTraining->complaint_id = $complaintId;
        $cgTraining->date = $trainingDate->toDateString();
        $cgTraining->save();

        return Response::json(PRResponse::getSuccessResponse('',true));
    }

    public function complaintResolutionCGReplacement(){
        $inputAll = Input::all();

        $cgId = $inputAll['cgId'];
        $complaintId = $inputAll['complaintId'];
        $leadId = $inputAll['leadId'];
        $reason = $inputAll['reason'];

        $dateRaw = strtotime($inputAll['startDate']);
        $startDate = Carbon::now();
        $startDate->timestamp($dateRaw);

        $dateRaw = strtotime($inputAll['replacementDate']);
        $replacementDate = Carbon::now();
        $replacementDate->timestamp($dateRaw);

        $cgReplacement = new CgReplacement;
        $cgReplacement->employee_id = Auth::user()->id;
        $cgReplacement->user_id = $cgId;
        $cgReplacement->lead_id = $leadId;
        $cgReplacement->complaint_id = $complaintId;
        $cgReplacement->start_date = $startDate->toDateString();
        $cgReplacement->replacement_date = $replacementDate->toDateString();
        $cgReplacement->reason = $reason;
        $cgReplacement->save();

        return Response::json(PRResponse::getSuccessResponse('',true));
    }

    public function getComplaintHistoryCGTraining($complaintId){
        $data = CgTraining::select('cg_training.user_id','cg_training.date','users.name','users.phone')
                            ->join('users', 'users.id', '=', 'cg_training.user_id')
                            ->where('cg_training.complaint_id', $complaintId)
                            ->orderBy('cg_training.id', 'DESC')
                            ->get();

        return Response::json(PRResponse::getSuccessResponse('',$data));
    }

    public function getComplaintHistoryCGReplacement($complaintId){
        $data = CgReplacement::select('cg_replacement.user_id','cg_replacement.start_date','cg_replacement.replacement_date','cg_replacement.reason','users.name','users.phone')
                            ->join('users', 'users.id', '=', 'cg_replacement.user_id')
                            ->where('cg_replacement.complaint_id', $complaintId)
                            ->orderBy('cg_replacement.id', 'DESC')
                            ->get();

        return Response::json(PRResponse::getSuccessResponse('',$data));
    }

    public function getComplaintResolutionGroups(){
        $data = ComplaintResolutionGroups::select('id','group_name')->get();
        return Response::json(PRResponse::getSuccessResponse('',$data));
    }

    public function postComplaintResolutionGroupAddMember(){
        $inputAll = Input::all();

        $group_id = $inputAll['group_id'];
        $user_id = $inputAll['user_id'];
        
        $crgMember = new ComplaintResolutionGroupsMembers;
        $crgMember->group_id = $group_id;
        $crgMember->user_id = $user_id;
        $crgMember->save();

        return Response::json(PRResponse::getSuccessResponse('',true));
    }

    public function getComplaintResolutionMembers(){
        $data = ComplaintResolutionGroupsMembers::select('users.id','users.name','users.phone','complaint_resolution_groups.group_name','complaint_resolution_groups_members.id AS member_relation_id')
                ->join('users', 'users.id', '=', 'complaint_resolution_groups_members.user_id')
                ->join('complaint_resolution_groups', 'complaint_resolution_groups.id', '=', 'complaint_resolution_groups_members.group_id')
                ->orderBy('complaint_resolution_groups_members.id', 'DESC')
                ->get();

        return Response::json(PRResponse::getSuccessResponse('',$data));
    }

    public function deleteComplaintResolutionMember($memberRelationId){
        $data = ComplaintResolutionGroupsMembers::where('id', $memberRelationId)->delete();
        return Response::json(PRResponse::getSuccessResponse('',$data));
    }

    public function getComplaintResolutionMemberGroup($user_id){
        return ComplaintResolutionGroupsMembers::where('user_id', $user_id)->get();
    }

    public function addComplaintLog(){
        $inputAll = Input::all();

        $complaintId = $inputAll['complaintId'];
        $leadId = $inputAll['leadId'];
        $leadId = $inputAll['leadId'];
        
        $complaintLog = new ComplaintLogs;
        $complaintLog->lead_id = $leadId;
        $complaintLog->user_id = Auth::user()->id;
        $complaintLog->comment = trim($inputAll['comment']);
        $complaintLog->complaint_id = $complaintId;
        $complaintLog->save();

        return Response::json(PRResponse::getSuccessResponse('',true));
    }

    public function getReplacementRequests(){
        $data = CgReplacement::select('cg_replacement.lead_id','cg_replacement.user_id','cg_replacement.start_date','cg_replacement.replacement_date','cg_replacement.reason','cg_replacement.created_at','users.name','users.phone')
                            ->join('users', 'users.id', '=', 'cg_replacement.user_id')
                            ->orderBy('cg_replacement.id', 'DESC')
                            ->get();

        return Response::json(PRResponse::getSuccessResponse('',$data));
    }
    
}