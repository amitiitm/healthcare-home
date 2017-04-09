/**
 * Created by SYMB on 5/24/2016.
 */
var adminService = angular.module('user.services',[]);

adminService.factory('UserService', ["$http", "$q" ,function($http, $q) {
    return {
        getUserGridList: function () {
            var deferred = $q.defer();
            var getUrl = baseUrl+'/api/v1/user/grid/data';
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        addUser: function(user){
            var deferred = $q.defer();
            var urlToUse = baseUrl+'/api/v1/user/new';
            $http({
                url: urlToUse,
                method: "POST",
                data: user
            }).success(function(data){
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject();
            });
            return deferred.promise;
        }
    }
}]);