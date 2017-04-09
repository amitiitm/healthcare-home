angular.module('reportModule', ['ngMaterial','md.data.table','ui.bootstrap','ui.grid','ui.grid.exporter', 'ui.grid.expandable', 'ui.grid.selection', 'ui.grid.pinning','ngtimeago','uiGmapgoogle-maps','google.places','ngSanitize', 'ui.select','ngFileUpload','infrastructure.imageupload','admin.services','operation.services','report.services'])
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
    .controller('VendorAttendanceController', function($scope,$uibModal, OperationService,ReportService) {
        $scope.reportDate = new Date();
        $scope.reportDate.setDate($scope.reportDate.getDate() - 1);
        $scope.data = {};
        $scope.getAttendance  = function(){
            ReportService.getVendorAttendanceForDate($scope.reportDate).then(function(response){
               $scope.gridOptions.data = response.data;
            });
        }
        $scope.getLeadLink = function(id){
            return baseUrl+"/lead/"+id;
        }

        $scope.gridOptions = {
            enableSorting: true,
            enableFiltering: true,
            columnDefs: [
                { field: 'vendorAttendance.vendor.name', displayName:'CG Name'},
                { field: 'vendorAttendance.vendor.phone', displayName:'CG Phone'},
                { field: 'leadId', displayName:'Lead Id'},
                { field: 'customerName', displayName:'Customer Name', cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block" ><a ng-href="{{grid.appScope.getLeadLink(row.entity.leadId)}}">{{row.entity[col.field]}}</a></span></div>' },
                { field: 'customerVendorAttendance.isPresent', displayName:'Customer Attendance',cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block" ><span ng-show="row.entity.customerVendorAttendance.isPresent==1">Present</span><span ng-show="row.entity.customerVendorAttendance.isPresent==0">Absent</span><span ng-show="row.entity.customerVendorAttendance.isPresent==null">NA</span></span></div>'  },
                { field: 'customerVendorAttendance.isOnTime', displayName:'On Time',cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block" ><span ng-show="row.entity.customerVendorAttendance.isOnTime==1">Yes</span><span ng-show="row.entity.customerVendorAttendance.isOnTime==0">No</span><span ng-show="row.entity.customerVendorAttendance.isOnTime==null">NA</span></span></div>' },
                { field: 'customerVendorAttendance.isWellDressed', displayName:'Well Dressed',cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block" ><span ng-show="row.entity.customerVendorAttendance.isWellDressed==1">Yes</span><span ng-show="row.entity.customerVendorAttendance.isWellDressed==0">No</span><span ng-show="row.entity.customerVendorAttendance.isWellDressed==null">NA</span></span></div>'  },
                { field: 'vendorAttendance.attendance', displayName:'CG Attendance',cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block" ><span ng-show="row.entity.vendorAttendance.attendance==1">Present</span><span ng-show="row.entity.vendorAttendance.attendance==0">Absent</span><span ng-show="row.entity.vendorAttendance.attendance==null">NA <button class="btn btn-success btn-xs" ng-click="grid.appScope.markAttendance(row.entity.leadId,row.entity.vendorAttendance.vendor.assignee_user_id,1,0)" title="Present">P</button><button class="btn btn-danger btn-xs" ng-click="grid.appScope.markAttendance(row.entity.leadId,row.entity.vendorAttendance.vendor.assignee_user_id,0,1)" title="Uninformed Leave">A</button><button class="btn btn-primary btn-xs" ng-click="grid.appScope.markAttendance(row.entity.leadId,row.entity.vendorAttendance.vendor.assignee_user_id,0,0)" title="Informed Leave">L</button></span></span></div>' },
                { field: 'vendorAttendance.comment', displayName:'Remark' },
                { field: 'vendorAttendance.medium', displayName:'Medium' },
                { field: 'vendorIncentive.id', displayName:'Going Today',cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents display-block" ><span ng-show="row.entity.vendorIncentive.id!=null">Yes</span><span ng-show="row.entity.vendorIncentive.id==null">No</span></span></div>' },
                { field: 'id',displayName:'Action', enableSorting: false ,  cellTemplate: '<div class="text-center colt{{$index}}" class="text-center"><a class="btn btn-xs" ng-href="{{grid.appScope.getLeadLink(row.entity.leadId)}}" target="_blank">View</a></div>'}
            ],
            enableGridMenu: true,
            exporterCsvFilename: 'active_project_list_'+(new Date()).toISOString().slice(0,10).replace(/-/g,"")+'.csv',
            exporterMenuPdf: false,
            exporterCsvLinkElement: angular.element(document.querySelectorAll(".custom-csv-link-location")),
            onRegisterApi: function( gridApi ) {
                $scope.grid1Api = gridApi;
            }
        };

        $scope.markAttendance = function(leadId,cgId,attendance,uninformed){
            $scope.formData = {
                comment: '',
                caregiver: {
                    id: cgId
                },
                attendance: attendance,
                price:'',
                leadId: leadId,
                date: $scope.reportDate,
                assignCaregiver: '',
                uninformed: uninformed
            }
            
            $scope.requestData = angular.copy($scope.formData);

            OperationService.markCGAttendance($scope.requestData).then(function(response){
                //window.location.reload();
                $scope.getAttendance();
            });
        };
    })
    .controller('ActiveProjectController', function($scope,$uibModal, OperationService,ReportService) {
        $scope.employeeList = [];

        $scope.userToAssign = {
            user: null,
            leadId: $scope.leadId
        };

        $scope.gridOptions = {
            enableSorting: true,
            columnDefs: [
                { field: 'name', displayName:'Name' },
                { field: 'phone', displayName:'Contact No'},
                { field: 'email', displayName:'Email' },
                { field: 'location', displayName:'Location' },
                { field: 'refSource', displayName:'Reference' },
                { field: 'gender', displayName:'Patient Gender' },
                { field: 'ailments', displayName:'ailments' },
                { field: 'shiftRequired', displayName:'Shift Required' },
                { field: 'cgName', displayName:'CG Name' },
                { field: 'cgPhone', displayName:'CG Phone' },
            ],
            enableGridMenu: true,
            exporterCsvFilename: 'active_project_list_'+(new Date()).toISOString().slice(0,10).replace(/-/g,"")+'.csv',
            exporterMenuPdf: false,
            exporterCsvLinkElement: angular.element(document.querySelectorAll(".custom-csv-link-location")),
            onRegisterApi: function( gridApi ) {
                $scope.grid1Api = gridApi;
            }
        };

        $scope.viewEmployee = function(employee){
            window.location.href = baseUrl+'/admin/employee/'+employee.id;
        }
        $scope.init = function(){
            ReportService.getActiveProjectList().then(function(response){
                $scope.gridOptions.data = response.data;
            });
        }
        $scope.toggleEmployeeDetail = function(){
            $scope.showMoreEmployeeDetail = !$scope.showMoreEmployeeDetail;
        }

        $scope.init();
    })
    .controller('SalaryController', function($scope,$uibModal, OperationService,ReportService, $interval) {
        $scope.reportDateFrom = new Date();
        $scope.reportDateFrom.setDate($scope.reportDateFrom.getDate() - 31);
        $scope.reportDateTo = new Date();
        $scope.reportDateTo.setDate($scope.reportDateTo.getDate() - 1);

        $scope.showModes = ["CG","Customer"];
        $scope.selectedShowMode = "CG";

        $scope.getLeadLink = function(id){
            return baseUrl+"/lead/"+id;
        }
        //$scope.ngShowMode = "";

        $scope.data = {};
        $scope.getSalaryReport = function(){
            ReportService.getSalaryReportForPeriod($scope.reportDateFrom,$scope.reportDateTo,$scope.selectedShowMode).then(function(response){
               
                var data = response.data;

                if($scope.selectedShowMode == "CG"){
                    for(i = 0; i < data.length; i++){

                        var total = 0;
                        for(j = 0; j < data[i].leads.length; j++){
                            var curLead = data[i].leads[j];
                            total += ((curLead.presentDays*curLead.pricePerDay)+curLead.incentive)-(curLead.deduction);
                        }
                        data[i].total = total;

                        data[i].subGridOptions = {
                            columnDefs: [
                                {name:"Customer Name", cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block" ><a ng-href="'+baseUrl+'/lead/{{row.entity.leadInfo.leadId}}" target="_blank">{{ row.entity.leadInfo.customerName }}</a></span></div>'},
                                {name:"Price Per Day (Rs)", field:"pricePerDay"},
                                {name:"Working Days", field:"presentDays"},
                                {name:"Incentive (Rs)", field:"incentive"},
                                {name:"Deduction (Rs)", field:"deduction"},
                                {name:"Total (Rs)", cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block" >{{ ((row.entity.presentDays*row.entity.pricePerDay)+row.entity.incentive)-(row.entity.deduction) }}</a></span></div>'}
                            ],
                            data: data[i].leads
                        }
                    }

                    //$scope.ngShowMode = "CG";
                    
                } else if($scope.selectedShowMode == "Customer"){
                    //$scope.ngShowMode = "Customer";
                }

                //angular.element(document.getElementsByClassName('grid')[0]).css('width', $('#gridDiv').width()+'px');
                $scope.gridOptions.data = data; 
            });
        }

        $scope.gridOptions = {
            expandableRowTemplate: 'expandableRowTemplate.html',
            expandableRowHeight: 150,
            expandableRowScope: {
              subGridVariable: 'subGridScopeVariable'
            },
            enableSorting: true,
            enableFiltering: true,
            enableGridMenu: true,
            exporterCsvFilename: 'active_project_list_'+(new Date()).toISOString().slice(0,10).replace(/-/g,"")+'.csv',
            exporterMenuPdf: false,
            exporterCsvLinkElement: angular.element(document.querySelectorAll(".custom-csv-link-location")),
        }

        /*$scope.gridOptions.columnDefs = [
            { name: 'ID', field:"vendorId",width:300 },
            { name: 'Name', field:"vendorInfo.user.name",width:400},
            { name: 'Total Salary (Rs)', field:"total",width:400 }
        ];*/
        $scope.gridOptions.columnDefs = [
            { name: 'ID', field:"vendorId" },
            { name: 'Name', field:"vendorInfo.user.name"},
            { name: 'Total Salary (Rs)', field:"total"}
        ];

        $scope.gridOptions.onRegisterApi = function(gridApi){
          $scope.gridApi = gridApi;
        };

        /*$scope.expandAllRows = function() {
          $scope.gridApi.expandable.expandAllRows();
        }

        $scope.collapseAllRows = function() {
          $scope.gridApi.expandable.collapseAllRows();
        }*/

        $scope.gridOptionsCustomer = {
            expandableRowTemplate: 'expandableRowTemplate.html',
            expandableRowHeight: 150,
            expandableRowScope: {
              subGridVariable: 'subGridScopeVariable'
            },
            enableSorting: true,
            enableFiltering: true,
            enableGridMenu: true,
            exporterCsvFilename: 'active_project_list_'+(new Date()).toISOString().slice(0,10).replace(/-/g,"")+'.csv',
            exporterMenuPdf: false,
            exporterCsvLinkElement: angular.element(document.querySelectorAll(".custom-csv-link-location")),
        }

        $scope.gridOptionsCustomer.columnDefs = [
            { name: 'ID', field:"vendorId",width:300 },
            { name: 'Name', field:"vendorInfo.user.name",width:400},
            { name: 'Total Salary (Rs)', field:"total",width:400 }
        ];

        $scope.gridOptionsCustomer.onRegisterApi = function(gridApi){
          $scope.gridApiCustomer = gridApi;
        };
    });
