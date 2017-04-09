<?php

namespace App\Services\Rest;

use App\Contracts\Domain\IOperationDomainContract;
use App\Contracts\Domain\IUserDomainContract;
use App\Contracts\Domain\IVendorDomainContract;
use App\Contracts\Rest\IUserRestContract;
use App\Contracts\Rest\IVendorRestContract;
use App\Models\DTO\TaskCategoryDTO;
use App\Models\DTO\UserGridItemDTO;
use App\Models\DTO\UserMinimalDTO;
use App\Models\DTO\VendorDetailedDTO;
use App\Models\DTO\VendorDocumentDTO;
use App\Models\DTO\VendorGidItemDTO;
use App\Models\DTO\VendorGridItemDTO;
use App\Models\Enums\SCConstants;
use App\Models\ORM\Locality;
use App\Models\ORM\UserVendor;
use App\Models\ORM\VendorAvailability;
use App\Models\ORM\VendorBankDetail;
use App\Models\User;
use App\Templates\PRResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class VendorRestService implements IVendorRestContract
{
    protected $userDomainService;
    protected $vendorDomainService;
    protected $operationDomainService;

    public function __construct(IUserDomainContract $userDomainService, IVendorDomainContract $vendorDomainContract, IOperationDomainContract $IOperationDomainContract)
    {
        $this->vendorDomainService = $vendorDomainContract;
        $this->userDomainService = $userDomainService;
        $this->operationDomainService = $IOperationDomainContract;
    }

    public function getVendorDeployedStatusList(){
        $vendorDeployedList = $this->vendorDomainService->getDeployedVendor();
    }
    
    public function getVendorGridListByLead($leadId){
        $userOrmList = $this->vendorDomainService->getVendorListByLead($leadId);
        //$userList = $userOrmList->reverse();
        $userList = $userOrmList;
        $returnList = array();
        $vendorGridItemDto = new VendorGridItemDTO();
        //$vendorDeployedStatusMapped = $this->vendorDomainService->getDeployedVendor();
        foreach($userList as $tempUser){
            $tempDto = $vendorGridItemDto->convertToDTO($tempUser);
            //2239
            //if(isset($vendorDeployedStatusMapped[$tempDto->id])){
            if(false){
                $tempDto->setDeployed(1);
            }else{
                $tempDto->setDeployed(0);
            }

            //check if app installed
            //$appDB = \DB::select( \DB::raw("SELECT COUNT(*) AS appTimes FROM oauth_sessions WHERE owner_id = :owner_id AND client_id = :client_id"), array('owner_id' => $tempDto->id, 'client_id'=>'androidapp' ));
            //if($appDB[0]->appTimes > 0)
                //$tempDto->appInstalled = true;
            //else
                $tempDto->appInstalled = false;

            if($tempDto->getName()!=""){
                array_push($returnList,$tempDto);
            }

        }
        //return array_reverse($returnList);
        return $returnList;
    }
    
    public function getVendorGridList($pageNumber,$pageSize){
        if($pageNumber && $pageNumber > 0){
            $userOrmList = $this->vendorDomainService->getVendorList($pageNumber,$pageSize);
        }else{
          $userOrmList = $this->vendorDomainService->getVendorList(0,0);  
        }
        $userList = $userOrmList->reverse();
        $returnList = array();
        $vendorGridItemDto = new VendorGridItemDTO();
        //$vendorDeployedStatusMapped = $this->vendorDomainService->getDeployedVendor();        
        foreach($userList as $tempUser){
            $tempDto = $vendorGridItemDto->convertToDTO($tempUser);
            //2239
            //if(isset($vendorDeployedStatusMapped[$tempDto->id])){
            if(false){
                $tempDto->setDeployed(1);
            }else{
                $tempDto->setDeployed(0);
            }

            //check if app installed
            //$appDB = \DB::select( \DB::raw("SELECT COUNT(*) AS appTimes FROM oauth_sessions WHERE owner_id = :owner_id AND client_id = :client_id"), array('owner_id' => $tempDto->id, 'client_id'=>'androidapp' ));
            //if($appDB[0]->appTimes > 0)
                //$tempDto->appInstalled = true;
            //else
                $tempDto->appInstalled = false;

            if($tempDto->getName()!=""){
                array_push($returnList,$tempDto);
            }

        }
        //echo dump($returnList);
        //die();
        //return array_reverse($returnList);
        return $returnList;
    }
    
    public function getVendorCount(){
        return UserVendor::count();
    }

    private function prepareVendorOrm($inputAll){
        $vendorOrm = new UserVendor();
        if(isset($inputAll['gender']) && isset($inputAll['gender']['id'])){
            $vendorOrm->gender = $inputAll['gender']['id'];
        }
        if(isset($inputAll['work_for_male'])){
            $vendorOrm->works_for_male = $inputAll['work_for_male'];
        }

        if(isset($inputAll['category']) && isset($inputAll['category']['id'])){
            $vendorOrm->employee_category_id = $inputAll['category']['id'];
        }
        if(isset($inputAll['weight'])){
            $vendorOrm->weight = $inputAll['weight'];
        }
        if(isset($inputAll['age'])){
            $vendorOrm->age = $inputAll['age'];
        }
        if(isset($inputAll['height'])){
            $vendorOrm->height = $inputAll['height'];
        }
        if(isset($inputAll['religion']) && isset($inputAll['religion']['id'])){
            $vendorOrm->religion_id = $inputAll['religion']['id'];
        }
        if(isset($inputAll['food']) && isset($inputAll['food']['id'])){
            $vendorOrm->food_type_id = $inputAll['food']['id'];
        }

        if(isset($inputAll['address'])){
            $vendorOrm->address = $inputAll['address'];
        }
        if(isset($inputAll['alternate_no'])){
            $vendorOrm->alternate_no = $inputAll['alternate_no'];
        }
        if(isset($inputAll['has_smart_phone'])){
            $vendorOrm->has_smart_phone = $inputAll['has_smart_phone'];
        }
        if(isset($inputAll['has_bank_account'])){
            $vendorOrm->has_bank_account = $inputAll['has_bank_account'];
        }
        if(isset($inputAll['qualification']) && isset($inputAll['qualification']['id'])){
            $vendorOrm->qualification_id = $inputAll['qualification']['id'];
        }
        if(isset($inputAll['training_attended'])){
            $vendorOrm->training_attended = $inputAll['training_attended'];
        }
        if($vendorOrm->training_attended && isset($inputAll['training_date'])){
            $dateValue = strtotime($inputAll['training_date']);
            $dateValueCarbon = Carbon::now();
            $dateValueCarbon->timestamp($dateValue);
            $dateValueCarbon->hour = 0;
            $dateValueCarbon->minute = 0;
            $dateValueCarbon->second = 0;
            $vendorOrm->training_attended_date = $dateValueCarbon;
        }
        if(!$vendorOrm->training_attended){
            if(isset($inputAll['training_not_attended_reason']) && isset($inputAll['training_not_attended_reason']['id'])){
                $vendorOrm->training_not_attended_reason_id = $inputAll['training_not_attended_reason']['id'];
            }
            if(isset($inputAll['training_not_attended_other_reason'])){
                $vendorOrm->training_not_attended_other_reason = $inputAll['training_not_attended_other_reason'];
            }
        }

        if(isset($inputAll['experience'])){
            $vendorOrm->experience = $inputAll['experience'];
        }
        if(isset($inputAll['preferred_shift']) && isset($inputAll['preferred_shift']['id'])){
            $vendorOrm->preferred_shift_id = $inputAll['preferred_shift']['id'];
        }
        if(isset($inputAll['zone']) && isset($inputAll['zone']['id'])){
            $vendorOrm->location_of_work = $inputAll['zone']['id'];
        }
        if(isset($inputAll['agency']) && isset($inputAll['agency']['id'])){
            $vendorOrm->agency_id = $inputAll['agency']['id'];
        }
        if(isset($inputAll['v']) && isset($inputAll['agency']['id'])){
            $vendorOrm->agency_id = $inputAll['agency']['id'];
        }
        if(isset($inputAll['agency']) && isset($inputAll['agency']['id'])){
            $vendorOrm->agency_id = $inputAll['agency']['id'];
        }
        if(isset($inputAll['voter'])){
            $vendorOrm->voter = $inputAll['voter'];
        }
        if(isset($inputAll['aadhar'])){
            $vendorOrm->aadhar = $inputAll['aadhar'];
        }
        if(isset($inputAll['source']) && isset($inputAll['source']['id'])){
            $vendorOrm->source_id = $inputAll['source']['id'];
        }

        return $vendorOrm;
    }

    private function addBankDetail($inputAll,$vendorId){
        if(!isset($inputAll['name'])){
            return;
        }
        $bankDetail = new  VendorBankDetail();
        $bankDetail->vendor_id = $vendorId;
       // d($vendorId);

        $bankDetail->name = $inputAll['name'];
        $bankDetail->bank_name = $inputAll['bankName'];
        $bankDetail->account_no = $inputAll['accountNo'];
        $bankDetail->ifsc = $inputAll['ifsc'];
        $bankDetail->save();
    }

    private function updateBankDetail($inputAll,$vendorId){

        $bankDetail = VendorBankDetail::where('vendor_id','=',$vendorId)->first();
        if(!$bankDetail){
            $bankDetail = new VendorBankDetail();
            $bankDetail->vendor_id = $vendorId;
        }
        $bankDetail->vendor_id = $vendorId;
        $bankDetail->name = $inputAll['name'];
        $bankDetail->bank_name = $inputAll['bank_name'];
        $bankDetail->account_no = $inputAll['account_no'];
        $bankDetail->ifsc = $inputAll['ifsc'];
        $bankDetail->save();
    }


    private function prepareVendorLocalityOrm($inputLocality){

        if(!isset($inputLocality['id'])){
            return false;
        }
        $localityOrm = new Locality();
        $localityOrm->json = json_encode($inputLocality);
        $localityOrm->formatted_address = $inputLocality['formatted_address'];
        return $localityOrm;
    }
    private function updateVendorOrm($inputAll,$vendorId){
       // $vendorOrm = new UserVendor();
       
       $vendorOrm = UserVendor::where('id','=',$vendorId)->first();

        if(isset($inputAll['gender']) && isset($inputAll['gender']['id'])){
            $vendorOrm->gender = $inputAll['gender']['id'];
        }
        if(isset($inputAll['worksForMale']) ){
            $vendorOrm->works_for_male = $inputAll['worksForMale'];
        }
        if(isset($inputAll['category']) && isset($inputAll['category']['id'])){
            $vendorOrm->employee_category_id = $inputAll['category']['id'];
        }
        if(isset($inputAll['weight'])){
            $vendorOrm->weight = $inputAll['weight'];
        }
        if(isset($inputAll['age'])){
            $vendorOrm->age = $inputAll['age'];
        }
        if(isset($inputAll['height'])){
            $vendorOrm->height = $inputAll['height'];
        }
        if(isset($inputAll['religion']) && isset($inputAll['religion']['id'])){
            $vendorOrm->religion_id = $inputAll['religion']['id'];
        }
        if(isset($inputAll['food']) && isset($inputAll['food']['id'])){
            $vendorOrm->food_type_id = $inputAll['food']['id'];
        }

        if(isset($inputAll['address'])){
            $vendorOrm->address = $inputAll['address'];
        }
        if(isset($inputAll['alternateNo'])){
            $vendorOrm->alternate_no = $inputAll['alternateNo'];
        }
        if(isset($inputAll['hasSmartPhone'])){
            $vendorOrm->has_smart_phone = $inputAll['hasSmartPhone'];
        }
        if(isset($inputAll['hasBankAccount'])){
            $vendorOrm->has_bank_account = $inputAll['hasBankAccount'];
        }
        if(isset($inputAll['qualification']) && isset($inputAll['qualification']['id'])){
            $vendorOrm->qualification_id = $inputAll['qualification']['id'];
        }
        if(isset($inputAll['trainingAttended'])){
            $vendorOrm->training_attended = $inputAll['trainingAttended'];
        }
        if(isset($inputAll['trainingDate'])){

            $dateValue = strtotime($inputAll['trainingDate']);
            $dateValueCarbon = Carbon::now();
            $dateValueCarbon->timestamp($dateValue);
            $dateValueCarbon->hour = 0;
            $dateValueCarbon->minute = 0;
            $dateValueCarbon->second = 0;
            $vendorOrm->training_attended_date = $dateValueCarbon;
        }

        if(isset($inputAll['trainingNotAttendedReason']) && isset($inputAll['trainingNotAttendedReason']['id'])){
            $vendorOrm->training_not_attended_reason_id = $inputAll['trainingNotAttendedReason']['id'];
        }
        if(isset($inputAll['trainingNotAttendedOtherReason'])){
            $vendorOrm->training_not_attended_other_reason	 = $inputAll['trainingNotAttendedOtherReason'];
        }
        if(isset($inputAll['experience'])){
            $vendorOrm->experience = $inputAll['experience'];
        }
        if(isset($inputAll['preferred_shift']) && isset($inputAll['preferred_shift']['id'])){
            $vendorOrm->preferred_shift_id = $inputAll['preferred_shift']['id'];
        }
        if(isset($inputAll['zone']) && isset($inputAll['zone']['id'])){
            $vendorOrm->location_of_work = $inputAll['zone']['id'];
        }
        if(isset($inputAll['agency']) && isset($inputAll['agency']['id'])){
            $vendorOrm->agency_id = $inputAll['agency']['id'];
        }
        if(isset($inputAll['shift']) && isset($inputAll['shift']['id'])){
            $vendorOrm->preferred_shift_id = $inputAll['shift']['id'];
        }
        if(isset($inputAll['voter'])){
            $vendorOrm->voter = $inputAll['voter'];
        }
        if(isset($inputAll['aadhar'])){
            $vendorOrm->aadhar = $inputAll['aadhar'];
        }
        if(isset($inputAll['source']) && isset($inputAll['source']['id'])){
            $vendorOrm->source_id = $inputAll['source']['id'];
        }

        return $vendorOrm;
    }


    private function getTaskSelected($validationData){
        $taskSelectedList = array();
        foreach($validationData as $tempValCat){
            foreach($tempValCat['tasks'] as $tempTask){
                if(isset($tempTask['selected']) && $tempTask['selected']==true){
                    array_push($taskSelectedList,$tempTask['id']);
                }
            }
        }
        return $taskSelectedList;
    }
    
    public function submitVendor($inputAll) {
        $vendorOrm = $this->prepareVendorOrm($inputAll);

        $vendorOrm->added_by_user_id = Auth::user()->id;

        $voterData =$this->vendorDomainService->voterIdExist($vendorOrm->voter);
        if($voterData && $vendorOrm->voter!='')
        {
            return PRResponse::getErrorResponse('voter id value already exist','Voter id already Exists');
        }
        
        $phoneData =$this->vendorDomainService->phoneExist($inputAll['phone']);
        if($phoneData)
        {
            return PRResponse::getErrorResponse('Phone Already Exists.','Phone Already Exists.');
        }
        
        $aadharData =$this->vendorDomainService->aadharIdExist($vendorOrm->aadhar);
        if($aadharData && $vendorOrm->aadhar!='')
        {
            return PRResponse::getErrorResponse('Aadhar id value already exist','aadhar');
        }else{

        }
        if(!isset($inputAll['email']) || $inputAll['email']==""){
            $vendorCount = $this->vendorDomainService->getVendorCount();
            $inputAll['email']=$vendorCount."vendor@pramaticare.com";
        }else if(isset($inputAll['email']) && $inputAll['email']!=""){
            $emailExist = $this->vendorDomainService->isEmailExistForAdd($inputAll['email']);
            if($emailExist){
                return PRResponse::getErrorResponse('Email already exist.','email');
            }
        }
//d($inputAll['locality']);
        if(isset($inputAll['locality']) && isset($inputAll['locality']['id'])){
            $localityOrm = $this->prepareVendorLocalityOrm($inputAll['locality']);
            if($localityOrm){
                $saveLocalityOrm = $this->vendorDomainService->createLocalityByOrm($localityOrm);
                $vendorOrm->locality_id = $saveLocalityOrm->id;
            }else{
                $vendorOrm->locality_id = null;
            }
        }


        $createUser = $this->vendorDomainService->createVendorUser($inputAll['name'],$inputAll['email'],$inputAll['phone'],'pramaticare');
        $vendorOrm->user_id = $createUser->id;
        $vendorOrm->is_active = true;
        $vendorCreated = $this->vendorDomainService->createVendorDetailByORM($vendorOrm);

        if($inputAll['documents'] && count($inputAll['documents'])>0){
            $this->vendorDomainService->addVendorDocumentToVendor($vendorCreated->id,$inputAll['documents']);

        }

        $taskList = $this->getTaskSelected($inputAll['validationData']);
        if(count($taskList)>0){
            $this->vendorDomainService->updateVendorTask($vendorCreated->id,$taskList);
        }
        if($vendorOrm->has_bank_account==true){
            $this->addBankDetail($inputAll['bank_account'],$vendorCreated->id);
        }
        else{

        }

        if($vendorCreated && $createUser){
            return PRResponse::getSuccessResponse('Successfully added caregiver',$vendorCreated);
        }
        return PRResponse::getErrorResponse('Unable to add vendor, please contact system administrator',array());


    }

    public function getVendorDetail($vendorId){
        $vendorOrm = $this->vendorDomainService->getVendorDetailedOrm($vendorId);
      // dd($vendorOrm);
        $vendorDetailedDto = new VendorDetailedDTO();
        return ($vendorDetailedDto->convertToDTO($vendorOrm));
    }


    public function deleteVendorDetail($vendorId){
        $vendorOrm = User::where('id','=',$vendorId)->delete();
        return $vendorOrm;
    }

    public function deleteVendorsDetails($vendorIds){
        $ids = $vendorIds['vendorIds'];
        if(!empty($ids)){
            foreach ($ids as $vendorId) {
                $vendorOrm = User::where('id','=',$vendorId)->delete();
            }
        }
        return TRUE;
    }

    public  function  updateVendorDetail($inputAll , $vendorId){
        $vendorOrm = $this->updateVendorOrm($inputAll,$vendorId);
        $vendorDetailedOrm = $this->vendorDomainService->getVendorDetailedOrm($vendorOrm->user_id);
        $locality = ($vendorDetailedOrm->localityObject);

        $phoneData =$this->vendorDomainService->phoneExistForUpdate($inputAll['phone'],$inputAll['userId']);
        if($phoneData)
        {
            return PRResponse::getErrorResponse('Phone Already Exists.','Phone Already Exists.');
        }               

        $vendorsWithSameVoterIdExist =$this->vendorDomainService->voterIdExistForUpdate($vendorOrm->voter,$vendorId);
        if($vendorOrm->voter != "" && !$vendorsWithSameVoterIdExist){
            return PRResponse::getErrorResponse("Voter id is already registered with some other caregiver",array());
        }
        $vendorsWithSameAadharIdExist =$this->vendorDomainService->aaddharIdExistForUpdate($vendorOrm->aadhar,$vendorId);
        if($vendorOrm->aadhar != "" && !$vendorsWithSameAadharIdExist){
            return PRResponse::getErrorResponse("Aaddhar id is already registered with some other caregiver",array());
        }

        if($inputAll['email']==""){
            $vendorCount = $this->vendorDomainService->getVendorCount();
            $inputAll['email']=$vendorCount."vendor@pramaticare.com";
        }else{
            // TODO: Email check and update
        }

        $vendorOrm->is_active = true;
        if(isset($inputAll['locality']) && isset($inputAll['locality']['formatted_address']) && $inputAll['locality']['formatted_address']!=$locality->formatted_address){
            // todo: update locality
            $locality->json = json_encode($inputAll['locality']);
            $locality->formatted_address = trim($inputAll['locality']['formatted_address']);
            $locality->save();
        }
        $vendorCreated = $this->vendorDomainService->updateVendorDetailByORM($vendorOrm);
        $createUser = $this->vendorDomainService->updateVendorUser($vendorCreated->user_id ,$inputAll['name'],$inputAll['email'],$inputAll['phone'],'pramaticare');


        $vendorDetail = $this->vendorDomainService->getVendorBankDetailedOrmByVendorId($vendorId);
       // d($vendorDetail);
        if($vendorOrm->hasBankAccount==true){
            $this->updateBankDetail($inputAll['bankDetail'],$vendorId);
        }

        $taskList = $this->getTaskSelected($inputAll['validationData']);
        if(count($taskList)>0){
            $this->vendorDomainService->updateVendorTask($vendorCreated->id,$taskList);
        }

        if($vendorCreated && $createUser){
            return PRResponse::getSuccessResponse('updated sucessfully',$vendorCreated);
        }
        return PRResponse::getErrorResponse('Unable to add vendor, please contact system administrator',array());
    }

    public function getVendorAvailabilityOptions(){
        $vendorAvailabilityOptionWithReasons = $this->vendorDomainService->getVendorAvailabilityOptionsWithReason();
        return $vendorAvailabilityOptionWithReasons;
    }
    public function getVendorDocumentTypes(){
        $vendorDocumentTypeOrmList = $this->vendorDomainService->getVendorDocumentTypes();
        return $vendorDocumentTypeOrmList;
    }
    public  function  getVendorTrainingReasons(){
        $vendorDocumentTypeOrmList = $this->vendorDomainService->getVendorTrainingReasons();
        return $vendorDocumentTypeOrmList;
    }
    public function getVendorAvailabilityMapper(){

    }
    public function uploadVendorDocument($file,$inputAll){
        $docData = ((array)json_decode($inputAll['data']));
        $docTypeId = null;
        $docCaption = '';
        $docVendorId = null;
        if(isset($docData['type']) && $docData['type']->id){
            $docTypeId = $docData['type']->id;
        }
        if(isset($docData['caption']) && $docData['caption']!=""){
            $docCaption=$docData['caption'];
        }
        if(isset($docData['vendor_id']) && $docData['vendor_id']){
            $docVendorId=$docData['vendor_id'];
        }
        $docOrm = $this->vendorDomainService->uploadVendorDocument($file,$docTypeId,$docCaption,$docVendorId);
        if($docOrm){
            // TODO: create DTO
            $vendorDocumentDto = new VendorDocumentDTO();
            return $vendorDocumentDto->convertToDTO($docOrm);
        }
        return false;
    }

    public function deleteVendorDocument($vendorDocumentId){
        // TODO : add permission to delete vendor document

        $deleteResponse = $this->vendorDomainService->deleteVendorDocument($vendorDocumentId);
        //var_dump($deleteResponse);
        if($deleteResponse){

            return PRResponse::getSuccessResponse("Vendor document deleted successfully", array());
        }
        return PRResponse::getErrorResponse("Unbale to delete vendor document. Unknown error occured",array());

    }

    public function updateVendorAvailability($vendorId,$inputAll){
        $available = $inputAll['available'];
        $vendorAvailabilityOrm = new VendorAvailability();
        $vendorAvailabilityOrm->available = $available;

        if(isset($inputAll['option']) && isset($inputAll['option']['id']) && $inputAll['option']['id']!=""){
            $availabilityOption = $inputAll['option'];
            $vendorAvailabilityOrm->option_id= $availabilityOption['id'];
            if($availabilityOption['slug']=="employed-somewhere" || $availabilityOption['slug']=="date-available"){
                $dateValue = strtotime($inputAll['date']);
                $dateValueCarbon = Carbon::now();
                $dateValueCarbon->timestamp($dateValue);
                $dateValueCarbon->hour = 0;
                $dateValueCarbon->minute = 0;
                $dateValueCarbon->second = 0;
                $vendorAvailabilityOrm->available_date = $dateValueCarbon;
            }else{
                $vendorAvailabilityOrm->available_date = null;
            }
            if(isset($inputAll['reason']) && isset($inputAll['reason']['id'])){
                $vendorAvailabilityOrm->reason_id = $inputAll['reason']['id'];
            }else{
                $vendorAvailabilityOrm->reason_id = null;
            }
            if(isset($inputAll['shift']) && isset($inputAll['shift']['id'])){
                $vendorAvailabilityOrm->changed_shift_id = $inputAll['shift']['id'];
            }else{
                $vendorAvailabilityOrm->changed_shift_id = null;
            }
            if(isset($inputAll['location']) && isset($inputAll['location']['id'])){
                $vendorAvailabilityOrm->changed_zone_id = $inputAll['location']['id'];
            }else{
                $vendorAvailabilityOrm->changed_zone_id = null;
            }
            if(isset($inputAll['otherReason']) && trim($inputAll['otherReason']) !=""){
                $vendorAvailabilityOrm->other_reason = $inputAll['otherReason'];
            }else{
                $vendorAvailabilityOrm->other_reason = "";
            }


        }
        $responseObject = $this->vendorDomainService->updateVendorAvailabilityByORM($vendorId,$vendorAvailabilityOrm);

        if($responseObject){
            return PRResponse::getSuccessResponse('Availability updated successfully', array());
        }
        return PRResponse::getErrorResponse("Unable to update vendor availability.", array());


    }


    public function getVendorTaskDetailGrouped($vendorId){
        $getTaskCategoryList = $this->operationDomainService->getTaskCategoryWithTask();
        $taskCategoryDto = new TaskCategoryDTO();
        $toReturnArray = array();
        foreach($getTaskCategoryList as $temp){
            array_push($toReturnArray,$taskCategoryDto->convertToDTO($temp));
        }
        $vendorOrm = $this->vendorDomainService->getVendorDetailedOrm($vendorId);
        $taskMapper = [];
        foreach($vendorOrm->task as $tempTask){
            $taskMapper[$tempTask->id]=true;
        }
        foreach($toReturnArray as $tempTaskCat){
            foreach($tempTaskCat->tasks as $tempTask){
                if(isset($taskMapper[$tempTask->id]) && $taskMapper[$tempTask->id]==true){
                    $tempTask->selected = true;
                }else{
                    $tempTask->selected = false;
                }
            }
        }
        return $toReturnArray;


    }

    public function getAvailableVendors(){
        $vendorList = $this->vendorDomainService->getAvailableVendors();

        $toReturn = [];
        foreach($vendorList as $tempVendor){
            if(!$tempVendor->name || $tempVendor->name ==""){
                continue;
            }
            array_push($toReturn,array(
                'id'=>$tempVendor->id,
                'name'=>$tempVendor->name,
                'phone'=>$tempVendor->phone,
            ));
        }
        return $toReturn;
    }

    public function changeVendorFlag($vendorId, $currentFlag){
        $responseObject = $this->vendorDomainService->changeVendorFlag($vendorId,$currentFlag);

        if($responseObject){
            return PRResponse::getSuccessResponse('Flag Changed.', array());
        }
        return PRResponse::getErrorResponse("Unable to update flag.", array());


    }

    public function getVendorIdByUserId($userId){
        return UserVendor::where('user_id',$userId)->first()->id;
    }


}