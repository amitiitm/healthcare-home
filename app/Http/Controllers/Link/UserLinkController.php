<?php
/**
 * Created by PhpStorm.
 * User: anand
 * Date: 4/11/16
 * Time: 7:28 AM
 */

namespace App\Http\Controllers\Link;


use App\Http\Controllers\Controller;
use App\Models\ORM\LeadFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use App\Templates\PRResponse;
class UserLinkController extends Controller
{
    public function user($id)
    {
        return view('user.user')->with('userId', $id);
    }

    public function leadFeedback(){
        return view('admin.feedback.feedbacks');
    }
    public function leadFeedbackData(){
        $feedbacks = LeadFeedback::orderBy('feedback_date','desc');
        return Datatables::of($feedbacks)
                ->editColumn('caregiver_name', function ($model) {              
                            return '<a href="/vendor/' . $model->caregiver_id .'" class="edit_link" data-value=' . $model->caregiver_id . '>'.$model->caregiver_name.'</a>';
                        }) 
                ->editColumn('created_at', function ($model) {              
                            return Carbon::parse($model->created_at)->format('Y-m-d');
                        })
                ->editColumn('feedback_date', function ($model) {              
                            return Carbon::parse($model->feedback_date)->format('Y-m-d');
                        })
                ->make(true);
    }
    public function leadFeedbackNew(){
        $leads = LeadFeedback::getLeadsForDropdown();
        $statuses = ['happy' => 'Happy','not_happy' => 'Not Happy'];
        return view('admin.feedback.new_lead_feedback',array('statuses' => $statuses,'leads' => $leads));
    }
    
    public function leadDetails($leadId){
        $lead_details = LeadFeedback::getLeadDetails($leadId);
        return Response::json(PRResponse::getSuccessResponse('',$lead_details));
    }
    
    public function leadFeedbackCreate(Request $request){
        $loggedInUser = $request->user();
        $feedback = new LeadFeedback();
        $input = $request->all();
        $feedback->fill($input);
        if($request['feedback_date'] == '') {
         $feedback->feedback_date = Carbon::now();  
        }  
        $feedback->created_by = $loggedInUser->id;
        $feedback->created_by_name = $loggedInUser->name;
        $feedback->save();
        return Redirect::action('Link\UserLinkController@leadFeedback');
    }
}