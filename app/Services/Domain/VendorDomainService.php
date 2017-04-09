<?php

namespace App\Services\Domain;

use App\Contracts\Domain\IUserDomainContract;
use App\Contracts\Domain\IVendorDomainContract;
use App\Models\Article;
use App\Models\Enums\SCConstants;
use App\Models\ORM\Lead;
use App\Models\ORM\UserVendor;
use App\Models\ORM\VendorAvailabilityOption;
use App\Models\ORM\VendorBankDetail;
use App\Models\ORM\VendorDocument;
use App\Models\ORM\VendorDocumentType;
use App\Models\ORM\VendorTask;
use App\Models\ORM\VendorTracker;
use App\Models\ORM\VendorTrainingReason;
use App\Models\User;
use App\Models\ORM\PreferredCaregiver;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Cache;
class VendorDomainService implements IVendorDomainContract
{


    public function getVendorList($pageNumber = 0,$pageSize = 0){
        if($pageNumber && $pageNumber > 0){
            return UserVendor::with('user')
                ->with('religion')
                ->with('foodType')
                ->with('genderObject')
                ->with('locationOfWork')
                ->with('vendorAvailabilities')
                ->with('genderObject')
                ->with('addedByUser')
                ->with('vendorAvailabilities')
                ->join('users', 'users.id', '=', 'user_vendors.user_id')
                ->whereNotNull('users.name')
                ->where('users.name','<>','')
                ->select('user_vendors.*')
                ->offset(($pageNumber - 1) * $pageSize)
                ->limit($pageSize)
                ->get();  
        }else{
            return UserVendor::with('user')
                ->with('religion')
                ->with('foodType')
                ->with('genderObject')
                ->with('locationOfWork')
                ->with('vendorAvailabilities')
                ->with('genderObject')
                ->with('addedByUser')
                ->with('vendorAvailabilities')
                ->get();
        }
    }

    public function getVendorListByLead($leadId){
        //$preferred_cg_ids = PreferredCaregiver::getPreferredCaregiversIds($leadId);
        return UserVendor::with('user')
            ->with('religion')
            ->with('foodType')
            ->with('genderObject')
            ->with('locationOfWork')
            ->with('vendorAvailabilities')
            ->with('genderObject')
            ->with('addedByUser')
            ->with('vendorAvailabilities')
            ->where('preferred_caregivers.lead_id',$leadId)
            ->where('preferred_caregivers.status_id',1)
            ->join('preferred_caregivers', 'preferred_caregivers.user_id', '=', 'user_vendors.user_id')
            ->orderBy('points','desc')
            ->limit(1)
            ->get();
    }
    
    public function getDeployedVendor(){
        $allLeadWithStatusAndVendor = Cache::remember('primary_vendors_assigned', 60, function () {
            return Lead::with('primaryVendorsAssigned')->with('statuses')->get();
        });
        //$allLeadWithStatusAndVendor = Lead::with('primaryVendorsAssigned')->with('statuses')->get();
        //d($allLeadWithStatusAndVendor);
        $vendorListMapped = [];
        foreach($allLeadWithStatusAndVendor as $tempLead){
            if($tempLead->statuses->count()==0){
                continue;
            }
            $currentStatus = $tempLead->statuses->last();
            if($currentStatus->slug!='started'){
                continue;
            }
            $vendorAssigned = $tempLead->primaryVendorsAssigned->last();
            if($vendorAssigned && !isset($vendorListMapped[$vendorAssigned->id])){
                $vendorListMapped[$vendorAssigned->id]=array(
                    'info'=>$vendorAssigned,
                    'leads'=>array()
                );
                array_push($vendorListMapped[$vendorAssigned->id]['leads'],$tempLead);
            }
        }
        return $vendorListMapped;
    }

    public function getVendorCount(){
        return User::withTrashed()->count();
    }

    public function createVendorUser($name,$email,$phone,$password){
        $userTypeId = 2;
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->phone = $phone;
        $user->password = $password;
        $user->user_type_id = $userTypeId;

        try{
            $user->save();
            return $user;
        }catch (\SQLiteException $ex){
            return false;
        }
    }

    public function updateVendorUser($userId,$name,$email,$phone,$password){
        $userVendor = User::where('id','=',$userId)->first();
        $userTypeId = 2;
        $userVendor->name = $name;
        $userVendor->email = $email;
        $userVendor->phone = $phone;
        $userVendor->password = $password;
        $userVendor->user_type_id = $userTypeId;

        try{
            $userVendor->save();
            return $userVendor;
        }catch (\SQLiteException $ex){
            return false;
        }
    }

    public function createVendorDetailByORM($vendorOrm){
        $vendorOrm->save();
        return $vendorOrm;
    }

    public function updateVendorDetailByORM($vendorOrm){
        $vendorOrm->save();
        return $vendorOrm;
    }

    public function updateVendorTask($vendorId,$taskList){
        //d($taskList);
        $alreadyTasks = VendorTask::where('vendor_id','=',$vendorId)->get();
        $alreadyMapped = array();
        foreach ($alreadyTasks as $tempItem) {
            $alreadyMapped[$tempItem->task_id]=true;
        }
        $toAddTask = array();
        foreach($taskList as $tempItem){
            if(!isset($alreadyMapped[$tempItem])){
                array_push($toAddTask,$tempItem);
            }else{
                $alreadyMapped[$tempItem]=false;
            }
        }
        // delete
        foreach($alreadyMapped as $key=>$tempItem){
            if($tempItem==true){
                $vendorTaskOrmToDelete = VendorTask::whereRaw('vendor_id=? and task_id =?', array($vendorId,$key))->first();
                $vendorTaskOrmToDelete->delete();
            }
        }
        $pushArray = array();
        foreach($toAddTask as $key=>$tempItem){
            array_push($pushArray,array(
                'vendor_id'=>$vendorId,
                'task_id'=>$tempItem
            ));
        }
        $vendorTaskInsert = VendorTask::insert($pushArray);
        return $vendorTaskInsert;

    }

    public function createLocalityByOrm($localityOrm){
        try{
            if(!$localityOrm){
                return ;
            }
            $localityOrm->save();
            return $localityOrm;
        }catch (\SQLiteException $ex){
            return false;
        }
    }
    public function updateBankAccountDetail($vendorId,$bankDetail){

        //d($bankDetail);
        if(isset($bankDetail['accountNo']) && isset($bankDetail['name']) && isset($bankDetail['bankName']) && isset($bankDetail['ifsc'])){
            //echo "dd";
            $newVendorBankDetail = new VendorBankDetail();
            $newVendorBankDetail->name = $bankDetail['name'];
            $newVendorBankDetail->bank_name = $bankDetail['bankName'];
            $newVendorBankDetail->ifsc = $bankDetail['ifsc'];
            $newVendorBankDetail->account_no = $bankDetail['accountNo'];
            $newVendorBankDetail->vendor_id = $vendorId;
            $newVendorBankDetail->save();
            return true;
        }
        return false;
    }

    public function getVendorLocality($vendorId){

    }
    public function getVendorDetailedOrm($vendorId){

        $vendorDetailed = UserVendor::where('user_id','=',$vendorId)
            ->with('category')
            ->with('agency')
            ->with('task')
            ->with('source')
            ->with('religion')
            ->with('user')
            ->with('genderObject')
            ->with('foodType')
            ->with('qualification')
            ->with('locationOfWork')
            ->with('shift')
            ->with('bankDetail')
            ->with('services')
            ->with('localityObject')
            ->with('trainingNotAttendedReason')
            ->with('uploadedDocuments')
            ->with('vendorAvailabilities')
            ->with('vendorAvailabilities.availabilityOption')
            ->with('vendorAvailabilities.availabilityReason')
            ->with('vendorAvailabilities.availabilityShift')
            ->first();
        return ($vendorDetailed);

    }

    public function getVendorBankDetailedOrmByVendorId($vendorId){

        $vendorDetailed = UserVendor::where('id','=',$vendorId)
            ->with('category')
            ->with('source')
            ->with('religion')
            ->with('user')
            ->with('genderObject')
            ->with('foodType')
            ->with('qualification')
            ->with('locationOfWork')
            ->with('shift')
            ->with('bankDetail')
            ->with('services')
            ->with('vendorAvailabilities')
            ->with('vendorAvailabilities.availabilityOption')
            ->with('vendorAvailabilities.availabilityReason')
            ->with('vendorAvailabilities.availabilityShift')
            ->first();
        return ($vendorDetailed);

    }

    public function phoneExist($phone)
    {
        $phoneData = User::where('user_type_id','=',2)->where('phone','=',$phone)->get();
        $data = count($phoneData);
        if($data>0)
        {
          return true;
        }
          return false;
    }
    
    public function phoneExistForUpdate($phone,$userId)
    {
        $phoneData = User::where('user_type_id','=',2)->where('phone','=',$phone)->where('id','!=',$userId)->get();
        $data = count($phoneData);
        if($data>0)
        {
          return true;
        }
          return false;
    }

    public function voterIdExist($voter)
    {
        $voterdata = UserVendor::where('voter','=',$voter)->get();
        $data = count($voterdata);
        if($data>0)
        {
          return true;
        }
          return false;
    }

    public function aadharIdExist($aadhar)
    {
        $aadhardata = UserVendor::where('aadhar','=',$aadhar)->get();
        $data = count($aadhardata);
        if($data>0)
        {
            return true;
        }
        return false;
    }


    public function isEmailExistForAdd($email){
        $emailCount = User::where('email','=',$email)->count();
        if($emailCount>0){
            return true;
        }
        return false;
    }
    
    public function voterIdExistForUpdate($voter, $vendorId)
    {
        $vendorsWithSameVoterId = UserVendor::whereRaw('voter = ? and id != ?', array($voter,$vendorId))->count();
        if($vendorsWithSameVoterId>0){
            return false;
        }else{
            return true;
        }
    }

    public function aaddharIdExistForUpdate($aaddhar, $vendorId)
    {
        $vendorsWithSameaaddharId = UserVendor::whereRaw('aadhar = ? and id != ?', array($aaddhar,$vendorId))->count();
        if($vendorsWithSameaaddharId>0){
            return false;
        }else{
            return true;
        }
    }



    public function updateVendorTrackingLocation($vendorUserId,$latitude,$longitude,$locationTime){
        $vendorTrackerOrm = new VendorTracker();
        $vendorTrackerOrm->vendor_user_id = $vendorUserId;
        $vendorTrackerOrm->latitude = $latitude;
        $vendorTrackerOrm->longitude = $longitude;
        if($locationTime && $locationTime!=""){
            $locationDateCarbon = Carbon::now();
            $locationDateCarbon->timestamp($locationTime);
            $locationDateCarbon->second = 0;
            $vendorTrackerOrm->location_time = $locationDateCarbon;
        }else{
            $vendorTrackerOrm->location_time = Carbon::now();
        }
        return $vendorTrackerOrm->save();
    }

    public function getVendorAvailabilityOptionsWithReason(){
        return VendorAvailabilityOption::with('reasons')->get();
    }


    public function getVendorDocumentTypes(){
        return VendorDocumentType::orderBy('label','asc')->get();
    }

    public function getVendorTrainingReasons(){
        return VendorTrainingReason::orderBy('label','asc')->get();
    }
    public function uploadVendorDocument($file,$documentTypeId,$documentCaption,$vendorId){
        $vendorDocumentOrm = new VendorDocument();
        $vendorDocumentOrm->document_type_id= $documentTypeId;
        $vendorDocumentOrm->vendor_id = $vendorId;
        $vendorDocumentOrm->caption = $documentCaption;
        $vendorDocumentOrm->mime = $file->getMimeType();

        $vendorDocumentOrm->filename = $file->getClientOriginalName();
        $vendorDocumentOrm->save();
        $documentType = VendorDocumentType::where('id','=',$documentTypeId)->first();
        $folder = "other";
        if($documentType){
            $folder=$documentType->directory;
        }

        $imageUploadPath = storage_path() . DIRECTORY_SEPARATOR;
        $imageUploadPath .= "app".DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR;
        if (! File::exists ( $imageUploadPath )) {
            File::makeDirectory ( $imageUploadPath, 0775, true, true );
        }

        if (! File::exists ( $imageUploadPath )) {
            File::makeDirectory ( $imageUploadPath, 0775, true, true );
        }

        try{
            if($file->move ( $imageUploadPath, $vendorDocumentOrm->id.".png" )){
                return VendorDocument::where('id','=',$vendorDocumentOrm->id)->with('documentType')->first();
            }
        }catch (Exception $e){
            return false;
        }


    }

    public function addVendorDocumentToVendor($vendorId,$documentList){
        foreach($documentList as $tempDocId){
            $vendorDoc = VendorDocument::where('id','=',$tempDocId)->first();
            $vendorDoc->vendor_id= $vendorId;
            $vendorDoc->save();
        }
    }


    public function updateVendorAvailabilityByORM($vendorId, $vendorAvailabilityOrm){
        try{
            $vendorAvailabilityOrm->vendor_id = $vendorId;
            $vendorAvailabilityOrm->save();
            return $vendorAvailabilityOrm;
        }catch (\SQLiteException $ex){
            return false;
        }
    }
    public function deleteVendorDocument($vendorDocumentId){
        $deleteResponse = VendorDocument::where('id','=',$vendorDocumentId)->delete();
        if($deleteResponse){
            return true;
        }
        return false;
    }

    public function getVendorDocumentById($documentId){
        return VendorDocument::where('id','=',$documentId)->with('documentType')->first();
    }

    public function getAvailableVendors(){
        return User::where('user_type_id','=',2)->get();
    }

    public function changeVendorFlag($vendorId, $currentFlag){
        $setFlag = 1;
        if($currentFlag == 1){
            $setFlag = 0;
        }
        $response = User::where('id', $vendorId)->update(['is_flagged' => $setFlag]);
        if($response){
            return true;
        }
        return false;
    }

}