angular.module('employeeModule', ['ngMaterial','md.data.table','ui.bootstrap','ui.grid','ngtimeago','uiGmapgoogle-maps','google.places','ngSanitize', 'ui.select','ngFileUpload','infrastructure.imageupload','admin.services','operation.services','employee.services'])
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
    .filter('carbontime', ["$filter",function ($filter) {
        return function(carbonDate) {
            if(carbonDate==null ||  angular.isUndefined(carbonDate)){
                return null;
            }
            if(angular.isDefined(carbonDate.date)){
                var tempDateObj =  new Date(carbonDate.date);
                return $filter("date")(tempDateObj,'medium');
            }
            return "";
        };
    }])
    .controller('EmployeeTrackingController', function($scope,$uibModal, OperationService,EmployeeService) {
        $scope.employeeList = [];

        $scope.selectedEmployee = {

        }
        $scope.marker = [];

        $scope.fetchUser = function(){
            var user_name = $scope.searchEmployee;
            if(user_name !== '' && user_name.length >= 3){
                    EmployeeService.getFilteredEmployeeTrackingList($scope.leadId,500,user_name).then(function(response){
                    $scope.employeeList = response.data;
                });
            }
        }

        $scope.showLocation = function(employeeData){
            $scope.selectedEmployee = employeeData;

            var lastLocation = employeeData.locations[employeeData.locations.length-1];

            var geocoder = new google.maps.Geocoder();
            var latlng = new google.maps.LatLng(lastLocation.latitude, lastLocation.longitude);

            geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[1]) {

                        console.log(results[1].formatted_address); // details address
                        $scope.selectedEmployee.formattedAddress = (results[1].formatted_address); // details address
                    } else {

                    }
                } else {

                }
            });


            var marker = {
                id: Date.now(),
                showWindow:true,
                coords: {
                    latitude: lastLocation.latitude,
                    longitude: lastLocation.longitude
                },
                options: {
                    animation:0
                },
                events: {
                    click: function(mapModel, eventName, originalEventArgs,ok) {
                        var e = originalEventArgs[0];



                    }
                },
                employeeData: $scope.selectedEmployee,
                show: false,
                isIconVisibleOnClick: true,
                closeClick: false
            };
            $scope.map.center.latitude = lastLocation.latitude;
            $scope.map.center.longitude = lastLocation.longitude;
            $scope.map.markers = [];
            $scope.map.markers.push(marker);
            /*$scope.marker.push({
                id: Date.now(),
                coords: {
                    latitude: lastLocation.latitude,
                    longitude: lastLocation.longitude
                },
                options: { draggable: false },
                events: {

                }
            });*/
        }
        $scope.init = function(){
            EmployeeService.getFilteredEmployeeTrackingList($scope.leadId,50,'').then(function(response){
                $scope.employeeList = response.data;
            });
        }
        $scope.toggleEmployeeDetail = function(){
            $scope.showMoreEmployeeDetail = !$scope.showMoreEmployeeDetail;
        }


        $scope.map = { center: { latitude: 28.6139, longitude: 77.2090 }, zoom: 14 };
        angular.extend($scope, {
            map: {
                center: {
                    latitude: 28.6139,
                    longitude:77.2090
                },
                zoom: 12,
                markers: [],
                events: {
                    click: function (map, eventName, originalEventArgs) {
                        /*var e = originalEventArgs[0];
                        var lat = e.latLng.lat(),lon = e.latLng.lng();
                        var marker = {
                            id: Date.now(),
                            coords: {
                                latitude: lat,
                                longitude: lon
                            }
                        };
                        $scope.map.markers.push(marker);
                        console.log($scope.map.markers);
                        $scope.$apply();*/
                    }
                }
            }
        });
        $scope.init();
    })
    .controller('EmployeeEditController', function($scope,$uibModal, OperationService,EmployeeService) {
        $scope.userData = {};
        $scope.employeeUserId = employeeId;
        EmployeeService.getEmployeeDetail($scope.employeeUserId).then(function(response){
            $scope.userData = response.data;
        });
        $scope.generateSlackUser = function(){
            EmployeeService.generateSlackUser($scope.employeeUserId).then(function(response){
                if(response.status){
                    window.location.reload();
                }
            });
        }
        $scope.updateBasicInformation = function(){
            EmployeeService.updateBasicInformation($scope.employeeUserId,$scope.userData.basic).then(function(response){
                window.location.reload();
            });
        }
    })
    .controller('EmployeeViewController', function($scope,$uibModal, OperationService,EmployeeService) {
        $scope.userData = {};
        $scope.employeeUserId = employeeId;
        EmployeeService.getEmployeeInfo($scope.employeeUserId).then(function(response){
            $scope.userData = response.data;
        });
    })
    .controller('EmployeeListController', function($scope,$uibModal, OperationService,EmployeeService) {
        $scope.employeeList = [];

        $scope.userToAssign = {
            user: null,
            leadId: $scope.leadId
        };

        $scope.gridOptions = {
            enableSorting: true,
            columnDefs: [
                { field: 'name', displayName:'Name' },
                { field: 'mobile', displayName:'Mobile'},
                { field: 'departments', displayName:'Departments' },
                { field: 'id',displayName:'', enableSorting: false ,  cellTemplate: '<div class="text-center colt{{$index}}" class="text-center"><a class="btn btn-xs" ng-click="grid.appScope.viewEmployee(row.entity)">View</button></div>'}
            ],
            onRegisterApi: function( gridApi ) {
                $scope.grid1Api = gridApi;
            }
        };

        $scope.viewEmployee = function(employee){
            window.location.href = baseUrl+'/admin/employee/'+employee.id;
        }
        $scope.init = function(){
            EmployeeService.getEmployeeList($scope.leadId).then(function(response){
                $scope.gridOptions.data = response.data;
            });
        }
        $scope.toggleEmployeeDetail = function(){
            $scope.showMoreEmployeeDetail = !$scope.showMoreEmployeeDetail;
        }

        $scope.init();
    });
