<?php

namespace App\Http\Controllers\API;

use App\Contracts\Rest\ICommonRestContract;
use App\Contracts\Rest\IUserRestContract;
use App\Http\Controllers\Controller;
use App\Models\ORM\Incentives;
use App\Templates\PRResponse;
use App\Templates\SCResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use Carbon\Carbon;

class SalaryAPIController extends Controller
{
    protected $commonRestServices;

    public function __construct( ICommonRestContract $commonRestContract)
    {
        $this->commonRestServices = $commonRestContract;
    }

    public function postAddIncentiveAdmin(){
        $this->postAddIncentive('admin');
        return Response::json(PRResponse::getSuccessResponse('','Success'));
    }

    public function postAddIncentiveAPI(){
        $this->postAddIncentive('user');
        return Response::json(PRResponse::getSuccessResponse('','Success'));
    }

    public function postAddIncentive($userType=""){
        $incentive = new Incentives;

        if($userType == "admin"){
            $incentive->user_id = Input::get('userId');
        } else {
            $incentive->user_id = Authorizer::getResourceOwnerId();
        }
        
        $incentive->amount = 0;
        $incentive->type = 'daily confirmation';
        $incentive->comment = '';
        $incentive->lead_id = Input::get('lead_id');
        $incentive->is_active = 0;

        $attendanceDateCarbon = Carbon::now();
        $attendanceDateCarbon->timestamp(time());
        $incentive->date = $attendanceDateCarbon->toDateString();
        $incentive->save();

        return true;
    }

}