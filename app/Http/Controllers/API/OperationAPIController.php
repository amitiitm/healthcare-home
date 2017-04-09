<?php

namespace App\Http\Controllers\API;

use App\Contracts\Rest\ICommonRestContract;
use App\Contracts\Rest\IOperationRestContract;
use App\Contracts\Rest\IUserRestContract;
use App\Http\Controllers\Controller;
use App\Models\DTO\AilmentDTO;
use App\Models\DTO\Mobile\CaregiverLeadItemDTO;
use App\Models\DTO\Mobile\CaregiverLeadListItemDTO;
use App\Models\ORM\Agency;
use App\Models\ORM\Gender;
use App\Models\ORM\Lead;
use App\Models\ORM\LocationZone;
use App\Models\ORM\Qualification;
use App\Models\ORM\VendorSource;
use App\Templates\PRResponse;
use App\Templates\SCResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

class OperationAPIController extends Controller
{
    protected $operationRestService;

    protected $commonRestServices;

    public function __construct( IOperationRestContract $operationRestService, ICommonRestContract $commonRestContract)
    {
        $this->operationRestService = $operationRestService;
        $this->commonRestServices = $commonRestContract;
    }

    public function getLeadListForCG(){
        $leadTop = Lead::orderBy('id', 'desc')->with('locality')->with('patient')->with('patient.shift')->take(10)->get();

        $leadItemList = array();
        $caregiverLeadListDto = new CaregiverLeadListItemDTO();
        foreach($leadTop as $tempLeadItem){
            array_push($leadItemList,$caregiverLeadListDto->convertToDTO($tempLeadItem));
        }
        return Response::json(PRResponse::getSuccessResponse('',$leadItemList));
    }

    public function getLeadForCG($leadId){
        $leadTop = Lead::where('id','=',$leadId)->orderBy('id', 'desc')->with('locality')->with('patient')->with('patient.ailments')->with('patient.shift')->first();
        $caregiverLeadItemDtoTemp = new CaregiverLeadItemDTO();
        $caregiverLeadItemDto = $caregiverLeadItemDtoTemp->convertToDTO($leadTop);
        $taskListLabel = array();
        $ailmentItemList = array();
        $ailmentDto = new AilmentDTO();
        if($leadTop->patient && $leadTop->patient->ailments){
            foreach($leadTop->patient->ailments as $tempAilment){
                array_push($ailmentItemList,$ailmentDto->convertToDTO($tempAilment));
            }
        }

        $caregiverLeadItemDto->setAilments($ailmentItemList);
        if($leadTop->patient){
            $validationData = $this->operationRestService->getPatientValidationData($leadTop->patient->id);
            //d($validationData);
            foreach($validationData->tasks as $tempCat){
                    //d($tempCat);
                foreach($tempCat->tasks as $tempItem){
                    if(isset($tempItem->selected) && $tempItem->selected==true){
                        array_push($taskListLabel,$tempItem);
                    }
                }
            }
        }
        $caregiverLeadItemDto->setTaskRequired($taskListLabel);

        return Response::json(PRResponse::getSuccessResponse('',$caregiverLeadItemDto));
    }

    public function submitLeadResponseByCG($leadId){
        return Response::json(PRResponse::getSuccessResponse('Successfully updated response.', array()));
    }


    public function leadClosureOptions(){
        return Response::json(PRResponse::getSuccessResponse('Lead closure list.', $this->operationRestService->getLeadClosureOptions()));
    }

    public function leadClosureRequest(){

        return Response::json($this->operationRestService->submitLeadClosureRequest(Input::all()));
    }

    public function leadVendorNotReachedStatus(){
        return Response::json($this->operationRestService->submitVendorNotReachedStatus(Input::all()));
    }


    public function leadMarkCGAttendance(){
        return Response::json($this->operationRestService->submitCustomerCaregiverAttendance(Input::all()));
    }

    public function leadRestartRequest($leadId){

        $customerId = Authorizer::getResourceOwnerId();
        return Response::json($this->operationRestService->leadRestartRequest($customerId,$leadId));
    }
    public function customerFeedbackSubmit(){

        $customerId = Authorizer::getResourceOwnerId();
        return Response::json($this->operationRestService->submitCustomerFeedback($customerId,Input::all()));
    }

    public function updateLeadPhysioPatientInfo($leadId){
        $inputAll = Input::all();
        return Response::json($this->operationRestService->updateLeadPhysioPatientInfo($leadId,$inputAll));
    }


    public function updateLeadPatientInfoMobile($leadId){
        $inputAll = Input::all();
        // print_r($inputAll);
        return Response::json($this->operationRestService->updateLeadPatientInfoMobile($leadId,$inputAll));
    }

    public function updateLeadTaskInfoMobile($leadId){
        $inputAll = Input::all();
        return Response::json($this->operationRestService->updateLeadTaskInfoMobile($leadId,$inputAll));
    }

    public function updateLeadSpecialRequestMobile($leadId){
        $inputAll = Input::all();
        return Response::json($this->operationRestService->updateLeadSpecialRequest($leadId,$inputAll));
    }
}