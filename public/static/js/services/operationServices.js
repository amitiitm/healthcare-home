/**
 * Created by SYMB on 5/24/2016.
 */
var adminService = angular.module('operation.services',[]);

adminService.factory('OperationService', ["$http", "$q" ,function($http, $q) {
    return {
        createSlackChannel: function(leadId){
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/lead/create/slack/channel';
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        submitAdminLead: function (lead) {
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/lead/submit';
            $http({
                url: urlToUse,
                method: "POST",
                data: lead
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        updateLeadDetail: function (leadId,lead) {
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/lead/update/'+leadId;
            $http({
                url: urlToUse,
                method: "POST",
                data: lead
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },

        getTaskListForValidation: function (enquiry) {
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/lead/validation/task/categories';
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        trainingNotAttendedReasons: function (enquiry) {
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/vendor/training/reasons';
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        getPatientValidationData: function (patientId) {
            var deferred = $q.defer();

            var getUrl = baseUrl + '/api/v1/lead/validation/data/patient/'+patientId;
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },getPatientValidatedData: function (patientId) {
            var deferred = $q.defer();

            var getUrl = baseUrl + '/api/v1/lead/validated/data/patient/'+patientId;
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },getVendorAvailabilityOptions: function(){
            var deferred = $q.defer();
            var getUrl = baseUrl+'/api/v1/vendor/availability/options';
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },getVendorAvailabilityMapper: function(){
            var deferred = $q.defer();
            var getUrl = baseUrl+'/api/v1/vendor/availability/mapper';
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        getLeads : function(count){
            var urlToFire =baseUrl+'/api/v1/lead/list/grid/data';
            if(angular.isDefined(count)){
                urlToFire += '?count=';
                urlToFire += count
            }
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: urlToFire
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },getClosedLeads : function(){
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: baseUrl+'/api/v1/lead/closed/grid/data'
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },getPendingLeads : function(){
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: baseUrl+'/api/v1/lead/pending/grid/data'
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },getValidatedLeads : function(){
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: baseUrl+'/api/v1/lead/validated/grid/data'
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },getActiveLeads : function(){
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: baseUrl+'/api/v1/lead/started/grid/data'
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },getTodaysLeads : function(){
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: baseUrl+'/api/v1/lead/today/grid/data'
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        getCustomerNotificationTemplates : function(){
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: baseUrl+'/api/v1/lead/customer/notification/templates'
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        sendNotificationToCustomer : function(leadId,notificationData){
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/lead/customer/notification/submit/'+leadId;
            $http({
                url: urlToUse,
                method: "POST",
                data: notificationData
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        getLeadDetail : function(leadId){
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: baseUrl+'/api/v1/lead/detail/'+leadId
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        syncSlackChat : function(leadId){
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: baseUrl+'/api/v1/lead/sync/slack/comment/'+leadId
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        getCarePlanGrid : function(leadId){
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: baseUrl+'/api/v1/lead/careplan/grid/'+leadId
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        markCarePlanCheck: function(leadId,action, data){
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/lead/careplan/evaluation/'+action+'/'+leadId;
            $http({
                url: urlToUse,
                method: "POST",
                data: data
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        getLeadLogs : function(leadId){
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: baseUrl+'/api/v1/lead/logs/'+leadId
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        getEmployeeListToAssign: function(){
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: baseUrl+'/api/v1/lead/assignment/employee/list'
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        getVendorAutoListToAssign: function(leadId){
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: baseUrl+'/api/v1/lead/'+leadId+'/assignment/vendor/list?filter=auto'
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },getVendorDetail: function (vendorId) {
            var deferred = $q.defer();
            var getUrl = baseUrl+'/api/v1/data/vendor/'+vendorId
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },updateVendorAvailability: function (vendorId,availability) {
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/vendor/availability/update/'+vendorId;
            $http({
                url: urlToUse,
                method: "POST",
                data: availability
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        getPaginatedVendorListToAssign: function(leadId,paginationOptions){
            var pagenumber = paginationOptions.pageNumber;
            var pagesize = paginationOptions.pageSize;
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: baseUrl+'/api/v1/lead/'+leadId+'/assignment/vendor/list?pagenumber='+pagenumber+'&pagesize='+pagesize
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },getVendorListToAssign: function(leadId){
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: baseUrl+'/api/v1/lead/'+leadId+'/assignment/vendor/list'
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        getVendorDeployedStatus: function(){
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: baseUrl+'/api/v1/lead/vendor/deployed/status/list'
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        getOperationalStausesGrouped: function(){
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: baseUrl+'/api/v1/lead/statuses/grouped/list'
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        getLeadContactNo: function(leadId){
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: baseUrl+'/api/v1/lead/view/phone/'+leadId
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        getCareGiverList: function(leadId){
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: baseUrl+'/api/v1/vendor/available/list'
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        markCGAttendance : function(requestData){
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/lead/vendor/attendance/mark/'+requestData.leadId;
            $http({
                url: urlToUse,
                method: "POST",
                data: requestData
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        addComment : function(userComment){
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/lead/comment/submit/'+userComment.leadId;
            $http({
                url: urlToUse,
                method: "POST",
                data: userComment
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        assignEmployeeToLead : function(userAssignment){
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/lead/assignment/employee/'+userAssignment.leadId;
            $http({
                url: urlToUse,
                method: "POST",
                data: {
                    leadId: userAssignment.leadId,
                    userId: userAssignment.user.id
                }
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        assignQcEmployeeToLead : function(userAssignment){
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/lead/assignment/qcEmployee/'+userAssignment.leadId;
            $http({
                url: urlToUse,
                method: "POST",
                data: {
                    leadId: userAssignment.leadId,
                    userId: userAssignment.user.id
                }
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },assignFieldEmployeeToLead : function(userAssignment){
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/lead/assignment/fieldEmployee/'+userAssignment.leadId;
            $http({
                url: urlToUse,
                method: "POST",
                data: {
                    leadId: userAssignment.leadId,
                    userId: userAssignment.user.id
                }
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        assignCGToLead : function(leadId,vendorId,pricePerDay, isPrimary,sourcingData){
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/lead/assignment/vendor/'+leadId;
            $http({
                url: urlToUse,
                method: "POST",
                data: {
                    leadId: leadId,
                    vendorId: vendorId,
                    price:pricePerDay,
                    primary: isPrimary,
                    sourcingData: sourcingData
                }
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        submitQCBriefing : function(leadId,qcAssignmentId,briefingData){
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/lead/qc/briefing/submit/'+leadId;
            $http({
                url: urlToUse,
                method: "POST",
                data: {
                    leadId: leadId,
                    qcAssignmentId: qcAssignmentId,
                    briefingData: briefingData
                }
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        deleteLead : function(leadId){
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/lead/delete/'+leadId;
            $http({
                url: urlToUse,
                method: "POST",
                data: {
                    leadId: leadId
                }
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        deleteBulkLead : function(leadList){
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/lead/bulk/delete';
            $http({
                url: urlToUse,
                method: "POST",
                data: {
                    leadId: leadList
                }
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },startLeadService : function(leadId, vendorId){
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/lead/start/'+leadId;
            $http({
                url: urlToUse,
                method: "POST",
                data: {
                    leadId: leadId,
                    vendorId: vendorId
                }
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        updateLeadStatus : function(statusObject){
            var deferred = $q.defer();
            var dataObject = {
                leadId: statusObject.leadId,
                statusId: statusObject.status
            }
            if(angular.isDefined(statusObject.user) && angular.isDefined(statusObject.user.id)){
                dataObject.userId = statusObject.user.id;
            }else{
                dataObject.userId = null;
            }
            if(angular.isDefined(statusObject.comment) && statusObject.comment!=""){
                dataObject.comment = statusObject.comment;
            }else{
                dataObject.comment = '';
            }
            if(angular.isDefined(statusObject.reason) && statusObject.reason!=""){
                dataObject.reasonId = statusObject.reason;
            }else{
                dataObject.reasonId = null;
            }
            if(angular.isDefined(statusObject.data) && statusObject.data!=""){
                dataObject.data = statusObject.data;
            }else{
                dataObject.data = null;
            }
            if(angular.isDefined(statusObject.date) && statusObject.date!=""){
                dataObject.date = statusObject.date;
            }else{
                dataObject.date = null;
            }
            if(angular.isDefined(statusObject.deduction) && statusObject.deduction!=0){
                dataObject.deduction = statusObject.deduction;
            }else{
                dataObject.deduction = 0;
            }


            var urlToUse = baseUrl+'/api/v1/lead/status/update/'+statusObject.leadId;
            $http({
                url: urlToUse,
                method: "POST",
                data: dataObject
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        approveLead : function(statusObject){
            var deferred = $q.defer();
            var dataObject = {
                leadId: statusObject.leadId
            }
            if(angular.isDefined(statusObject.user) && angular.isDefined(statusObject.user.id)){
                dataObject.userId = statusObject.user.id;
            }else{
                dataObject.userId = null;
            }
            if(angular.isDefined(statusObject.comment) && statusObject.comment!=""){
                dataObject.comment = statusObject.comment;
            }else{
                dataObject.comment = '';
            }

            var urlToUse = baseUrl+'/api/v1/lead/approve/'+statusObject.leadId;
            $http({
                url: urlToUse,
                method: "POST",
                data: dataObject
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },

        getCreateLeadDataMapped: function (enquiry) {
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/lead/mapped/data';
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        getAilments : function(){
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/lead/ailments';
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        getAilmentTasks : function(){
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/lead/ailment/tasks';
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },

        complaintCategoriesByType : function($userType){
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/complaints/categories/'+$userType;
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        getUsersList : function($query){
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/user/searchByName/'+$query;
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        getUserLeads : function($userId){
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/user/getUserLeads/'+$userId;
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        submitComplaint: function (complaintData) {
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/complaints/addComplaint';
            $http({
                url: urlToUse,
                method: "POST",
                data: complaintData
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        getAllComplaints: function () {
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/complaints/getAllComplaints';
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        getUserComplaints: function () {
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/complaints/getUserComplaints';
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        getCgComplaints: function () {
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/complaints/getCgComplaints';
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        getComplaintStatuses: function () {
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/complaints/getComplaintStatuses';
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        getComplaintDetailed: function (complaintId,userType) {
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/complaints/getComplaintDetailed/'+complaintId+'/'+userType;
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        changeComplaintStatus: function (changeStatusData) {
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/complaints/changeComplaintStatus';
            $http({
                url: urlToUse,
                method: "POST",
                data: changeStatusData
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        submitResolutionCGTraining : function(requestData){
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/complaints/complaintResolutionCGTraining';
            $http({
                url: urlToUse,
                method: "POST",
                data: requestData
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        submitResolutionCGReplacement : function(requestData){
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/complaints/complaintResolutionCGReplacement';
            $http({
                url: urlToUse,
                method: "POST",
                data: requestData
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        getComplaintHistoryCGTraining: function (complaintId) {
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/complaints/getComplaintHistoryCGTraining/'+complaintId;
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        getComplaintHistoryCGReplacement: function (complaintId) {
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/complaints/getComplaintHistoryCGReplacement/'+complaintId;
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        getAllotableEmployeesComplaints : function(){
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/complaints/getAllotableEmployeesComplaints/list';
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        getComplaintResolutionGroups : function(){
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/complaints/getComplaintResolutionGroups/list';
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        submitComplaintResolutionGroupAddMember : function(requestData){
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/complaints/postComplaintResolutionGroupAddMember';
            $http({
                url: urlToUse,
                method: "POST",
                data: requestData
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        getComplaintResolutionMembers : function(){
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/complaints/getComplaintResolutionMembers/list';
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        deleteComplaintResolutionMember : function(member_relation_id){
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/complaints/deleteComplaintResolutionMember/'+member_relation_id;
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        getCurrentVendor : function(leadId){
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/lead/getCurrentVendor/'+leadId;
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        getComplaintSubCategories : function(categoryId, userType){
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/complaints/getComplaintSubCategories/'+categoryId+'/'+userType;
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        addComplaintLog : function(requestData){
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/complaints/addComplaintLog';
            $http({
                url: urlToUse,
                method: "POST",
                data: requestData
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        changeFlag : function(requestData){
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/vendor/changeFlag';
            $http({
                url: urlToUse,
                method: "POST",
                data: requestData
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        getReplacementRequests: function () {
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/complaints/getReplacementRequests';
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
    }
}]);