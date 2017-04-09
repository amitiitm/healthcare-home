/**
 * Created by SYMB on 5/24/2016.
 */
var adminService = angular.module('employee.services',[]);

adminService.factory('EmployeeService', ["$http", "$q" ,function($http, $q) {
    return {
        getEmployeeList: function () {
            var deferred = $q.defer();
            var getUrl = baseUrl+'/api/v1/employee/grid/data';
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        getEmployeeInfo: function (employeeId) {
            var deferred = $q.defer();
            var getUrl = baseUrl+'/api/v1/employee/detail/'+employeeId;
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        getEmployeeDetail: function (employeeId) {
            var deferred = $q.defer();
            var getUrl = baseUrl+'/api/v1/employee/detailed/'+employeeId;
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },generateSlackUser: function (employeeId) {
            var deferred = $q.defer();
            var getUrl = baseUrl+'/api/v1/employee/generate/slack/'+employeeId;
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        updateBasicInformation: function (employeeId,basicData) {
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/employee/update/basic/'+employeeId;
            $http({
                url: urlToUse,
                method: "POST",
                data: basicData
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        getFilteredEmployeeTrackingList: function($leadId,$limit,$userName){
            var deferred = $q.defer();
            var getUrl = baseUrl+'/api/v1/employee/tracking/data?list_type=filtered&limit='+$limit+'&user_name='+$userName;
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        getEmployeeTrackingList: function(){
            var deferred = $q.defer();
            var getUrl = baseUrl+'/api/v1/employee/tracking/data';
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        getVendorDetail: function (vendorId) {
            var deferred = $q.defer();
            var getUrl = baseUrl+'/api/v1/data/vendor/'+vendorId
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        submitVendor: function (info) {
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/vendor/submit';
            $http({
                url: urlToUse,
                method: "POST",
                data: info
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        }
    }
}]);