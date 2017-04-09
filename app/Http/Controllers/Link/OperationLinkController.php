<?php

namespace App\Http\Controllers\Link;

use App\Contracts\Domain\IOperationDomainContract;
use App\Contracts\Domain\IUserDomainContract;
use App\Contracts\Rest\IOperationRestContract;
use App\Http\Controllers\Controller;
use App\Models\ORM\Lead;
use App\Models\ORM\LeadStatus;
use App\Services\Domain\OperationDomainService;
use App\Services\Rest\OperationRestService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Vluzrmos\SlackApi\Facades\SlackChannel;
use Illuminate\Http\Request;
use App\Services\Domain\CaregiverDomainService;
use Cache;
class OperationLinkController extends Controller
{
    protected $operationDomainService;

    protected $operationRestService;

    protected $userDomainService;

    public function __construct(IOperationDomainContract $IOperationDomainContract, IOperationRestContract $IOperationRestContract, IUserDomainContract $IUserDomainContract)
    {
        $this->operationDomainService = $IOperationDomainContract;
        $this->operationRestService = $IOperationRestContract;
        $this->userDomainService = $IUserDomainContract;
    }




    public function get()
    {
        return '';
    }
    public function leads()
    {
        $model = array('services'=>$this->operationDomainService->getServices(true));
        return view('operation.leads')->with('model',$model);
    }

    public function lead($leadId)
    {
        $leadOrm = $this->operationDomainService->getLeadDetail($leadId);
        $leadFeedback = \App\Models\ORM\LeadFeedback::getFeedback($leadId);
        $model = array('services'=>$this->operationDomainService->getServices(true),'lead'=>$leadOrm,'leadId'=>$leadId,'leadFeedback' => $leadFeedback);
        return view('operation.lead')->with('model',$model);
    }
    public function leadVendorSuggestion($leadId,Request $request)
    {
        $filter = $request->input('filter');
        $leadOrm = $this->operationDomainService->getLeadDetail($leadId);
        $model = array('services'=>$this->operationDomainService->getServices(true),'lead'=>$leadOrm,'leadId'=>$leadId);
        if($filter == 'auto'){
            return view('operation.lead_vendor_auto_suggestions')->with('model',$model);
        }else{
            return view('operation.lead_vendor_suggestions')->with('model',$model);
        }
    }
    
    public function reloadLeadVendorSuggestion($leadId,CaregiverDomainService $caregiverDomainService){
       $preferred_cgs = $caregiverDomainService->populateCaregivers($leadId);
       return redirect()->back(); 
    }
    
    public function deactiveLeadVendorSuggestion($leadId,$userId,CaregiverDomainService $caregiverDomainService){
       $preferred_cgs = $caregiverDomainService->deactiveAutoCaregiver($leadId,$userId);
       return redirect()->back(); 
    }
    
    public function refreshPreferredCGCaching(){
       Cache::forget('vendors_listing');
       return redirect()->back(); 
    }
    
    public function validateLead($leadId)
    {
        $leadOrm = $this->operationDomainService->getLeadDetail($leadId);
        $model = array('services'=>$this->operationDomainService->getServices(true),'lead'=>$leadOrm,'leadId'=>$leadId);
        return view('operation.leadValidate')->with('model',$model);
    }
    public function editLead($leadId)
    {

        $leadOrm = $this->operationDomainService->getLeadDetail($leadId);
        $model = array('services'=>$this->operationDomainService->getServices(true),'lead'=>$leadOrm,'leadId'=>$leadId);
        $service = $this->operationDomainService->getServices(true);
        $model = array('services'=>$service,'lead'=>$leadOrm,'leadId'=>$leadId);
        return view('operation.leadEdit')->with('model',$model);
    }

    public function newLead(){
        return view('admin.operation.newlead');
    }
    public function newVendor() {
        return view('admin.vendor.newVendor');
    }

    public function careplan($leadId)
    {
        $leadOrm = $this->operationDomainService->getLeadDetailedOrm($leadId);

        if(!$this->operationDomainService->isAuthorizedToViewContact(Auth::user())){
            $leadOrm->phone = "xxxxxxxxxx";
        }

        $model = array(
            'services'=>$this->operationDomainService->getServices(true),
            'lead'=>$leadOrm,
            'leadId'=>$leadId,
            'lead_validation'=>$this->operationRestService->getCarePlanData($leadId)
        );
        return view('operation.care_plan')->with('model',$model);
    }

    public function getEmployeeProfile($userId) {
        $operationDomainService = new OperationDomainService();
        $userOrm = $operationDomainService->getUserOrm($userId);
        //$operationRestService = new OperationRestService($operationDomainService);
        $model = array(
            'user'=>$userOrm,
            'userId'=>$userId
        );
        return view('admin.employee_profile')->with('model',$model);
    }
    public function getVendorProfile() {
        return view('admin.vendor_profile');
    }
    public function editVendorProfile() {
        return view('admin.vendor_profile_edit');
    }
    public function getCustomerProfile() {
        return view('admin.customer_profile');
    }
    public function createSlackChannel($leadId) {
        $slackChannelName = $this->operationDomainService->checkAndGenerateSlackChannelForLead($leadId);

        if($slackChannelName && $slackChannelName!=null && $slackChannelName!=""){
            $channelCreated = SlackChannel::create($slackChannelName);

            if(!$channelCreated->ok && $channelCreated->error=="name_taken"){
                $leadDetail = $this->operationDomainService->getLeadDetail($leadId);
                SlackChannel::unarchive($leadDetail->slack_channel_id);
            }else if($channelCreated->ok==true && $channelCreated->channel){
                $this->operationDomainService->updateSlackChannelIdForLead($leadId,$channelCreated->channel->id);
                SlackChannel::setTopic($channelCreated->channel->id,env('APP_URL')."lead/".$leadId);
                $this->operationDomainService->addWatchersToLead($leadId,$this->userDomainService->getUserIdByEmailList([
                    'seema.chauhan@pramaticare.com',
                    'robin@pramaticare.com',
                    'richa@pramaticare.com',
                    'kajal@pramaticare.com',
                    'vishal@pramaticare.com',
                    'harshit@pramaticare.com'
                ]));



            }
        }
        return redirect(url('lead/'.$leadId));
    }




    public function syncLead(){

        $leadMapper = array();
        $leadIdList = array();

        $x = LeadStatus::orderBy('created_at','desc')->with('status')->get();
        foreach($x as $y){
            if(!isset($leadMapper[$y->lead_id])){
                $leadMapper[$y->lead_id] = $y->status;
                array_push($leadIdList,$y->lead_id);
            }
        }

        $leadList = Lead::whereIn('id',$leadIdList)->get();
        foreach($leadList as $tempLead){
            if($tempLead->current_status==0){
                $tempLead->current_status = $leadMapper[$tempLead->id]->id;
                $tempLead->save();
            }
        }

        return;


        $leadList = $this->operationDomainService->getStartedLead();

        $leadMapper = array();


        foreach($leadList as $tempLead){
            if(!isset($leadMapper[$tempLead->lead_id])){
                $leadMapper[$tempLead->lead_id] = array();
                $leadMapper[$tempLead->lead_id]['started_date'] = $tempLead->created_at;
                $leadMapper[$tempLead->lead_id]['status_changed'] = false;
            }else{
              // echo "yes".$tempLead->lead_id;
            }
        }

        $startedLeadStatus = $this->operationDomainService->getStatusBySlug('started');

        foreach($leadMapper as $leadId=>$mappedData){
            $startedDate = $mappedData['started_date'];
            $leadStatusChanged = LeadStatus::whereRaw('lead_id = ? and created_at > ?', array($leadId,$startedDate))->get();
            if(count($leadStatusChanged)==0){
                // no status changed

                // save into table
            }
            $leadDateStatusMapper = array();
            foreach($leadStatusChanged as $tempLeadStatus){
                $carbonStartedDate = Carbon::parse($tempLeadStatus->created_at);
                $leadDateStatusMapper[$carbonStartedDate->toDateString()] = $tempLeadStatus->status_id;
            }
            // TODO: optimize code with time consideration
            $startedDateCopied = $startedDate->copy();

            $leadStarted = true;
            for(;;){
                if($startedDateCopied->gt(Carbon::now())){
                    break;
                }
                if(!isset($leadDateStatusMapper[$startedDateCopied->toDateString()]) && $leadStarted){
                    // lead is active on the date
                    $this->operationDomainService->checkAndMarkActiveDate($leadId,$startedDateCopied->toDateString());
                }else if( isset($leadDateStatusMapper[$startedDateCopied->toDateString()]) && $leadDateStatusMapper[$startedDateCopied->toDateString()]== $startedLeadStatus->id){
                    //lead is active on date
                    $this->operationDomainService->checkAndMarkActiveDate($leadId,$startedDateCopied->toDateString());
                    $leadStarted = true;
                }else{
                    $leadStarted = false;
                }
                $startedDateCopied->addDay();
            }

        }
    }
}