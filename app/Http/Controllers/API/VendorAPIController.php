<?php

namespace App\Http\Controllers\API;

use App\Contracts\Domain\IVendorDomainContract;
use App\Contracts\Rest\ICommonRestContract;
use App\Contracts\Rest\IOperationRestContract;
use App\Contracts\Rest\IUserRestContract;
use App\Contracts\Rest\IVendorRestContract;
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

class VendorAPIController extends Controller
{
    protected $vendorRestService;

    protected $commonRestServices;

    protected $vendorRestServiceCg;

    public function __construct( IVendorDomainContract $vendorRestService, ICommonRestContract $commonRestContract, IVendorRestContract $vendorRestServiceCg)
    {
        $this->vendorRestService = $vendorRestService;
        $this->commonRestServices = $commonRestContract;
        $this->vendorRestServiceCg = $vendorRestServiceCg;
    }


    public function updateCareGiverLocation(){
        $inputAll = Input::all();
        if(!Input::has('latitude')){
            return Response::json(PRResponse::getErrorResponse('Unable to get latitude',array()));
        }
        if(!Input::has('latitude')){
            return Response::json(PRResponse::getErrorResponse('Unable to get longitude',array()));
        }
        if(!Input::has('location_time')){
            //return Response::json(PRResponse::getErrorResponse('Location time is required',array()));
        }
        $responseObj = $this->vendorRestService->updateVendorTrackingLocation(Authorizer::getResourceOwnerId(),Input::get('latitude'),Input::get('longitude'), Input::get('location_time'));
        if($responseObj){
            return Response::json(PRResponse::getErrorResponse('Successfully updated caregiver location',array()));
        }
        return Response::json(PRResponse::getErrorResponse('Unable to update position',array()));
    }

    public function userProfile(){
        $userId = Authorizer::getResourceOwnerId();
        return Response::json(PRResponse::getSuccessResponse('',$this->vendorRestServiceCg->getVendorDetail($userId)));
    }

    

}