angular.module('userModule', ['ngMaterial','md.data.table', 'ui.grid','user.services'])
    .config(function($mdThemingProvider) {
        $mdThemingProvider.theme('default')
            .primaryPalette('teal')
            .accentPalette('green');
    })
    .filter('carbondate', ["$filter",function ($filter) {
        return function(carbonDate) {
            if(carbonDate==null ||  angular.isUndefined(carbonDate)){
                return null;
            }
            if(angular.isDefined(carbonDate.date)){
                var tempDateObj =  new Date(carbonDate.date);
                return $filter("date")(tempDateObj);
            }
            return "";
        };
    }])
    .controller('userListController', function($scope,$mdDialog,$mdEditDialog, UserService) {
        'use strict';
        $scope.gridOptions = {
            enableSorting: true,
            columnDefs: [
                { field: 'name' },
                { field: 'email' },
                { field: 'role'},
                { field: 'id',displayName:'', enableSorting: false ,  cellTemplate: '<div class="text-center colt{{$index}}" class="text-center"><button class="btn btn-xs" ng-click="grid.appScope.viewUser(row.entity.id)">View</button></div>'}
            ],
            onRegisterApi: function( gridApi ) {
                $scope.grid1Api = gridApi;
            }
        };
        $scope.viewUser = function(userId){
            window.location.href = baseUrl+"/user/edit/employee/"+userId;
        }
        var fetchUser = function(){

        }
        $scope.init = function(){
            UserService.getUserGridList().then(function(response){
                $scope.gridOptions.data = response.data;
            });
        }
        $scope.createNewUser = function(ev){
            $mdDialog.show({
                controller: NewUserDialogController,
                templateUrl: baseUrl+'/static/js/admin/templates/newUserAddDialog.tmpl.html',
                parent: angular.element(document.body),
                targetEvent: ev,
                clickOutsideToClose:true
            })
            .then(function(answer) {
                UserService.getUserGridList().then(function(response){
                    $scope.gridOptions.data = response.data;
                });
            }, function() {

            });
        }
        $scope.init();
    });


function NewUserDialogController($scope,$mdDialog,UserService){
    $scope.user = {
        name:'',
        email:'',
        phone:'',
        type:1,
        password: ''
    };
    $scope.message = {
        show: false,
        message: "Hi"
    }
    $scope.cancel = function() {
        $mdDialog.cancel();
    };
    $scope.submitAddUser = function(){
        $scope.message.show = false;
        angular.forEach($scope.addUser.$error.required, function(field) {
            field.$setTouched();
        });
        if($scope.addUser.$invalid){
            return;
        }

        UserService.addUser($scope.user).then(function(response){
            if(!response.status){
                $scope.message.message = response.message;
                $scope.message.show = true;
                //$mdDialog.cancel(response.data);
            }else{
                $scope.message.show = false;
                $mdDialog.hide(response.data);
            }

        });
    }

}