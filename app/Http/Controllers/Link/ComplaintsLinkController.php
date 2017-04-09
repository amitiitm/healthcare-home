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
use App\Models\ORM\Complaints;

class ComplaintsLinkController extends Controller
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

    public function complaints()
    {
        $model = array('services'=>$this->operationDomainService->getServices(true));
        return view('complaints.complaints')->with('model',$model);
    }

    public function complaint($complaintId,$userType)
    {
        $model['complaintId'] = $complaintId;
        $model['userType'] = $userType;
        return view('complaints.complaint')->with('model',$model);
    }

    public function newComplaint(){
        return view('admin.complaints.newcomplaint');
    }

    public function complaints_resolution_groups()
    {
        return view('complaints.complaints_resolution_groups');
        //$model = array('services'=>$this->operationDomainService->getServices(true));
        //return view('complaints.complaints_resolution_groups')->with('model',$model);
    }

    public function replacement_requests()
    {
        return view('complaints.replacement_requests');
    }




}