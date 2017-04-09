angular.module('adminModule', ['ngMaterial','md.data.table','ui.bootstrap','ui.grid','ui.grid.selection','ngtimeago','google.places','ngSanitize', 'ui.select','ngFileUpload','infrastructure.imageupload','admin.services','operation.services'])
    .config(function($mdThemingProvider) {
        $mdThemingProvider.theme('default')
            .primaryPalette('teal')
            .accentPalette('green');

    })
    .filter('carbondate', ["$filter",function ($filter) {
        return function(carbonDate) {
            if(carbonDate==null ||  angular.isUndefined(carbonDate)){
                return '';
            }
            if(angular.isDefined(carbonDate.date)){
                var tempDateObj =  new Date(carbonDate.date);
                return $filter("date")(tempDateObj);
            }else{
                var tempDateObj =  new Date(carbonDate);
                return $filter("date")(tempDateObj);
            }
            return "";
        };
    }])
    .directive('leadLogItem',function() {
        return {
            restrict: 'A',
            scope: {
                'daycare': '=',
                'selectedLocation': '=',
                'refreshDistance': '=',
                'searchParam': '='
            },
            link: function (scope, element, attrs, controllers) {

            },
            templateUrl: baseUrl+'/app/js/daycarebooking/partials/daycaresearchitem.html',
            controller: ["$scope","GoogleDistanceAPI","CommonService",function($scope,GoogleDistanceAPI,CommonService){
                $scope.ratingmodel = {};
                $scope.ratingmodel.rate = 2.5;
                $scope.ratingmodel.max = 5;
            }]
        }
    })
    .controller('adminDashboardCtrl', function($scope,$uibModal, AdminService, OperationService) {
        'use strict';

        $scope.dashboardData = {};
        $scope.init = function(){
            AdminService.getDashboardData().then(function(response){
                $scope.dashboardData = response.data;
            });
            $scope.getLeads();
        }



        $scope.baseUrl = "mohit";
        $scope.tabActive = 'all';
        $scope.openTab = function(param){
            $scope.tabActive=param;
            if(param=='closedlead'){
                OperationService.getClosedLeads().then(function(response){
                    $scope.gridOptionsClosedLead.data = response.data;
                });
            } else if(param=='activelead'){
                OperationService.getActiveLeads().then(function(response){
                    $scope.gridOptionsActiveLead.data = response.data;
                });
            }else if(param=='validated'){
                OperationService.getValidatedLeads().then(function(response){
                    $scope.gridOptionsValidated.data = response.data;
                });
            }else if(param=='pending'){
                OperationService.getPendingLeads().then(function(response){
                    $scope.gridOptionsPending.data = response.data;
                });
            }else if(param=='today'){
                OperationService.getTodaysLeads().then(function(response){
                    $scope.gridOptionsToday.data = response.data;
                });
            }         
        }

        $scope.name = "lana";
        $scope.getLeadLink = function(id){
            return baseUrl+"/lead/"+id;
        }

        $scope.startDateHighlighting = function(row){
            //console.log(row);
            var dNow = new Date()
            var enquiryDate = angular.isDefined(row.enquiryDate.date)? new Date(row.enquiryDate.date): new Date(row.enquiryDate);
            if(row.operationStatus.slug=='pending'){
                var diff = Math.abs(dNow.getTime() - enquiryDate.getTime());
                if(diff/60>10){
                    return 'bg-lead-no-action';
                }
            }
        }
        $scope.positiveArr = ['active','validated','started','approved','lead-approved'];
        $scope.noEmployeeAssignedHighlighting = function(row){
            var dNow = new Date();
            if(row.salesApprovedAt==null){
                return '';
            }
            var enquiryDate = (angular.isDefined(row.salesApprovedAt) && angular.isDefined(row.salesApprovedAt.date))? new Date(row.salesApprovedAt.date): new Date(row.salesApprovedAt);
            if( $scope.positiveArr.indexOf(row.operationStatus.slug)>=0 && row.employeeAssigned==null){
                var diff = Math.abs(dNow.getTime() - enquiryDate.getTime());
                if(diff/60>15){
                    return 'bg-lead-no-employee';
                }
            }
        }
        $scope.noQCAssignedHighlighting = function(row){
            var dNow = new Date();
            if(row.salesApprovedAt==null){
                return '';
            }
            var enquiryDate = (angular.isDefined(row.salesApprovedAt) && angular.isDefined(row.salesApprovedAt.date))? new Date(row.salesApprovedAt.date): new Date(row.salesApprovedAt);
            if( $scope.positiveArr.indexOf(row.operationStatus.slug)>=0 && row.qcUserAssigned==null){
                var diff = Math.abs(dNow.getTime() - enquiryDate.getTime());
                if(diff/60>30){
                    return 'bg-lead-no-qc';
                }
            }
        }
        $scope.noCGAssignedHighlighting = function(row){
            var dNow = new Date();
            if(row.employeeAssignedAt==null){
                return '';
            }
            var enquiryDate = (angular.isDefined(row.employeeAssignedAt) && angular.isDefined(row.employeeAssignedAt.date))? new Date(row.employeeAssignedAt.date): new Date(row.employeeAssignedAt);
            if( $scope.positiveArr.indexOf(row.operationStatus.slug)>=0 && row.primaryVendorAssigned==null){
                var diff = Math.abs(dNow.getTime() - enquiryDate.getTime());
                if(diff/60>(4*60)){
                    return 'bg-lead-no-cg';
                }
            }
        }

        $scope.CGAppHighlight = function(row, cgMode){
            var obj;
            if(cgMode == 'primary'){
                obj = row.primaryVendorAssigned;
            } else if(cgMode == 'backUp'){
                obj = row.backUpVendorAssigned;
            } else {
                return '';
            }

            if(obj==null){
                return '';    
            }
            if(obj.appInstalled==true){
                return '';
            } else {
                return 'customer-app-installed';
            }
        }

        $scope.gridOptions = {
            enableSorting: true,
            enableFiltering: true,
            enableRowSelection: true,
            enableSelectAll: true,
            columnDefs: [
                { field: 'enquiryDate.date', displayName:'Enquiry Date', cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents display-block">{{row.entity.enquiryDate | carbondate}}</span></div>' },
                { field: 'startDate', displayName:'Start Date', cellTemplate: '<div class="ngCellText" ng-class="grid.appScope.startDateHighlighting(row.entity)"><span ng-cell-text class="ui-grid-cell-contents display-block">{{(row.entity.startDate | carbondate) || "NA"}}</span></div>' },
                { field: 'customerName', displayName:'Customer', cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block" ng-class="{\'customer-app-installed\': !row.entity.appInstalled}"><a ng-href="{{grid.appScope.getLeadLink(row.entity.id)}}">{{row.entity[col.field]}}</a></span></div>' },
                { field: 'service.name', displayName:'Service' },
                { field: 'employeeAssigned.name', displayName:'Employee Assigned' , cellTemplate: '<div class="ui-grid-cell-contents" ng-class="grid.appScope.noEmployeeAssignedHighlighting(row.entity)">{{grid.getCellValue(row, col)}}</div>'  },
                { field: 'primaryVendorAssigned.name', displayName:'CG Assigned',cellTemplate: '<div class="ngCellText" ng-class="grid.appScope.noCGAssignedHighlighting(row.entity)"><span ng-cell-text class="ui-grid-cell-contents display-block" ng-class="grid.appScope.CGAppHighlight(row.entity, \'primary\')"><a>{{grid.getCellValue(row, col)}}</a></span></div>'},
                { field: 'backUpVendorAssigned.name', displayName:'Back-Up CG Assigned',cellTemplate: '<div class="ngCellText"><span ng-cell-text class="ui-grid-cell-contents display-block" ng-class="grid.appScope.CGAppHighlight(row.entity, \'backUp\')"><a>{{grid.getCellValue(row, col)}}</a></span></div>' },
                { field: 'qcUserAssigned.name', displayName:'QC Assigned',cellTemplate: '<div class="ui-grid-cell-contents" ng-class="grid.appScope.noQCAssignedHighlighting(row.entity)">{{grid.getCellValue(row, col)}}</div>'  },
                { field: 'fieldUserAssigned.name', displayName:'Field Assigned' },
                { field: 'submissionMode', displayName:'Source' },
                { field: 'operationStatus.label', displayName:'Status' }
            ],
            onRegisterApi: function( gridApi ) {
                $scope.grid1Api = gridApi;
            }
        };
        $scope.gridOptions.multiSelect = true;

        $scope.gridOptionsToday = angular.copy($scope.gridOptions);
        $scope.gridOptionsPending = angular.copy($scope.gridOptions);
        $scope.gridOptionsForMe = angular.copy($scope.gridOptions);
        $scope.gridOptionsValidated = angular.copy($scope.gridOptions);
        $scope.gridOptionsActiveLead = angular.copy($scope.gridOptions);
        $scope.gridOptionsClosedLead = angular.copy($scope.gridOptions);


        $scope.gridOptionsActiveLead.columnDefs =[
            { field: 'enquiryDate.date', displayName:'Enquiry Date', cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents display-block">{{row.entity.enquiryDate | carbondate}}</span></div>' },
            { field: 'startDate', displayName:'Start Date', cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents display-block">{{(row.entity.startDate | carbondate) || "NA"}}</span></div>' },
            { field: 'customerName', displayName:'Customer', cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block"  ng-class="{\'customer-app-installed\': !row.entity.appInstalled}"><a ng-href="{{grid.appScope.getLeadLink(row.entity.id)}}">{{row.entity[col.field]}}</a></span></div>' },
            { field: 'service.name', displayName:'Service' },
            { field: 'employeeAssigned.name', displayName:'Employee Assigned' },
            { field: 'primaryVendorAssigned.name', displayName:'CG Assigned' },
            { field: 'submissionMode', displayName:'Source' },
            { field: 'operationStatus.label', displayName:'Status' },
            { field: 'id',displayName:'', enableFiltering: false, enableSorting: false ,  cellTemplate: '<div class="text-center colt{{$index}}" class="text-center"><button class="btn btn-xs btn-success" ng-click="grid.appScope.markAttendance(row.entity)">Mark Attendance</button></div>'}
        ];
        $scope.gridOptionsClosedLead.columnDefs =[
            { field: 'enquiryDate.date', displayName:'Enquiry Date', cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents display-block">{{row.entity.enquiryDate | carbondate}}</span></div>' },
            { field: 'startDate', displayName:'Start Date', cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents display-block">{{(row.entity.startDate | carbondate) || "NA"}}</span></div>' },
            { field: 'customerName', displayName:'Customer', cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block"  ng-class="{\'customer-app-installed\': !row.entity.appInstalled}"><a ng-href="{{grid.appScope.getLeadLink(row.entity.id)}}">{{row.entity[col.field]}}</a></span></div>' },
            { field: 'closedDate', displayName:'Closed Date', cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents display-block">{{(row.entity.closedDate | carbondate) || "NA"}}</span></div>' },

            { field: 'markedBy.name', displayName:'Closed By' },
            { field: 'reason.label', displayName:'Reason' },
            { field: 'service.name', displayName:'Service' },

            { field: 'operationStatus.label', displayName:'Status' }
        ];


        $scope.markAttendance = function(lead){
            var validationStatusObject=null;

            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'markAttendanceLeadModal.html',
                controller: 'MarkLeadAttendanceModalCtrl',
                size: 'md',
                resolve: {
                    LeadObject: function () {
                        return lead;
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                if(angular.isDefined(responseData.userAssigned)){
                    $scope.lead.employeeAssigned = responseData.userAssigned;
                }
                $scope.updateLeadLog();
            }, function () {

            });
        }


        $scope.gridOptions.onRegisterApi = function(gridApi){
            //set gridApi on scope
            $scope.gridApi = gridApi;
            gridApi.selection.on.rowSelectionChanged($scope,function(row){
                var msg = 'row selected ' + row.isSelected;

            });
            gridApi.selection.on.rowSelectionChangedBatch($scope,function(rows){
                var msg = 'rows changed ' + rows.length;

            });
        };
        $scope.deleteSelectedRow = function(leadList){
            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'bulkDeleteLinkConfirmation.html',
                controller: 'BulkDeleteLeadModalCtrl',
                size: 'md',
                resolve: {
                    LeadList: function () {
                        return leadList;
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                if(angular.isDefined(responseData.userAssigned)){
                    $scope.lead.employeeAssigned = responseData.userAssigned;
                }
                $scope.updateLeadLog();
            }, function () {

            });
        }


        $scope.createServiceLead = function(ev,type,serviceId) {
            window.location.href = baseUrl+'/operation/lead/add';
            return;
            if(type=='nursing-care' || type=='assistive-care' || type=='doctor-visit'){
                $mdDialog.show({
                    controller: NewLeadDetailedDialogController,
                    templateUrl: baseUrl+'/static/js/admin/templates/newLeadDetailedDialog.tmpl.html',
                    locals:{
                        ServiceId: serviceId
                    },
                    parent: angular.element(document.body),
                    targetEvent: ev,
                    clickOutsideToClose:true
                })
                    .then(function(answer) {
                        $scope.status = 'You said the information was "' + answer + '".';
                    }, function() {
                        $scope.status = 'You cancelled the dialog.';
                    });
            }
            else if(type=='physiotherapist'){
                $mdDialog.show({
                    controller: NewLeadPsysioDialogController,
                    templateUrl: baseUrl+'/static/js/admin/templates/newLeadPsysioDialog.tmpl.html',
                    locals:{
                        ServiceId: serviceId
                    },
                    parent: angular.element(document.body),
                    targetEvent: ev,
                    clickOutsideToClose:true
                })

                    .then(function(answer) {
                        $scope.status = 'You said the information was "' + answer + '".';
                    }, function() {
                        $scope.status = 'You cancelled the dialog.';
                    });

            }
            else{
                $mdDialog.show({
                    controller: NewLeadOtherDialogController,
                    templateUrl: baseUrl+'/static/js/admin/templates/newLeadOtherDialog.tmpl.html',
                    locals:{
                        ServiceId: serviceId
                    },
                    parent: angular.element(document.body),
                    targetEvent: ev,
                    clickOutsideToClose:true
                })
                    .then(function(answer) {
                        $scope.status = 'You said the information was "' + answer + '".';
                    }, function() {
                        $scope.status = 'You cancelled the dialog.';
                    });
            }
        };
        'use strict';

        $scope.selected = [];
        /*$scope.limitOptions = [5, 10, 15];
         $scope.options = {
         rowSelection: true,
         multiSelect: true,
         autoSelect: true,
         decapitate: false,
         largeEditDialog: false,
         boundaryLinks: false,
         limitSelect: true,
         pageSelect: true
         };
         $scope.query = {
         order: 'name',
         limit: 5,
         page: 1
         };*/




        $scope.leads  = [];

        $scope.filterTodaysLead = function(data){
            var filterLead = [];
            var todayJSDate = new Date();
            var todayMonth = todayJSDate.getMonth()+1;
            var todayDate = todayJSDate.getDate();
            var todayYear = todayJSDate.getFullYear();

            angular.forEach(data,function(value,key){
                if(value.startDate && value.startDate !=null){
                    if(value.startDate.date){
                        var newDate = (value.startDate.date);
                    }else{
                        var newDate = (value.startDate);
                    }
                    //var newDate = (value.startDate.date);
                    var enquiryDateArr = newDate.split(" ");
                    var dateArr = enquiryDateArr[0].split('-');
                    if(parseInt(dateArr[0])==parseInt(todayYear) && parseInt(dateArr[1])==parseInt(todayMonth) && parseInt(dateArr[2])==parseInt(todayDate)){
                        filterLead.push(value);
                    }
                }
            });
            return filterLead;
        }
        $scope.filterPendingLead = function(data){
            var filterLead = [];

            angular.forEach(data,function(value,key){
                if(value.operationStatus.label=="Pending"){
                    filterLead.push(value);
                }
            });
            return filterLead;
        }
        $scope.filterForMeLead = function(data){
            var filterLead = [];

            angular.forEach(data,function(value,key){
                if(angular.isDefined(value.employeeAssigned) && value.employeeAssigned && angular.isDefined(value.employeeAssigned.id)  && value.employeeAssigned.id == parseInt(authUserId)){
                    filterLead.push(value);
                }
            });
            return filterLead;
        }
        $scope.filterValidatedLead = function(data){
            var filterLead = [];

            angular.forEach(data,function(value,key){

                if(angular.isDefined(value.operationStatus) && value.operationStatus && angular.isDefined(value.operationStatus.id)  && value.operationStatus.slug == 'validated'){
                    filterLead.push(value);
                }
            });
            return filterLead;
        }

        $scope.filterActiveLead = function(data){
            var filterLead = [];

            angular.forEach(data,function(value,key){

                if(angular.isDefined(value.operationStatus) && value.operationStatus && angular.isDefined(value.operationStatus.id)  && value.operationStatus.slug == 'started'){
                    filterLead.push(value);
                }
            });
            return filterLead;
        }

        $scope.getLeads = function(){
            OperationService.getLeads(100).then(function(response){
                $scope.gridOptions.data = response.data;
                $scope.gridOptionsToday.data = $scope.filterTodaysLead(response.data);
                $scope.gridOptionsPending.data = $scope.filterPendingLead(response.data);
                $scope.gridOptionsForMe.data = $scope.filterForMeLead(response.data);
                $scope.gridOptionsValidated.data = $scope.filterValidatedLead(response.data);
                $scope.gridOptionsActiveLead.data = $scope.filterActiveLead(response.data);
                OperationService.getLeads().then(function(response){
                    $scope.gridOptions.data = response.data;
                    $scope.gridOptionsToday.data = $scope.filterTodaysLead(response.data);
                    $scope.gridOptionsPending.data = $scope.filterPendingLead(response.data);
                    $scope.gridOptionsForMe.data = $scope.filterForMeLead(response.data);
                    $scope.gridOptionsValidated.data = $scope.filterValidatedLead(response.data);
                    $scope.gridOptionsActiveLead.data = $scope.filterActiveLead(response.data);

                });
            });
        }
        $scope.leadTabSelected = function(){

        }



        $scope.toggleLimitOptions = function () {
            $scope.limitOptions = $scope.limitOptions ? undefined : [5, 10, 15];
        };

        $scope.loadStuff = function () {
            $scope.promise = $timeout(function () {
                // loading
            }, 2000);
        };

        $scope.logItem = function (item) {
            console.log(item.name, 'was selected');
        };

        $scope.logOrder = function (order) {
            console.log('order: ', order);
        };

        $scope.logPagination = function (page, limit) {
            console.log('page: ', page);
            console.log('limit: ', limit);
        };
        $scope.init();
    })
    .controller('adminUsersCtrl', function($scope,$mdDialog) {
        'use strict';

        $scope.selected = [];
        $scope.limitOptions = [5, 10, 15];

        $scope.options = {
            rowSelection: true,
            multiSelect: true,
            autoSelect: true,
            decapitate: false,
            largeEditDialog: false,
            boundaryLinks: false,
            limitSelect: true,
            pageSelect: true
        };

        $scope.query = {
            order: 'name',
            limit: 5,
            page: 1
        };


        $scope.editComment = function (event, dessert) {
            event.stopPropagation(); // in case autoselect is enabled

            var editDialog = {
                modelValue: dessert.comment,
                placeholder: 'Add a comment',
                save: function (input) {
                    if(input.$modelValue === 'Donald Trump') {
                        input.$invalid = true;
                        return $q.reject();
                    }
                    if(input.$modelValue === 'Bernie Sanders') {
                        return dessert.comment = 'FEEL THE BERN!'
                    }
                    dessert.comment = input.$modelValue;
                },
                targetEvent: event,
                title: 'Add a comment',
                validators: {
                    'md-maxlength': 30
                }
            };

            var promise;

            if($scope.options.largeEditDialog) {
                promise = $mdEditDialog.large(editDialog);
            } else {
                promise = $mdEditDialog.small(editDialog);
            }

            promise.then(function (ctrl) {
                var input = ctrl.getInput();

                input.$viewChangeListeners.push(function () {
                    input.$setValidity('test', input.$modelValue !== 'test');
                });
            });
        };

        $scope.toggleLimitOptions = function () {
            $scope.limitOptions = $scope.limitOptions ? undefined : [5, 10, 15];
        };

        $scope.getTypes = function () {
            return ['Candy', 'Ice cream', 'Other', 'Pastry'];
        };

        $scope.loadStuff = function () {
            $scope.promise = $timeout(function () {
                // loading
            }, 2000);
        };

        $scope.logItem = function (item) {
            console.log(item.name, 'was selected');
        };

        $scope.logOrder = function (order) {
            console.log('order: ', order);
        };

        $scope.logPagination = function (page, limit) {
            console.log('page: ', page);
            console.log('limit: ', limit);
        };
        $scope.showTabDialog = function(ev) {
            $mdDialog.show({
                controller: NewUserAddDialogController,
                templateUrl: baseUrl+'/static/js/admin/templates/newUserAddDialog.tmpl.html',
                parent: angular.element(document.body),
                targetEvent: ev,
                clickOutsideToClose:true
            })
                .then(function(answer) {
                    $scope.status = 'You said the information was "' + answer + '".';
                }, function() {
                    $scope.status = 'You cancelled the dialog.';
                });
        };
        $scope.title1 = 'Button';
        $scope.title4 = 'Warn';
        $scope.isDisabled = true;
        $scope.googleUrl = 'http://google.com';
    })
    .controller('adminEmpCtrl', function($scope,$mdDialog) {
        'use strict';

        $scope.selected = [];
        $scope.limitOptions = [5, 10, 15];

        $scope.options = {
            rowSelection: true,
            multiSelect: true,
            autoSelect: true,
            decapitate: false,
            largeEditDialog: false,
            boundaryLinks: false,
            limitSelect: true,
            pageSelect: true
        };

        $scope.query = {
            order: 'name',
            limit: 5,
            page: 1
        };



        $scope.editComment = function (event, dessert) {
            event.stopPropagation(); // in case autoselect is enabled

            var editDialog = {
                modelValue: dessert.comment,
                placeholder: 'Add a comment',
                save: function (input) {
                    if(input.$modelValue === 'Donald Trump') {
                        input.$invalid = true;
                        return $q.reject();
                    }
                    if(input.$modelValue === 'Bernie Sanders') {
                        return dessert.comment = 'FEEL THE BERN!'
                    }
                    dessert.comment = input.$modelValue;
                },
                targetEvent: event,
                title: 'Add a comment',
                validators: {
                    'md-maxlength': 30
                }
            };

            var promise;

            if($scope.options.largeEditDialog) {
                promise = $mdEditDialog.large(editDialog);
            } else {
                promise = $mdEditDialog.small(editDialog);
            }

            promise.then(function (ctrl) {
                var input = ctrl.getInput();

                input.$viewChangeListeners.push(function () {
                    input.$setValidity('test', input.$modelValue !== 'test');
                });
            });
        };

        $scope.toggleLimitOptions = function () {
            $scope.limitOptions = $scope.limitOptions ? undefined : [5, 10, 15];
        };

        $scope.getTypes = function () {
            return ['Candy', 'Ice cream', 'Other', 'Pastry'];
        };

        $scope.loadStuff = function () {
            $scope.promise = $timeout(function () {
                // loading
            }, 2000);
        };

        $scope.logItem = function (item) {
            console.log(item.name, 'was selected');
        };

        $scope.logOrder = function (order) {
            console.log('order: ', order);
        };

        $scope.logPagination = function (page, limit) {
            console.log('page: ', page);
            console.log('limit: ', limit);
        };
        $scope.showTabDialog = function(ev) {
            $mdDialog.show({
                controller: NewEmpAddDialogController,
                templateUrl: baseUrl+'/static/js/admin/templates/newEmpAddDialog.tmpl.html',
                parent: angular.element(document.body),
                targetEvent: ev,
                clickOutsideToClose:true
            })
                .then(function(answer) {
                    $scope.status = 'You said the information was "' + answer + '".';
                }, function() {
                    $scope.status = 'You cancelled the dialog.';
                });
        };
        $scope.title1 = 'Button';
        $scope.title4 = 'Warn';
        $scope.isDisabled = true;
        $scope.googleUrl = 'http://google.com';
    })
    .filter('propsFilter', function() {
        return function(items, props) {
            var out = [];

            if (angular.isArray(items)) {
                var keys = Object.keys(props);

                items.forEach(function(item) {
                    var itemMatches = false;

                    for (var i = 0; i < keys.length; i++) {
                        var prop = keys[i];
                        var text = props[prop].toLowerCase();
                        if (item[prop].toString().toLowerCase().indexOf(text) !== -1) {
                            itemMatches = true;
                            break;
                        }
                    }

                    if (itemMatches) {
                        out.push(item);
                    }
                });
            } else {
                // Let the output be the input untouched
                out = items;
            }

            return out;

        };
    })
    .controller('operationLeadValidateCtrl', ["$scope","$uibModal","$http","$timeout","OperationService",function($scope,$uibModal,$http,$timeout,OperationService) {
        $scope.mappedData = {};
        $scope.statusesGrouped = [];
        $scope.rejectionStatus = {};
        $scope.postponeStatus = {};
        $scope.followUpStatus = {};
        $scope.validatedStatus = {};
        $scope.hstep = 1;
        $scope.mstep = 1;
        $scope.ismeridian = true;


        $scope.showValidationPitch = function(){
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'validationPitch.html',
                controller: 'ValidationPitchModalCtrl',
                size: 'md',
                resolve: {
                    LeadObject: function () {
                        return $scope.lead;
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                if(angular.isDefined(responseData.userAssigned)){
                    $scope.lead.employeeAssigned = responseData.userAssigned;
                }
                $scope.updateLeadLog();
            }, function () {

            });
        }
        $scope.rejectLead = function(){
            var validationStatusObject=null;

            console.log(validationStatusObject);
            console.log('lead rejection');
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'rejectLeadModal.html',
                controller: 'RejectLeadModalCtrl',
                size: 'md',
                resolve: {
                    LeadObject: function () {
                        return $scope.lead;
                    },
                    LeadStatus: function(){
                        return $scope.rejectionStatus;
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                if(angular.isDefined(responseData.userAssigned)){
                    $scope.lead.employeeAssigned = responseData.userAssigned;
                }
                $scope.updateLeadLog();
            }, function () {

            });
        }
        $scope.followUpLead = function(){
            var validationStatusObject=null;

            console.log(validationStatusObject);
            console.log('lead rejection');
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'followupLeadModal.html',
                controller: 'FollowUpLeadModalCtrl',
                size: 'md',
                resolve: {
                    LeadObject: function () {
                        return $scope.lead;
                    },
                    LeadStatus: function(){
                        return $scope.followUpStatus;
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                if(angular.isDefined(responseData.userAssigned)){
                    $scope.lead.employeeAssigned = responseData.userAssigned;
                }
                $scope.updateLeadLog();
            }, function () {

            });
        }
        $scope.postponeLead = function(){
            var validationStatusObject=null;

            console.log(validationStatusObject);
            console.log('lead rejection');
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'postponeLeadModal.html',
                controller: 'PostponeLeadModalCtrl',
                size: 'md',
                resolve: {
                    LeadObject: function () {
                        return $scope.lead;
                    },
                    LeadStatus: function(){
                        return $scope.postponeStatus;
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                if(angular.isDefined(responseData.userAssigned)){
                    $scope.lead.employeeAssigned = responseData.userAssigned;
                }
                $scope.updateLeadLog();
            }, function () {

            });
        }
        /*window.onbeforeunload = function (e) {
            var confirmationMessage = 'It looks like you have been editing something. '
                + 'If you leave before saving, your changes will be lost.';

            (e || window.event).returnValue = confirmationMessage; //Gecko + IE
            return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
        };*/
        $scope.message = {
            body:'',
            show:false,
            type: 'warning',
            timeout:null
        };
        $scope.leadId = leadId;
        if(angular.isUndefined(leadId) || leadId==null ){
            window.location.href=baseUrl;
        }

        $scope.lead = {};
        $scope.validationTasks = [];
        $scope.leadDataValidation = {
            "customerName": {
                "valid": true,
                "message": "Please provide first name"
            }, "customerLastName": {
                "valid": true,
                "message": "Please provide last name"
            },
            "customerPhone": {
                "valid": true,
                "message": "Please provide phone number"
            },
            "serviceRequired": {
                "valid": true,
                "message": "Service requirement is required"
            },
            "localityRequired": {
                "valid": true,
                "message": "Costomer locality is required"
            }
        }

        $scope.validateLeadBeforeUpdate = function(){
            var valid = true;
            if($scope.leadData.enquiry.name==''){
                $scope.leadDataValidation.customerName.valid=false;
                valid=false;
            }else{
                $scope.leadDataValidation.customerName.valid=true;
            }
            if($scope.leadData.enquiry.lastName==''){
                $scope.leadDataValidation.customerLastName.valid=false;
                valid=false;
            }else{
                $scope.leadDataValidation.customerLastName.valid=true;
            }
            if($scope.leadData.enquiry.phone==''){
                $scope.leadDataValidation.customerPhone.valid=false;
                valid=false;
            }else{
                $scope.leadDataValidation.customerPhone.valid=true;
            }
            if(angular.isUndefined($scope.leadData.service.id)){
                $scope.leadDataValidation.serviceRequired.valid=false;
                valid=false;
            }else{
                $scope.leadDataValidation.serviceRequired.valid=true;
            }
            if(angular.isUndefined($scope.leadData.locality) || angular.isUndefined($scope.leadData.locality.id)){
                $scope.leadDataValidation.localityRequired.valid=false;
                valid=false;
            }else{
                $scope.leadDataValidation.localityRequired.valid=true;
            }
            return valid;
        }
        $scope.validateLead = function(){

        }
        $scope.updateLeadAndValidate = function(){
            var valid = $scope.validateLeadBeforeUpdate();

            if(!valid){
                return;
            }
            $scope.leadData.validationInfo = $scope.validationData;
            var validationStatusObject=$scope.validatedStatus;

            console.log(validationStatusObject)
            OperationService.updateLeadDetail($scope.leadId,$scope.leadData).then(function(response){
                if(response.status && response.data.id){
                    OperationService.updateLeadStatus({
                        leadId: $scope.leadId,
                        status: validationStatusObject.id
                    }).then(function(response){
                        window.onbeforeunload = function(){

                        }
                        window.location.href = baseUrl+"/lead/"+$scope.leadId;
                    });
                }else{
                    // TODO: error
                }
            });


        }
        $scope.updateLeadAndContinue = function(){
            var valid = $scope.validateLeadBeforeUpdate();

            if(!valid){
                return;
            }
            $scope.leadData.validationInfo = $scope.validationData;
            OperationService.updateLeadDetail($scope.leadId,$scope.leadData).then(function(response){
                if(response.status && response.data.id){

                    window.location.href = baseUrl+"/lead/"+response.data.id;
                }else{
                    // TODO: error
                }
            });
        }
        $scope.updateLead = function(){
            var valid = $scope.validateLeadBeforeUpdate();

            if(!valid){
                return;
            }
            $scope.leadData.validationInfo = $scope.validationData;
            OperationService.updateLeadDetail($scope.leadId,$scope.leadData).then(function(response){
                if(response.status && response.data.id){
                    //window.location.href = baseUrl+"/lead/"+response.data.id;
                    $scope.message.body = response.message;
                    $scope.message.type = response.type;
                    $scope.message.show = true;
                    $scope.message.timeout = $timeout(function(){
                        $scope.message.show = false;
                    },3000);
                }else{
                    // TODO: error
                }
            });
        }
        $scope.leadData = {
            service: {},
            enquiry: {
                name: '',
                lastName:'',
                email: '',
                phone: '',
                city: [],
                references: []
            },
            reference:{},
            patientInfo: {
                name: '',
                gender:'',
                age: '',
                weight: '',
                ailments: [],
                equipmentSupport: false,
                equipments: [],
                mobility: '',
                illness:'',
                physicalCondition:'',
                morningWakeupTime:'',
                morningBreakfastTime:'',
                medicine:'',
                lunchTime:'',
                dinnerTime:'',
                walkingTime:'',
                walkingLocation:'',
                doctorName:'',
                hospitalName:'',
                otherAilment:''
            },
            payment:{
                mode:{},
                type:{},
                period:{},
                price:'',
                priceUnit:{}
            },
            address: '',
            location: '',
            landmark: '',
            shift: '',
            tasks:[],
            taskOther:'',
            request: {
                gender:false,
                genderRequired:'',
                religion:false,
                religionRequired: '',
                language: false,
                languageRequired: '',
                age:false,
                ageRequired:'',
                food:false,
                foodRequired:'',
                startDate: new Date(),
                duration: 1,
                startTime: new Date('h:i:sa'),
                locality: '',
                address: '',
                remark:''

            },
            enquiryId: null
        };
        $scope.convertToFormObject = function(lead){
            // customer info
            $scope.leadData.enquiry.name = $scope.lead.customerName;
            $scope.leadData.enquiry.lastName = $scope.lead.customerLastName;
            $scope.leadData.enquiry.email = $scope.lead.email;
            $scope.leadData.enquiry.phone = $scope.lead.phone;// TODO: phone number
            // patient info
            if($scope.lead.patient){
                $scope.leadData.patientInfo.name = $scope.lead.patient.name;
                $scope.leadData.patientInfo.age = $scope.lead.patient.age;
                $scope.leadData.patientInfo.weight = $scope.lead.patient.weight;
                $scope.leadData.patientInfo.gender = $scope.lead.patient.gender;
                $scope.leadData.patientInfo.ailments = $scope.lead.patient.ailments;
                $scope.leadData.patientInfo.equipmentSupport = ($scope.lead.patient.isOnEquipmentSupport==1)?true:false;
                $scope.leadData.patientInfo.otherAilment = ($scope.lead.patient.otherAilment);
                $scope.leadData.patientInfo.equipments = $scope.lead.patient.equipments;
                $scope.leadData.patientInfo.mobility = $scope.lead.patient.mobility;
                $scope.leadData.shift =  $scope.lead.patient.shiftRequired;
                $scope.leadData.tasks =  $scope.lead.patient.tasks;
            }else{
                $scope.leadData.patientInfo.name = '';
                $scope.leadData.patientInfo.age = '',
                $scope.leadData.patientInfo.weight = '',
                $scope.leadData.patientInfo.gender = {};
                $scope.leadData.patientInfo.ailments = [];
                $scope.leadData.patientInfo.equipmentSupport = null;
                $scope.leadData.patientInfo.otherAilment = '',
                $scope.leadData.patientInfo.equipments = [];
                $scope.leadData.patientInfo.mobility = {};
                $scope.leadData.shift =  {};
                $scope.leadData.tasks =  []
            }

            // TODO: Mobility
            $scope.leadData.request.remark = $scope.lead.remark;
            // task detail

            $scope.leadData.taskOther = $scope.lead.taskOther;
            $scope.leadData.startDate = new Date($scope.lead.startDate);
            $scope.leadData.request.startDate = new Date($scope.lead.startDate);
            $scope.leadData.payment = {
                mode:$scope.lead.paymentMode,
                type:$scope.lead.paymentType,
                period: $scope.lead.paymentPeriod,
                price: $scope.lead.price,
                priceUnit: $scope.lead.priceUnit
            };

            $scope.leadData.service = $scope.lead.service;
            $scope.leadData.reference = $scope.lead.leadReference;
            $scope.leadData.address = $scope.lead.address;
            if(angular.isDefined($scope.lead.locality) && $scope.lead.locality != null && angular.isDefined($scope.lead.locality.json)){
                $scope.leadData.locality = $scope.lead.locality.json;
            }else{
                $scope.leadData.locality = {};
            }

            $scope.leadData.landmark = $scope.lead.landmark;

            // patient special informatio
            if($scope.lead.patient){
                $scope.leadData.patientInfo.medicine = $scope.lead.patient.medicine;
                $scope.leadData.patientInfo.morningWakeupTime = new Date("2016-01-01 "+$scope.lead.patient.morningWakeUpTime);
                $scope.leadData.patientInfo.morningBreakfastTime = new Date("2016-01-01 "+$scope.lead.patient.breakfastTime);
                $scope.leadData.patientInfo.lunchTime = new Date("2016-01-01 "+$scope.lead.patient.lunchTime);
                $scope.leadData.patientInfo.dinnerTime = new Date("2016-01-01 "+$scope.lead.patient.dinnerTime);
                $scope.leadData.patientInfo.walkingTime = new Date("2016-01-01 "+$scope.lead.patient.walkTiming);
                $scope.leadData.patientInfo.walkingLocation = $scope.lead.patient.walkLocation;
                $scope.leadData.request.religion = ($scope.lead.patient.religionPreference==1)?true:false;
                $scope.leadData.request.religionRequired = $scope.lead.patient.religionPreferred;
                $scope.leadData.request.gender = ($scope.lead.patient.genderPreference==1)?true:false;
                $scope.leadData.request.genderRequired = $scope.lead.patient.genderPreferred;
                $scope.leadData.request.doctorName = $scope.lead.patient.doctorName;
                $scope.leadData.request.hospitalName = $scope.lead.patient.hospitalName;
                $scope.leadData.request.language = ($scope.lead.patient.languagePreference==1)?true:false;
                $scope.leadData.request.languageRequired = $scope.lead.patient.languagePreferred;
                $scope.leadData.request.age = ($scope.lead.patient.agePreferece==1)?true:false;
                $scope.leadData.request.ageRequired = $scope.lead.patient.agePreferred;
                $scope.leadData.request.food = ($scope.lead.patient.foodPreference==1)?true:false;
                $scope.leadData.request.foodRequired = $scope.lead.patient.foodPreferred;
            }else{
                $scope.leadData.patientInfo.medicine = '';
                $scope.leadData.patientInfo.morningWakeupTime = new Date();
                $scope.leadData.patientInfo.morningBreakfastTime = new Date();
                $scope.leadData.patientInfo.lunchTime = new Date();
                $scope.leadData.patientInfo.dinnerTime = new Date();
                $scope.leadData.patientInfo.walkingTime = new Date();
                $scope.leadData.patientInfo.walkingLocation = '';
                $scope.leadData.request.religion = false;
                $scope.leadData.request.religionRequired = {};
                $scope.leadData.request.gender = false;
                $scope.leadData.request.genderRequired = {};
                $scope.leadData.request.doctorName = {};
                $scope.leadData.request.hospitalName = {};
                $scope.leadData.request.language = false;
                $scope.leadData.request.languageRequired = {};
                $scope.leadData.request.age =false;
                $scope.leadData.request.ageRequired = {};
                $scope.leadData.request.food = false;
                $scope.leadData.request.foodRequired = {};
            }

           
        }

        $scope.init = function(){
            OperationService.getLeadDetail($scope.leadId).then(function(response){
                $scope.lead = response.data;
                $scope.convertToFormObject($scope.lead);
                if($scope.lead.patient){
                    OperationService.getPatientValidationData($scope.lead.patient.id).then(function(response){
                        $scope.validationData = response.data;
                    });
                }else{
                    OperationService.getPatientValidationData(0).then(function(response){
                        $scope.validationData = response.data;
                    });
                }
                //$scope.lead.patientInfo.morningWakeupTimeFormatted = new Date($scope.lead.patientInfo.morningWakeupTime);
            });
            OperationService.getOperationalStausesGrouped().then(function(response){
                $scope.statusesGrouped = response.data;
                angular.forEach($scope.statusesGrouped, function(value){
                    angular.forEach(value.statuses, function(valueSingle){
                        if(valueSingle.slug == 'rejected'){
                            $scope.rejectionStatus=valueSingle;
                        }else if(valueSingle.slug == 'postponed'){
                            $scope.postponeStatus=valueSingle;
                        }else if(valueSingle.slug == 'validated'){
                            $scope.validatedStatus=valueSingle;
                        }else if(valueSingle.slug == 'follow-up'){
                            $scope.followUpStatus=valueSingle;
                        }
                    });
                });
            });
            OperationService.getTaskListForValidation().then(function(response){
                $scope.validationTasks = response.data;
            });
            OperationService.getCreateLeadDataMapped().then(function(response){
                $scope.mappedData = response.data;
            });
        }
        $scope.init();
    }])
    .controller('operationLeadCreateTaskModalCtrl', ["$scope","$uibModalInstance","$http","OperationService","ValidationData",function($scope,$uibModalInstance,$http,OperationService,ValidationData) {
        $scope.validationData = ValidationData;
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
        $scope.updateTask = function(){
            $uibModalInstance.close();
        }

    }])
    .controller('uploadPrescriptionModalCtrl', ["$scope","$uibModalInstance","$http", 'Upload', '$timeout',"OperationService","ValidationData",function($scope,$uibModalInstance,$http, Upload, $timeout,OperationService,ValidationData) {
        $scope.validationData = ValidationData;
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
        $scope.updateTask = function(){
            $uibModalInstance.close();
        }
        $scope.prescriptionUploadModel = {
            url: 'url to post image',
            data: 'additional data to send with image'
        };
        $scope.uploadPic = function(file) {
            file.upload = Upload.upload({
                url: baseUrl+'/api/v1/operation/patient/prescription/upload',
                data: {file: file}
            });

            file.upload.then(function (response) {
                $timeout(function () {
                    file.result = response.data;
                });
            }, function (response) {
                if (response.status > 0)
                    $scope.errorMsg = response.status + ': ' + response.data;
            }, function (evt) {
                // Math.min is to fix IE which reports 200% sometimes
                file.progress = Math.min(100, parseInt(100.0 * evt.loaded / evt.total));
            });
        }


    }])
    .controller('operationLeadCreateCtrl', ["$scope","$rootScope","$uibModal","$http","OperationService",function($scope,$rootScope,$uibModal,$http,OperationService) {
        $scope.mappedData = {};

        $scope.validationData = {};
        $scope.selectTask = function(){
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'leadTaskModalTemplate.html',
                controller: 'operationLeadCreateTaskModalCtrl',
                size: 'md',
                resolve: {
                    ValidationData: function () {
                        return $scope.leadData.validationData;
                    }
                }
            });
            modalInstance.result.then(function (responseData) {
                if(responseData.status){
                    window.location.href = baseUrl+"/lead/"+$scope.lead.id;
                }
            }, function () {

            });
        }

        $scope.range = function(min, max, step) {
            step = step || 1;
            var input = [];
            for (var i = min; i <= max; i += step) {
                input.push(i);
            }
            return input;
        };

        $scope.prescriptionUploadModel = {
            open: false,
            url: 'operation/patient/prescription/upload'
        }
        $scope.uploadPrescription = function(){
            $scope.prescriptionUploadModel.open=true;
            return;
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'uploadPrescriptionModalTemplate.html',
                controller: 'uploadPrescriptionModalCtrl',
                size: 'md',
                resolve: {
                    ValidationData: function () {
                        return $scope.leadData.validationData;
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                if(responseData.status){
                    window.location.href = baseUrl+"/lead/"+$scope.lead.id;
                }
            }, function () {

            });
        }
//        window.addEventListener("beforeunload", );
        /*window.onbeforeunload = function (e) {
            var confirmationMessage = 'It looks like you have been editing something. '
                + 'If you leave before saving, your changes will be lost.';

            (e || window.event).returnValue = confirmationMessage; //Gecko + IE
            return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
        }*/
        $scope.init = function(){
            OperationService.getCreateLeadDataMapped().then(function(response){
                $scope.mappedData = response.data;

            });
            OperationService.getPatientValidationData(0).then(function(response){
                $scope.leadData.validationData = response.data;
            });

        }
        $scope.addLead = function(){
            var valid = true;
            if($scope.leadData.enquiry.name==''){
                $scope.leadDataValidation.customerName.valid=false;
                valid=false;
            }else{
                $scope.leadDataValidation.customerName.valid=true;
            }
           if($scope.leadData.enquiry.lastName==''){
                $scope.leadDataValidation.customerLastName.valid=false;
                valid=false;
            }else{
                $scope.leadDataValidation.customerLastName.valid=true;
            }
            if($scope.leadData.enquiry.email==''){
                $scope.leadDataValidation.customerEmail.valid=false;
                valid=false;
            }else{
                $scope.leadDataValidation.customerEmail.valid=true;
            }
            if($scope.leadData.enquiry.phone==''){
                $scope.leadDataValidation.customerPhone.valid=false;
                valid=false;
            }else{
                $scope.leadDataValidation.customerPhone.valid=true;
            }
            if(angular.isUndefined($scope.leadData.service.id)){
                $scope.leadDataValidation.serviceRequired.valid=false;
                valid=false;
            }else{
                $scope.leadDataValidation.serviceRequired.valid=true;
            }
            if(angular.isUndefined($scope.leadData.locality) || angular.isUndefined($scope.leadData.locality.id)){
                $scope.leadDataValidation.localityRequired.valid=false;
                valid=false;
            }else{
                $scope.leadDataValidation.localityRequired.valid=true;
            }
            if(angular.isUndefined($scope.leadData.landmark)){
                $scope.leadDataValidation.landmarkRequired.valid=false;
                valid=false;
            }else{
                $scope.leadDataValidation.landmarkRequired.valid=true;
            }
            if(!valid){
                return;
            }

            $rootScope.loadingMask = true;
            OperationService.submitAdminLead($scope.leadData).then(function(response){
                window.onbeforeunload = function(){

                }
                $rootScope.loadingMask = false;
                if(response.status && response.data.id){
                       window.location.href = baseUrl+"/lead/"+response.data.id;
                }else{
                    // TODO: error
                }
            });
        }
        $scope.leadDataValidation = {
            "customerName": {
                "valid": true,
                "message": "Provide first name"
            },
            "customerLastName": {
                "valid": true,
                "message": "Provide last name"
            },
            "customerEmail": {
                "valid": true,
                "message": "Provide Email"
            },
            "customerPhone": {
                "valid": true,
                "message": "Please provide phone number"
            },
            "serviceRequired": {
                "valid": true,
                "message": "Service requirement is required"
            },
            "localityRequired": {
                "valid": true,
                "message": "Costomer locality is required"
            },
            "landmarkRequired": {
                "valid": true,
                "message": "Costomer landmark is required"
            }
        }
        $scope.hstep = 1;
        $scope.mstep = 1;
        $scope.ismeridian = true;
        $scope.uploadedPrescription = {};
        $scope.leadData = {
            leadSource: {
                id: 2
            },
            service: {},
            reference: {
                children:[]
            },
            enquiry: {
                name: '',
                lastName:'',
                email: '',
                phone: '',
                city: [],
                references: {
                    children:[]
                }
            },
            patientInfo: {
                name: '',
                gender:'',
                age: '',
                weight: '',
                ailment: [],
                otherAilment:'',
                equipmentSupport: false,
                equipments: [],
                mobility: '',
                illness:'',
                physicalCondition:'',
                morningWakeupTime:'',
                morningBreakfastTime:'',
                medicine:'',
                lunchTime:'',
                dinnerTime:'',
                walkingTime:'',
                walkingLocation:'',
                prescriptionList : [],
                doctorName: '',
                hospitalName: ''

            },
            payment:{
                mode:{},
                type:{},
                period:{},
                price:'',
                priceunit:{}
            },
            location: '',
            shift: '',
            tasks:[],
            taskOther:'',
            request: {
                gender:false,
                genderRequired:'',
                religion:false,
                religionRequired: '',
                language: false,
                languageRequired: '',
                age:false,
                ageRequired:'',
                food:false,
                foodRequired:'',
                startDate: new Date(),
                startTime: new Date(),
                duration: 1,
                locality: '',
                address: '',
                remark:''
            },
            physio: {
                condition: {},
                painSeverity:'',
                rangeOfMotion:'',
                presentCondition:'',
                modality:'',
                noOfSessions:''
            },
            enquiryId: null
        };
        $scope.init();
        $scope.$watch('uploadedPrescription.id',function(newValue,oldValue){
            if(newValue != oldValue && $scope.uploadedPrescription && angular.isDefined($scope.uploadedPrescription.id)){
                $scope.leadData.patientInfo.prescriptionList.push($scope.uploadedPrescription);

            }
        });
    }])
    .filter('filterRows', function() {
        // visit http://plnkr.co/edit/cTxLLI84kXy9HR2JekbX?p=preview
        return function(data, grid, query) {

            matches = [];

            //no filter defined so bail
            if (query === undefined|| query==='') {
                return data;
            }

            query = query.toLowerCase();

            //loop through data items and visible fields searching for match
            for (var i = 0; i < data.length; i++) {
                for (var j = 0; j < grid.columnDefs.length; j++) {
                    var dataItem = data[i];
                    var fieldName = grid.columnDefs[j]['field'];
                    //as soon as search term is found, add to match and move to next dataItem
                    if (dataItem[fieldName].toString().toLowerCase().indexOf(query)>-1) {
                        matches.push(dataItem);
                        break;
                    }
                }
            }
            return matches;
        }
    })
    .filter('todaysLead', function() {
        // visit http://plnkr.co/edit/cTxLLI84kXy9HR2JekbX?p=preview
        return function(data, grid, query) {

            matches = [];




            //loop through data items and visible fields searching for match
            for (var i = 0; i < data.length; i++) {
                var dataItem = data[i];
                console.log(dataItem);
                matches.push(dataItem);
            }
            return matches;
        }
    })
    .controller('adminComplaintsCtrl', ["$scope","$mdDialog","$http","$filter","$uibModal","OperationService",function($scope,$mdDialog,$http,$filter,$uibModal,OperationService) {

        $scope.baseUrl = "mohit";
        $scope.getLeadLink = function(id){
            return baseUrl+"/lead/"+id;
        }
        $scope.getEmployeeLink = function(id){
            return baseUrl+"/admin/employee/"+id;
        }
        $scope.getVendorLink = function(id){
            return baseUrl+"/vendor/"+id;
        }

        $scope.getComplaintLink = function(id,userType){
            return baseUrl+"/complaint/"+id+"/"+userType;
        }

        $scope.gridOptionsComplaintsUser = {
            enableSorting: true,
            enableFiltering: true,
            enableRowSelection: true,
            enableSelectAll: true,
            columnDefs: [
                { field: 'id', displayName:'ID' , width: 50},
                { field: 'user_name', displayName:'Customer', cellTemplate:'<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block"><a ng-href="{{grid.appScope.getLeadLink(row.entity.lead_id)}}" target="_blank">{{row.entity[col.field]}}</a></span></div>' },
                { field: 'cg', displayName:'Alloted CG', cellTemplate:'<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block"><a ng-href="{{grid.appScope.getVendorLink(row.entity.cg.id)}}" target="_blank">{{row.entity.cg.name}}</a></span></div>' },
                { field: 'category_name', displayName:'Complaint Category' },
                { field: 'resolution_group', displayName:'Resolution Category' },
                { field: 'id', displayName:'Logged By', cellTemplate:'<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block"><span ng-show="row.entity.employee==null">App</span><span ng-show="row.entity.employee!==null"><a ng-href="{{grid.appScope.getEmployeeLink(row.entity.employee.id)}}" target="_blank">{{row.entity.employee.name}}</a></span></span></div>' },
                { field: 'assignedEmployee.name', displayName:'Assigned Employee' },
                { field: 'created_at', displayName:'Complaint Date' },
                /*{
                    field: 'label',
                    //editType: 'dropdown',
                    //enableCellEdit: true,
                    //editableCellTemplate: '<div><form name="inputForm"><select ng-class="\'colt\' + col.uid" ui-grid-edit-dropdown ng-model="MODEL_COL_FIELD" ng-options="field[editDropdownIdLabel] as field[editDropdownValueLabel] CUSTOM_FILTERS for field in editDropdownOptionsArray"></select></form></div>',
                    //editDropdownOptionsArray: $scope.complaintStatuses,
                    //editDropdownIdLabel: 'id',
                    //editDropdownValueLabel: 'label',
                    displayName:'Status',
                    //cellTemplate:'<select  ng-options="id as complaintStatusItem in complaintStatuses">'
                },*/
                { field: 'label', displayName:'Status' },
                { field: 'id',displayName:'Action', enableSorting: false ,  cellTemplate: '<div class="text-center colt{{$index}}" class="text-center"><a class="btn btn-xs" ng-href="{{grid.appScope.getComplaintLink(row.entity.id,3)}}">View</a></div>'}
            ],
            onRegisterApi: function( gridApi ) {
                $scope.grid1Api = gridApi;
            }
        };

        $scope.gridOptionsComplaintsCg = {
            enableSorting: true,
            enableFiltering: true,
            enableRowSelection: true,
            enableSelectAll: true,
            columnDefs: [
                { field: 'id', displayName:'ID' , width: 50},
                { field: 'user_name', displayName:'CG Name', cellTemplate:'<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block"><a ng-href="{{grid.appScope.getLeadLink(row.entity.lead_id)}}" target="_blank">{{row.entity[col.field]}}</a></span></div>' },
                { field: 'customer_name', displayName:'Alloted Customer'},
                { field: 'category_name', displayName:'Complaint Category' },
                { field: 'id', displayName:'Logged By', cellTemplate:'<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block"><span ng-show="row.entity.employee==null">App</span><span ng-show="row.entity.employee!==null"><a ng-href="{{grid.appScope.getEmployeeLink(row.entity.employee.id)}}" target="_blank">{{row.entity.employee.name}}</a></span></span></div>' },
                { field: 'assignedEmployee.name', displayName:'Assigned Employee' },
                { field: 'created_at', displayName:'Complaint Date' },
                { field: 'label', displayName:'Status' },
                { field: 'id',displayName:'Action', enableSorting: false ,  cellTemplate: '<div class="text-center colt{{$index}}" class="text-center"><a class="btn btn-xs" ng-href="{{grid.appScope.getComplaintLink(row.entity.id,2)}}">View</a></div>'}
            ],
            onRegisterApi: function( gridApi ) {
                $scope.grid2Api = gridApi;
            }
        };

        $scope.tabActive = 'userComplaintsTab';
        $scope.openTab = function(param){
            $scope.tabActive=param;
            if(param=='userComplaintsTab'){
                OperationService.getUserComplaints().then(function(response){
                    $scope.gridOptionsComplaintsUser.data = response.data;
                });
            }else if(param=='cgComplaintsTab'){
                OperationService.getCgComplaints().then(function(response){
                    $scope.gridOptionsComplaintsCg.data = response.data;
                });
            }
        }

        $scope.init = function(){
            if(window.location.href.indexOf("complaints/add") == -1) {
                $scope.openTab('userComplaintsTab');
            }
        };
        $scope.init();
     
        $scope.userTypes = [
            {"id":3 ,"label": "User"},
            {"id":2 ,"label": "Caregiver"},
        ];

        $scope.createComplaint = function(ev,type,serviceId) {
            window.location.href = baseUrl+'/complaints/add';
            return;
        };

        $scope.complaintCategories = [];
        $key = 'user';
        $scope.changeUserType = function($item){
            if($item.label === 'User'){
                $key = 'user';
            } else if($item.label === 'Caregiver'){
                $key = 'cg';
            }

            OperationService.complaintCategoriesByType($key).then(function(response){
                $scope.complaintCategories = response.data;
            });
        };

        $scope.searchuser = function (query) {
            OperationService.getUsersList(query).then(function(response){
                $scope.usersList = response.data;
            });
        };

        $scope.getUserLeads = function($item){
            OperationService.getUserLeads($item).then(function(response){
                $scope.leadsList = response.data;
            });
        };

        $scope.getCurrentVendor = function($item){
            OperationService.getCurrentVendor($item).then(function(response){
                if($scope.complaintData.userType != 2){
                    if(response.hasOwnProperty('id')){
                        $scope.vendorInfo = response;
                        $scope.vendorInfoFalse = false;
                    } else {
                        $scope.vendorInfoFalse = true;
                        $scope.vendorInfo = false;
                    }
                }
            });
        };

        $scope.getComplaintSubCategories = function($item){
            OperationService.getComplaintSubCategories($item, $key).then(function(response){
                if(response.data.length > 0){
                    $scope.complaintSubCategories = response.data;
                } else {
                    $scope.complaintSubCategories = [];
                }
            });
        };

        $scope.getComplaintSubChildCategories = function($item){
            OperationService.getComplaintSubCategories($item, $key).then(function(response){
                if(response.data.length > 0){
                    $scope.complaintSubChildCategories = response.data;
                } else {
                    $scope.complaintSubChildCategories = [];
                }
            });
        };


        $scope.complaintData = {
            "category" : "",
            "userId" : "",
            "details" : "",
            "lead_id" : ""
        };
        $scope.addComplaint = function(){
            // if(!$scope.basicDetails.$valid){
            //     return;
            // }

            if($scope.complaintSubCategories.length > 0){
                $scope.complaintData.category = $scope.complaintData.complaintSubCategories;
            }
            if($scope.complaintSubChildCategories.length > 0){
                $scope.complaintData.category = $scope.complaintData.complaintSubChildCategories;
            }
            OperationService.submitComplaint($scope.complaintData).then(function(response){
                if(response.type == 'success'){
                    window.location.href = baseUrl+'/admin/complaints';
                }
            });
        };

        'use strict';

        $scope.init = function(){
            
        }

        $scope.init();
    }])
    .controller('adminComplaintCtrl', ["$scope","$mdDialog","$http","$filter","$uibModal","OperationService",function($scope,$mdDialog,$http,$filter,$uibModal,OperationService) {
        $scope.complaintId = complaintId;
        $scope.userType = userType;

        $scope.baseUrl = "mohit";
        $scope.getLeadLink = function(id){
            return baseUrl+"/lead/"+id;
        }
        $scope.getVendorLink = function(id){
            return baseUrl+"/vendor/"+id;
        }
        $scope.changeStatusData = {
            "complaintId" : $scope.complaintId,
            "complaintStatus" : ""
        };

        $scope.getStatusLabelById = function(id){
            var data = $scope.complaintStatuses;
            for(var i = 0; i < data.length; i++) {
                if(data[i].id == id) {
                    return data[i].label;
                }
            }
        };

        $scope.getComplaintStatuses = function(){
            OperationService.getComplaintStatuses().then(function(response){
                $scope.complaintStatuses = response.data;
                OperationService.getComplaintDetailed($scope.complaintId,$scope.userType).then(function(response){
                    $scope.complaintData = response.data;
                    $scope.changeStatusData.complaintStatus = response.data.complaint_status_id;
                });
            });
        };

        $scope.init = function(){
            $scope.getComplaintStatuses();
        };
        $scope.init();

        $scope.changeComplaintStatus = function(){
            OperationService.changeComplaintStatus($scope.changeStatusData).then(function(response){
                if(response.type == 'success'){
                    $scope.complaintData.status = $scope.getStatusLabelById($scope.changeStatusData.complaintStatus);
                }
            });
        };

        $scope.resolutionCGTraining = function(complaintData){
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'resolutionCGTrainingModal.html',
                controller: 'resolutionComplaintCtrl',
                size: 'md',
                resolve: {
                    complaintObject: function () {
                        return complaintData;
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                //if(angular.isDefined(responseData.userAssigned)){
                //    $scope.lead.employeeAssigned = responseData.userAssigned;
                //}
                //$scope.updateLeadLog();
            }, function () {

            });
        };

        $scope.resolutionCGCounselling = function(){
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'resolutionCGCounsellingModal.html',
                controller: 'resolutionComplaintCtrl',
                size: 'md',
                resolve: {
                    complaintObject: function () {
                        return complaintData;
                    }
                }
            });
        };

        $scope.resolutionCGReplacement = function(complaintData){
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'resolutionCGReplacementModal.html',
                controller: 'resolutionComplaintCtrl',
                size: 'md',
                resolve: {
                    complaintObject: function () {
                        return complaintData;
                    }
                }
            });
        };

        $scope.historyCGTraining = function(complaintData){
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'historyCGTrainingModal.html',
                controller: 'historyCGTrainingCtrl',
                size: 'md',
                resolve: {
                    complaintObject: function () {
                        return complaintData;
                    }
                }
            });
        };

        $scope.historyCGReplacement = function(complaintData){
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'historyCGReplacementModal.html',
                controller: 'historyCGReplacementCtrl',
                size: 'lg',
                resolve: {
                    complaintObject: function () {
                        return complaintData;
                    }
                }
            });
        };



        $scope.logData = {
            complaintId: $scope.complaintId,
            comment: '',
            leadId: ''
        };

        $scope.addComplaintLog = function(){
            $scope.logData.leadId = $scope.complaintData.lead_id;

            $scope.requestData = angular.copy($scope.logData);
            OperationService.addComplaintLog($scope.requestData).then(function(response){
                //window.location.reload();
                $("#logSucess").show();
                setTimeout(function() {
                    $("#logSucess").fadeOut('slow')
                }, 3000);
                $scope.logData.comment = '';
            });
        }

    }])
    .controller('resolutionComplaintCtrl', ["$scope","$uibModalInstance","$http","complaintObject","OperationService",function($scope,$uibModalInstance, $http,complaintObject, OperationService) {
        $scope.complaintData = complaintObject;
        //$scope.primaryCGAssigned = $scope.lead.primaryVendorAssigned;

        $scope.searchuser = function (query) {
            OperationService.getUsersList(query).then(function(response){
                $scope.usersList = response.data;
            });
        };

        $scope.trainingFormData = {
            cgId: '',
            trainingDate: new Date(),
            complaintId: $scope.complaintData.id,
            leadId: $scope.complaintData.lead_id
        };

        //$scope.careGiverAssignmentLink = baseUrl+'/lead/'+$scope.lead.id+'/vendor/suggestions';

        $scope.submitResolutionCGTraining = function(){
            $scope.requestData = angular.copy($scope.trainingFormData);

            OperationService.submitResolutionCGTraining($scope.requestData).then(function(response){
                window.location.reload();
            });
        }

        $scope.replacementFormData = {
            cgId: '',
            startDate: new Date(),
            replacementDate: new Date(),
            reason: '',
            complaintId: $scope.complaintData.id,
            leadId: $scope.complaintData.lead_id
        };

        $scope.submitResolutionCGReplacement = function(){
            $scope.requestData = angular.copy($scope.replacementFormData);

            OperationService.submitResolutionCGReplacement($scope.requestData).then(function(response){
                window.location.reload();
            });
        }

        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
    }])
    .controller('historyCGTrainingCtrl', ["$scope","$uibModalInstance","$http","complaintObject","OperationService",function($scope,$uibModalInstance, $http,complaintObject, OperationService) {
        $scope.complaintData = complaintObject;

        OperationService.getComplaintHistoryCGTraining($scope.complaintData.id).then(function(response){
            if(response.data){
                $scope.history = response.data;
            }
        });
        
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
    }])
    .controller('historyCGReplacementCtrl', ["$scope","$uibModalInstance","$http","complaintObject","OperationService",function($scope,$uibModalInstance, $http,complaintObject, OperationService) {
        $scope.complaintData = complaintObject;

        OperationService.getComplaintHistoryCGReplacement($scope.complaintData.id).then(function(response){
            if(response.data){
                $scope.history = response.data;
            }
        });
        
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
    }])
    .controller('replacementRequestsCtrl', ["$scope","$http","OperationService",function($scope, $http, OperationService) {
        $scope.getLeadLink = function(id){
            return baseUrl+"/lead/"+id;
        }
        $scope.getEmployeeLink = function(id){
            return baseUrl+"/admin/employee/"+id;
        }
        $scope.getVendorLink = function(id){
            return baseUrl+"/vendor/"+id;
        }

        $scope.getComplaintLink = function(id,userType){
            return baseUrl+"/complaint/"+id+"/"+userType;
        }

        $scope.gridOptionsReplacementRequests = {
            enableSorting: true,
            enableFiltering: true,
            enableRowSelection: true,
            enableSelectAll: true,
            columnDefs: [
                { field: 'lead_id', displayName:'Lead ID', cellTemplate:'<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block"><a ng-href="{{grid.appScope.getLeadLink(row.entity.lead_id)}}" target="_blank">{{row.entity[col.field]}}</a></span></div>' },
                { field: 'cg', displayName:'CG Name', cellTemplate:'<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block"><a ng-href="{{grid.appScope.getVendorLink(row.entity.user_id)}}" target="_blank">{{row.entity.name}}</a></span></div>' },
                { field: 'phone', displayName:'CG Phone' },
                { field: 'created_at', displayName:'Date' },
            ],
            onRegisterApi: function( gridApi ) {
                $scope.grid1Api = gridApi;
            }
        };

        OperationService.getReplacementRequests().then(function(response){
            if(response.data){
                $scope.gridOptionsReplacementRequests.data = response.data;
            }
        });

    }])
    .controller('adminResolutionGroupsMembersCtrl', ["$scope","$mdDialog","$http","$filter","$uibModal","OperationService",function($scope,$mdDialog,$http,$filter,$uibModal,OperationService) {

        $scope.getEmployeeLink = function(id){
            return baseUrl+"/admin/employee/"+id;
        };

        $scope.gridOptionsResolutionGroups = {
            enableSorting: true,
            enableFiltering: true,
            enableRowSelection: true,
            enableSelectAll: true,
            columnDefs: [
                { field: 'name', displayName:'Name', cellTemplate:'<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block"><a ng-href="{{grid.appScope.getEmployeeLink(row.entity.id)}}" target="_blank">{{row.entity[col.field]}}</a></span></div>' },
                { field: 'phone', displayName:'Phone' },
                { field: 'group_name', displayName:'Group'},
                { field: 'id',displayName:'Action', enableSorting: false ,  cellTemplate: '<div class="text-center colt{{$index}}" class="text-center"><button class="btn btn-xs btn-danger" ng-click="grid.appScope.deleteEmployee(row.entity.member_relation_id)">Delete</button></div>'}
            ],
            onRegisterApi: function( gridApi ) {
                $scope.grid1Api = gridApi;
            }
        };

        $scope.init = function(){
            OperationService.getComplaintResolutionMembers().then(function(response){
                $scope.gridOptionsResolutionGroups.data = response.data;
            });
        };
        $scope.init();

        $scope.addResolutionGroupMember = function(){
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'addResolutionGroupMember.html',
                controller: 'addResolutionGroupMemberCtrl',
                size: 'md'
            });
        };

        $scope.deleteEmployee = function(member_relation_id){
            OperationService.deleteComplaintResolutionMember(member_relation_id).then(function(response){
                window.location.reload();
            });
        };

        'use strict';
    }])
    .controller('addResolutionGroupMemberCtrl', ["$scope","$mdDialog","$http","$filter","$uibModal","OperationService",function($scope,$mdDialog,$http,$filter,$uibModal,OperationService) {

        $scope.getVendorLink = function(id){
            return baseUrl+"/admin/employee/"+id;
        }

        $scope.init = function(){
            OperationService.getAllotableEmployeesComplaints().then(function(response){
                $scope.usersList = response.data;
            });
            OperationService.getComplaintResolutionGroups().then(function(response){
                $scope.groupList = response.data;
            });
        };
        $scope.init();

        $scope.addMemberFromData = {
            user_id: '',
            group_id: ''
        };

        $scope.submitAddMember = function(){
            $scope.requestData = angular.copy($scope.addMemberFromData);

            OperationService.submitComplaintResolutionGroupAddMember($scope.requestData).then(function(response){
                window.location.reload();
            });
        };

        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        };

        'use strict';
    }])
    .controller('adminLeadsCtrl', ["$scope","$mdDialog","$http","$filter","$uibModal","OperationService",function($scope,$mdDialog,$http,$filter,$uibModal,OperationService) {

        $scope.baseUrl = "mohit";
        $scope.tabActive = 'all';
        $scope.openTab = function(param){
            $scope.tabActive=param;
            if(param=='closedlead'){
                OperationService.getClosedLeads().then(function(response){
                    $scope.gridOptionsClosedLead.data = response.data;
                });
            }else if(param=='pending'){
                OperationService.getPendingLeads().then(function(response){
                    $scope.gridOptionsPending.data = response.data;
                });
            }else if(param=='validated'){
                OperationService.getValidatedLeads().then(function(response){
                    $scope.gridOptionsValidated.data = response.data;
                });
            }else if(param=='activelead'){
                OperationService.getActiveLeads().then(function(response){
                    $scope.gridOptionsActiveLead.data = response.data;
                });
            }else if(param=='today'){
                OperationService.getTodaysLeads().then(function(response){
                    $scope.gridOptionsToday.data = response.data;
                });
            }

        }

        $scope.name = "lana";
        $scope.getLeadLink = function(id){
            return baseUrl+"/lead/"+id;
        }
        $scope.startDateHighlighting = function(row){
            //console.log(row);
            var dNow = new Date()
            var enquiryDate = angular.isDefined(row.enquiryDate.date)? new Date(row.enquiryDate.date): new Date(row.enquiryDate);
            if(row.operationStatus.slug=='pending'){
                var diff = Math.abs(dNow.getTime() - enquiryDate.getTime());
                if(diff/60>10){
                    return 'bg-lead-no-action';
                }
            }
        }
        $scope.positiveArr = ['active','validated','started','approved','lead-approved'];
        $scope.noEmployeeAssignedHighlighting = function(row){
            var dNow = new Date();
            if(row.salesApprovedAt==null){
                return '';
            }
            var enquiryDate = (angular.isDefined(row.salesApprovedAt) && angular.isDefined(row.salesApprovedAt.date))? new Date(row.salesApprovedAt.date): new Date(row.salesApprovedAt);
            if( $scope.positiveArr.indexOf(row.operationStatus.slug)>=0 && row.employeeAssigned==null){
                var diff = Math.abs(dNow.getTime() - enquiryDate.getTime());
                if(diff/60>15){
                    return 'bg-lead-no-employee';
                }
            }
        }
        $scope.noQCAssignedHighlighting = function(row){
            var dNow = new Date();
            if(row.salesApprovedAt==null){
                return '';
            }
            var enquiryDate = (angular.isDefined(row.salesApprovedAt) && angular.isDefined(row.salesApprovedAt.date))? new Date(row.salesApprovedAt.date): new Date(row.salesApprovedAt);
            if( $scope.positiveArr.indexOf(row.operationStatus.slug)>=0 && row.qcUserAssigned==null){
                var diff = Math.abs(dNow.getTime() - enquiryDate.getTime());
                if(diff/60>30){
                    return 'bg-lead-no-qc';
                }
            }
        }
        $scope.noCGAssignedHighlighting = function(row){
            var dNow = new Date();
            if(row.employeeAssignedAt==null){
                return '';
            }
            var enquiryDate = (angular.isDefined(row.employeeAssignedAt) && angular.isDefined(row.employeeAssignedAt.date))? new Date(row.employeeAssignedAt.date): new Date(row.employeeAssignedAt);
            if( $scope.positiveArr.indexOf(row.operationStatus.slug)>=0 && row.primaryVendorAssigned==null){
                var diff = Math.abs(dNow.getTime() - enquiryDate.getTime());
                if(diff/60>(4*60)){
                    return 'bg-lead-no-cg';
                }
            }
        }

        $scope.CGAppHighlight = function(row, cgMode){
            var obj;
            if(cgMode == 'primary'){
                obj = row.primaryVendorAssigned;
            } else if(cgMode == 'backUp'){
                obj = row.backUpVendorAssigned;
            } else {
                return '';
            }

            if(obj==null){
                return '';    
            }
            if(obj.appInstalled==true){
                return '';
            } else {
                return 'customer-app-installed';
            }
        }

        $scope.gridOptions = {
            enableSorting: true,
            enableFiltering: true,
            enableRowSelection: true,
            enableSelectAll: true,
            columnDefs: [
                { field: 'enquiryDate.date', displayName:'Enquiry Date', cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents display-block">{{row.entity.enquiryDate | carbondate}}</span></div>' },
                { field: 'startDate', displayName:'Start Date', cellTemplate: '<div class="ngCellText" ng-class="grid.appScope.startDateHighlighting(row.entity)"><span ng-cell-text class="ui-grid-cell-contents display-block">{{(row.entity.startDate | carbondate) || "NA"}}</span></div>' },
                { field: 'customerName', displayName:'Customer', cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block" ng-class="{\'customer-app-installed\': !row.entity.appInstalled}"><a ng-href="{{grid.appScope.getLeadLink(row.entity.id)}}">{{row.entity[col.field]}}</a></span></div>' },
                { field: 'service.name', displayName:'Service' },
                { field: 'employeeAssigned.name', displayName:'Employee Assigned' , cellTemplate: '<div class="ui-grid-cell-contents" ng-class="grid.appScope.noEmployeeAssignedHighlighting(row.entity)">{{grid.getCellValue(row, col)}}</div>'  },
                { field: 'primaryVendorAssigned.name', displayName:'CG Assigned',cellTemplate: '<div class="ngCellText" ng-class="grid.appScope.noCGAssignedHighlighting(row.entity)"><span ng-cell-text class="ui-grid-cell-contents display-block" ng-class="grid.appScope.CGAppHighlight(row.entity, \'primary\')"><a>{{grid.getCellValue(row, col)}}</a></span></div>'},
                { field: 'backUpVendorAssigned.name', displayName:'Back-Up CG Assigned',cellTemplate: '<div class="ngCellText"><span ng-cell-text class="ui-grid-cell-contents display-block" ng-class="grid.appScope.CGAppHighlight(row.entity, \'backUp\')"><a>{{grid.getCellValue(row, col)}}</a></span></div>' },
                { field: 'qcUserAssigned.name', displayName:'QC Assigned',cellTemplate: '<div class="ui-grid-cell-contents" ng-class="grid.appScope.noQCAssignedHighlighting(row.entity)">{{grid.getCellValue(row, col)}}</div>'  },
                { field: 'fieldUserAssigned.name', displayName:'Field Assigned' },
                { field: 'submissionMode', displayName:'Source' },
                { field: 'operationStatus.label', displayName:'Status' }
            ],
            onRegisterApi: function( gridApi ) {
                $scope.grid1Api = gridApi;
            }
        };
        $scope.gridOptionsToday = angular.copy($scope.gridOptions);
        $scope.gridOptionsPending = angular.copy($scope.gridOptions);
        $scope.gridOptionsForMe = angular.copy($scope.gridOptions);
        $scope.gridOptionsValidated = angular.copy($scope.gridOptions);
        $scope.gridOptionsActiveLead = angular.copy($scope.gridOptions);
        $scope.gridOptionsClosedLead = angular.copy($scope.gridOptions);

        $scope.gridOptionsActiveLead.columnDefs =[
            { field: 'enquiryDate.date', displayName:'Enquiry Date', cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents display-block">{{row.entity.enquiryDate | carbondate}}</span></div>' },
            { field: 'startDate', displayName:'Start Date', cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents display-block">{{(row.entity.startDate | carbondate) || "NA"}}</span></div>' },
            { field: 'customerName', displayName:'Customer', cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block"  ng-class="{\'customer-app-installed\': !row.entity.appInstalled}"><a ng-href="{{grid.appScope.getLeadLink(row.entity.id)}}">{{row.entity[col.field]}}</a></span></div>' },
            { field: 'service.name', displayName:'Service' },
            { field: 'employeeAssigned.name', displayName:'Employee Assigned' },
            { field: 'primaryVendorAssigned.name', displayName:'CG Assigned' },
            { field: 'submissionMode', displayName:'Source' },
            { field: 'operationStatus.label', displayName:'Status' },
            { field: 'id',displayName:'', enableFiltering: false, enableSorting: false ,  cellTemplate: '<div class="text-center colt{{$index}}" class="text-center"><button class="btn btn-xs btn-success" ng-click="grid.appScope.markAttendance(row.entity)">Mark Attendance</button></div>'}
        ];


        $scope.gridOptionsClosedLead.columnDefs =[
            { field: 'enquiryDate.date', displayName:'Enquiry Date', cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents display-block">{{row.entity.enquiryDate | carbondate}}</span></div>' },
            { field: 'startDate', displayName:'Start Date', cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents display-block">{{(row.entity.startDate | carbondate) || "NA"}}</span></div>' },
            { field: 'customerName', displayName:'Customer', cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block"  ng-class="{\'customer-app-installed\': !row.entity.appInstalled}"><a ng-href="{{grid.appScope.getLeadLink(row.entity.id)}}">{{row.entity[col.field]}}</a></span></div>' },
            { field: 'closedDate', displayName:'Closed Date', cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents display-block">{{(row.entity.closedDate | carbondate) || "NA"}}</span></div>' },

            { field: 'markedBy.name', displayName:'Closed By' },
            { field: 'reason.label', displayName:'Reason' },
            { field: 'service.name', displayName:'Service' },

            { field: 'operationStatus.label', displayName:'Status' }
        ];


        $scope.markAttendance = function(lead){
            var validationStatusObject=null;

            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'markAttendanceLeadModal.html',
                controller: 'MarkLeadAttendanceModalCtrl',
                size: 'md',
                resolve: {
                    LeadObject: function () {
                        return lead;
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                if(angular.isDefined(responseData.userAssigned)){
                    $scope.lead.employeeAssigned = responseData.userAssigned;
                }
                $scope.updateLeadLog();
            }, function () {

            });
        }


        $scope.createServiceLead = function(ev,type,serviceId) {
            window.location.href = baseUrl+'/operation/lead/add';
            return;
            if(type=='nursing-care' || type=='assistive-care' || type=='doctor-visit'){
                $mdDialog.show({
                    controller: NewLeadDetailedDialogController,
                    templateUrl: baseUrl+'/static/js/admin/templates/newLeadDetailedDialog.tmpl.html',
                    locals:{
                        ServiceId: serviceId
                    },
                    parent: angular.element(document.body),
                    targetEvent: ev,
                    clickOutsideToClose:true
                })
                    .then(function(answer) {
                        $scope.status = 'You said the information was "' + answer + '".';
                    }, function() {
                        $scope.status = 'You cancelled the dialog.';
                    });
            }
            else if(type=='physiotherapist'){
                $mdDialog.show({
                    controller: NewLeadPsysioDialogController,
                    templateUrl: baseUrl+'/static/js/admin/templates/newLeadPsysioDialog.tmpl.html',
                    locals:{
                        ServiceId: serviceId
                    },
                    parent: angular.element(document.body),
                    targetEvent: ev,
                    clickOutsideToClose:true
                })

                    .then(function(answer) {
                        $scope.status = 'You said the information was "' + answer + '".';
                    }, function() {
                        $scope.status = 'You cancelled the dialog.';
                    });

            }
            else{
                $mdDialog.show({
                    controller: NewLeadOtherDialogController,
                    templateUrl: baseUrl+'/static/js/admin/templates/newLeadOtherDialog.tmpl.html',
                    locals:{
                        ServiceId: serviceId
                    },
                    parent: angular.element(document.body),
                    targetEvent: ev,
                    clickOutsideToClose:true
                })
                    .then(function(answer) {
                        $scope.status = 'You said the information was "' + answer + '".';
                    }, function() {
                        $scope.status = 'You cancelled the dialog.';
                    });
            }
        };
        'use strict';

        $scope.selected = [];
        /*$scope.limitOptions = [5, 10, 15];
        $scope.options = {
            rowSelection: true,
            multiSelect: true,
            autoSelect: true,
            decapitate: false,
            largeEditDialog: false,
            boundaryLinks: false,
            limitSelect: true,
            pageSelect: true
        };
        $scope.query = {
            order: 'name',
            limit: 5,
            page: 1
        };*/




        $scope.leads  = [];

        $scope.filterTodaysLead = function(data){
            var filterLead = [];
            var todayJSDate = new Date();
            var todayMonth = todayJSDate.getMonth()+1;
            var todayDate = todayJSDate.getDate();
            var todayYear = todayJSDate.getFullYear();

            angular.forEach(data,function(value,key){
                console.log(value.startDate);
                if(value.startDate && value.startDate !=null){
                    if(value.startDate.date){
                        var newDate = (value.startDate.date);
                    }else{
                        var newDate = (value.startDate);
                    }
                    //var newDate = (value.startDate.date);
                    var enquiryDateArr = newDate.split(" ");
                    var dateArr = enquiryDateArr[0].split('-');
                    console.log(dateArr);
                    if(parseInt(dateArr[0])==parseInt(todayYear) && parseInt(dateArr[1])==parseInt(todayMonth) && parseInt(dateArr[2])==parseInt(todayDate)){
                        filterLead.push(value);
                    }
                }
            });
            return filterLead;
        }
        $scope.filterPendingLead = function(data){
            var filterLead = [];

            angular.forEach(data,function(value,key){
                if(value.operationStatus.label=="Pending"){
                    filterLead.push(value);
                }
            });
            return filterLead;
        }
        $scope.filterForMeLead = function(data){
            var filterLead = [];

            angular.forEach(data,function(value,key){
                if(angular.isDefined(value.employeeAssigned) && value.employeeAssigned && angular.isDefined(value.employeeAssigned.id)  && value.employeeAssigned.id == parseInt(authUserId)){
                    filterLead.push(value);
                }
            });
            return filterLead;
        }

        $scope.filterValidatedLead = function(data){
            var filterLead = [];

            angular.forEach(data,function(value,key){

                if(angular.isDefined(value.operationStatus) && value.operationStatus && angular.isDefined(value.operationStatus.id)  && value.operationStatus.slug == 'validated'){
                    filterLead.push(value);
                }
            });
            return filterLead;
        }

        $scope.filterActiveLead = function(data){
            var filterLead = [];

            angular.forEach(data,function(value,key){

                if(angular.isDefined(value.operationStatus) && value.operationStatus && angular.isDefined(value.operationStatus.id)  && value.operationStatus.slug == 'started'){
                    filterLead.push(value);
                }
            });
            return filterLead;
        }
        $scope.getLeads = function(){
            OperationService.getLeads(100).then(function(response){
                $scope.gridOptions.data = response.data;
                OperationService.getLeads().then(function(response){
                    $scope.gridOptions.data = response.data;
                    $scope.gridOptionsForMe.data = $scope.filterForMeLead(response.data);

                });

            });
        }
        $scope.leadTabSelected = function(){

        }
        $scope.init = function(){
            $scope.getLeads();
        }


        $scope.toggleLimitOptions = function () {
            $scope.limitOptions = $scope.limitOptions ? undefined : [5, 10, 15];
        };

        $scope.loadStuff = function () {
            $scope.promise = $timeout(function () {
                // loading
            }, 2000);
        };

        $scope.logItem = function (item) {
            console.log(item.name, 'was selected');
        };

        $scope.logOrder = function (order) {
            console.log('order: ', order);
        };

        $scope.logPagination = function (page, limit) {
            console.log('page: ', page);
            console.log('limit: ', limit);
        };
        $scope.init();
    }])
    .controller('operationLeadVendorAutoSuggestionCtrl', ["$scope","$uibModal","$http","uiGridConstants","OperationService",function($scope,$uibModal,$http,uiGridConstants,OperationService) {
        $scope.employeeList = [];
        $scope.leadId = leadId;

        $scope.userToAssign = {
            user:null,
            leadId: $scope.leadId
        };
        $scope.lead = {};
        $scope.showMoreEmployeeDetail = false;
        $scope.changeEmployeeMode = false;
        $scope.changeCGMode = false;
        $scope.changeQcMode = false;
        $scope.changeFieldMode = false;
        $scope.userComment = {
            comment: '',
            leadId: $scope.leadId
        };

        var deploymentType = [
            { value: 1, label: 'Deployed' },
            { value: 0, label: 'Not Deployed'}
        ];
        var genderType = [
            { value: 'Male', label: 'Male' },
            { value: 'Female', label: 'Female'}
        ];
        var appInstalledType = [
            { value: true, label: 'Yes' },
            { value: false, label: 'No'}
        ];
        var flaggedType = [
            { value: 1, label: 'Yes' },
            { value: 0, label: 'No'}
        ];
        $scope.mappedData = {};
        $scope.message = {
            body: '',
            show: false,
            type: ''
        }
        $scope.trainingNotAttendedReason = [];
        $scope.autocompleteOptions = {
            componentRestrictions: {
                country: 'in'
            }
        }
        $scope.vendorData = {
            category:{},
            source:{},
            agency:{},
            name:'',
            email:'',
            phone:'',
            alternate_no:'',
            address:'',
            locality:'',
            zone:'',
            age:'',
            gender:'',
            work_for_male:'',
            weight:'',
            height:'',
            religion:{},
            food:{},
            qualification:{},
            experience:'',
            preferred_shift:{},
            bank_account: {
                name:'',
                accountNo:'',
                bankName:'',
                ifsc:''
            },
            has_smart_phone:'',
            has_bank_account:'',
            training_attended:null,
            training_attended_date:null,
            training_not_attended_reason:{},
            training_not_attended_other_reason:'',
            voter:'',
            aadhar:'',

            validationData:{}
        }
        $scope.availabilityOptions = [];
        $scope.dataMapper = [];
        $scope.validationData = {};
        $scope.gridOptions = {
            enableSorting: true,
            enableRowSelection: true,
            enableSelectAll: true,
            columnDefs: [
                { field: 'name', displayName:'Name',  cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block" ng-class="grid.appScope.CGBlacklistHighlight(row.entity)"><a ng-href="{{grid.appScope.getVendorLink(row.entity.id)}}">{{row.entity[col.field]}}</a></span></div>' },
                { field: 'mobile', displayName:'Mobile', cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block"><button class="btn btn-xs btn-success" ng-click="grid.appScope.editVendorAvailability(row.entity)">{{row.entity[col.field]}}</button></span></div>'},
                { field: 'age', displayName:'Age' },
                { field: 'gender', displayName:'Gender' },
                { field: 'zone.label', displayName:'Location' },
                { field: 'preferredShift.label', displayName:'Preferred Shift' },
                { field: 'trainingDate', displayName:'Training Date',cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents display-block">{{row.entity.trainingDate | carbondate}}</span></div>' },
                { field: 'availability', displayName:'Availability'},
                { field: 'deployed',
                    displayName:'Deployed',
                    filter: { selectOptions: deploymentType, type: uiGridConstants.filter.SELECT },
                    cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents display-block"><span ng-show="row.entity.deployed==1" class="badge bg-success">Deployed</span><span ng-show="row.entity.deployed==0" class="badge bg-warning">Not Deployed</span></span></div>'},
                {
                    field: 'isFlagged',
                    displayName:'Flag',
                    filter: { selectOptions: flaggedType, type: uiGridConstants.filter.SELECT },
                    cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents display-block"><span ng-show="row.entity.isFlagged==1">Yes</span><span ng-show="row.entity.isFlagged!=1">No</span></span></div>'},
                { field: 'addedByUser.name', displayName:'Added By'},
                { field: 'entryDate.date', displayName:'Entry Date',cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents display-block">{{row.entity.entryDate | carbondate}}</span></div>' },
                { field: 'appInstalled',
                    displayName:'App Installed',
                    filter: { selectOptions: appInstalledType, type: uiGridConstants.filter.SELECT },
                    cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents display-block"><span ng-show="row.entity.appInstalled==true" class="badge bg-success">Yes</span><span ng-show="row.entity.appInstalled==false" class="badge bg-warning">No</span></span></div>'},
                { field: 'id',displayName:'', enableFiltering: false, enableSorting: false ,  cellTemplate: '<div class="text-center colt{{$index}}" class="text-center"><button class="btn btn-xs btn-success" ng-click="grid.appScope.viewVendor(row.entity)">View</button> <button ng-show="row.entity.deployed==0" class="btn btn-success btn-xs" ng-click="grid.appScope.assignVendor(row.entity)">Assign</button></div>'}
            ],
            enableFiltering: true,
            onRegisterApi: function( gridApi ) {
                $scope.grid1Api = gridApi;
            }
        };

        $scope.CGBlacklistHighlight = function(row){
            if(row.isBlackListed){
                return 'color-red';
            }
            return '';
        };
        
        $scope.getVendorLink = function(id){
            return baseUrl+"/vendor/"+id;
        }
        /*$scope.gridOptions = {
            enableSorting: true,
            enableFiltering: true,
            columnDefs: [
                { field: 'name', displayName:'Name' },
                { field: 'mobile', displayName:'Mobile'},
                { field: 'age', displayName:'Age' },
                { field: 'religion.label', displayName:'Religion' },
                { field: 'preferredShift.label', displayName:'Preferred Shift' },
                { field: 'id',displayName:'', enableFiltering: false, enableSorting: false ,  cellTemplate: '<div class="text-center colt{{$index}}" class="text-center"><button class="btn btn-xs btn-success" ng-click="grid.appScope.viewVendor(row.entity)">View</button> <button class="btn btn-success btn-xs" ng-click="grid.appScope.assignVendor(row.entity)">Assign</button></div>'}
            ],
            onRegisterApi: function( gridApi ) {
                $scope.grid1Api = gridApi;
            }
        };*/
        $scope.editVendorAvailability = function(vendorData){
            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'vendorAvailabilityModalTemplate.html',
                controller: 'operationLeadVendorAutoSuggestionCtrl',
                size: 'md',
                resolve: {
                    VendorData: function(){
                        //OperationService.getVendorDetail(vendorId).then(function (response) {
                            //$scope.vendorData = vendorData;
                            return $scope.vendorData;
                        //});
                        
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                if(responseData.status){
                    window.location.reload();
                }
            }, function () {

            });
        }
            
        $scope.assignVendor = function(vendor){
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'assignVendorToLead.html',
                controller: 'AssignVendorModalCtrl',
                size: 'md',
                resolve: {
                    VendorObject: function () {
                        return vendor;
                    },
                    LeadObject: function(){
                        return $scope.lead;
                    },
                    ValidatedData: function(){
                        return $scope.validatedData;
                    },
                    ComplaintDataObject: function(){
                        return {
                            "operationStatusGroupId": $scope.lead.operationStatus.groupId,
                            "category" : 9,
                            "userId" : $scope.lead.UserCreated.id,
                            "details" : "",
                            "lead_id" : $scope.leadId,
                            "userType" : 3
                        };
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                if(responseData.status){
                    window.location.href = baseUrl+"/lead/"+$scope.lead.id;
                }
            }, function () {

            });
        }
        $scope.viewVendor = function(vendor){
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'viewVendorProfile.html',
                controller: 'ViewVendorProfileModalCtrl',
                size: 'md',
                resolve: {
                    VendorObject: function () {
                        return vendor;
                    },
                    LeadObject: function(){
                        return $scope.lead;
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                if(responseData.status){
                    window.location.href = baseUrl+"/lead/"+$scope.lead.id;
                }
            }, function () {

            });
        }
        $scope.init = function(){
            OperationService.getVendorAutoListToAssign($scope.leadId).then(function(response){
                //alert(response.data[0].name);
                $scope.gridOptions.data = response.data;
                $scope.vendorData = response.data[0];
            });

            OperationService.getLeadDetail($scope.leadId).then(function(response){
                $scope.lead = response.data;
                if($scope.lead.patient==null){
                    return;
                }
                OperationService.getPatientValidatedData($scope.lead.patient.id).then(function(response){
                    $scope.validatedData = response.data;
                });
            });
            OperationService.getCreateLeadDataMapped().then(function(response){
                $scope.mappedData = response.data;
            });
            OperationService.getTaskListForValidation().then(function(response){
                $scope.vendorData.validationData = response.data;
            });
            OperationService.trainingNotAttendedReasons().then(function(response){
                $scope.trainingNotAttendedReason = response.data;
            });
            OperationService.getVendorAvailabilityOptions().then(function(response){
                $scope.availabilityOptions = response.data;
            });
            OperationService.getVendorAvailabilityMapper().then(function(response){
                $scope.dataMapper = response.data;
            });
        }
        $scope.$watch('availability.available',function(newValue,oldValue){
            if(oldValue!=newValue){
                $scope.availability.option = {};
                $scope.availability.reason = {};
                $scope.availability.otherReason = "";
            }
        })
        $scope.cancel = function(){
            $uibModal.close();
        }
        $scope.updateVendorLocality = function(){
            $scope.availability.lead_id = $scope.leadId;
            OperationService.updateVendorAvailability($scope.vendorData.id, $scope.availability).then(function(response){
                if(response.status){
                    //$uibModalInstance.close(response);
                    window.location.reload();
                }
            });
        }
        $scope.available = true;
        $scope.toggleEmployeeDetail = function(){
            $scope.showMoreEmployeeDetail = !$scope.showMoreEmployeeDetail;
        }

        $scope.init();
    }])
    .controller('operationLeadVendorSuggestionCtrl', ["$scope","$uibModal","$http","uiGridConstants","OperationService",function($scope,$uibModal,$http,uiGridConstants,OperationService) {
        $scope.employeeList = [];
        $scope.leadId = leadId;

        $scope.userToAssign = {
            user:null,
            leadId: $scope.leadId
        };
        $scope.lead = {};
        $scope.showMoreEmployeeDetail = false;
        $scope.changeEmployeeMode = false;
        $scope.changeCGMode = false;
        $scope.changeQcMode = false;
        $scope.changeFieldMode = false;
        $scope.userComment = {
            comment: '',
            leadId: $scope.leadId
        };

        var deploymentType = [
            { value: 1, label: 'Deployed' },
            { value: 0, label: 'Not Deployed'}
        ];
        var genderType = [
            { value: 'Male', label: 'Male' },
            { value: 'Female', label: 'Female'}
        ];
        var appInstalledType = [
            { value: true, label: 'Yes' },
            { value: false, label: 'No'}
        ];
        var flaggedType = [
            { value: 1, label: 'Yes' },
            { value: 0, label: 'No'}
        ];
        $scope.validationData = {};
        $scope.gridOptions = {
            enableSorting: true,
            enableRowSelection: true,
            enableSelectAll: true,
            columnDefs: [
                { field: 'name', displayName:'Name',  cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block" ng-class="grid.appScope.CGBlacklistHighlight(row.entity)"><a ng-href="{{grid.appScope.getVendorLink(row.entity.id)}}">{{row.entity[col.field]}}</a></span></div>' },
                { field: 'mobile', displayName:'Mobile', cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block"><a ng-href="{{grid.appScope.getVendorLink(row.entity.id)}}">{{row.entity[col.field]}}</a></span></div>'},
                { field: 'age', displayName:'Age' },
                { field: 'gender', displayName:'Gender' },
                { field: 'zone.label', displayName:'Location' },
                { field: 'preferredShift.label', displayName:'Preferred Shift' },
                { field: 'trainingDate', displayName:'Training Date',cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents display-block">{{row.entity.trainingDate | carbondate}}</span></div>' },
                { field: 'availability', displayName:'Availability'},
                { field: 'deployed',
                    displayName:'Deployed',
                    filter: { selectOptions: deploymentType, type: uiGridConstants.filter.SELECT },
                    cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents display-block"><span ng-show="row.entity.deployed==1" class="badge bg-success">Deployed</span><span ng-show="row.entity.deployed==0" class="badge bg-warning">Not Deployed</span></span></div>'},
                {
                    field: 'isFlagged',
                    displayName:'Flag',
                    filter: { selectOptions: flaggedType, type: uiGridConstants.filter.SELECT },
                    cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents display-block"><span ng-show="row.entity.isFlagged==1">Yes</span><span ng-show="row.entity.isFlagged!=1">No</span></span></div>'},
                { field: 'addedByUser.name', displayName:'Added By'},
                { field: 'entryDate.date', displayName:'Entry Date',cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents display-block">{{row.entity.entryDate | carbondate}}</span></div>' },
                { field: 'appInstalled',
                    displayName:'App Installed',
                    filter: { selectOptions: appInstalledType, type: uiGridConstants.filter.SELECT },
                    cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents display-block"><span ng-show="row.entity.appInstalled==true" class="badge bg-success">Yes</span><span ng-show="row.entity.appInstalled==false" class="badge bg-warning">No</span></span></div>'},
                { field: 'id',displayName:'', enableFiltering: false, enableSorting: false ,  cellTemplate: '<div class="text-center colt{{$index}}" class="text-center"><button class="btn btn-xs btn-success" ng-click="grid.appScope.viewVendor(row.entity)">View</button> <button ng-show="row.entity.deployed==0" class="btn btn-success btn-xs" ng-click="grid.appScope.assignVendor(row.entity)">Assign</button></div>'}
            ],
            enableFiltering: true,
            onRegisterApi: function( gridApi ) {
                $scope.grid1Api = gridApi;
            }
        };

        $scope.CGBlacklistHighlight = function(row){
            if(row.isBlackListed){
                return 'color-red';
            }
            return '';
        };
        
        $scope.getVendorLink = function(id){
            return baseUrl+"/vendor/"+id;
        }
        /*$scope.gridOptions = {
            enableSorting: true,
            enableFiltering: true,
            columnDefs: [
                { field: 'name', displayName:'Name' },
                { field: 'mobile', displayName:'Mobile'},
                { field: 'age', displayName:'Age' },
                { field: 'religion.label', displayName:'Religion' },
                { field: 'preferredShift.label', displayName:'Preferred Shift' },
                { field: 'id',displayName:'', enableFiltering: false, enableSorting: false ,  cellTemplate: '<div class="text-center colt{{$index}}" class="text-center"><button class="btn btn-xs btn-success" ng-click="grid.appScope.viewVendor(row.entity)">View</button> <button class="btn btn-success btn-xs" ng-click="grid.appScope.assignVendor(row.entity)">Assign</button></div>'}
            ],
            onRegisterApi: function( gridApi ) {
                $scope.grid1Api = gridApi;
            }
        };*/
        $scope.assignVendor = function(vendor){
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'assignVendorToLead.html',
                controller: 'AssignVendorModalCtrl',
                size: 'md',
                resolve: {
                    VendorObject: function () {
                        return vendor;
                    },
                    LeadObject: function(){
                        return $scope.lead;
                    },
                    ValidatedData: function(){
                        return $scope.validatedData;
                    },
                    ComplaintDataObject: function(){
                        return {
                            "operationStatusGroupId": $scope.lead.operationStatus.groupId,
                            "category" : 9,
                            "userId" : $scope.lead.UserCreated.id,
                            "details" : "",
                            "lead_id" : $scope.leadId,
                            "userType" : 3
                        };
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                if(responseData.status){
                    window.location.href = baseUrl+"/lead/"+$scope.lead.id;
                }
            }, function () {

            });
        }
        $scope.viewVendor = function(vendor){
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'viewVendorProfile.html',
                controller: 'ViewVendorProfileModalCtrl',
                size: 'md',
                resolve: {
                    VendorObject: function () {
                        return vendor;
                    },
                    LeadObject: function(){
                        return $scope.lead;
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                if(responseData.status){
                    window.location.href = baseUrl+"/lead/"+$scope.lead.id;
                }
            }, function () {

            });
        }
        $scope.init = function(){
            OperationService.getVendorListToAssign($scope.leadId).then(function(response){
                $scope.gridOptions.data = response.data;
            });

            OperationService.getLeadDetail($scope.leadId).then(function(response){
                $scope.lead = response.data;
                if($scope.lead.patient==null){
                    return;
                }
                OperationService.getPatientValidatedData($scope.lead.patient.id).then(function(response){
                    $scope.validatedData = response.data;
                });
            });
        }
        $scope.toggleEmployeeDetail = function(){
            $scope.showMoreEmployeeDetail = !$scope.showMoreEmployeeDetail;
        }

        $scope.init();
    }])
    .controller('leadCarePlanCtrl', ["$scope","$uibModal","$http","OperationService",function($scope,$uibModal,$http,OperationService) {
        $scope.leadId = leadId;
        $scope.employeeList = [];
        $scope.showMoreEmployeeDetail = false;

        $scope.toggleEmployeeDetail = function(){
            $scope.showMoreEmployeeDetail = !$scope.showMoreEmployeeDetail;
        }
        if(angular.isUndefined(leadId) || leadId==null ){
            window.location.href=baseUrl;
        }


        $scope.carePlanEditMode = '';
        $scope.cancelCarePlanEdit = function(){
            $scope.carePlanEditMode = '';
        }
        $scope.saveData = function(){
            var checkboxString = "."+$scope.carePlanEditMode+"-checkbox";
            var allElement = $(checkboxString);
            var submitResponse = [];
            for(var i=0; i<allElement.length; i++){
                var taskInfo = $(allElement[i]).find('.task-info');
                var checkBox = $(allElement[i]).find('input');
                submitResponse.push({
                    'task_id':taskInfo.html(),
                    'checked':$(checkBox).is(":checked")
                })
            }
            OperationService.markCarePlanCheck($scope.leadId,$scope.carePlanEditMode,submitResponse).then(function(){
                window.location.reload();
            });

        }

        $scope.markEvaluation = function(action){
            $scope.carePlanEditMode = action;
            return;
            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'carePlanCheckList.html',
                controller: 'CarePlanCheckListModalController',
                size: 'lg',
                resolve: {
                    LeadObject: function () {
                        return $scope.lead;
                    },
                    CarePlanGrid: function(){
                        return $scope.validationGrid
                    },
                    CarePlanAction: function(){
                        return action;
                    }
                }
            });

            modalInstance.result.then(function (selectedItem) {
                $scope.updateLeadLog();
            }, function () {

            });
        }

        $scope.init = function(){
            OperationService.getCarePlanGrid($scope.leadId).then(function(response){
                $scope.validationGrid = response.data;

            });
            OperationService.getLeadDetail($scope.leadId).then(function(response){
                $scope.lead = response.data;

            });
        }

        $scope.init();
    }])
    .controller('operationLeadCtrl', ["$scope","$uibModal","$http","OperationService",function($scope,$uibModal,$http,OperationService) {
        $scope.leadId = leadId;
        $scope.employeeList = [];
        $scope.closedStatus = [];
        $scope.leadHoldStatus = [];

        $scope.getSlackChannelCreateUrl = function(leadId){
            return baseUrl+"/lead/"+leadId+"/create/slack/channel";
        }
        $scope.getSlackChannelLink = function(slackChannelName){
            var slackUrl = "http://pramati-care.slack.com";
            if(!slackChannelName || slackChannelName==''||slackChannelName=='1'||slackChannelName==1){
                return slackUrl;
            }
            slackUrl+="/messages/"+slackChannelName+"/details/";

            return slackUrl;
        }
        if(angular.isUndefined(leadId) || leadId==null ){
            window.location.href=baseUrl;
        }

        $scope.createSlackChannel = function(){
            OperationService.createSlackChannel($scope.lead.id).then(function(response){

            });
        }

        $scope.notifyCustomer = function(){
            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'notifyCustomerTemplate.html',
                controller: 'NotifyCustomerModalCtrl',
                size: 'md',
                resolve: {
                    LeadObject: function () {
                        return $scope.lead;
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                //window.location.href = baseUrl+'/admin/leads'
            }, function () {

            });
        }
        $scope.showPhoneNo = function(){
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'viewLeadPhoneModalTemplate.html',
                controller: 'ViewLeadPhoneModalCtrl',
                size: 'md',
                resolve: {
                    LeadObject: function () {
                        return $scope.lead;
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                window.location.href = baseUrl+'/admin/leads'
            }, function () {

            });
        }
        $scope.startService = function(){
            var modalInstance = $uibModal.open({
                animation:true,
                templateUrl: 'startLeadServiceModalTemplate.html',
                controller: 'StartLeadServiceModalCtrl',
                size: 'md',
                resolve: {
                    LeadObject: function () {
                        return $scope.lead;
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                //window.location.href = baseUrl+'/admin/leads'
            }, function () {

            });
        }
        $scope.closeService = function(){
            var validationStatusObject=null;

            console.log(validationStatusObject);
            console.log('lead rejection');
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'closeLeadModal.html',
                controller: 'CloseLeadModalCtrl',
                size: 'md',
                resolve: {
                    LeadObject: function () {
                        return $scope.lead;
                    },
                    LeadStatus: function(){
                        return $scope.closedStatus;
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                if(angular.isDefined(responseData.userAssigned)){
                    $scope.lead.employeeAssigned = responseData.userAssigned;
                }
                $scope.updateLeadLog();
            }, function () {

            });
        }
        $scope.markAttendance = function(){
            var validationStatusObject=null;

            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'markAttendanceLeadModal.html',
                controller: 'MarkLeadAttendanceModalCtrl',
                size: 'md',
                resolve: {
                    LeadObject: function () {
                        return $scope.lead;
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                if(angular.isDefined(responseData.userAssigned)){
                    $scope.lead.employeeAssigned = responseData.userAssigned;
                }
                $scope.updateLeadLog();
            }, function () {

            });
        }
        $scope.userToAssign = {
            user:null,
            leadId: $scope.leadId
        }

        $scope.qcToAssign = {
            user:null,
            leadId: $scope.leadId
        };
        $scope.fieldToAssign = {
            user:null,
            leadId: $scope.leadId
        };
        $scope.lead = {};
        $scope.showMoreEmployeeDetail = false;
        $scope.changeEmployeeMode = false;
        $scope.changeCGMode = false;
        $scope.changeFieldMode = false;
        $scope.userComment = {
            comment: '',
            leadId: $scope.leadId
        };
        $scope.deleteLead = function(){
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'deleteLeadModalTemplate.html',
                controller: 'DeleteLeadModalCtrl',
                size: 'md',
                resolve: {
                    LeadObject: function () {
                        return $scope.lead;
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                window.location.href = baseUrl+'/admin/leads'
            }, function () {

            });
        }
        $scope.isLeadApproved = function(){
            if(angular.isDefined($scope.lead.logs) && $scope.lead.logs.length>0){
                var logTemp = $scope.lead.logs;
                for(var i=0; i<logTemp.length; i++){
                    if(logTemp[i].taskType!="status_change"){
                        continue;
                    }
                    if(logTemp[i].data.status.slug=="lead-approved"){
                        return true;
                    }
                }
            }
            return false;
        }
        $scope.isLeadValidated = function(){
            if(angular.isDefined($scope.lead.logs) && $scope.lead.logs.length>0){
                var logTemp = $scope.lead.logs;
                for(var i=0; i<logTemp.length; i++){
                    if(logTemp[i].taskType!="status_change"){
                        continue;
                    }
                    if(logTemp[i].data.status.slug=="validated"){
                        return true;
                    }
                }
            }
            return false;
        }
        $scope.readyToStart = function(){
            if($scope.lead){
                if(!$scope.lead.employeeAssigned){
                    return false;
                }
                //if(!$scope.lead.vendorAssigned){
                    //return false;
                //}
                if(!$scope.lead.fieldAssigned){
                    return false;
                }
                if(!$scope.lead.qcAssigned){
                    return false;
                }
                return true;
            }
            return false;
        }
        $scope.isLeadClosed = function(){
            if(angular.isDefined($scope.lead.statusLog) && $scope.lead.statusLog!=null && $scope.lead.statusLog.length>0){
                var currentStatus = $scope.lead.statusLog[$scope.lead.statusLog.length-1];
                if(currentStatus.slug=='closed'){
                    return true;
                }
            }
            return false;
        }
        $scope.isLeadOnHold = function(){
            if(angular.isDefined($scope.lead) && angular.isDefined($scope.lead.statusLog) && $scope.lead.statusLog!=null && $scope.lead.statusLog.length>0){
                var currentStatus = $scope.lead.statusLog[$scope.lead.statusLog.length-1];
                if(currentStatus.slug=='hold'){
                    return true;
                }
            }
            return false;
        }
        $scope.isLeadStarted = function(){
            if(angular.isDefined($scope.lead.logs) && $scope.lead.logs.length>0){
                var logTemp = $scope.lead.logs;
                for(var i=0; i<logTemp.length; i++){
                    if(logTemp[i].taskType!="status_change"){
                        continue;
                    }
                    if(logTemp[i].data.status.slug=="started"){
                        return true;
                    }
                }
            }
            return false;
        }
        $scope.updateLeadStatus = function(){
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'changeLeadStatusModalTemplate.html',
                controller: 'ChangeLeadStatusModalCtrl',
                size: 'md',
                resolve: {
                    LeadObject: function () {
                        return $scope.lead;
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                if(angular.isDefined(responseData.userAssigned)){
                    $scope.lead.employeeAssigned = responseData.userAssigned;
                }
                $scope.updateLeadLog();
            }, function () {

            });
        }
        $scope.validateLead = function(){
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'validateLeadModalTemplate.html',
                controller: 'ValidateLeadModalController',
                size: 'md',
                resolve: {
                    LeadObject: function () {
                        return $scope.lead;
                    }
                }
            });

            modalInstance.result.then(function (selectedItem) {
                $scope.updateLeadLog();
            }, function () {
                $log.info('Modal dismissed at: ' + new Date());
            });
        }
        $scope.approveLead = function(){
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'approveLeadModalTemplate.html',
                controller: 'ApproveLeadModalController',
                size: 'md',
                resolve: {
                    LeadObject: function () {
                        return $scope.lead;
                    }
                }
            });

            modalInstance.result.then(function (selectedItem) {
                $scope.updateLeadLog();
            }, function () {
                $log.info('Modal dismissed at: ' + new Date());
            });
        }
        $scope.addComment = function(){
            OperationService.addComment($scope.userComment).then(function(response){
                if(response.status){
                    $scope.updateLeadLog();
                    $scope.userComment.comment = '';
                }
            });
        }

        $scope.changeCG = function(){
            OperationService.getVendorListToAssign().then(function(response){
                $scope.employeeList = response.data;
            });
            $scope.changeCGMode = true;
        }
        $scope.cancelChangeCG = function(){
            $scope.changeCGMode = false;
        }
        $scope.changeQcEmployee = function(){
            OperationService.getEmployeeListToAssign().then(function(response){
                $scope.employeeList = response.data;
            });
            $scope.changeQcEmployeeMode = true;
        }
        $scope.cancelChangeQcEmployee = function(){
            $scope.changeQcEmployeeMode = false;
        }
        $scope.changeEmployee = function(){
            OperationService.getEmployeeListToAssign().then(function(response){
                $scope.employeeList = response.data;
            });
            $scope.changeEmployeeMode = true;
        }
        $scope.cancelChangeEmployee = function(){
            $scope.changeEmployeeMode = false;
        }
        $scope.changeFieldEmployee = function(){
            OperationService.getEmployeeListToAssign().then(function(response){
                $scope.employeeList = response.data;
            });
            $scope.changeFieldEmployeeMode = true;
        }
        $scope.cancelChangeEmployee = function(){
            $scope.changeFieldEmployeeMode = false;
        }
        $scope.submitAssignEmployee = function(){
            OperationService.assignEmployeeToLead($scope.userToAssign).then(function(response){
                if(response.status){
                    $scope.lead.employeeAssigned = response.data;
                    $scope.changeEmployeeMode = false;
                    $scope.updateLeadLog();
                }
            });
        }
        $scope.submitAssignQcEmployee  = function(){
            OperationService.assignQcEmployeeToLead($scope.qcToAssign).then(function(response){
                if(response.status){
                    $scope.lead.qcAssigned = response.data;
                    $scope.changeQcEmployeeMode = false;
                    $scope.updateLeadLog();
                }
            });
        }
        $scope.markQcBriefing = function(){
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'qcBriefing.html',
                controller: 'QcBriefingModalCtrl',
                size: 'md',
                resolve: {
                    LeadObject: function(){
                        return $scope.lead;
                    },
                    QCAssignmentId:function(){
                        return $scope.lead.qcAssignmentId;
                    }
                }
            });
            modalInstance.result.then(function (responseData) {
                if(responseData.status){
                    window.location.href = baseUrl+"/lead/"+$scope.lead.id;
                }
            }, function () {

            });
        }
        $scope.submitAssignFieldEmployee  = function(){
            OperationService.assignFieldEmployeeToLead($scope.fieldToAssign).then(function(response){
                if(response.status){
                    $scope.lead.fieldAssigned = response.data;
                    $scope.changeFieldEmployeeMode = false;
                    $scope.updateLeadLog();
                }
            });
        }

        $scope.updateLeadLog = function(){
            OperationService.getLeadLogs($scope.leadId).then(function(response){
                $scope.lead.logs = response.data;
            });
        }



        $scope.init = function(){
            OperationService.getLeadDetail($scope.leadId).then(function(response){
                $scope.lead = response.data;

                $scope.updateLeadLog();
            });

            OperationService.syncSlackChat($scope.leadId).then(function(response){
                $scope.updateLeadLog();
            });


            OperationService.getOperationalStausesGrouped().then(function(response){
                $scope.statusesGrouped = response.data;
                angular.forEach($scope.statusesGrouped, function(value){
                    angular.forEach(value.statuses, function(valueSingle){
                        if(valueSingle.slug == 'closed'){
                            $scope.closedStatus.push(valueSingle);
                        }else if(valueSingle.slug == 'hold'){
                            $scope.closedStatus.push(valueSingle);
                        }
                    });
                });
            });
        }
        $scope.toggleEmployeeDetail = function(){
            $scope.showMoreEmployeeDetail = !$scope.showMoreEmployeeDetail;
        }

        $scope.init();
    }])
    .controller('profileCtrl', ["$scope","$uibModal","$http",function($scope,$uibModal,$http) {
        $scope.leadId = leadId;
        $scope.employeeList = [];
        if(angular.isUndefined(leadId) || leadId==null ){
            window.location.href=baseUrl;
        }
        $scope.updateLeadStatus = function(){
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'changeLeadStatusModalTemplate.html',
                controller: 'ChangeLeadStatusModalCtrl',
                size: 'md',
                resolve: {
                    LeadObject: function () {
                        return $scope.lead;
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                if(angular.isDefined(responseData.userAssigned)){
                    $scope.lead.employeeAssigned = responseData.userAssigned;
                }
                $scope.updateLeadLog();
            }, function () {

            });
        }
        
    }])
    .controller('ValidationPitchModalCtrl', ["$scope","$uibModalInstance","$http","LeadObject","OperationService",function($scope,$uibModalInstance, $http,LeadObject, OperationService) {
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
    }])
    .controller('BulkDeleteLeadModalCtrl', ["$scope","$uibModalInstance","$http","LeadList","OperationService",function($scope,$uibModalInstance, $http,LeadList, OperationService) {
        $scope.leadList = LeadList;
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
        $scope.message = {
            type:'',
            body:'',
            show:false
        }
        $scope.deleteBulkLead = function(){
            var toDeleteLeads = [];
            for(var i=0; i<$scope.leadList.length; i++){
                toDeleteLeads.push($scope.leadList[i].id);
            }
            OperationService.deleteBulkLead(toDeleteLeads).then(function(response){

                if(response.status){
                    window.location.reload();
                }else{
                    $scope.message.body = response.message;
                    $scope.message.show = true;
                    $scope.message.type = response.type;
                }
            });
        }
    }])
    .controller('AssignVendorModalCtrl', ["$scope","$uibModalInstance","$http","VendorObject","OperationService","LeadObject","ValidatedData","ComplaintDataObject",function($scope,$uibModalInstance, $http,VendorObject, OperationService, LeadObject, ValidatedData, ComplaintDataObject) {
        $scope.vendor = VendorObject;
        $scope.lead = LeadObject;
        $scope.validatedData = ValidatedData;
        $scope.cgPrice = '';
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }

        $scope.complaintData = ComplaintDataObject;

        $scope.getComplaintSubCategories = function($item){
            OperationService.getComplaintSubCategories($item, 'user').then(function(response){
                if(response.data.length > 0){
                    $scope.complaintSubCategories = response.data;
                } else {
                    $scope.complaintSubCategories = [];
                }
            });
        };

        $scope.getComplaintSubChildCategories = function($item){
            OperationService.getComplaintSubCategories($item, 'user').then(function(response){
                if(response.data.length > 0){
                    $scope.complaintSubChildCategories = response.data;
                } else {
                    $scope.complaintSubChildCategories = [];
                }
            });
        };

        $scope.getComplaintSubCategories(9);


        $scope.showTaskCategory = function(taskCategory){
            for(var i=0; i<taskCategory.tasks.length; i++){
                if(taskCategory.tasks[i].selected==true){
                    return true;
                }
            }
            return false;
        }
        $scope.checkAll = true;
        $scope.$watch('checkAll',function(){
            if(angular.isDefined($scope.checkAll)){
                for(var i=0;i<$scope.validatedData.tasks.length;i++){
                    for(var j=0; j<$scope.validatedData.tasks[i].tasks.length; j++){
                        $scope.validatedData.tasks[i].tasks[j].sourcingSelected = false;
                    }
                }
            }
        });
        $scope.isAssignable = function(){
            var sourcingData = [];
            for(var i=0;angular.isDefined($scope.validatedData) && $scope.validatedData && i<$scope.validatedData.tasks.length;i++){
                for(var j=0; j<$scope.validatedData.tasks[i].tasks.length; j++){
                    if(angular.isDefined($scope.validatedData.tasks[i].tasks[j].sourcingSelected) && $scope.validatedData.tasks[i].tasks[j].sourcingSelected==true){
                        sourcingData.push($scope.validatedData.tasks[i].tasks[j].id);
                    }
                }
            }
            if(sourcingData.length>0){
                return true;
            }
            return true;
        }
        $scope.assignCareGiverToLead = function(primary){
            var sourcingData = [];
            for(var i=0;angular.isDefined($scope.validatedData) && i<$scope.validatedData.tasks.length;i++){
                for(var j=0; j<$scope.validatedData.tasks[i].tasks.length; j++){
                    if(angular.isDefined($scope.validatedData.tasks[i].tasks[j].sourcingSelected) && $scope.validatedData.tasks[i].tasks[j].sourcingSelected==true){
                        sourcingData.push($scope.validatedData.tasks[i].tasks[j].id);
                    }
                }
            }
            
            // raise pre placement complaint
            if($scope.complaintData.operationStatusGroupId == 1 && $scope.lead.primaryVendorAssigned){
                if($scope.complaintSubCategories.length > 0){
                    $scope.complaintData.category = $scope.complaintData.complaintSubCategories;
                }
                if($scope.complaintSubChildCategories.length > 0){
                    $scope.complaintData.category = $scope.complaintData.complaintSubChildCategories;
                }
                OperationService.submitComplaint($scope.complaintData).then(function(response){
                    if(response.type == 'success'){
                        //window.location.href = baseUrl+'/admin/complaints';
                    }
                });
            }

            OperationService.assignCGToLead($scope.lead.id,$scope.vendor.id,$scope.cgPrice,primary, sourcingData).then(function(response){
                $uibModalInstance.close(response);
                if(response.status){


                }
            });

        }

    }])
    .controller('QcBriefingModalCtrl', ["$scope","$uibModalInstance","$http","OperationService","LeadObject","QCAssignmentId",function($scope,$uibModalInstance, $http, OperationService, LeadObject,QCAssignmentId) {
        $scope.lead = LeadObject;
        $scope.qcAssignmentId = QCAssignmentId;
        $scope.validatedData = {
            tasks:[]
        }
        $scope.checkAll = false;
        OperationService.getPatientValidatedData($scope.lead.patient.id).then(function(response){
            $scope.validatedData = response.data;
            $scope.checkAll = true;
            $scope.$watch('checkAll',function(){
                if(angular.isDefined($scope.checkAll)){
                    for(var i=0;i<$scope.validatedData.tasks.length;i++){
                        for(var j=0; j<$scope.validatedData.tasks[i].tasks.length; j++){
                            $scope.validatedData.tasks[i].tasks[j].briefingSelected = $scope.checkAll;
                        }
                    }
                }
            });
        });
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }


        $scope.isAssignable = function(){
            var sourcingData = [];
            for(var i=0;i<$scope.validatedData.tasks.length;i++){
                for(var j=0; j<$scope.validatedData.tasks[i].tasks.length; j++){
                    if(angular.isDefined($scope.validatedData.tasks[i].tasks[j].briefingSelected) && $scope.validatedData.tasks[i].tasks[j].briefingSelected==true){
                        sourcingData.push($scope.validatedData.tasks[i].tasks[j].id);
                    }
                }
            }
            if(sourcingData.length>0){
                return true;
            }
            return false;
        }
        $scope.submitBriefing = function(){
            var sourcingData = [];
            for(var i=0;i<$scope.validatedData.tasks.length;i++){
                for(var j=0; j<$scope.validatedData.tasks[i].tasks.length; j++){
                    if(angular.isDefined($scope.validatedData.tasks[i].tasks[j].briefingSelected) && $scope.validatedData.tasks[i].tasks[j].briefingSelected==true){
                        sourcingData.push($scope.validatedData.tasks[i].tasks[j].id);
                    }
                }
            }
            OperationService.submitQCBriefing($scope.lead.id,$scope.qcAssignmentId, sourcingData).then(function(response){
                $uibModalInstance.close(response);
                if(response.status){

                }
            });
        }

    }])
    .controller('DeleteLeadModalCtrl', ["$scope","$uibModalInstance","$http","OperationService","LeadObject",function($scope,$uibModalInstance, $http, OperationService, LeadObject) {
        $scope.lead = LeadObject;
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
        $scope.deleteLead = function(){
            OperationService.deleteLead($scope.lead.id).then(function(response){
                $uibModalInstance.close(response);
                if(response.status){

                }
            });
        }

    }])
    
    .controller('StartLeadServiceModalCtrl', ["$scope","$uibModalInstance","$http","OperationService","LeadObject",function($scope,$uibModalInstance, $http, OperationService, LeadObject) {
        $scope.lead = LeadObject;
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }

        $scope.cgAssigned = $scope.lead.primaryVendorAssigned.id;
        $scope.showBackUpVendor = true;
        //if($scope.lead.primaryVendorAssigned.id==$scope.lead.vendorAssigned.id){
            //$scope.showBackUpVendor=false;
        //}
        $scope.startService = function(){
            if($scope.lead.paymentType == null || $scope.lead.paymentPeriod == null ||
               $scope.lead.paymentMode == null || $scope.lead.start_date == '' ||
               $scope.lead.email == '') 
            {
                alert('Please make sure to update the payment information and customer information. Lead can be started once payment info updated.');
              return false;
            }
            OperationService.startLeadService($scope.lead.id,$scope.cgAssigned ).then(function(response){
                if(response.status){
                    $uibModalInstance.close();
                }
            });
        }


    }])

    .controller('NotifyCustomerModalCtrl', ["$scope","$uibModalInstance","$http","OperationService","LeadObject",function($scope,$uibModalInstance, $http, OperationService, LeadObject) {
        $scope.lead = LeadObject;
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
        $scope.notificationTemplates = [];
        OperationService.getCustomerNotificationTemplates().then(function(response){
            $scope.notificationTemplates = response.data;
        });
        $scope.formData = {
            header:'',
            content:'',
            template:'',
            new_template:false,
            _new_template_name:''
        }

        $scope.templateSelected = function(item){
            $scope.formData.header = item.header;
            $scope.formData.content = item.content;
        }


        $scope.sendNotificationToCustomer = function(){
            $uibModalInstance.close();
            OperationService.sendNotificationToCustomer($scope.lead.id,{
                header: $scope.formData.header,
                content: $scope.formData.content,
                new_template: $scope.formData.new_template,
                new_template_name: $scope.formData.new_template_name
            }).then(function(response){

            });
        }

    }])
    .controller('RejectLeadModalCtrl', ["$scope","$uibModalInstance","$http","LeadObject","OperationService","LeadObject","LeadStatus",function($scope,$uibModalInstance, $http,LeadObject, OperationService, LeadObject, LeadStatus) {
        $scope.lead = LeadObject;
        $scope.status = LeadStatus;
        $scope.requestData = {
            reason: null,
            comment: '',
            leadId: $scope.lead.id,
            status:$scope.status.id
        }
        $scope.updateStatus = function(){
            OperationService.updateLeadStatus($scope.requestData).then(function(response){
                window.location.href = baseUrl+"/lead/"+$scope.lead.id;
            });
        }
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
    }])
    .controller('CloseLeadModalCtrl', ["$scope","$uibModalInstance","$http","LeadObject","OperationService","LeadObject","LeadStatus",function($scope,$uibModalInstance, $http,LeadObject, OperationService, LeadObject, LeadStatus) {
        $scope.lead = LeadObject;

        $scope.statuses = LeadStatus;
        $scope.statusSelected = {};
        $scope.statusReasonSelected = {};
        $scope.serviceIssueList = [];
        $scope.serviceIssueList.push({
            'id':1,
            'label':'CG Skill Issue'
        });
        $scope.serviceIssueList.push({
            'id':2,
            'label':'CG Behaviour Issue'
        });
        $scope.serviceIssueList.push({
            'id':3,
            'label': 'Backend Issue'
        });
        $scope.serviceIssueList.push({
            'id':4,
            'label':'Other Issue'
        });
        $scope.requestData = {
            reason: null,
            comment: '',
            leadId: $scope.lead.id,
            status:'',
            reason:'',
            data:'',
            date:new Date()

        }
        $scope.formData = {
            comment: '',
            status:'',
            reason:'',
            issue:'',
            date: new Date(),
            deduction: 0
        }

        $scope.askAmountDeduction = false;
        $scope.checkAskCGDeduction = function($itemVal){
            if($itemVal == 1 || $itemVal == 2){
                $scope.askAmountDeduction = true;
            } else {
                $scope.askAmountDeduction = false;
            }
        };

        $scope.updateStatus = function(){

            $scope.requestData.status = $scope.formData.status.id;
            $scope.requestData.reason = $scope.formData.reason.id;
            $scope.requestData.data = $scope.formData.issue.label;
            $scope.requestData.comment = $scope.formData.comment;
            $scope.requestData.comment = $scope.formData.comment;
            $scope.requestData.date = $scope.formData.date;
            $scope.requestData.deduction = $scope.formData.deduction;

            OperationService.updateLeadStatus($scope.requestData).then(function(response){
                window.location.href = baseUrl+"/lead/"+$scope.lead.id;
            });
        }
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
    }])
    .controller('MarkLeadAttendanceModalCtrl', ["$scope","$uibModalInstance","$http","LeadObject","OperationService",function($scope,$uibModalInstance, $http,LeadObject, OperationService) {
        $scope.lead = LeadObject;

        $scope.primaryCGAssigned = $scope.lead.primaryVendorAssigned;
        $scope.backUpCGAssigned = $scope.lead.vendorAssigned;


        $scope.formData = {
            comment: '',
            caregiver:'',
            attendance:true,
            price:'',
            leadId:$scope.lead.id,
            date: new Date(),
            assignCaregiver: '',
            uninformed: 0
        }

        if($scope.primaryCGAssigned){
            $scope.formData.assignCaregiver = 'primaryCG'
        }else if($scope.backUpCGAssigned){
            $scope.formData.assignCaregiver = 'backupCG';
        }else{
            $scope.formData.assignCaregiver = 'otherCG';
        }

        $scope.caregiverList = [];

        OperationService.getCareGiverList().then(function(response){
            if(response.data){
                $scope.caregiverList = response.data;
            }
        });
        $scope.careGiverAssignmentLink = baseUrl+'/lead/'+$scope.lead.id+'/vendor/suggestions';

        $scope.markAttendance = function(){


            $scope.requestData = angular.copy($scope.formData);

            if($scope.formData.assignCaregiver=='primaryCG' || true){
                $scope.requestData.caregiver = $scope.primaryCGAssigned;
            }else if($scope.formData.assignCaregiver=='backupCG'){
                $scope.requestData.caregiver = $scope.backUpCGAssigned;
            }



            OperationService.markCGAttendance($scope.requestData).then(function(response){
                window.location.reload();
            });
        }
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
    }])
    .controller('FollowUpLeadModalCtrl', ["$scope","$uibModalInstance","$http","LeadObject","OperationService","LeadObject","LeadStatus",function($scope,$uibModalInstance, $http,LeadObject, OperationService, LeadObject, LeadStatus) {
        $scope.lead = LeadObject;
        $scope.status = LeadStatus;
        $scope.requestData = {
            reason: null,
            comment: '',
            leadId: $scope.lead.id,
            status:$scope.status.id
        }
        $scope.updateStatus = function(){
            OperationService.updateLeadStatus($scope.requestData).then(function(response){
               // window.location.href = baseUrl+"/lead/"+$scope.lead.id;
            });
        }
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
    }])
    .controller('PostponeLeadModalCtrl', ["$scope","$uibModalInstance","$http","LeadObject","OperationService","LeadObject","LeadStatus",function($scope,$uibModalInstance, $http,LeadObject, OperationService, LeadObject, LeadStatus) {
        $scope.lead = LeadObject;
        $scope.status = LeadStatus;
        $scope.dateChangeReasonID = $scope.status.reasons[0].id;
        $scope.requestData = {
            reason: $scope.dateChangeReasonID,
            comment: '',
            leadId: $scope.lead.id,
            status:$scope.status.id,
            data: {
                date:null
            }
        }
        $scope.updateStatus = function(){
            var requestFormatted = angular.copy($scope.requestData);
            requestFormatted.data.date = $scope.requestData.data.date.getFullYear()+"-"+($scope.requestData.data.date.getMonth() + 1) + '-' + $scope.requestData.data.date.getDate()+" 00:00:00";
            OperationService.updateLeadStatus(requestFormatted).then(function(response){
                window.location.href = baseUrl+"/lead/"+$scope.lead.id;
            });
        }
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
    }])
    .controller('AssignVendorModalCtrlWaste', ["$scope","$uibModalInstance","$http","VendorObject","OperationService","LeadObject",function($scope,$uibModalInstance, $http,VendorObject, OperationService, LeadObject) {
        $scope.vendor = VendorObject;
        $scope.lead = LeadObject;
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
        $scope.assignCareGiverToLead = function(){
            OperationService.assignCGToLead($scope.lead.id,$scope.vendor.id).then(function(response){
                $uibModalInstance.close(response);
                if(response.status){
                	window.location.href = baseUrl+"/lead/"+$scope.lead.id;

                }
            });
        }
    }])
    .controller('ViewVendorProfileModalCtrl', ["$scope","$uibModalInstance","$http","VendorObject","OperationService",function($scope,$uibModalInstance, $http,VendorObject, OperationService) {
        $scope.vendor = VendorObject;
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
    }])
    .controller('ChangeLeadStatusModalCtrl', ["$scope","$uibModalInstance","$http","LeadObject","OperationService",function($scope,$uibModalInstance, $http,LeadObject, OperationService) {
        $scope.employeeList = [];
        $scope.statusesGrouped = [];
        $scope.statusChangeObject = {
            leadId: LeadObject.id,
            status:null,
            reason:{

            }
        };

        $scope.statusReason = [];
        $scope.statusChange = function(){
            $scope.statusReason = [];
            
        }

        $scope.statusChangeObject.user;
        $scope.init = function(){
            OperationService.getEmployeeListToAssign().then(function(response){
                $scope.employeeList = response.data;
            });
            OperationService.getOperationalStausesGrouped().then(function(response){
                $scope.statusesGrouped = response.data;
            });
        }
        $scope.updateStatus = function(){
            OperationService.updateLeadStatus($scope.statusChangeObject).then(function(response){
                if(response.status){
                    $uibModalInstance.close(response.data);
                }else{
                    $uibModalInstance.close({});
                }

            });
        }
        $scope.init();
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
    }])
    .controller('ApproveLeadModalController', ["$scope","$uibModalInstance","$http","LeadObject","OperationService",function($scope,$uibModalInstance, $http,LeadObject, OperationService) {
        $scope.employeeList = [];
        $scope.statusesGrouped = [];
        $scope.statusChangeObject = {
            leadId: LeadObject.id,
            status: {
                id:null,
                reasons:[]
            },
            reason:{

            }
        };
        $scope.statusChangeObject.user;
        $scope.init = function(){
            OperationService.getEmployeeListToAssign().then(function(response){
                $scope.employeeList = response.data;
            });
            OperationService.getOperationalStausesGrouped().then(function(response){
                $scope.statusesGrouped = response.data;
            });
        }
        $scope.approveLead = function(){
            OperationService.approveLead($scope.statusChangeObject).then(function(response){
                if(response.status){
                    $uibModalInstance.close(response.data);
                }else{
                    $uibModalInstance.close({});
                }

            });
        }
        $scope.init();
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
    }])
    .controller('CarePlanCheckListModalController', ["$scope","$uibModalInstance","$http","LeadObject","OperationService","CarePlanGrid","CarePlanAction",function($scope,$uibModalInstance, $http,LeadObject, OperationService,CarePlanGrid, CarePlanAction) {
        $scope.lead = LeadObject;
        $scope.carePlanGrid = CarePlanGrid;
        $scope.carePlanAction = CarePlanAction;
        $scope.ailments = [];

        $scope.carePlanGridOptions = {  };
        $scope.carePlanGridOptions.columnDefs = [
            { name:'Task', field: 'taskInfo.label', width: 200 },
            { name:'Validation', field: 'validation' },
            { name:'Primary Sourcing', field: 'primarySourcing'},
            { name:'Backup Sourcing', field: 'backUpSourcing'},
            { name:'Primary CG Evaluation', field: 'primaryCGEvaluationByQc'},
            { name:'Backup CG Evaluation',field: 'backUpCGEvaluationByQc'},
            { name:'CG Training', field: 'cgTrainingDone'},
            { name:'Customer Signoff', field: 'finalEvaluation'}
        ];
        $scope.carePlanGridOptions.data = CarePlanGrid;






        $scope.init = function(){

        }
        $scope.updateCarePlan = function(){
            OperationService.x($scope.statusChangeObject).then(function(response){
                if(response.status){
                    $uibModalInstance.close(response.data);
                }else{
                    $uibModalInstance.close({});
                }

            });
        }
        $scope.init();
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
    }])
    .controller('ValidateLeadModalController', ["$scope","$uibModalInstance","$http","LeadObject","OperationService",function($scope,$uibModalInstance, $http,LeadObject, OperationService) {
        $scope.employeeList = [];
        $scope.statusesGrouped = [];
        $scope.statusChangeObject = {
            leadId: LeadObject.id
        };
        $scope.validationData = {
            ailment:{},
            leadId:LeadObject.id,
            tasks:[]
        }
        $scope.ailments = [];
        $scope.getValidationAilments = function(){

        }

        $scope.statusChangeObject.user;
        $scope.init = function(){
            OperationService.getEmployeeListToAssign().then(function(response){
                $scope.employeeList = response.data;
            });
            OperationService.getAilments().then(function(response){
                var allAilments = response.data;
                $scope.ailments = [];
                angular.forEach(allAilments, function(value){
                    if(value.validationRequired){
                        $scope.ailments.push(value);
                    }
                });
            });
        }
        $scope.updateStatus = function(){
            OperationService.updateLeadStatus($scope.statusChangeObject).then(function(response){
                if(response.status){
                    $uibModalInstance.close(response.data);
                }else{
                    $uibModalInstance.close({});
                }

            });
        }
        $scope.init();
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
    }])
    .controller('adminEnquiriesCtrl', ["$scope","$mdDialog","$http",function($scope,$mdDialog, $http) {
            'use strict';

            $scope.selected = [];
            $scope.limitOptions = [5, 10, 15];
            $scope.options = {
                rowSelection: true,
                multiSelect: false,
                autoSelect: true,
                decapitate: false,
                largeEditDialog: false,
                boundaryLinks: false,
                limitSelect: true,
                pageSelect: true
            };
            $scope.query = {
                order: 'name',
                limit: 5,
                page: 1
            };




            $scope.enquiries  = [];

            $scope.getEnquiries = function(){
                $http({
                    method: 'GET',
                    url: baseUrl+'/api/v1/enquiry/list/grid/data'
                }).then(function successCallback(response) {
                    $scope.enquiries.data = response.data.data;
                    $scope.enquiries.count = $scope.enquiries.data.length;
                    // this callback will be called asynchronously
                    // when the response is available
                }, function errorCallback(response) {
                    // called asynchronously if an error occurs
                    // or server returns response with an error status.
                });
            }
            $scope.init = function(){
                $scope.getEnquiries();
            }


            $scope.editComment = function (event, dessert) {
                event.stopPropagation(); // in case autoselect is enabled

                var editDialog = {
                    modelValue: dessert.comment,
                    placeholder: 'Add a comment',
                    save: function (input) {
                        if(input.$modelValue === 'Donald Trump') {
                            input.$invalid = true;
                            return $q.reject();
                        }
                        if(input.$modelValue === 'Bernie Sanders') {
                            return dessert.comment = 'FEEL THE BERN!'
                        }
                        dessert.comment = input.$modelValue;
                    },
                    targetEvent: event,
                    title: 'Add a comment',
                    validators: {
                        'md-maxlength': 30
                    }
                };

                var promise;

                if($scope.options.largeEditDialog) {
                    promise = $mdEditDialog.large(editDialog);
                } else {
                    promise = $mdEditDialog.small(editDialog);
                }

                promise.then(function (ctrl) {
                    var input = ctrl.getInput();

                    input.$viewChangeListeners.push(function () {
                        input.$setValidity('test', input.$modelValue !== 'test');
                    });
                });
            };

            $scope.toggleLimitOptions = function () {
                $scope.limitOptions = $scope.limitOptions ? undefined : [5, 10, 15];
            };

            $scope.getTypes = function () {
                return ['Candy', 'Ice cream', 'Other', 'Pastry'];
            };

            $scope.loadStuff = function () {
                $scope.promise = $timeout(function () {
                    // loading
                }, 2000);
            };

            $scope.logItem = function (item) {
                console.log(item.name, 'was selected');
            };

            $scope.logOrder = function (order) {
                console.log('order: ', order);
            };

            $scope.logPagination = function (page, limit) {
                console.log('page: ', page);
                console.log('limit: ', limit);
            };

        $scope.init();
    }])
    .controller('adminFieldCtrl', function($scope,$mdEditDialog) {
        'use strict';

        $scope.selected = [];
        $scope.limitOptions = [5, 10, 15];

        $scope.options = {
            rowSelection: true,
            multiSelect: true,
            autoSelect: true,
            decapitate: false,
            largeEditDialog: false,
            boundaryLinks: false,
            limitSelect: true,
            pageSelect: true
        };

        $scope.query = {
            order: 'name',
            limit: 5,
            page: 1
        };



        $scope.editComment = function (event, dessert) {
            event.stopPropagation(); // in case autoselect is enabled

            var editDialog = {
                modelValue: dessert.comment,
                placeholder: 'Add a comment',
                save: function (input) {
                    if(input.$modelValue === 'Donald Trump') {
                        input.$invalid = true;
                        return $q.reject();
                    }
                    if(input.$modelValue === 'Bernie Sanders') {
                        return dessert.comment = 'FEEL THE BERN!'
                    }
                    dessert.comment = input.$modelValue;
                },
                targetEvent: event,
                title: 'Add a comment',
                validators: {
                    'md-maxlength': 30
                }
            };

            var promise;

            if($scope.options.largeEditDialog) {
                promise = $mdEditDialog.large(editDialog);
            } else {
                promise = $mdEditDialog.small(editDialog);
            }

            promise.then(function (ctrl) {
                var input = ctrl.getInput();

                input.$viewChangeListeners.push(function () {
                    input.$setValidity('test', input.$modelValue !== 'test');
                });
            });
        };

        $scope.toggleLimitOptions = function () {
            $scope.limitOptions = $scope.limitOptions ? undefined : [5, 10, 15];
        };

        $scope.getTypes = function () {
            return ['Candy', 'Ice cream', 'Other', 'Pastry'];
        };

        $scope.loadStuff = function () {
            $scope.promise = $timeout(function () {
                // loading
            }, 2000);
        };

        $scope.logItem = function (item) {
            console.log(item.name, 'was selected');
        };

        $scope.logOrder = function (order) {
            console.log('order: ', order);
        };

        $scope.logPagination = function (page, limit) {
            console.log('page: ', page);
            console.log('limit: ', limit);
        }
    })
    .controller('adminOperationsCtrl', function($scope,$mdEditDialog) {
        'use strict';

        $scope.selected = [];
        $scope.limitOptions = [5, 10, 15];

        $scope.options = {
            rowSelection: true,
            multiSelect: true,
            autoSelect: true,
            decapitate: false,
            largeEditDialog: false,
            boundaryLinks: false,
            limitSelect: true,
            pageSelect: true
        };

        $scope.query = {
            order: 'name',
            limit: 5,
            page: 1
        };


        $scope.editComment = function (event, dessert) {
            event.stopPropagation(); // in case autoselect is enabled

            var editDialog = {
                modelValue: dessert.comment,
                placeholder: 'Add a comment',
                save: function (input) {
                    if(input.$modelValue === 'Donald Trump') {
                        input.$invalid = true;
                        return $q.reject();
                    }
                    if(input.$modelValue === 'Bernie Sanders') {
                        return dessert.comment = 'FEEL THE BERN!'
                    }
                    dessert.comment = input.$modelValue;
                },
                targetEvent: event,
                title: 'Add a comment',
                validators: {
                    'md-maxlength': 30
                }
            };

            var promise;

            if($scope.options.largeEditDialog) {
                promise = $mdEditDialog.large(editDialog);
            } else {
                promise = $mdEditDialog.small(editDialog);
            }

            promise.then(function (ctrl) {
                var input = ctrl.getInput();

                input.$viewChangeListeners.push(function () {
                    input.$setValidity('test', input.$modelValue !== 'test');
                });
            });
        };

        $scope.toggleLimitOptions = function () {
            $scope.limitOptions = $scope.limitOptions ? undefined : [5, 10, 15];
        };

        $scope.getTypes = function () {
            return ['Candy', 'Ice cream', 'Other', 'Pastry'];
        };

        $scope.loadStuff = function () {
            $scope.promise = $timeout(function () {
                // loading
            }, 2000);
        };

        $scope.logItem = function (item) {
            console.log(item.name, 'was selected');
        };

        $scope.logOrder = function (order) {
            console.log('order: ', order);
        };

        $scope.logPagination = function (page, limit) {
            console.log('page: ', page);
            console.log('limit: ', limit);
        }
    })


function NewLeadDetailedDialogController($scope, $mdDialog, $http, AdminService, ServiceId) {

    $scope.gotoTabNext = function(index){
        if( $scope.selectedTab==0){
            if($scope.basicDetails.name.$valid && $scope.basicDetails.phone.$valid && $scope.basicDetails.email.$valid) {
                $scope.selectedTab = index;
            }
        }
        if( $scope.selectedTab==1){
            if($scope.basicDetails.gender.$valid && $scope.basicDetails.cname.$valid && $scope.basicDetails.age.$valid && $scope.basicDetails.ailment.$valid) {
                $scope.selectedTab = index;
            }
        }
        if( $scope.selectedTab==2){
            if($scope.basicDetails.shift.$valid) {
                $scope.selectedTab = index;
            }
        }
        if( $scope.selectedTab==3){
            if($scope.basicDetails.duration.$valid && $scope.basicDetails.date.$valid) {
                $scope.selectedTab = index;
            }
        }
    }
    $scope.gotoTabPrev = function(index){
        $scope.selectedTab = index;
    }
    $scope.svgUrl = baseUrl+"/static/images/";  
    $scope.hide = function() {
        $mdDialog.hide();
    };
    $scope.cancel = function() {
        $mdDialog.cancel();
    };
    $scope.answer = function(answer) {
        $mdDialog.hide(answer);
    };
    $scope.references= [];
    $http({
        method: 'GET',
        url: baseUrl+'/api/v1/lead/references'
    }).then(function successCallback(response) {
        $scope.references = response.data.data;
        // this callback will be called asynchronously
        // when the response is available
    }, function errorCallback(response) {
        // called asynchronously if an error occurs
        // or server returns response with an error status.
    });
    $scope.shifts = [];
    $http({
        method: 'GET',
        url: baseUrl+'/api/v1/lead/shifts'
    }).then(function successCallback(response) {
        $scope.shifts = response.data.data;
        // this callback will be called asynchronously
        // when the response is available
    }, function errorCallback(response) {
        // called asynchronously if an error occurs
        // or server returns response with an error status.
    });

    $scope.ailments = [];
    $http({
        method: 'GET',
        url: baseUrl+'/api/v1/lead/ailments'
    }).then(function successCallback(response) {
        $scope.ailments = response.data.data;
        // this callback will be called asynchronously
        // when the response is available
    }, function errorCallback(response) {
        // called asynchronously if an error occurs
        // or server returns response with an error status.
    });

    $scope.equipments = [];
    $http({
        method: 'GET',
        url: baseUrl+'/api/v1/lead/equipments'
    }).then(function successCallback(response) {
        $scope.equipments = response.data.data;
        // this callback will be called asynchronously
        // when the response is available
    }, function errorCallback(response) {
        // called asynchronously if an error occurs
        // or server returns response with an error status.
    });

    $scope.languages = [];
    $http({
        method: 'GET',
        url: baseUrl+'/api/v1/lead/languages'
    }).then(function successCallback(response) {
        $scope.languages = response.data.data;
        // this callback will be called asynchronously
        // when the response is available
    }, function errorCallback(response) {
        // called asynchronously if an error occurs
        // or server returns response with an error status.
    });

    $scope.religions = [];
    $http({
        method: 'GET',
        url: baseUrl+'/api/v1/lead/religions'
    }).then(function successCallback(response) {
        $scope.religions = response.data.data;
        // this callback will be called asynchronously
        // when the response is available
    }, function errorCallback(response) {
        // called asynchronously if an error occurs
        // or server returns response with an error status.
    });


    $scope.careFinderData = {
        service: {
            id: ServiceId
        },
        enquiry: {
            name: '',
            email: '',
            phone: '',
            references: []
        },
        patientInfo: {
            name: '',
            gender:'',
            age: 0,
            weight: 0,
            ailment: [],
            equipmentSupport: false,
            equipments: []
        },
        location: '',
        shift: '',
        task:'',
        taskOther:'',
        request: {
            gender:false,
            genderRequired:'',
            religion:false,
            religionRequired: '',
            language: false,
            languageRequired: '',
            age:false,
            ageRequired:'',
            food:false,
            foodRequired:'',
            startDate: new Date(),
            duration: 1,
            locality: '',
            address: '',
            remark:''
        },
        enquiryId: null
    };
    $scope.addlead = function(ev){
        if(!$scope.basicDetails.$valid){
            return;
        }
        AdminService.submitAdminLead($scope.careFinderData).then(function(response){
            window.localStorage['Lead_Data_Submitted'] = response.data;
            $mdDialog.hide();
        });
    }
}

function NewLeadPsysioDialogController($scope, $mdDialog, $http, AdminService, ServiceId) {

    $scope.gotoTabNext = function(index){
        if($scope.selectedTab==0){
            if($scope.physioDetails.name.$valid && $scope.physioDetails.email.$valid && $scope.physioDetails.phone.$valid && $scope.physioDetails.location.$valid) {
                $scope.selectedTab = index;
            }
        }
        if($scope.selectedTab==1){
            if($scope.physioDetails.gender.$valid && $scope.physioDetails.pname.$valid && $scope.physioDetails.age.$valid && $scope.physioDetails.modalities.$valid) {
                $scope.selectedTab = index;
            }
        }
        if($scope.selectedTab==2){
            if($scope.physioDetails.start_date.$valid && $scope.physioDetails.closing_date.$valid && $scope.physioDetails.sessions.$valid && $scope.physioDetails.pricing.$valid && $scope.physioDetails.MOP.$valid) {
                $scope.selectedTab = index;
            }
        }
    }
    $scope.gotoTabPrev = function(index){
        $scope.selectedTab = index;
    }

    $scope.formNotice = function () {
        if (!$scope.physioDetails.$valid) {
            formNotice.show();
        }
    }

    $scope.svgUrl = baseUrl + "/static/images/";
    $scope.hide = function () {
        $mdDialog.hide();
    };
    $scope.cancel = function () {
        $mdDialog.cancel();
    };
    $scope.answer = function (answer) {
        $mdDialog.hide(answer);
    };
    $scope.references = [];
    $http({
        method: 'GET',
        url: baseUrl + '/api/v1/lead/references'
    }).then(function successCallback(response) {
        $scope.references = response.data.data;
        // this callback will be called asynchronously
        // when the response is available
    }, function errorCallback(response) {
        // called asynchronously if an error occurs
        // or server returns response with an error status.
    });
    $scope.conditions = [];
    $http({
        method: 'GET',
        url: baseUrl + '/api/v1/lead/mapped/data'
    }).then(function successCallback(response) {
        $scope.conditions = response.data.data.conditions;
        // this callback will be called asynchronously
        // when the response is available
    }, function errorCallback(response) {
        // called asynchronously if an error occurs
        // or server returns response with an error status.
    });
    $scope.complaints = [];
    $http({
        method: 'GET',
        url: baseUrl + '/api/v1/lead/mapped/data'
    }).then(function successCallback(response) {
        $scope.complaints = response.data.data.complaints;
        // this callback will be called asynchronously
        // when the response is available
    }, function errorCallback(response) {
        // called asynchronously if an error occurs
        // or server returns response with an error status.
    });
    $scope.ptconditions = [];
    $http({
        method: 'GET',
        url: baseUrl + '/api/v1/lead/mapped/data'
    }).then(function successCallback(response) {
        $scope.ptconditions = response.data.data.ptconditions;
        // this callback will be called asynchronously
        // when the response is available
    }, function errorCallback(response) {
        // called asynchronously if an error occurs
        // or server returns response with an error status.
    });
    $scope.modalities = [];
    $http({
        method: 'GET',
        url: baseUrl + '/api/v1/lead/mapped/data'
    }).then(function successCallback(response) {
        $scope.modalities = response.data.data.modalities;
        // this callback will be called asynchronously
        // when the response is available
    }, function errorCallback(response) {
        // called asynchronously if an error occurs
        // or server returns response with an error status.
    });

    $scope.careFinderData = {
        service: {
            id: ServiceId
        },
        enquiry: {
            name: '',
            email: '',
            phone: '',
            references: []
        },
        patientInfo: {
            name: '',
            gender: '',
            age: 0,
            weight: 0,
            ailment: [],
            equipmentSupport: false,
            equipments: []
        },
        physioInfo: {
            condition: '',
            present_condition: '',
            chief_conplaint: '',
            modalities: '',
            expected_closing_date: '',
            no_of_sessions: '',
            pricing: '',
            condition_id: ''
        },
        location: '',
        shift: '',
        task: '',
        taskOther: '',
        request: {
            gender: false,
            genderRequired: '',
            religion: false,
            religionRequired: '',
            language: false,
            languageRequired: '',
            age: false,
            ageRequired: '',
            food: false,
            foodRequired: '',
            startDate: new Date(),
            duration: 1,
            locality: '',
            address: '',
            remark: ''
        },
        enquiryId: null
    }

    $scope.getConditionFilteredByParentId = function (index) {
        var arr = [];
        for(var i =0 ;i<$scope.conditions.length; i++){
            if($scope.conditions[i].parent_id==index){
                arr.push($scope.conditions[i]);
            }
        }
        return arr;
    }
    $scope.getComplaintFilteredByParentId = function (index) {
        var arr = [];
        for(var i =0 ;i<$scope.complaints.length; i++){
            if($scope.complaints[i].parent_id==index){
                arr.push($scope.complaints[i]);
            }
        }
        return arr;
    }
    $scope.addlead = function (ev) {
        if (!$scope.physioDetails.$valid) {
            return;
        }
        if(angular.isDefined($scope.conditionLevelFour) && angular.isDefined($scope.conditionLevelFour.id) && $scope.conditionLevelFour != ""){
            $scope.careFinderData.physioInfo.condition_id=($scope.conditionLevelFour.id);
        }else if(angular.isDefined($scope.conditionLevelThree) && angular.isDefined($scope.conditionLevelThree.id) && $scope.conditionLevelThree != ""){
            $scope.careFinderData.physioInfo.condition_id=($scope.conditionLevelThree.id);
        }else if(angular.isDefined($scope.conditionLevelTwo) && angular.isDefined($scope.conditionLevelTwo.id) && $scope.conditionLevelTwo != ""){
            $scope.careFinderData.physioInfo.condition_id=($scope.conditionLevelTwo.id);
        }else if(angular.isDefined($scope.conditionLevelOne) && angular.isDefined($scope.conditionLevelOne.id) && $scope.conditionLevelOne != ""){
            $scope.careFinderData.physioInfo.condition_id=($scope.conditionLevelOne.id);
        }
        AdminService.submitAdminLead($scope.careFinderData).then(function (response) {
            window.localStorage['Lead_Data_Submitted'] = response.data;
            $mdDialog.hide();
        });
    }
}

function NewLeadOtherDialogController($scope, $mdDialog, AdminService, ServiceId) {
    $scope.svgUrl = baseUrl + "/static/images/";
    $scope.hide = function () {
        $mdDialog.hide();
    };
    $scope.cancel = function () {
        $mdDialog.cancel();
    };
    $scope.answer = function (answer) {
        $mdDialog.hide(answer);
    };

    $scope.careFinderData = {
        service: {
            id: ServiceId
        },
        enquiry: {
            name: '',
            email: '',
            phone: '',
            references: []
        },
        patientInfo: {
            name: '',
            gender: '',
            age: 0,
            weight: 0,
            ailment: [],
            equipmentSupport: false,
            equipments: ''
        },
        physioInfo: {
            condition: '',
            present_condition: '',
            chief_conplaint: '',
            modalities: '',
            expected_closing_date: '',
            no_of_sessions: '',
            pricing: '',
            condition_id: ''
        },
        location: '',
        shift: '',
        task: '',
        taskOther: '',
        request: {
            gender: false,
            genderRequired: '',
            religion: false,
            religionRequired: '',
            language: false,
            languageRequired: '',
            age: false,
            ageRequired: '',
            food: false,
            foodRequired: '',
            startDate: new Date(),
            duration: 1,
            locality: '',
            address: '',
            remark: ''
        },
        locality: '',
        address: '',
        enquiryId: null
    };
    $scope.addlead = function (ev) {
        if (!$scope.otherDetails.$valid) {
            return;
        }
        AdminService.submitAdminLead($scope.careFinderData).then(function (response) {
            window.localStorage['Lead_Data_Submitted'] = response.data;
            $mdDialog.hide();
        });
    }

    function NewUserAddDialogController($scope, $mdDialog) {
        $scope.svgUrl = baseUrl + "/static/images/";
        $scope.hide = function () {
            $mdDialog.hide();
        };
        $scope.cancel = function () {
            $mdDialog.cancel();
        };
        $scope.answer = function (answer) {
            $mdDialog.hide(answer);
        };
    }

    function NewEmpAddDialogController($scope, $mdDialog) {
        $scope.svgUrl = baseUrl + "/static/images/";
        $scope.hide = function () {
            $mdDialog.hide();
        };
        $scope.cancel = function () {
            $mdDialog.cancel();
        };
        $scope.answer = function (answer) {
            $mdDialog.hide(answer);
        };
    }
}

function viewComlaintDialogController($scope,$mdDialog,complaintId){
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