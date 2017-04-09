<?php

namespace App\Contracts\Rest;

interface IOperationRestContract
{
    public function submitEnquiry($inputAll);
    public function submitEnquiryMobile($inputAll);
    public function submitEnquiryForCall($inputAll);
    public function verifyOTPForEnquiry($inputAll);
    public function callMeNow($leadId);
    public function submitEnquiryNotification($leadId,$inputAll);
    public function submitContact($inputAll);

    public function submitCRMLead($inputAll,$userId);
    public function updateLeadDetail($leadId,$inputAll,$userId);

    public function getTasksByService($serviceId);
    public function getTasksList();
    public function getEnquiryListGridData();
    public function getLeadListGridData($inputAll);
    public function getClosedLeadListGridData();
    public function getPendingLeadListGridData();
    public function getValidatedLeadListGridData();
    public function getStartedLeadListGridData();
    public function getTodayLeadListGridData();
    public function getLeadDetail($leadId);
    public function getLeadComments($leadId);
    public function submitLeadComment($leadId,$comment,$userId);
    public function getLeadEmployeeAssignment($leadId);
    public function getLeadVendorAssignment($leadId);
    public function getLeadLogs($leadId);
    public function getAssignableEmployees();
    public function assignEmployeeToLead($leadId,$assigneeId,$userId);
    public function assignQcEmployeeToLead($leadId,$assigneeId,$userId);
    public function assignFieldEmployeeToLead($leadId,$assigneeId,$userId);
    public function assignVendorToLead($leadId,$assigneeId,$pricePerDay,$isPrimary, $sourcingData,$userId);
    public function submitQcBriefing($leadId,$qcAssignmentId,$briefingData,$userId);
    public function getOperationalStatusesGrouped();
    public function updateLeadStatus($leadId,$inputAll,$userId);
    public function approveLead($leadId,$inputAll,$userId);
    public function ailmentTasksMapped($serviceId);
    public function getAilmentList($serviceId);

    public function updateLeadPatientInfo($leadId,$patientInfo);
    public function updateLeadPatientInfoMobile($leadId,$patientInfo);
    public function updateLeadPhysioPatientInfo($leadId,$patientInfo);
    public function updateLeadTaskInfo($leadId,$taskInfo);
    public function updateLeadTaskInfoMobile($leadId,$taskInfo);
    public function updateLeadSpecialRequest($leadId,$requestInfo);

    public function getPaymentType();
    public function getPaymentPeriod();
    public function getPaymentMode();
    public function getPriceUnit();
    public function getLeadReferences();
    public function getMobilities();
    public function getValidationTaskCategory();
    public function getPatientValidationData($patientId);
    public function getPatientValidatedData($patientId);

    public function getLeadCount();
    public function getUnassignedLeadCount();
    public function getActiveLeadCount();
    public function uploadPrescription($file,$patientId);
    public function sendMailToCustomer($leadId);

    public function deleteLead($leadId);
    public function deleteBulkLead($leadList);
    public function isAuthorizedToViewContact();
    public function viewLeadContact($leadId);
    public function startLeadService($leadId,$inputAll);

    public function getCarePlanData($leadId);
    public function submitCarePlanEvaluationData($action,$leadId, $data,$userId);
    public function submitCGAttendance($leadId,$inputAll,$userId);
    public function getCustomerLeadByUserId($phone);
    public function getLeadDetailForApp($leadId);
    public function getNotificationForUser($userId);


    public function getLeadClosureOptions();
    public function submitLeadClosureRequest($inputAll);
    public function submitVendorNotReachedStatus($inputAll);
    public function submitCustomerCaregiverAttendance($inputAll);
    public function leadRestartRequest($customerId,$leadId);
    public function submitCustomerFeedback($customerId,$inputAll);
    public function getActiveProjectGridList();
    public function getVendorAttendanceReport($date);


    public function syncLeadSlackComment($leadId);


    // customer notification services
    public function getPendingCGAssignmentNotification();

    public function connectLeadTableToUser();
}