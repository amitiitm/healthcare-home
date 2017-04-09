<?php

namespace App\Http\Controllers\Link;

use App\Http\Controllers\Controller;
use App\Services\Domain\OperationDomainService;
use App\Models\ORM\UserVendor;
use App\Models\ORM\LeadVendor;
use App\Models\User;
use App\Models\ORM\Shift;
use App\Models\ORM\Enquiry;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use \App\Services\Domain\VendorDomainService;
use App\Models\Enums\PramatiConstants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
class DashboardLinkController extends Controller
{
    public function get()
    {
        return '';
    }
    public function index()
    {
        $model = array('name'=>"Mohit");
        $operationDomainService = new OperationDomainService();
        $model = array('services'=>$operationDomainService->getServices(true));
        
        return view('admin.dashboard')->with('model',$model);
    }
    public function empdash(){
        return view('operation.leads');
    }
    public function enquiries()
    {
        $model = array();
        return view('admin.enquiries')->with('model',$model);
    }
    public function users(){
        return view('admin.users');
    }
    public function employees()
    {
        return view('admin.employee.employees');
    }
    public function employeeView($userId)
    {

        return view('admin.employee.viewEmployee')->with('employeeId',$userId);
    }
    public function employeeEdit($userId)
    {

        return view('admin.employee.editEmployee')->with('employeeId',$userId);
    }
    public function employeeTracker()
    {
        return view('admin.employee.employeeTracker');
    }
    public function vendors()
    {
        return view('admin.vendor.vendors');
    }
    public function reports()
    {
        return view('admin.reports');
    }
    public function field()
    {
        return view('admin.field');
    }
    public function operations()
    {
        return view('admin.operations');
    }
    public function caregivers()
    {
        return view('admin.vendor.caregivers');
    }

    public function caregiversData(VendorDomainService $vendorDomainService) {
        $vendors = User::join('user_vendors','user_vendors.user_id','=','users.id')
                ->select('users.id','users.name','phone','is_flagged','age',
                        'location_of_work','training_attended_date','user_vendors.created_at','added_by_user_id','gender','preferred_shift_id')
                ->where('users.name','<>','')
                ->get();
        //$vendorDeployedStatusMapped = $vendorDomainService->getDeployedVendor();  
        return Datatables::of($vendors)
                ->editColumn('name', function ($model) {              
                            return '<a href="/vendor/' . $model->id .'" class="edit_link" data-value=' . $model->id . '>'.$model->name.'</a>';
                        })  
                ->addColumn('action_info', function ($model) {
                    $actionInfo = '<a href="#" onClick="deleteCG('.$model->id.');">delete</a>';
                    return $actionInfo;                   
                })
                ->addColumn('deployed', function ($model){
                    //return LeadVendor::checkDeployedVendor($model->id);
                    //if(isset($vendorDeployedStatusMapped[$model->id])){
                        //return '<span class="badge bg-success">Deployed</span>';
                    //}else{
                        //return '<span class="badge bg-warning">Not Deployed</span>';
                    //}
                })         
                ->addColumn('availability', function ($model) {
                    if($model->vendorAvailabilities){
                        $availability = '';
                        $vendorAvailability = $model->vendorAvailabilities->last();
                        if($vendorAvailability && $vendorAvailability->available==1){
                            $availability = 'Available';
                        }else if($vendorAvailability && $vendorAvailability->available==0){
                            $availability = 'Un-Available';
                        }
                        return $availability;
                    }
                })                      
                ->editColumn('training_attended_date', function ($model) {  
                    if($model->training_attended_date == '' || $model->training_attended_date == null){
                       return '';
                    }else{
                       return Carbon::parse($model->training_attended_date)->format('Y-m-d');
                    }
                            
                })
                ->editColumn('preferred_shift_id', function ($model) {  
                    if($model->preferred_shift_id !== ''){
                        $shift = Shift::where('id',$model->preferred_shift_id)->first();
                        if($shift){
                          return $shift->label;  
                        }else{
                            return '';
                        }
                    }else{
                        return '';
                    }
                            
                })
                ->editColumn('location_of_work', function ($model) {  
                    if($model->locationOfWork){
                        return $model->locationOfWork->label;
                    }else{
                        return '';
                    }
                            
                })
                ->editColumn('gender', function ($model) {  
                    if($model->gender == 1){
                        return 'Male';
                    }else if($model->gender == 2){
                        return 'Female';
                    }else{
                        return '';
                    }
                            
                })
                ->editColumn('is_flagged', function ($model) {  
                    if($model->is_flagged == 1){
                        return '<a class="badge bg-success" href="#" onClick="changeFlag('.$model->id.');" id='.$model->id.'>Yes</a>';
                    }else{
                        return '<a class="badge bg-warning" href="#" onClick="changeFlag('.$model->id.');" id='.$model->id.'>No</a>';
                    }                        
                })
                ->editColumn('added_by_user_id', function ($model) {  
                    if($model->addedByUser){
                       //return $model->addedByUser->name;
                    }else{
                        return '';
                    }
                            
                })
                ->editColumn('created_at', function ($model) {  
                    if($model->created_at !== ''){
                       return Carbon::parse($model->created_at)->format('Y-m-d');
                    }else{
                        return '';
                    }
                            
                })
                ->make(true);
    }
    
    public function enquiries1()
    {
        return view('admin.enquiries1');
    }
    
    public function enquiriesData()
    {
        $enquiries = Enquiry::whereIn('status',[PramatiConstants::INFO_GATHERING, PramatiConstants::PRICE_CHECKING, PramatiConstants::NOT_INTERESTED,PramatiConstants::FOLLOW_UP])->orderBy('followup_time','asc')->get();
        return Datatables::of($enquiries)
                ->editColumn('message', function ($model) {  
                    if($model->message){
                        return '<span title="'.$model->message.'">'.substr($model->message,0,50).'</span>';
                    }else{
                        return '';
                    }
                            
                })
                ->AddColumn('action_info', function ($model) {              
                            return '<a href="/admin/edit_enquiry/' . $model->id.'" class="edit_link" data-value=' . $model->id . '>Edit</a>';
                        })
                ->make(true);
    }
    public function enquiryNew()
    {
        $user_types = [PramatiConstants::NONEXISTING_USER_TYPE => 'Non Existing',PramatiConstants::EXISTING_USER_TYPE => 'Existing'];
        $statuses = [PramatiConstants::INFO_GATHERING => 'Info Gathering', PramatiConstants::PRICE_CHECKING => 'Price Checking', PramatiConstants::NOT_INTERESTED => 'Not Interested',PramatiConstants::FOLLOW_UP => 'Follow Up',PramatiConstants::WILL_GET_BACK => 'Will Get Back',PramatiConstants::FOLLOWUP_CONVERTED => 'Converted'];
        return view('admin.add_enquiry',array('statuses' => $statuses,'user_types' => $user_types));
    }
    public function enquiryCreate(Request $request)
    {
        $enquiry = new Enquiry();
        $input = $request->all();
        $enquiry->fill($input);
        $enquiry->save();
        //return redirect()->back();
        return Redirect::action('Link\DashboardLinkController@enquiries1');
    }
    public function changeEnquiryStatus(){
        $message = 'Some Error Occured.';
        $input = Input::all();
        $enquiryId = $input['enquiryId']; 
        $status = $input['status'];
        $enquiry = Enquiry::find($enquiryId);
        if($enquiry){
           $enquiry->status = $status;
           $enquiry->save();
           $message = 'Enquiry Updated Successfully.';
        }
        return Response::json($message);
    }
    public function enquiryEdit($enquiry_id)
    {
        $enquiry = Enquiry::find($enquiry_id);
        if($enquiry){   
            $user_types = [PramatiConstants::NONEXISTING_USER_TYPE => 'Non Existing',PramatiConstants::EXISTING_USER_TYPE => 'Existing'];
            $statuses = [PramatiConstants::INFO_GATHERING => 'Info Gathering', PramatiConstants::PRICE_CHECKING => 'Price Checking', PramatiConstants::NOT_INTERESTED => 'Not Interested',PramatiConstants::FOLLOW_UP => 'Follow Up',PramatiConstants::WILL_GET_BACK => 'Will Get Back',PramatiConstants::FOLLOWUP_CONVERTED => 'Converted'];
            return view('admin.edit_enquiry',array('statuses' => $statuses,'enquiry' => $enquiry,'user_types' => $user_types));
        }else{
          return redirect()->back();  
        }
    }
    public function enquiryUpdate(Request $request)
    {
        $enquiry = Enquiry::find($request->input('id'));
        if ($enquiry == null) {
            return Redirect::action('Link\DashboardLinkController@enquiries1');
        } else {
            $input = $request->all();
            $enquiry->fill($input);
            $enquiry->save();
            return Redirect::action('Link\DashboardLinkController@enquiries1');
        }
    }
}