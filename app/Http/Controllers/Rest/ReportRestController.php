<?php

namespace App\Http\Controllers\Rest;

use App\Contracts\Rest\ICommonRestContract;
use App\Contracts\Rest\IOperationRestContract;
use App\Contracts\Rest\IUserRestContract;
use App\Contracts\Rest\IVendorRestContract;
use App\Http\Controllers\Controller;
use App\Models\ORM\Gender;
use App\Models\ORM\LocationZone;
use App\Templates\PRResponse;
use App\Templates\SCResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ReportRestController extends Controller
{
    protected $operationRestService;

    protected $commonRestServices;

    public function __construct( IOperationRestContract $IOperationRestContract, ICommonRestContract $commonRestContract)
    {
        $this->operationRestService = $IOperationRestContract;
        $this->commonRestServices = $commonRestContract;
    }


    public function getActiveProjectGridData(){
        return Response::json(PRResponse::getSuccessResponse("",$this->operationRestService->getActiveProjectGridList()));
    }

    public function getVendorAttendanceReport(){
        $date = Input::get('date');
        return Response::json(PRResponse::getSuccessResponse("",$this->operationRestService->getVendorAttendanceReport($date)));
    }

    public function getSalaryByPeriodReport(){
        $dateFrom = Input::get('dateFrom');
        $dateTo = Input::get('dateTo');
        $showMode = Input::get('showMode');
        return Response::json(PRResponse::getSuccessResponse("",$this->operationRestService->getSalaryByPeriodReport($dateFrom,$dateTo,$showMode)));
    }

}