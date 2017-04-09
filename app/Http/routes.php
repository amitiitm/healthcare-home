<?php
ini_set('memory_limit', '12008M');





/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
 * Done to override Blade Templating Tags
 * that conflict with AngularJS
 */
Blade::setContentTags('<%', '%>');        // for variables and all things Blade
Blade::setEscapedContentTags('<%%', '%%>');   // for escaped data

function d($arr){
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}
function ddie($arr){
    d($arr);
    die();
}


Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');


//Route::get('/', function() {
//    return view('welcome');
//});


Route::get('/', function () {
    d(Auth::user());

    if(Auth::user()) {
        return Redirect::to('/admin/dashboard', 301);
    } else {
        return Redirect::to('/auth/login', 301);
    }
});

Route::get('/care-finder','Link\FrontLinkController@careFinder');


Route::get('/services/doctor-visit', function() {
    return view ('docvisit');
});

Route::get('/about-us', function() {
    return view ('aboutus');
});

Route::get('/services/nursing-care', function() {
    return view ('nursingcare');
});

Route::get('/services/assistive-care', function() {
    return view ('assistivecare');
});

Route::get('/services/physiotherapist', function() {
    return view ('physiotherapist');
});

Route::get('/services/occupational-therapist', function() {
    return view ('occtherapist');
});
Route::get('/services/medicine-delivery', function() {
    return view ('medicine');
});

Route::get('/services/speech-occupational', function() {
    return view ('speech_occupational');
});

Route::get('/services/diagnostics-at-home', function() {
    return view ('diagnostics');
});

Route::get('/services/equipments', function() {
    return view ('equipment');
});

Route::get('/contact', function() {
    return view ('contact');
});

Route::get('/reset/password/complete', function() {
    return view ('front.passwordresetmailsuccess');
});


Route::group(['middleware'=>'auth'], function(){
    Route::get('/home',function(){
        return redirect("/");
    });
});
Route::get('user/profile/{userId}','Link\ImageLinkController@generateUserImage');
Route::post('/payumoney_callback','Link\ReportLinkController@payumoneyCallback');
Route::group(['middleware'=>'pr.employee', ], function(){
    Route::get('/admin/dashboard','Link\DashboardLinkController@index');
    Route::get('/admin/enquiries','Link\DashboardLinkController@enquiries');
    Route::get('/admin/enquiries1','Link\DashboardLinkController@enquiries1');
    Route::get('/admin/enquiries_data','Link\DashboardLinkController@enquiriesData');
    Route::get('/admin/enquiry/new','Link\DashboardLinkController@enquiryNew');
    Route::post('/admin/enquiry/create','Link\DashboardLinkController@enquiryCreate');
    Route::get('/admin/edit_enquiry/{enquiry_id}','Link\DashboardLinkController@enquiryEdit');
    Route::post('/admin/enquiry/update','Link\DashboardLinkController@enquiryUpdate');
    Route::get('/admin/leads','Link\OperationLinkController@leads');
    Route::get('/admin/orders','Link\ReportLinkController@orders');
    Route::get('/admin/order_data','Link\ReportLinkController@orderData');
    Route::get('/admin/invoice_data','Link\ReportLinkController@invoiceData');
    Route::get('/admin/invoices','Link\ReportLinkController@invoices');
    Route::get('/admin/assigned_invoices','Link\ReportLinkController@assignedInvoices');
    Route::get('/admin/online_assigned_invoices','Link\ReportLinkController@onlineAssignedInvoices');
    Route::get('/admin/download_invoice/{invoice_id}','Link\ReportLinkController@downloadInvoice');
    Route::get('/admin/edit_invoice/{invoice_id}','Link\ReportLinkController@editInvoice');
    Route::get('/admin/view_invoice/{invoice_id}','Link\ReportLinkController@viewInvoice');
    Route::get('/admin/generate_invoice_manually/{order_id}','Link\ReportLinkController@generateInvoiceManually');
    Route::get('/admin/edit_invoice_payment/{invoice_id}','Link\ReportLinkController@editInvoicePayment');
    Route::post('/admin/update_invoice','Link\ReportLinkController@updateInvoice');
    Route::post('/admin/create_invoice_payment','Link\ReportLinkController@createInvoicePayment');
    Route::get('/admin/approve_invoice/{invoice_id}','Link\ReportLinkController@approveInvoice');

    Route::get('/reports/budget_movement','Link\ReportLinkController@budgetMovement');
    Route::post('/reports/budget_movement','Link\ReportLinkController@budgetMovement');
    
    Route::get('/reports/total_collection','Link\ReportLinkController@totalCollection');
    Route::post('/reports/total_collection','Link\ReportLinkController@totalCollection');
    Route::get('/reports/daily_collection','Link\ReportLinkController@dailyCollection');
    Route::post('/reports/daily_collection','Link\ReportLinkController@dailyCollection');
    Route::get('/reports/field_executive_collection','Link\ReportLinkController@fieldExecutiveCollection');
    Route::post('/reports/field_executive_collection','Link\ReportLinkController@fieldExecutiveCollection');
    Route::get('/reports/cg_tracking','Link\ReportLinkController@cgTrackingReport');
    Route::post('/reports/cg_tracking','Link\ReportLinkController@cgTrackingReport');
    Route::get('/reports/cg_attendance','Link\ReportLinkController@cgAttendanceReport');
    Route::post('/reports/cg_attendance','Link\ReportLinkController@cgAttendanceReport');
    
    Route::get('/lead/{leadId}','Link\OperationLinkController@lead');
    Route::get('/lead/{leadId}/create/slack/channel','Link\OperationLinkController@createSlackChannel');
    Route::get('/lead/{leadId}/vendor/suggestions','Link\OperationLinkController@leadVendorSuggestion');
    Route::get('/lead/{leadId}/vendor/suggestions/reload','Link\OperationLinkController@reloadLeadVendorSuggestion');
    Route::get('/lead/{leadId}/vendor/suggestions/deactive/{userId}','Link\OperationLinkController@deactiveLeadVendorSuggestion');
    Route::get('/refresh_caching/preferred_caregivers','Link\OperationLinkController@refreshPreferredCGCaching');
    Route::get('/refresh_caching/preferred_caregivers','Link\OperationLinkController@refreshPreferredCGCaching');
    Route::get('/admin/caregiver/auto_attendance','Rest\VendorRestController@caregiverAutoAttendance');
    Route::get('/admin/caregiver/auto_attendance_data','Rest\VendorRestController@caregiverAutoAttendanceData');

    Route::get('/admin/employee/tracker','Link\DashboardLinkController@employeeTracker');
    Route::get('/admin/users','Link\DashboardLinkController@users');
    Route::get('/admin/employees','Link\DashboardLinkController@employees');
    Route::get('/admin/employee/{userId}','Link\DashboardLinkController@employeeView');
    Route::get('/admin/employee/edit/{userId}','Link\DashboardLinkController@employeeEdit');

    Route::get('/admin/caregivers1','Link\DashboardLinkController@vendors');
    Route::get('/admin/caregivers','Link\DashboardLinkController@caregivers');
    Route::get('/admin/caregivers_data','Link\DashboardLinkController@caregiversData');
    Route::get('/admin/vendors','Link\DashboardLinkController@vendors');
    Route::get('/admin/reports','Link\DashboardLinkController@reports');
    Route::get('/admin/field','Link\DashboardLinkController@field');
    Route::get('/admin/operations','Link\DashboardLinkController@operations');
    Route::get('/operation/lead/add','Link\OperationLinkController@newLead');
    Route::get('/operation/lead/validate/{leadId}','Link\OperationLinkController@validateLead');
    Route::get('/operation/lead/edit/{leadId}','Link\OperationLinkController@editLead');
    Route::get('/operation/lead/careplan/{leadId}','Link\OperationLinkController@careplan');
    Route::get('/operation/vendor/add','Link\VendorLinkController@newVendor');
    Route::get('/operation/vendor/edit/{userId}','Link\VendorLinkController@editVendor');
    Route::get('/vendor/document/view/{documentId}','Link\VendorLinkController@getVendorDocumentPreview');

    Route::get('/report/active/projects','Link\ReportLinkController@activeProjectReport');
    Route::get('/report/vendor/attendance','Link\ReportLinkController@vendorAttendanceReport');

    //complaints
    Route::get('/admin/complaints','Link\ComplaintsLinkController@complaints');
    Route::get('/complaints/add','Link\ComplaintsLinkController@newComplaint');
    Route::get('/complaint/{complaintId}/{userType}','Link\ComplaintsLinkController@complaint');

    Route::get('/admin/complaints_resolution_groups','Link\ComplaintsLinkController@complaints_resolution_groups');
    Route::get('/admin/replacement_requests','Link\ComplaintsLinkController@replacement_requests');

    // lead feedback
    Route::get('/admin/lead/feedback','Link\UserLinkController@leadFeedback');
    Route::get('/admin/lead/feedback_data','Link\UserLinkController@leadFeedbackData');
    Route::get('/admin/lead/feedback/new','Link\UserLinkController@leadFeedbackNew');
    Route::get('/admin/lead_details/{leadId}','Link\UserLinkController@leadDetails');
    Route::post('/admin/lead/feedback/create','Link\UserLinkController@leadFeedbackCreate');
    
    //salary reports
    Route::get('/report/salary','Link\ReportLinkController@salaryReport');


    // Image controller's route
    Route::get('user/profile/{userId}','Link\ImageLinkController@generateUserImage');

    //Temp
    Route::get('connectLeadTableToUser', array('uses' => 'Rest\OperationRestController@connectLeadTableToUser'));

    // cron tester
    Route::get('/cron_tester/{vendorId}','Rest\OperationRestController@cronTester');

});

Route::get('dataexport','Rest\CommonRestController@getExportData');
Route::get('feedback','Rest\CommonRestController@getFeedback');
Route::get('office','Rest\CommonRestController@getOfficeEmp');
Route::get('customer','Rest\CommonRestController@getCustomer');
Route::get('user/edit/employee/{userId}','Link\OperationLinkController@getEmployeeProfile');
Route::get('vendor/{userId}','Link\VendorLinkController@getVendorProfile');
Route::get('user/edit/vendor/{userId}','Link\VendorLinkController@editVendorProfile');
Route::get('user/edit/customer/{userId}','Link\OperationLinkController@getCustomerProfile');
Route::get('sms','Rest\OperationRestController@submitEnquiryNotification');
Route::get('mail','Rest\OperationRestController@submitMail');
Route::get('demo','Rest\OperationRestController@demo');


/*
 * Rest APIs
 */

Route::group(['prefix' => 'api/v1/',  'middleware' => 'pr.employee' ], function () {

    // Front website api calls
    Route::get('admin/dashboard', array('uses' => 'Rest\CommonRestController@getAdminDashboardData'));
    Route::post('enquiry/submit', array('uses' => 'Rest\OperationRestController@submitEnquiry'));
    Route::post('enquiry/submit/call', array('uses' => 'Rest\OperationRestController@submitEnquiryForCall'));
    Route::post('enquiry/verify/otp', array('uses' => 'Rest\OperationRestController@verifyOTPForEnquiry'));
    Route::get('enquiry/callmenow/{leadId}', array('uses' => 'Rest\OperationRestController@callMeNow'));
    Route::post('enquiry/notification/{leadId}', array('uses' => 'Rest\OperationRestController@submitEnquiryNotification'));
    Route::post('lead/submit', array('uses' => 'Rest\OperationRestController@submitCRMLead'));
    Route::post('lead/update/{leadId}', array('uses' => 'Rest\OperationRestController@updateLeadDetail'));
    Route::post('contact/submit', array('uses' => 'Rest\OperationRestController@submitContact'));
    Route::post('operation/patient/prescription/upload', array('uses' => 'Rest\OperationRestController@uploadPrescription'));


    Route::get('leadmail/{leadId}', array('uses' => 'Rest\OperationRestController@leadCreateMail'));
    Route::get('sendmail/{leadId}', array('uses' => 'Rest\OperationRestController@sendMailToCustomer'));
    Route::get('lead/references', array('uses' => 'Rest\CommonRestController@getLeadReferences'));
    Route::get('lead/services', array('uses' => 'Rest\CommonRestController@getServicesList'));
    Route::get('lead/shifts', array('uses' => 'Rest\CommonRestController@getShifts'));
    Route::get('lead/ailments', array('uses' => 'Rest\CommonRestController@getAilments'));
    Route::get('lead/equipments', array('uses' => 'Rest\CommonRestController@getEquipments'));
    Route::get('lead/languages', array('uses' => 'Rest\CommonRestController@getLanguages'));
    Route::get('lead/religions', array('uses' => 'Rest\CommonRestController@getReligions'));
    Route::get('lead/mapped/data', array('uses' => 'Rest\OperationRestController@getMappedOptions'));
    Route::get('lead/task/service/{serviceId}', array('uses' => 'Rest\OperationRestController@getTasksByService'));
    Route::get('enquiry/list/grid/data', array('uses' => 'Rest\OperationRestController@getEnquiryGridData'));
    Route::get('lead/list/grid/data', array('uses' => 'Rest\OperationRestController@getLeadGridData'));
    Route::get('lead/closed/grid/data', array('uses' => 'Rest\OperationRestController@getClosedLeadGridData'));
    Route::get('lead/pending/grid/data', array('uses' => 'Rest\OperationRestController@getPendingLeadGridData'));
    Route::get('lead/validated/grid/data', array('uses' => 'Rest\OperationRestController@getValidatedLeadGridData'));
    Route::get('lead/started/grid/data', array('uses' => 'Rest\OperationRestController@getStartedLeadGridData'));
    Route::get('lead/today/grid/data', array('uses' => 'Rest\OperationRestController@getTodayLeadGridData'));
    Route::get('lead/detail/{leadId}', array('uses' => 'Rest\OperationRestController@getLeadDetail'));
    Route::get('lead/sync/slack/comment/{leadId}', array('uses' => 'Rest\OperationRestController@syncSlackComment'));
    Route::get('lead/careplan/grid/{leadId}', array('uses' => 'Rest\OperationRestController@getLeadCarePlanGrid'));
    Route::post('lead/careplan/evaluation/{action}/{leadId}', array('uses' => 'Rest\OperationRestController@submitCarePlanEvaluationData'));
    Route::get('lead/comments/{leadId}', array('uses' => 'Rest\OperationRestController@getLeadComments'));
    Route::post('lead/comment/submit/{leadId}', array('uses' => 'Rest\OperationRestController@submitLeadComment'));
    Route::get('lead/logs/{leadId}', array('uses' => 'Rest\OperationRestController@getLeadLogs'));
    Route::get('lead/assignment/employee/list', array('uses' => 'Rest\OperationRestController@getAssignableEmployees'));
    Route::post('lead/assignment/employee/{leadId}', array('uses' => 'Rest\OperationRestController@assignEmployeeToLead'));
    Route::post('lead/assignment/qcEmployee/{leadId}', array('uses' => 'Rest\OperationRestController@assignQcEmployeeToLead'));
    Route::post('lead/assignment/fieldEmployee/{leadId}', array('uses' => 'Rest\OperationRestController@assignFieldEmployeeToLead'));
    Route::post('lead/assignment/vendor/{leadId}', array('uses' => 'Rest\OperationRestController@assignVendorToLead'));
    Route::get('lead/statuses/grouped/list', array('uses' => 'Rest\OperationRestController@getOperationalStatusesGrouped'));
    Route::post('lead/status/update/{leadId}', array('uses' => 'Rest\OperationRestController@updateLeadStatus'));
    Route::post('lead/approve/{leadId}', array('uses' => 'Rest\OperationRestController@approveLead'));
    Route::get('/lead/ailment/tasks/mapped/{serviceId}', array('uses' => 'Rest\OperationRestController@ailmentTasksMapped'));
    Route::get('service/ailment/{serviceId}', array('uses' => 'Rest\OperationRestController@getAilmentList'));
    Route::get('ailment/task/{ailmentId}', array('uses' => 'Rest\OperationRestController@getLeadLogs'));
    Route::post('/lead/patient/update/{leadId}', array('uses' => 'Rest\OperationRestController@updateLeadPatientInfo'));
    Route::post('/lead/physioPatient/update/{leadId}', array('uses' => 'Rest\OperationRestController@updateLeadPhysioPatientInfo'));
    Route::post('/lead/task/update/{leadId}', array('uses' => 'Rest\OperationRestController@updateLeadTaskInfo'));
    Route::post('/lead/special/update/{leadId}', array('uses' => 'Rest\OperationRestController@updateLeadSpecialRequest'));
    Route::get('/lead/validation/task/categories', array('uses' => 'Rest\OperationRestController@getValidationTaskCategories'));
    Route::get('/lead/validation/data/patient/{patientId}', array('uses' => 'Rest\OperationRestController@getPatientValidationData'));
    Route::get('/lead/validated/data/patient/{patientId}', array('uses' => 'Rest\OperationRestController@getPatientValidatedData'));
    Route::get('/lead/view/phone/{leadId}', array('uses' => 'Rest\OperationRestController@viewLeadContactInformation'));
    Route::post('/lead/start/{leadId}', array('uses' => 'Rest\OperationRestController@startLeadService'));
    Route::get('/data/vendor/{vendorId}', array('uses' => 'Rest\VendorRestController@getVendorDetail'));
    Route::post('/vendor/submit', array('uses' => 'Rest\VendorRestController@submitVendor'));
    Route::post('/vendor/availability/update/{vendorId}', array('uses' => 'Rest\VendorRestController@updateVendorAvailability'));
    Route::get('/vendor/available/list','Rest\VendorRestController@getAvailableVendors');
    Route::post('/lead/qc/briefing/submit/{leadId}', array('uses' => 'Rest\OperationRestController@submitQCBriefing'));
    Route::post('/lead/vendor/attendance/mark/{leadId}', array('uses' => 'Rest\OperationRestController@submitCGAttendance'));
    Route::get('/lead/customer/notification/templates', array('uses' => 'Rest\OperationRestController@getCustomerNotificationTemplates'));
    Route::post('/lead/customer/notification/submit/{leadId}', array('uses' => 'Rest\OperationRestController@submitCustomerNotification'));


    // only for Admin
    Route::get('user/grid/data', array('uses' => 'Rest\UserRestController@getUserGridData'));
    Route::get('vendor/grid/data', array('uses' => 'Rest\VendorRestController@getVendorGridData'));
    Route::get('vendor/task/grouped/{vendorId}', array('uses' => 'Rest\VendorRestController@getVendorTaskDetailGrouped'));

    Route::get('lead/{leadId}/assignment/vendor/list', array('uses' => 'Rest\VendorRestController@getVendorSuggestionForLead'));
    Route::get('lead/vendor/deployed/status/list', array('uses' => 'Rest\VendorRestController@getVendorDeployedStatusList'));
    Route::post('user/new', array('uses' => 'Rest\UserRestController@addUser'));
    Route::post('api/v1/vendor/submit', array('uses' => 'Rest\vendorRestController@submitVendor'));
    Route::post('/lead/bulk/delete', array('uses' => 'Rest\OperationRestController@deleteBulkLead'));
    Route::post('/lead/delete/{leadId}', array('uses' => 'Rest\OperationRestController@deleteLead'));
    Route::post('/vendor/operation/update/{vendorId}', array('uses' => 'Rest\VendorRestController@updateVendorDetail'));
    Route::post('/vendor/operation/document/upload', array('uses' => 'Rest\VendorRestController@addVendorDocument'));
    Route::post('/vendor/operation/delete/{vendorId}', array('uses' => 'Rest\VendorRestController@deleteVendorDetail'));
    Route::post('/vendor/operation/deleteVendors', array('uses' => 'Rest\VendorRestController@deleteVendorsDetails'));

    Route::post('/vendor/operation/document/delete/{vendorDocumentId}', array('uses' => 'Rest\VendorRestController@deleteVendorDocument'));
    Route::get('/vendor/availability/options','Rest\VendorRestController@getVendorAvailabilityOptions');
    Route::get('/vendor/availability/mapper','Rest\VendorRestController@getVendorAvailabilityMapper');
    Route::get('/vendor/document/types','Rest\VendorRestController@getVendorDocumentTypes');
    Route::get('/vendor/training/reasons','Rest\VendorRestController@getVendorTrainingReasons');

    Route::get('complaints/getAllotableEmployeesComplaints/list', array('uses' => 'Rest\OperationRestController@getAllotableEmployeesComplaints'));
    Route::get('complaints/getComplaintResolutionGroups/list', array('uses' => 'API\ComplaintsAPIController@getComplaintResolutionGroups'));
    Route::post('complaints/postComplaintResolutionGroupAddMember', array('uses' => 'API\ComplaintsAPIController@postComplaintResolutionGroupAddMember'));
    Route::get('complaints/getComplaintResolutionMembers/list', array('uses' => 'API\ComplaintsAPIController@getComplaintResolutionMembers'));
    Route::get('complaints/deleteComplaintResolutionMember/{memberRelationId}', array('uses' => 'API\ComplaintsAPIController@deleteComplaintResolutionMember'));
    Route::post('complaints/addComplaintLog', array('uses' => 'API\ComplaintsAPIController@addComplaintLog'));
    
    Route::get('lead/getCurrentVendor/{leadId}', array('uses' => 'Rest\OperationRestController@getCurrentVendor'));

    Route::get('/report/activeproject/grid/data','Rest\ReportRestController@getActiveProjectGridData');
    Route::get('/report/activeproject/vendor/attendance','Rest\ReportRestController@getVendorAttendanceReport');





    // Employee services
    Route::get('employee/grid/data', array('uses' => 'Rest\EmployeeRestController@getEmployeeGridData'));
    Route::get('employee/detail/{employeeId}', array('uses' => 'Rest\EmployeeRestController@getEmployeeDetail'));
    Route::get('employee/detailed/{employeeId}', array('uses' => 'Rest\EmployeeRestController@getEmployeeDetailedInfo'));
    Route::get('employee/generate/slack/{employeeId}', array('uses' => 'Rest\EmployeeRestController@generateSlackUser'));

    Route::get('employee/tracking/data', array('uses' => 'Rest\EmployeeRestController@getEmployeeTrackingData'));

    Route::post('vendor/changeFlag', array('uses' => 'Rest\VendorRestController@changeVendorFlag'));


    /****************/
/*
    Route::get('getUser/{id}', array('uses' => 'Rest\UserRestController@getUser'));

    Route::get('getCurrentUser', array('uses' => 'Rest\UserRestController@getCurrentUser'));

    Route::get('getCurrentUserContributions', array('uses' => 'Rest\UserRestController@getCurrentUserContributions'));

    Route::get('getUserArticles/{id}', array('uses' => 'Rest\UserRestController@getUserArticles'));

    Route::get('getArticle/{id}', array('uses' => 'Rest\ArticleRestController@getArticle'));

    Route::get('getAllArticles', array('uses' => 'Rest\ArticleRestController@getAllArticles'));

    Route::post('createArticle', array('uses' => 'Rest\ArticleRestController@createArticle'));

    Route::post('editArticle', array('uses' => 'Rest\ArticleRestController@editArticle'));

    Route::get('makeAdmin/{id}', 'Rest\UserRestController@makeAdmin');

    Route::get('makeEditor/{id}', 'Rest\UserRestController@makeEditor');

    Route::get('makeModerator/{id}', 'Rest\UserRestController@makeModerator');

    Route::get('makeAuthor/{id}', 'Rest\UserRestController@makeAuthor');

    Route::get('removeAdmin/{id}', 'Rest\UserRestController@removeAdmin');

    Route::get('removeEditor/{id}', 'Rest\UserRestController@removeEditor');

    Route::get('removeModerator/{id}', 'Rest\UserRestController@removeModerator');

    Route::get('removeAuthor/{id}', 'Rest\UserRestController@removeAuthor');*/

    // complaint
    Route::get('/complaints/categories/{userType}', array('uses' => 'API\ComplaintsAPIController@getComplaintsCategories'));
    Route::get('/complaints/getComplaintSubCategories/{categoryId}/{userType}', array('uses' => 'API\ComplaintsAPIController@getComplaintSubCategories'));
    Route::post('/complaints/addComplaint', array('uses' => 'API\ComplaintsAPIController@postAddComplaintAdmin'));
    Route::get('/complaints/getAllComplaints', array('uses' => 'API\ComplaintsAPIController@getAllComplaints'));
    Route::get('/complaints/getUserComplaints', array('uses' => 'API\ComplaintsAPIController@getUserComplaints'));
    Route::get('/complaints/getCgComplaints', array('uses' => 'API\ComplaintsAPIController@getCgComplaints'));
    Route::get('/complaints/getComplaintStatuses', array('uses' => 'API\ComplaintsAPIController@getComplaintStatuses'));
    Route::get('/complaints/getComplaintDetailed/{complaintId}/{userType}', array('uses' => 'API\ComplaintsAPIController@getComplaintDetailed'));
    
    Route::post('/complaints/changeComplaintStatus', array('uses' => 'API\ComplaintsAPIController@changeComplaintStatus'));
    Route::post('/complaints/complaintResolutionCGTraining', array('uses' => 'API\ComplaintsAPIController@complaintResolutionCGTraining'));
    Route::post('/complaints/complaintResolutionCGReplacement', array('uses' => 'API\ComplaintsAPIController@complaintResolutionCGReplacement'));
    Route::get('/complaints/getComplaintHistoryCGTraining/{complaintId}', array('uses' => 'API\ComplaintsAPIController@getComplaintHistoryCGTraining'));
    Route::get('/complaints/getComplaintHistoryCGReplacement/{complaintId}', array('uses' => 'API\ComplaintsAPIController@getComplaintHistoryCGReplacement'));

    Route::get('/complaints/getReplacementRequests', array('uses' => 'API\ComplaintsAPIController@getReplacementRequests'));

    Route::get('/user/searchByName/{query}', array('uses' => 'API\UserAPIController@getSearchByName'));
    Route::get('/user/getUserLeads/{userId}', array('uses' => 'API\UserAPIController@getUserLeads'));

    //salary API admin
    Route::get('/report/salaryByPeriod','Rest\ReportRestController@getSalaryByPeriodReport');

});




Route::group(['prefix' => 'oauth/cg/api/v1/'], function () {

    Route::post('user/send/otp', array('uses' => 'API\UserAPIController@postLoginOtpRequest'));

    Route::post('access_token', function() {
        return Response::json(\LucaDegasperi\OAuth2Server\Facades\Authorizer::issueAccessToken());
    });

    Route::post('otp/login', array('uses' => 'API\UserAPIController@loginWithOtp'));

    Route::get('complaints/categories/{userType}', array('uses' => 'API\ComplaintsAPIController@getComplaintsCategories'));
});
Route::group(['prefix' => 'oauth/cg/api/v1/', 'middleware' => 'oauth' , 'before' => 'oauth'], function () {

    Route::get('user/profile', array('uses' => 'API\UserAPIController@userProfile'));
    Route::get('user/profileDetailed', array('uses' => 'API\VendorAPIController@userProfile'));
    Route::get('lead/item/{leadId}', array('uses' => 'API\OperationAPIController@getLeadForCG'));
    Route::post('caregiver/location/update', array('uses' => 'API\VendorAPIController@updateCareGiverLocation'));
    Route::get('lead/list', array('uses' => 'API\OperationAPIController@getLeadListForCG'));
    Route::post('lead/response/{leadId}', array('uses' => 'API\OperationAPIController@submitLeadResponseByCG'));
    Route::post('user/register/device', array('uses' => 'API\UserAPIController@registerDevice'));

    Route::post('complaints/addComplaint', array('uses' => 'API\ComplaintsAPIController@postAddComplaintAPICg'));
    Route::get('complaints/list', array('uses' => 'API\ComplaintsAPIController@getComplaintsListAPI'));
    Route::get('complaints/complaintDetails/{complaintId}', array('uses' => 'API\ComplaintsAPIController@getComplaintDetailsAPI'));

    Route::post('salary/addIncentive', array('uses' => 'API\SalaryAPIController@postAddIncentiveAPI'));

});

Route::group(['prefix' => 'oauth/customer/api/v1/'], function () {

    Route::post('user/send/otp', array('uses' => 'API\UserAPIController@postCustomerLoginOtpRequest'));
    Route::post('user/signup/new', array('uses' => 'API\UserAPIController@postCustomerSignUpRequest'));

    Route::post('access_token', function() {
        return Response::json(\LucaDegasperi\OAuth2Server\Facades\Authorizer::issueAccessToken());
    });

    Route::post('otp/login', array('uses' => 'API\UserAPIController@loginWithOtp'));

    Route::post('enquiry/submit', array('uses' => 'Rest\OperationRestController@submitEnquiryMobile'));
    Route::post('lead/patient/update/{leadId}', array('uses' => 'API\OperationAPIController@updateLeadPatientInfoMobile'));
    Route::post('/lead/physioPatient/update/{leadId}', array('uses' => 'API\OperationAPIController@updateLeadPhysioPatientInfo'));
    Route::post('lead/task/update/{leadId}', array('uses' => 'API\OperationAPIController@updateLeadTaskInfoMobile'));
    Route::post('lead/special/update/{leadId}', array('uses' => 'API\OperationAPIController@updateLeadSpecialRequestMobile'));

    Route::get('lead/mapped/data', array('uses' => 'Rest\OperationRestController@getMappedOptions'));

    Route::get('complaints/categories/{userType}', array('uses' => 'API\ComplaintsAPIController@getComplaintsCategories'));

});
Route::group(['prefix' => 'oauth/customer/api/v1/', 'middleware' => 'oauth' , 'before' => 'oauth'], function () {

    Route::get('user/profile', array('uses' => 'API\UserAPIController@userProfile'));
    Route::get('lead/item/{leadId}', array('uses' => 'API\OperationAPIController@getLeadForCG'));
    Route::post('caregiver/location/update', array('uses' => 'API\VendorAPIController@updateCareGiverLocation'));
    Route::get('lead/list', array('uses' => 'API\OperationAPIController@getLeadListForCG'));
    Route::post('lead/response/{leadId}', array('uses' => 'API\OperationAPIController@submitLeadResponseByCG'));
    Route::post('user/register/device', array('uses' => 'API\UserAPIController@registerDevice'));

    // customer specific
    Route::get('user/services/list', array('uses' => 'API\UserAPIController@getUserServicesList'));
    Route::get('user/services/detail/{leadId}', array('uses' => 'API\UserAPIController@getUserServiceDetail'));

    Route::get('lead/closure/options', array('uses' => 'API\OperationAPIController@leadClosureOptions'));
    Route::post('lead/closure/request', array('uses' => 'API\OperationAPIController@leadClosureRequest'));
    Route::post('customer/feedback/submit', array('uses' => 'API\OperationAPIController@customerFeedbackSubmit'));
    Route::post('lead/vendor/status/notreached', array('uses' => 'API\OperationAPIController@leadVendorNotReachedStatus'));
    Route::get('lead/vendor/request/restart/{leadId}', array('uses' => 'API\OperationAPIController@leadRestartRequest'));
    Route::post('lead/cg/mark/attendance', array('uses' => 'API\OperationAPIController@leadMarkCGAttendance'));

    Route::get('user/notification/list', array('uses' => 'API\UserAPIController@getUserNotification'));

    Route::post('complaints/addComplaint', array('uses' => 'API\ComplaintsAPIController@postAddComplaintAPICustomer'));
    Route::get('complaints/list', array('uses' => 'API\ComplaintsAPIController@getComplaintsListAPI'));
    Route::get('complaints/complaintDetails/{complaintId}', array('uses' => 'API\ComplaintsAPIController@getComplaintDetailsAPI'));

});



Route::get('/Home/Contact', function(){
    return Redirect::to('/contact', 301);
});

Route::get('/Home/Service', function(){
    return Redirect::to('/', 301);
});

Route::get('/Home/Aboutus', function(){
    return Redirect::to('/about-us', 301);
});

Route::get('/Home/NursingService', function(){
    return Redirect::to('/services/nursing-care', 301);
});

Route::get('/Home/AssistiveCare', function(){
    return Redirect::to('/services/assistive-care', 301);
});

Route::get('/Home/rehabilitation', function(){
    return Redirect::to('/', 301);
});

Route::get('/Home/valueaddedservices', function(){
    return Redirect::to('/', 301);
});

Route::get('/Home/SetTaskRequire', function(){
    return Redirect::to('/', 301);
});

Route::get('/Home/GetTechnichalList', function(){
    return Redirect::to('/', 301);
});

Route::get('/Home/Login', function(){
    return Redirect::to('/auth/login', 301);
});



Route::get('/sesmail', function(){
    $user = \App\Models\User::where('email','=','mohit2007gupta@gmail.com')->first();
    Mail::send('emails.welcome', ['data' => $user], function ($m) use ($user) {
        $m->from(env('MAIL_FROM'), 'Ubunanny');
        $m->to(env('MAIL_FROM'), $user['name'])->subject('Pramati test mail');
    });
});

Route::get('/slack', 'Link\GuestLinkController@slack');
Route::get('/push', 'Link\GuestLinkController@push');
Route::get('/pushtoken', 'Link\GuestLinkController@pushTokens');

Route::get('/synclead', 'Link\OperationLinkController@syncLead');

// Attendance by 2 way SMS
Route::post('/lead/vendor/attendance/markBySms', array('uses' => 'Rest\OperationRestController@submitCGAttendanceBySMS'));