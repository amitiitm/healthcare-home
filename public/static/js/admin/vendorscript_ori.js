angular.module('vendorModule', ['ngMaterial','md.data.table','ui.bootstrap','ui.grid','ngtimeago','google.places','ngSanitize', 'ui.select','ngFileUpload','infrastructure.imageupload','admin.services','operation.services','vendor.services'])
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
    .controller('VendorAddController', ["$scope","$uibModal", "VendorService","OperationService", function ($scope, $uibModal,VendorService,OperationService) {
        $scope.mappedData = {};
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
            training_attended:'',

            validationData:{}
        }

        $scope.init =function () {
            OperationService.getCreateLeadDataMapped().then(function(response){
                $scope.mappedData = response.data;
            });
            OperationService.getTaskListForValidation().then(function(response){
                $scope.vendorData.validationData = response.data;
            });
        }
        $scope.init();
        $scope.validationData = {};
        $scope.selectTask = function(){
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'leadTaskModalTemplate.html',
                controller: 'vendorCreateTaskModalCtrl',
                size: 'md',
                resolve: {
                    ValidationData: function () {
                        return $scope.vendorData.validationData;
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
        $scope.vendorDataValidation = {
            "category": {
                "valid": true,
                "message": "Please provide vendor category"
            },
            "source": {
                "valid": true,
                "message": "Please provide vendor source"
            },
            "agency": {
                "valid": true,
                "message": "Source agency is required"
            },
            "name": {
                "valid": true,
                "message": "Vendor name is required"
            },
            "phone": {
                "valid": true,
                "message": "Phone number is required"
            },
            "address": {
                "valid": true,
                "message": "Address is required"
            },
            "locality": {
                "valid": true,
                "message": "Locality is required"
            },
            "zone": {
                "valid": true,
                "message": "Caregiver zone is required"
            },
            "age": {
                "valid": true,
                "message": "Age is required"
            },
            "gender": {
                "valid": true,
                "message": "Gender is required"
            },
            "readyToWorkWithMalePatient": {
                "valid": true,
                "message": "Gender is required"
            },
            "weight": {
                "valid": true,
                "message": "Weight is required"
            },
            "height": {
                "valid": true,
                "message": "Height is required"
            },
            "religion": {
                "valid": true,
                "message": "Religion is required"
            },
            "shift": {
                "valid": true,
                "message": "Preferred shift is required"
            },
            "task": {
                "valid": true,
                "message": "Task performed is required"
            },
            "havingSmartPhone": {
                "valid": true,
                "message": "Having smart phone is required"
            },
            "hasBankAccount": {
                "valid": true,
                "message": "Bank account detail is required"
            },
            "hasBankName": {
                "valid": true,
                "message": "Bank name is required"
            },
            "hasBankAccountNumber": {
                "valid": true,
                "message": "Bank account number is required"
            },
            "hasBankAccountHolderName": {
                "valid": true,
                "message": "Bank account holder name is required"
            },
            "hasBankIFSCCode": {
                "valid": true,
                "message": "Bank IFSC code is required"
            },
            "trainingAttended": {
                "valid": true,
                "message": "Training attended or not"
            }
        }

        $scope.validateVendorBeforeUpdate = function(){
            angular.forEach($scope.vendorDataValidation, function(value, key) {
                value.valid=true;
            });
            var valid = true;
            if($scope.vendorData.name==''){
                $scope.vendorDataValidation.name.valid=false;
                valid=false;
            }else{
                $scope.vendorDataValidation.name.valid=true;
            }
            if(!$scope.vendorData.category.id || $scope.vendorData.category.id==''){
                $scope.vendorDataValidation.category.valid=false;
                valid=false;
            }else{
                $scope.vendorDataValidation.category.valid=true;

            }
            if(!$scope.vendorData.source.id || $scope.vendorData.source.id==''){
                $scope.vendorDataValidation.source.valid=false;
                valid=false;
            }else{
                $scope.vendorDataValidation.source.valid=true;
                if($scope.vendorData.source.name =='Agency'){
                    if(!$scope.vendorData.agency.id || $scope.vendorData.agency.id==''){
                        $scope.vendorDataValidation.agency.valid=false;
                        valid=false;
                    }else{
                        $scope.vendorDataValidation.agency.valid=true;
                        valid=false;
                    }
                }else{
                    $scope.vendorDataValidation.agency.valid=true;
                    valid=false;
                }
            }
            if($scope.vendorData.phone==''){
                $scope.vendorDataValidation.phone.valid=false;
                valid=false;
            }else{
                $scope.vendorDataValidation.phone.valid=true;
            }
            if($scope.vendorData.address==''){
                $scope.vendorDataValidation.address.valid=false;
                valid=false;
            }else{
                $scope.vendorDataValidation.address.valid=true;
            }
            if($scope.vendorData.locality==''){
                $scope.vendorDataValidation.locality.valid=false;
                valid=false;
            }else{
                $scope.vendorDataValidation.locality.valid=true;
            }
            if($scope.vendorData.age==''){
                $scope.vendorDataValidation.age.valid=false;
                valid=false;
            }else{
                $scope.vendorDataValidation.age.valid=true;
            }
            if($scope.vendorData.height==''){
                $scope.vendorDataValidation.height.valid=false;
                valid=false;
            }else{
                $scope.vendorDataValidation.height.valid=true;
            }
            if($scope.vendorData.weight==''){
                $scope.vendorDataValidation.weight.valid=false;
                valid=false;
            }else{
                $scope.vendorDataValidation.weight.valid=true;
            }
            if(!$scope.vendorData.gender.id || $scope.vendorData.gender.id==''){
                $scope.vendorDataValidation.gender.valid=false;
                valid=false;
            }else{
                $scope.vendorDataValidation.gender.valid=true;
            }
            if(!$scope.vendorData.zone.id || $scope.vendorData.zone.id==''){
                $scope.vendorDataValidation.zone.valid=false;
                valid=false;
            }else{
                $scope.vendorDataValidation.zone.valid=true;
            }
            if(!$scope.vendorData.religion.id || $scope.vendorData.religion.id==''){
                $scope.vendorDataValidation.religion.valid=false;
                valid=false;
            }else{
                $scope.vendorDataValidation.religion.valid=true;
            }
            if(!$scope.vendorData.preferred_shift.id || $scope.vendorData.preferred_shift.id==''){
                $scope.vendorDataValidation.shift.valid=false;
                valid=false;
            }else{
                $scope.vendorDataValidation.shift.valid=true;
            }
            if(!$scope.vendorData.preferred_shift.id || $scope.vendorData.preferred_shift.id==''){
                $scope.vendorDataValidation.shift.valid=false;
                valid=false;
            }else{
                $scope.vendorDataValidation.shift.valid=true;
            }

            // task validation
            var taskSelected = [];
            for(var i=0; i<$scope.vendorData.validationData.length;i++){
                for(var j=0;j<($scope.vendorData.validationData[i].tasks).length;j++){
                    if($scope.vendorData.validationData[i].tasks[j].selected==true){
                        taskSelected.push($scope.vendorData.validationData[i].tasks[j]);
                        break;
                    }
                }
            }
            if(taskSelected.length==0){
                $scope.vendorDataValidation.task.valid=false;
                valid=false;
            }else{
                $scope.vendorDataValidation.task.valid=true;
            }


            if($scope.vendorData.has_bank_account===''){
                $scope.vendorDataValidation.hasBankAccount.valid=false;
                valid=false;
            }else{
                $scope.vendorDataValidation.hasBankAccount.valid=true;
                if($scope.vendorData.has_bank_account===true){
                    if($scope.vendorData.bank_account.bankName==''){
                        $scope.vendorDataValidation.hasBankName.valid=false;
                        valid=false;
                    }else{
                        $scope.vendorDataValidation.hasBankName.valid=true;
                    }
                    if($scope.vendorData.bank_account.name==''){
                        $scope.vendorDataValidation.hasBankAccountHolderName.valid=false;
                        valid=false;
                    }else{
                        $scope.vendorDataValidation.hasBankAccountHolderName.valid=true;
                    }
                    if($scope.vendorData.bank_account.accountNo==''){
                        $scope.vendorDataValidation.hasBankAccountNumber.valid=false;
                        valid=false;
                    }else{
                        $scope.vendorDataValidation.hasBankAccountNumber.valid=true;
                    }
                    if($scope.vendorData.bank_account.ifsc==''){
                        $scope.vendorDataValidation.hasBankIFSCCode.valid=false;
                        valid=false;
                    }else{
                        $scope.vendorDataValidation.hasBankIFSCCode.valid=true;
                    }
                }else{
                    $scope.vendorDataValidation.hasBankName.valid=true;
                    $scope.vendorDataValidation.hasBankAccountHolderName.valid=true;
                    $scope.vendorDataValidation.hasBankAccountNumber.valid=true;
                    $scope.vendorDataValidation.hasBankIFSCCode.valid=true;
                }
            }
            if($scope.vendorData.has_smart_phone===''){
                $scope.vendorDataValidation.havingSmartPhone.valid=false;
                valid=false;
            }else{
                $scope.vendorDataValidation.havingSmartPhone.valid=true;
            }
            if($scope.vendorData.training_attended===''){
                $scope.vendorDataValidation.trainingAttended.valid=false;
                valid=false;
            }else{
                $scope.vendorDataValidation.trainingAttended.valid=true;
            }

            return valid;
        }
        $scope.firstTimePosted = false;
        $scope.addVendor= function () {

            var valid = $scope.validateVendorBeforeUpdate();
            $scope.firstTimePosted = true;
            if(!valid){
                return;
            }


            VendorService.submitVendor($scope.vendorData).then(function(response){
                if(response.status && response.data.id){
                    window.location.href = baseUrl+"/vendor/"+response.data.id;
                }else{
                    // TODO: error
                }
            });
        }
        $scope.$watch('vendorData',function(newValue,oldValue){
            if($scope.firstTimePosted){
                $scope.validateVendorBeforeUpdate();
            }

        }, true);
    }])
    .controller('vendorCreateTaskModalCtrl', ["$scope","$uibModalInstance","$http","OperationService","ValidationData",function($scope,$uibModalInstance,$http,OperationService,ValidationData) {
        $scope.validationData = ValidationData;
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
        $scope.updateTask = function(){
            $uibModalInstance.close();
        }
    }])
    .controller('VendorEditController', ["$scope", "VendorService", function ($scope, VendorService) {
        $scope.vendorData = {
            id:'',
            name:'',
            email:'',
            phone:'',
            gender:'',
            alternate_no:'',
            age:'',
            weight:'',
            height:'',
            religion_id:'',
            address:'',
            locality_id:'',
            qualification_id:'',
            experience:'',
            preferred_shift_id:'',
            location_of_work:'',
            employee_category_id:'',
            agency_id:'',
            has_smart_phone:'',
            has_bank_account:'',
            food_type_id:'',
            training_attended:'',
            level:'',
            is_admin:'',
            is_vendor:'',
            is_customer:'',
            user_type_id:''
        }

        $scope.init =function () {
            VendorService.getVendorDetail(vendorId).then(function (response) {
                $scope.vendorData = response.data;
            });
        }
        $scope.init();

        $scope.addVendor= function () {

            VendorService.submitVendor($scope.vendorData).then(function(response){
                if(response.status && response.data.id){
                    window.location.href = baseUrl+"/vendor/"+response.data.id;
                }else{
                    // TODO: error
                }
            });
        }

        $scope.vendorDataValidation = {
            "Name": {
                "valid": true,
                "message": "Please provide customer name"
            },
            "Phone": {
                "valid": true,
                "message": "Please provide phone number"
            },
            "Age": {
                "valid": true,
                "message": "Costomer locality is required"
            },
            "Weight": {
                "valid": true,
                "message": "Costomer locality is required"
            },
            "Height": {
                "valid": true,
                "message": "Costomer locality is required"
            }
        }

        $scope.editVendorDialog = function (ev, vendorId) {
            $mdDialog.show({
                controller: VendorDetailController,
                templateUrl: baseUrl+'/static/js/admin/templates/vendorInfoEdit.tmpl.html',
                locals:{
                    VendorId: vendorId
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
    }])
    .controller('VendorViewController', function($scope,$uibModal, UserService, OperationService,VendorService) {
        $scope.vendorData = {
            id:'',
            name:'',
            email:'',
            phone:'',
            gender:'',
            alternate_no:'',
            age:'',
            weight:'',
            height:'',
            religion_id:'',
            address:'',
            locality_id:'',
            qualification_id:'',
            experience:'',
            preferred_shift_id:'',
            location_of_work:'',
            employee_category_id:'',
            agency_id:'',
            has_smart_phone:'',
            has_bank_account:'',
            food_type_id:'',
            training_attended:'',
            level:'',
            is_admin:'',
            is_vendor:'',
            is_customer:'',
            user_type_id:''
        }

        $scope.init =function () {
            VendorService.getVendorDetail(vendorId).then(function (response) {
                $scope.vendorData = response.data;
            });
        }
        $scope.init();

        $scope.addVendor= function () {

            VendorService.submitVendor($scope.vendorData).then(function(response){
                if(response.status && response.data.id){
                    window.location.href = baseUrl+"/vendor/"+response.data.id;
                }else{
                    // TODO: error
                }
            });
        }

        $scope.vendorDataValidation = {
            "Name": {
                "valid": true,
                "message": "Please provide customer name"
            },
            "Phone": {
                "valid": true,
                "message": "Please provide phone number"
            },
            "Age": {
                "valid": true,
                "message": "Costomer locality is required"
            },
            "Weight": {
                "valid": true,
                "message": "Costomer locality is required"
            },
            "Height": {
                "valid": true,
                "message": "Costomer locality is required"
            }
        }

        $scope.editVendorDialog = function (ev, vendorId) {
            $mdDialog.show({
                controller: VendorDetailController,
                templateUrl: baseUrl+'/static/js/admin/templates/vendorInfoEdit.tmpl.html',
                locals:{
                    VendorId: vendorId
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

    })
    .controller('VendorListController', function($scope,$uibModal, UserService, OperationService,VendorService) {
        $scope.employeeList = [];

        $scope.userToAssign = {
            user:null,
            leadId: $scope.leadId
        };

        $scope.gridOptions = {
            enableSorting: true,
            columnDefs: [
                { field: 'name', displayName:'Name' },
                { field: 'mobile', displayName:'Mobile'},
                { field: 'age', displayName:'Age' },
                { field: 'religion.label', displayName:'Religion' },
                { field: 'preferredShift.label', displayName:'Preferred Shift' },
                { field: 'id',displayName:'', enableSorting: false ,  cellTemplate: '<div class="text-center colt{{$index}}" class="text-center"><a class="btn btn-xs" ng-click="grid.appScope.viewVendor(row.entity)">View</button></div>'}
            ],
            onRegisterApi: function( gridApi ) {
                $scope.grid1Api = gridApi;
            }
        };
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

        $scope.createNewVendor = function(ev){
            $mdDialog.show({
                controller: NewVendorDialogController,
                templateUrl: baseUrl+'/static/js/admin/templates/newVendorAddDialog.tmpl.html',
                parent: angular.element(document.body),
                targetEvent: ev,
                clickOutsideToClose:true
            })
            .then(function(answer) {
                VendorService.getVendorGridList().then(function(response){
                    $scope.gridOptions.data = response.data;
                });
            }, function() {

            });
        }

        $scope.viewVendor = function(vendor){
            window.location.href = baseUrl+'/vendor/'+vendor.id;
        }
        $scope.init = function(){
            OperationService.getVendorListToAssign($scope.leadId).then(function(response){
                $scope.gridOptions.data = response.data;
            });
        }
        $scope.toggleEmployeeDetail = function(){
            $scope.showMoreEmployeeDetail = !$scope.showMoreEmployeeDetail;
        }

        $scope.init();
    });


function NewVendorDialogController($scope,$mdDialog,VendorService){
    $scope.vendorData = {
        name:'',
        email:'',
        phone:''
    };
    $scope.message = {
        show: false,
        message: "Error"
    }
    $scope.cancel = function() {
        $mdDialog.cancel();
    };
    $scope.submitAddVendor = function(){
        $scope.message.show = false;
        angular.forEach($scope.addVendor.$error.required, function(field) {
            field.$setTouched();
        });
        if($scope.addVendor.$invalid){
            return;
        }

        VendorService.addVendor($scope.vendorData).then(function(response){
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
