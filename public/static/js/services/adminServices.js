/**
 * Created by SYMB on 5/24/2016.
 */
var adminService = angular.module('admin.services',[]);

adminService.factory('AdminService', ["$http", "$q" ,function($http, $q) {
    return {
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
        getDashboardData : function(){
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: baseUrl+'/api/v1/admin/dashboard'
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        }
    }
}]);