/**
 * Created by SYMB on 5/24/2016.
 */
var adminService = angular.module('report.services',[]);

adminService.factory('ReportService', ["$http", "$q" ,function($http, $q) {
    return {
        getActiveProjectList: function () {
            var deferred = $q.defer();
            var getUrl = baseUrl+'/api/v1/report/activeproject/grid/data';
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        getVendorAttendanceForDate: function (date) {
            var dateString = "";
            dateString = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
            var deferred = $q.defer();
            var getUrl = baseUrl+'/api/v1/report/activeproject/vendor/attendance?date='+dateString;
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        },
        getSalaryReportForPeriod: function (dateFrom, dateTo, showMode) {
            var dateStringFrom = "";
            dateStringFrom = dateFrom.getFullYear()+"-"+(dateFrom.getMonth()+1)+"-"+dateFrom.getDate();
            var dateStringTo = "";
            dateStringTo = dateTo.getFullYear()+"-"+(dateTo.getMonth()+1)+"-"+dateTo.getDate();
            var deferred = $q.defer();
            var getUrl = baseUrl+'/api/v1/report/salaryByPeriod?dateFrom='+dateStringFrom+'&dateTo='+dateStringTo+'&showMode='+showMode;
            $http.get(getUrl).success(function (data) {
                deferred.resolve(data);
            }).error(function (data, status) {
                deferred.reject(data, status);
            });
            return deferred.promise;
        }
    }
}]);