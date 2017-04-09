/**
 * Created by SYMB on 5/24/2016.
 */
var adminService = angular.module('vendor.services',[]);

adminService.factory('VendorService', ["$http", "$q" ,function($http, $q) {
    return {
        getVendorGridList: function () {
            var deferred = $q.defer();
            var getUrl = baseUrl+'/api/v1/vendor/grid/data';
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
        getTaskListForVendor: function (vendorId) {
            var deferred = $q.defer();
            var getUrl = baseUrl+'/api/v1/vendor/task/grouped/'+vendorId
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        getVendorAvailabilityOptions: function(){
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
        },getVendorDocumentTypes: function(){
            var deferred = $q.defer();
            var getUrl = baseUrl+'/api/v1/vendor/document/types';
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },trainingNotAttendedReason: function(){
            var deferred = $q.defer();
            var getUrl = baseUrl+'/api/v1/vendor/training/reasons';
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
        },
        updateVendorAvailability: function (vendorId,availability) {
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
        updateVendor: function (vendorData) {
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/vendor/operation/update/'+vendorData.id;
            $http({
                url: urlToUse,
                method: "POST",
                data: vendorData
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        deleteVendor: function (vendorData) {
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/vendor/operation/delete/'+vendorData.userId;
            $http({
                url: urlToUse,
                method: "POST",
                data: {
                    vendorData: vendorData
                }
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        deleteVendors: function (vendorIds) {
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/vendor/operation/deleteVendors';
            $http({
                url: urlToUse,
                method: "POST",
                data: {
                    vendorIds: vendorIds
                }
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        },
        deleteVendorDocument: function (documentId) {
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/vendor/operation/document/delete/'+documentId
            $http({
                url: urlToUse,
                method: "POST",
                data: {

                }
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        }
    }
}]);