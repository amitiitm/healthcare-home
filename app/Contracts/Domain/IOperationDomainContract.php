<?php

namespace App\Contracts\Domain;

interface IOperationDomainContract
{

    public function getServices($sorted);
    public function getLeadReferencesList();
    public function getShifts();
    public function submitLeadEnquiry($name, $email, $phone,$localityOrm,$serviceId);
    public function generateOtpForPhone($leadId,$phone);
    public function verifyLeadOtp($leadId,$otp);
    public function callLead($leadId,$supportUserId);
    public function automatedCallLead($leadId,$supportUserId);
    public function sendSMS($phone,$message,$type=null);

    public function submitLeadEnquiryNotification($leadId,$name, $email, $phone,$localityOrm,$serviceId);
    public function submitEnquiry($name, $email, $phone,$message);
    public function getEnquiryById($enquiryId);
    public function createLocalityByOrm($localityOrm);
    public function createLeadByOrm($leadOrm);
    public function updateLeadByOrm($leadId,$leadOrm);
    public function createPatientByOrm($patientOrm);
    public function addPatientAilment($patientId,$ailmentList);
    public function addPatientEquipmentDetail($patientId,$equipmentList);
    public function addPatientPrescription($patientId,$prescriptionList);

    public function getAilments();
    public function getEquipments();
    public function getLanguages();
    public function getReligions();
    public function getAgeRanges();
    public function getFoodTypes();

    public function getTasksByService($serviceId);
    public function getAllTask();
    public function getConditions();
    public function getComplaints();
    public function getPtconditions();
    public function getModalities();
    

    public function getAilmentList($serviceId);
    public function getTaskList($ailmentId);

    public function getEnquiryList();
    public function getLeadList($limit);
    public function getLeadByStatus($statusId);
    public function getLeadCountByStatus($statusId);
    public function getTodayLead();
    public function getClosedLeadList();
    public function getLeadDetail($leadId);
    public function getPatientDetail($patientId);
    public function getLeadDetailedOrm($leadId);
    public function getLeadCommentsCollection($leadId);
    public function submitLeadComment($leadId,$comment,$userId);
    public function getLeadEmployeeAssignmentCollection($leadId);
    public function getLeadQcEmployeeAssignmentCollection($leadId);
    public function getLeadFieldEmployeeAssignmentCollection($leadId);
    public function getLeadVendorAssignmentCollection($leadId);
    public function getPrimaryLeadVendorAssignmentCollection($leadId);
    public function getLeadStatusChangeLog($leadId);
    public function getLeadVendorAttendanceLog($leadId);
    public function getAssignableEmployeesCollection();
    public function assignEmployeeToLead($leadId, $assingeeId,$userId);
    public function assignQcEmployeeToLead($leadId, $assingeeId,$userId);
    public function assignFieldEmployeeToLead($leadId, $assingeeId,$userId);
    public function assignVendorToLead($leadId, $assingeeId,$pricePerDay, $isPrimary, $userId,$sourcingData);
    public function submitQcBriefingDetails($leadId,$qcAssignmentId,$briefingData,$userId);
    public function getOperationalStatusesGroupList();
    public function getStatusBySlug($statusSlug);
    public function updateLeadStatus($leadId,$statusId,$userId,$reasonId,$statusComment,$statusData, $statusDate);
    public function markLeadStarted($leadId,$datetime);
    public function approveSalesLead($leadId,$userId);
    public function getAilmentTaskByServiceId($serviceId);

    public function updateLeadStartDate($leadId,$startDate);

    public function updatePatientInfo($leadId,$patientInfo);
    public function updatePhysioInfoCRM($leadId,$physioInfoOrm);
    public function updatePhysioPatientInfo($leadId,$patientInfo);
    public function updateTaskInfo($leadId,$taskInfo);
    public function updatePatientValidationInfo($patientId,$validationOrm,$taskList);
    public function addPriceToLead($leadId,$price,$priceUnit);
    public function updatePriceToLead($leadId,$price,$priceUnit);
    public function updateLeadSpecialRequest($leadId,$requestInfo);
    
    public function getExportData();
    public function updateLeadSpecialRequestCRM($leadId,$requestInfo);

    public function getTaskCategoryWithTask();
    public function patientValidationData($patientId);
    public function getVendorData($id);
    public function uploadPrescription($file,$patientId);
    public function sendMailToCustomer($leadId);


    public function sendNewLeadNotification($leadId);
    public function deleteLead($leadId);
    public function deleteBulkLead($leadList);
    public function getLeadPhoneNumber($leadId);
    public function isAuthorizedToViewContact($authUser);

    public function getLeadCurrentPrimaryVendor($leadId);
    public function getLeadCurrentBackUpVendor($leadId);
    public function getCurrentAssignedQc($leadId);
    public function getCGEvaluationData($leadId,$isPrimary);
    public function getCGTrainingData($leadId,$isPrimary);
    public function getCGCustomerSignOffData($leadId,$isPrimary);

    public function getNotNotifiedNewLeadFromGivenDate($timestamp);
    public function getLeadCreatedInDuration($startTimeStamp,$endTimeStamp);
    public function getPendingAssignmentNotification($startTimeStamp);
    public function sendSmsToCustomerAboutFieldAssignment($leadDetail);
    public function markLeadApprovalEscalation($leadId,$delay);
    public function markNewLeadNotificationSend($leadId);

    public function submitCarePlanPrimaryEvaluation($leadId,$isPrimary,$data,$userId);
    public function submitCarePlanTrainingEvaluation($leadId,$isPrimary,$data,$userId);
    public function submitCarePlanCustomerSignOff($leadId,$isPrimary,$data,$userId);

    public function submitCGAttendance($leadId,$vendorId,$attendanceDate,$attendance,$vendorPrice,$comment,$userId);

    public function markCallInitiationMailSend($leadDetail);

    // mobile service
    public function getLeadByCustomerPhone($phone);
    public function getLeadClosureOptions();
    public function markCustomerClosureRequest($leadId,$userId, $closureStatusId,$closureReasonId,$closureIssueId,$closureOtherReason);
    public function submitVendorNotReachedStatus($leadId,$customerId,$vendorStatusKey, $comment);
    public function submitLeadRestartRequestFromCustomer($customerId,$leadId);
    public function submitCustomerFeedback($customerId,$rating,$behaviourId,$comment);
    public function markCustomerCaregiverAttendance($leadId,$customerId,$attendanceDate, $isPresent,$isOnTime,$isWellDressed);


    public function getActiveLeads();
    public function getStartedLead();
    public function getVendorAttendanceOnDate($date);
    public function getActiveLeadOnDate($date);
    public function checkAndMarkActiveDate($leadId,$date);
    public function checkAndGenerateSlackChannelForLead($leadId);
    public function getAllSlackedLead();
    public function updateSlackChannelIdForLead($leadId,$slackChannelId);
    public function addWatchersToLead($leadId,$watcherList);
    public function getPendingWatcherInvitationForSlack();
    public function markAndSendSlackInvitationForWatcher($leadWatcherId);


    // customer App

    public function getStatusChangeRequestByCustomer($leadId);
    public function getCustomerNotifications($leadId);
    public function getVendorAttendanceByCustomer($leadId);
    public function getVendorStatusByCustomer($leadId);

    /* customer notification related service */
    public function getPendingCGAssignmentNotification();
    public function getPendingQCAssignmentNotification();
    public function getPendingLeadStartedNotification();
    public function markCgAssignmentNotificationSend($leadId);
    public function markQCAssignmentNotificationSend($leadId);
    public function markLeadStartedNotificationSend($leadId);

    public function getLeadIdByVendorId($vendorId);
    public function getLatestVendorByLead($leadId);
    public function getLatestPrimaryVendorByLead($leadId);

    public function vendor_rating_save();
}
