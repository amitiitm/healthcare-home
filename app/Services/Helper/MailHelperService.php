<?php

namespace App\Services\Helper;

use App\Contracts\Domain\IArticleDomainContract;
use App\Contracts\Domain\IOperationDomainContract;
use App\Contracts\Domain\IUserDomainContract;
use App\Contracts\Helper\IMailHelperContract;
use App\Contracts\Helper\ISlackHelperContract;
use App\Jobs\SendQCAssignmentEmail;
use App\Models\Article;
use App\Models\Enums\SCConstants;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Mail;

class MailHelperService implements IMailHelperContract
{
    use DispatchesJobs;

    protected $userDomainService;

    protected $slackHelperService;

    protected $operationDomainService;


    public function __construct(IUserDomainContract $userDomainContract, ISlackHelperContract $ISlackHelperContract, IOperationDomainContract $IOperationDomainContract)
    {
        $this->userDomainService = $userDomainContract;
        $this->slackHelperService = $ISlackHelperContract;
        $this->operationDomainService = $IOperationDomainContract;
    }

    public function sendMailOnEmployeeAssignment($leadOrm,$assignedId){

        return;
        $userOrm = $this->userDomainService->getUser($assignedId);

        $bccList = [];
        $ccList = [];
        $emails = [];
        if(env('APP_ENV')=="production") {
            $ccList = ['vishal@pramaticare.com','richa@pramaticare.com'];
            $emails = [$userOrm->email];
            $bccList = ['cs@pramaticare.com'];
        }else{
            $emails = [env('TESTING_MAIL')];
        }


        $mailSubject = 'Lead is assigned to '.$userOrm->name.' for CG assignment';

        $data = ['emailTo'=>$emails, 'subject'=>$mailSubject,'emailCCTo'=>$ccList, 'emailBccTo'=>$bccList];

            $userIdByEmail = $this->userDomainService->getUserIdByEmailList(array_merge($ccList,$emails,$bccList));
            $this->operationDomainService->addWatchersToLead($leadOrm->id,$userIdByEmail);



        Mail::queue('emails.employee_assigned_mailer', ['lead' => $leadOrm, 'assigneeUser'=>$userOrm, 'leadUrl'=>url('lead/'.$leadOrm->id)], function ($m) use ($data) {
            $m->from(env('PHPMAILER_FROM_EMAIL'),  env('PHPMAILER_FROM_NAME'));
            $m->to($data['emailTo'])
                ->cc($data['emailCCTo'])
                ->bcc($data['emailBccTo'])
                ->subject($data['subject']);
        });
    }


    public function sendMailOnQcAssignment($leadOrm,$assignedId){


        return;


        $userOrm = $this->userDomainService->getUser($assignedId);

        $bccList = [];
        $ccList = [];
        $emails = [];
        if(env('APP_ENV')=="production") {
            $ccList = ['cs@pramaticare.com', 'vishal@pramaticare.com','nandkishore@pramaticare.com','chetan@pramaticare.com'];
            $emails = [$userOrm->email];
            $bccList = ['cs@pramaticare.com'];
        }else{
            $emails = [env('TESTING_MAIL')];
        }

            $userIdByEmail = $this->userDomainService->getUserIdByEmailList(array_merge($ccList,$emails,$bccList));
            $this->operationDomainService->addWatchersToLead($leadOrm->id,$userIdByEmail);


        $mailSubject = 'Lead is assigned for quality control to '.$userOrm->name;

        $data = ['emailTo'=>$emails, 'subject'=>$mailSubject,'emailCCTo'=>$ccList, 'emailBccTo'=>$bccList];

        $mailView = view('emails.qc_assigned_mailer')->with('lead',$leadOrm);

        Mail::queue('emails.qc_assigned_mailer', ['lead' => $leadOrm, 'assigneeUser'=>$userOrm, 'leadUrl'=>url('lead/'.$leadOrm->id), 'carePlanUrl'=>url('operation/lead/careplan/'.$leadOrm->id)], function ($m) use ($data) {
            $m->from(env('PHPMAILER_FROM_EMAIL'),  env('PHPMAILER_FROM_NAME'));
            $m->to($data['emailTo'])
                ->cc($data['emailCCTo'])
                ->bcc($data['emailBccTo'])
                ->subject($data['subject']);
        });
    }

    public function sendMailForFieldAssignment($leadOrm){

        return;

        $bccList = [];
        $ccList = [];
        $emails = [];
        if(env('APP_ENV')=="production") {
            $ccList = ['mohit2007gupta@gmail.com','cs@pramaticare.com', 'vishal@pramaticare.com','chetan@pramaticare.com','nandkishore@pramaticare.com'];
            $emails = ['nandkishore@pramaticare.com'];
            $bccList = ['cs@pramaticare.com'];
        }else{
            $emails = [env('TESTING_MAIL')];
        }

        $userIdByEmail = $this->userDomainService->getUserIdByEmailList(array_merge($ccList,$emails,$bccList));
        $this->operationDomainService->addWatchersToLead($leadOrm->id,$userIdByEmail);

        $mailSubject = "Assign field employee for lead ".$leadOrm->customer_name;

        $data = ['emailTo'=>$emails, 'subject'=>$mailSubject,'emailCCTo'=>$ccList, 'emailBccTo'=>$bccList];

        $mailView = view('emails.field_assign_to_lead')->with('lead',$leadOrm);

        Mail::queue('emails.field_assign_to_lead', ['lead' => $leadOrm, 'leadUrl'=>url('lead/'.$leadOrm->id), 'carePlanUrl'=>url('operation/lead/careplan/'.$leadOrm->id)], function ($m) use ($data) {
            $m->from(env('PHPMAILER_FROM_EMAIL'),  env('PHPMAILER_FROM_NAME'));
            $m->to($data['emailTo'])
                ->cc($data['emailCCTo'])
                ->bcc($data['emailBccTo'])
                ->subject($data['subject']);
        });
    }

    public function sendMailOnFieldAssignment($leadOrm,$assignedId){

        return;

        $userOrm = $this->userDomainService->getUser($assignedId);

        $bccList = [];
        $ccList = [];
        $emails = [];

        //TODO: Caregiver assigned, QC assigned, and all other assigned employees And Nandu, Chetan
        if(env('APP_ENV')=="production") {
            $ccList = ['cs@pramaticare.com', 'nandkishore@pramaticare.com','vishal@pramaticare.com','neetu@pramaticare.com','richa@pramaticare.com'];
            $emails = [$userOrm->email];
            $bccList = ['cs@pramaticare.com'];
        }else{
            $emails = [env('TESTING_MAIL')];
        }

        $userIdByEmail = $this->userDomainService->getUserIdByEmailList(array_merge($ccList,$emails,$bccList));
        $this->operationDomainService->addWatchersToLead($leadOrm->id,$userIdByEmail);


        $mailSubject = 'Lead is assigned to '.$userOrm->name." for field operations";

        $data = ['emailTo'=>$emails, 'subject'=>$mailSubject,'emailCCTo'=>$ccList, 'emailBccTo'=>$bccList];

        $mailView = view('emails.field_assigned_mailer')->with('lead',$leadOrm);

        Mail::queue('emails.field_assigned_mailer', ['lead' => $leadOrm, 'assigneeUser'=>$userOrm, 'leadUrl'=>url('lead/'.$leadOrm->id), 'carePlanUrl'=>url('operation/lead/careplan/'.$leadOrm->id)], function ($m) use ($data) {
            $m->from(env('PHPMAILER_FROM_EMAIL'),  env('PHPMAILER_FROM_NAME'));
            $m->to($data['emailTo'])->cc($data['emailCCTo'])
                ->bcc($data['emailBccTo'])->subject($data['subject']);
        });
    }

    public function sendMailForCustomerAboutFieldAssignment($leadOrm){


        $bccList = [];
        $ccList = [];
        $emails = [];

        //TODO: Caregiver assigned, QC assigned, and all other assigned employees And Nandu, Chetan
        if(env('APP_ENV')=="production") {
            $ccList = ['cs@pramaticare.com', 'nandkishore@pramaticare.com','vishal@pramaticare.com','neetu@pramaticare.com','richa@pramaticare.com'];
            $emails = ['richa@pramaticare.com','kajal@pramaticare.com','aditya@pramaticare.com'];
            $bccList = ['cs@pramaticare.com'];
        }else{
            $emails = [env('TESTING_MAIL')];
        }


        $mailSubject = 'Lead '.$leadOrm->customer_name.' assignment is complete please notify customer about the assignment';

        $data = ['emailTo'=>$emails, 'subject'=>$mailSubject,'emailCCTo'=>$ccList, 'emailBccTo'=>$bccList];

        $mailView = view('emails.field_assigned_mailer')->with('lead',$leadOrm);

        Mail::queue('emails.field_assigned_to_lead', ['lead' => $leadOrm, 'leadUrl'=>url('lead/'.$leadOrm->id), 'carePlanUrl'=>url('operation/lead/careplan/'.$leadOrm->id)], function ($m) use ($data) {
            $m->from(env('PHPMAILER_FROM_EMAIL'),  env('PHPMAILER_FROM_NAME'));
            $m->to($data['emailTo'])->cc($data['emailCCTo'])
                ->bcc($data['emailBccTo'])->subject($data['subject']);
        });
    }

    public function sendMailToCustomerAboutFieldAssignment($leadDetail){
        $bccList = [];
        $ccList = [];
        $emails = [];

        $mailSubject = 'Pramati Care service will start at the scheduled time';


        if($leadDetail->email==null || trim($leadDetail->email)==""){
            return;
        }

        if(env('APP_ENV')=="production") {
            $emails = [$leadDetail->email];
            $bccList = ['cs@pramaticare.com'];
        }else{
            $emails = [env('TESTING_MAIL')];
        }
        $data = ['emailTo'=>$emails, 'lead' => $leadDetail,'subject'=>$mailSubject,'emailCCTo'=>$ccList, 'emailBccTo'=>$bccList];


        Mail::queue('emails.customer.customer_mail_field_assignment', ['data'=>$data,'lead' => $leadDetail], function ($m) use ($data) {
            $m->from(env('PHPMAILER_FROM_EMAIL'),  env('PHPMAILER_FROM_NAME'));
            $m->to($data['emailTo'])->bcc($data['emailBccTo'])->cc($data['emailCCTo'])->subject($data['subject']);
        });
    }

    public function sendMailForEmployeeAssignment($leadOrm){

        return;


        $bccList = [];
        $ccList = [];
        $emails = [];
        if(env('APP_ENV')=="production") {
            $ccList = ['cs@pramaticare.com', 'vishal@pramaticare.com'];
            $emails = ["mayur@pramaticare.com"];
            $bccList = ['cs@pramaticare.com'];
        }else{
            $emails = [env('TESTING_MAIL')];
        }

        $userIdByEmail = $this->userDomainService->getUserIdByEmailList(array_merge($ccList,$emails,$bccList));
        $this->operationDomainService->addWatchersToLead($leadOrm->id,$userIdByEmail);


        $mailSubject = 'Employee assignment for new lead created ';

        $data = ['emailTo'=>$emails, 'subject'=>$mailSubject,'emailCCTo'=>$ccList, 'emailBccTo'=>$bccList];



        Mail::queue('emails.assign_employee_mailer', ['lead' => $leadOrm, 'leadUrl'=>url('lead/'.$leadOrm->id)], function ($m) use ($data) {
            $m->from(env('PHPMAILER_FROM_EMAIL'),  env('PHPMAILER_FROM_NAME'));
            $m->to($data['emailTo'])->cc($data['emailCCTo'])
                ->bcc($data['emailBccTo'])->subject($data['subject']);
        });
    }

    public function sendMailOnCGAssignment($leadOrm){


        return;


        $bccList = [];
        $ccList = [];
        $emails = [];




        if(env('APP_ENV')=="production") {
            $ccList = ['cs@pramaticare.com', 'vishal@pramaticare.com'];
            // email of QA manager

            $emails = ["mayur@pramaticare.com","meenu@pramaticare.com","amit@pramaticare.com","ali904414@gmail.com"];
            $bccList = ['cs@pramaticare.com'];
        }else{
            $emails = [env('TESTING_MAIL')];
        }

        $userIdByEmail = $this->userDomainService->getUserIdByEmailList(array_merge($ccList,$emails,$bccList));
        $this->operationDomainService->addWatchersToLead($leadOrm->id,$userIdByEmail);



        $mailSubject = 'QC assignment and CG evaluation for lead '.$leadOrm->customer_name." ".$leadOrm->customer_last_name;

        $data = ['emailTo'=>$emails, 'subject'=>$mailSubject,'emailCCTo'=>$ccList, 'emailBccTo'=>$bccList];



        Mail::queue('emails.assign_qc_mailer', ['lead' => $leadOrm, 'leadUrl'=>url('lead/'.$leadOrm->id)], function ($m) use ($data) {
            $m->from(env('PHPMAILER_FROM_EMAIL'),  env('PHPMAILER_FROM_NAME'));
            $m->to($data['emailTo'])->cc($data['emailCCTo'])
                ->bcc($data['emailBccTo'])->subject($data['subject']);
        });
    }

    public function sendLeadApprovalEscalationMail($leadOrm){




        $bccList = [];
        $ccList = [];
        $emails = [];
        if(env('APP_ENV')=="production") {
            $ccList = ['cs@pramaticare.com', 'vishal@pramaticare.com','richa@pramaticare.com','kripa@pramaticare.com','aditya@pramaticare.com'];
            $emails = ["mayur@pramaticare.com"];
            $bccList = ['cs@pramaticare.com'];
        }else{
            $emails = [env('TESTING_MAIL')];
        }




        $mailSubject = 'Lead is not entertained from 15 mins: '.$leadOrm->customer_name." ".$leadOrm->customer_last_name;

        $data = ['emailTo'=>$emails, 'subject'=>$mailSubject,'emailCCTo'=>$ccList, 'emailBccTo'=>$bccList];



        Mail::queue('emails.approval_escalation_mailer', ['lead' => $leadOrm, 'leadUrl'=>url('lead/'.$leadOrm->id)], function ($m) use ($data) {
            $m->from(env('PHPMAILER_FROM_EMAIL'),  env('PHPMAILER_FROM_NAME'));
            $m->to($data['emailTo'])
                ->bcc($data['emailBccTo'])->cc($data['emailCCTo'])->subject($data['subject']);
        });
    }



    public function sendNewLeadCreationEmail($leadOrm){
        $bccList = [];
        $ccList = [];
        $emails = [];

        if(env('APP_ENV')=="production") {
            $emails = ['cs@pramaticare.com', 'vishal@pramaticare.com','mayur@pramaticare.com','richa@pramaticare.com'];
            $bccList = ['cs@pramaticare.com'];
        }else{
            $emails = [env('TESTING_MAIL')];
        }

        $mailSubject = 'New lead generated: '.$leadOrm->customer_name." ".$leadOrm->customer_last_name;

        $data = ['emailTo'=>$emails, 'subject'=>$mailSubject,'emailCCTo'=>$ccList, 'emailBccTo'=>$bccList];

        $mailView = view('emails.customer_mailer')->with('lead',$leadOrm);

        Mail::queue('emails.customer_mailer', ['lead' => $leadOrm, 'leadUrl'=>url('lead/'.$leadOrm->id)], function ($m) use ($data) {
            $m->from(env('PHPMAILER_FROM_EMAIL'),  env('PHPMAILER_FROM_NAME'));
            $m->to($data['emailTo'])
                ->bcc($data['emailBccTo'])->cc($data['emailCCTo'])->subject($data['subject']);
        });

    }

    public function sendWelcomeMailToCustomer($leadDetail){

        $bccList = [];
        $ccList = [];
        $emails = [];
        if($leadDetail->email && trim($leadDetail->email)!=""){
            $emails = [$leadDetail->email];
        }


        if(count($emails)==0){
            return;
        }


        $mailSubject = 'Thank you for choosing Pramati Care';

        $data = ['emailTo'=>$emails, 'subject'=>$mailSubject,'emailCCTo'=>$ccList, 'emailBccTo'=>$bccList];


        Mail::queue('emails.welcome', ['data'=>$data], function ($m) use ($data) {
            $m->from(env('PHPMAILER_FROM_EMAIL'),  env('PHPMAILER_FROM_NAME'));
            $m->to($data['emailTo'])->bcc($data['emailBccTo'])->cc($data['emailCCTo'])->subject($data['subject']);
        });

        return;


    }

    public function sendMailToCustomerOnServiceStart($leadDetail){
        $bccList = [];
        $ccList = [];
        $emails = [];

        $mailSubject = 'Thank you for choosing Pramati Care';

        if(env('APP_ENV')=="production") {
            $emails = [$leadDetail->email];
            $ccList = ['vishal@pramaticare.com'];
            $bccList = ['cs@pramaticare.com'];
        }else{
            $emails = [env('TESTING_MAIL')];
        }
        $data = ['emailTo'=>$emails, 'subject'=>$mailSubject,'emailCCTo'=>$ccList, 'emailBccTo'=>$bccList];



        Mail::queue('emails.customer.service_start_mail', ['data'=>$data], function ($m) use ($data) {
            $m->from(env('PHPMAILER_FROM_EMAIL'),  env('PHPMAILER_FROM_NAME'));
            $m->to($data['emailTo'])->bcc($data['emailBccTo'])->cc($data['emailCCTo'])->subject($data['subject']);
        });
    }

    public function sendLeadIncomingCallMail($leadDetail){



        $bccList = [];
        $ccList = [];
        $emails = [];
        if(env('APP_ENV')=="production") {
            $ccList = ['cs@pramaticare.com', 'vishal@pramaticare.com','richa@pramaticare.com','kripa@pramaticare.com','aditya@pramaticare.com'];
            $emails = ["mayur@pramaticare.com"];
            $bccList = ['cs@pramaticare.com'];
        }else{
            $emails = [env('TESTING_MAIL')];
        }




        $mailSubject = 'Call is will be initiated after 5 mins for '.$leadDetail->customer_name." ".$leadDetail->customer_last_name;

        $data = ['emailTo'=>$emails, 'subject'=>$mailSubject,'emailCCTo'=>$ccList, 'emailBccTo'=>$bccList];

        Mail::queue('emails.approval_lead_call_mailer', ['lead' => $leadDetail, 'leadUrl'=>url('lead/'.$leadDetail->id)], function ($m) use ($data) {
            $m->from(env('PHPMAILER_FROM_EMAIL'),  env('PHPMAILER_FROM_NAME'));
            $m->to($data['emailTo'])
                ->bcc($data['emailBccTo'])->cc($data['emailCCTo'])->subject($data['subject']);
        });
    }













    public function sendCgAssignedMailNotification($leadOrm,$sync){
        $bccList = [];
        $emails = [];
        if(env('APP_ENV')=="production") {
            $emails = [$leadOrm->email];
            $bccList = ['cs@pramaticare.com','vishal@pramaticare.com','robin@pramaticare.com','himanshi@pramaticare.com'];
        }else{
            $emails = [env('TESTING_MAIL')];
            $bccList = [env('TESTING_MAIL')];
        }

        $mailSubject = 'Service provider has been assigned for your service';

        $data = ['emailTo'=>$emails, 'subject'=>$mailSubject, 'emailBccTo'=>$bccList];

        if($sync){
            Mail::send('emails.customer.cg_assigned', ['lead' => $leadOrm, 'leadUrl'=>url('lead/'.$leadOrm->id)], function ($m) use ($data) {
                $m->from(env('PHPMAILER_FROM_EMAIL'),  env('PHPMAILER_FROM_NAME'));
                $m->to($data['emailTo'])
                    ->bcc($data['emailBccTo'])->subject($data['subject']);
            });
        }else{
            Mail::queue('emails.customer.cg_assigned', ['lead' => $leadOrm, 'leadUrl'=>url('lead/'.$leadOrm->id)], function ($m) use ($data) {
                $m->from(env('PHPMAILER_FROM_EMAIL'),  env('PHPMAILER_FROM_NAME'));
                $m->to($data['emailTo'])
                    ->bcc($data['emailBccTo'])->subject($data['subject']);
            });
        }

    }
    public function sendQcAssignedMailNotification($leadOrm,$sync){
        $bccList = [];
        $emails = [];
        if(env('APP_ENV')=="production") {
            $emails = [$leadOrm->email];
            $bccList = ['cs@pramaticare.com','vishal@pramaticare.com','robin@pramaticare.com','himanshi@pramaticare.com'];
        }else{
            $emails = [env('TESTING_MAIL')];
            $bccList = [env('TESTING_MAIL')];
        }

        $mailSubject = 'QC has been assigned for your service';

        $data = ['emailTo'=>$emails, 'subject'=>$mailSubject, 'emailBccTo'=>$bccList];

        if($sync){
            Mail::send('emails.customer.qc_assigned', ['lead' => $leadOrm, 'leadUrl'=>url('lead/'.$leadOrm->id)], function ($m) use ($data) {
                $m->from(env('PHPMAILER_FROM_EMAIL'),  env('PHPMAILER_FROM_NAME'));
                $m->to($data['emailTo'])
                    ->bcc($data['emailBccTo'])->subject($data['subject']);
            });
        }else{
            Mail::queue('emails.customer.cg_assigned', ['lead' => $leadOrm, 'leadUrl'=>url('lead/'.$leadOrm->id)], function ($m) use ($data) {
                $m->from(env('PHPMAILER_FROM_EMAIL'),  env('PHPMAILER_FROM_NAME'));
                $m->to($data['emailTo'])
                    ->bcc($data['emailBccTo'])->subject($data['subject']);
            });
        }

    }
}