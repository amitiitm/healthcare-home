<?php

namespace App\Contracts\Helper;

interface IMailHelperContract
{
    public function sendMailOnEmployeeAssignment($leadOrm,$assignedId);
    public function sendMailOnQcAssignment($leadOrm,$assignedId);
    public function sendMailForFieldAssignment($leadOrm);
    public function sendMailOnFieldAssignment($leadOrm,$assignedId);
    public function sendMailForCustomerAboutFieldAssignment($leadOrm);
    public function sendMailToCustomerAboutFieldAssignment($leadOrm);

    public function sendMailForEmployeeAssignment($leadOrm);
    public function sendMailOnCGAssignment($leadOrm);
    public function sendLeadApprovalEscalationMail($leadOrm);
    public function sendNewLeadCreationEmail($leadOrm);
    public function sendWelcomeMailToCustomer($leadOrm);

    public function sendMailToCustomerOnServiceStart($leadDetail);

    public function sendLeadIncomingCallMail($leadDetail);



    // customer notification emails
    public function sendCgAssignedMailNotification($leadOrm,$sync);
    public function sendQcAssignedMailNotification($leadOrm,$sync);
}
