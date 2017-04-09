/**
 * Created by mohitgupta on 20/05/16.
 */
var frontService = angular.module('front.services',[]);


frontService.factory('FrontService', ["$location", "$http", "$log", "$q",function($location, $http, $log, $q) {
    return {
        submitEnquiry: function (enquiry) { 
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/enquiry/submit';
            $http({
                url: urlToUse,  
                method: "POST",
                headers: {enctype:'multipart/form-data'},
                files: true,
                data: enquiry
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        submitEnquiryForCall : function(enquiry){
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/enquiry/submit/call';
            $http({
                url: urlToUse,
                method: "POST",
                data: enquiry
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        verifyOtp : function(leadId,otp){

            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/enquiry/verify/otp';
            $http({
                url: urlToUse,
                method: "POST",
                data: {
                    leadId: leadId,
                    otp: otp
                }
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        callToLead: function(leadId){
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/enquiry/callmenow/'+leadId;
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        sendNotificationAboutLead: function (leadId, enquiry) {
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/enquiry/notification/'+leadId;
            $http({
                url: urlToUse,
                method: "POST",
                data: enquiry
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        submitContactForm: function (enquiry) {
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/contact/submit';
            $http({
                url: urlToUse,
                method: "POST",
                data: enquiry
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        submitPatientInfo: function (leadId,patientInfo) {
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/lead/patient/update/'+leadId;
            $http({
                url: urlToUse,
                method: "POST",
                data: patientInfo
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        submitPhysioPatientInfo: function (leadId,patientInfo) {
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/lead/physioPatient/update/'+leadId;
            $http({
                url: urlToUse,
                method: "POST",
                data: patientInfo
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        submitLeadTaskDetail: function (leadId,taskInfo) {
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/lead/task/update/'+leadId;
            $http({
                url: urlToUse,
                method: "POST",
                data: taskInfo
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        submitSpecialRequest: function (leadId,request) {
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/lead/special/update/'+leadId;
            $http({
                url: urlToUse,
                method: "POST",
                data: request
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
        getAilmentByService: function (serviceId) {
            if(angular.isUndefined(serviceId)){
                return false;
            }
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/service/ailment/'+ serviceId;
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        getTaskByAilment: function (ailmentId) {
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/ailment/task/'+ ailmentId;
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        getTaskListByService: function (serviceId) {
            var deferred = $q.defer();
            var getUrl = baseUrl + '/api/v1/lead/task/service/'+serviceId;

            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        submitRequest: function (requestData) {
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/lead/submit';
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
        }
    }
}]);
