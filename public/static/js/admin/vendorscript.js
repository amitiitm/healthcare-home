angular.module('vendorModule', ['ngMaterial','md.data.table','ui.bootstrap','ui.grid','ui.grid.pagination','ui.grid.selection','ui.grid.exporter','ngtimeago','google.places','ngSanitize', 'ui.select','ngFileUpload','infrastructure.imageupload','admin.services','operation.services','vendor.services'])
    .config(function($mdThemingProvider) {
        $mdThemingProvider.theme('default')
            .primaryPalette('teal')
            .accentPalette('green');
    })
    .filter('carbondate', ["$filter",function ($filter) {
        return function(carbonDate) {
            if(carbonDate==null || carbonDate=='' ||  angular.isUndefined(carbonDate)){
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
    .controller('VendorAddController', ["$scope","$uibModal", "VendorService","OperationService", function ($scope, $uibModal,VendorService,OperationService) {
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

        $scope.init =function () {
            OperationService.getCreateLeadDataMapped().then(function(response){
                $scope.mappedData = response.data;
            });
            OperationService.getTaskListForValidation().then(function(response){
                $scope.vendorData.validationData = response.data;
            });
            OperationService.trainingNotAttendedReasons().then(function(response){
                $scope.trainingNotAttendedReason = response.data;
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
        $scope.vendorDocuments = [];
        $scope.uploadDocument = function(){
            console.log('uplaodDocument');
            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'vendorDocumentModalTemplate.html',
                controller: 'UploadDocumentModalCtrl',
                size: 'md',
                resolve: {
                    VendorDocumentTypeList: function () {
                        return $scope.vendorDocumentTypes;
                    },
                    Vendor:function(){
                        return {}
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                if(responseData){
                    $scope.vendorDocuments.push({
                        'document':responseData,
                        'selected':true
                    })
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
            },
            "voter": {
                "valid": true,
                "message": "Voter id is required"
            },
            "aadhar": {
                "valid": true,
                "message": "AAdhaar id is required and must be 12 digit long"
            }
        }

        $scope.validateVendorBeforeUpdate = function(){
            angular.forEach($scope.vendorDataValidation, function(value) {
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

                    }
                }else{
                    $scope.vendorDataValidation.agency.valid=true;

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
                //valid=false;
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
                        /*
             if($scope.vendorData.voter==''&& $scope.vendorData.aadhar==''){
             $scope.vendorDataValidation.voter.valid=false;
             valid=false;
             $scope.vendorDataValidation.aadhar.valid=false;
             valid=false;
             }*/
            /*else{
                $scope.vendorDataValidation.voter.valid=true;
                $scope.vendorDataValidation.aadhar.valid=true;
            }
            if($scope.vendorData.aadhar!='' && $scope.vendorData.aadhar.length!=12){
                $scope.vendorDataValidation.aadhar.valid=false;
            }*/
            //task validation
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

        $scope.uploadVendorDocument = function(){

        }
        $scope.addVendor= function () {

            var valid = $scope.validateVendorBeforeUpdate();
            $scope.firstTimePosted = true;
            if(!valid){
                return;
            }

            // getting all documents
            $scope.vendorData.documents = [];
            if($scope.vendorDocuments.length>0){
                for(var i=0;i<$scope.vendorDocuments.length;i++){
                    $scope.vendorData.documents.push($scope.vendorDocuments[i].document.id);
                }
            }

            VendorService.submitVendor($scope.vendorData).then(function(response){
                if(response.status && response.data.id){
                    window.location.href = baseUrl+"/vendor/"+response.data.user_id;
                }else{
                    $scope.message.type = response.type;
                    $scope.message.show = true;
                    $scope.message.body = response.message;
                }
            });
        }
        $scope.$watch('vendorData',function(newValue,oldValue){
            if($scope.firstTimePosted){
                $scope.validateVendorBeforeUpdate();
            }

        }, true);
        $scope.vendorDocumentTypes = [];
        VendorService.getVendorDocumentTypes().then(function(response){
            if(response.status){
                $scope.vendorDocumentTypes = response.data;
            }

        });
    }])
    .controller('vendorAvailabilityModalCtrl', ["$scope","$uibModalInstance","$http","VendorService","OperationService","VendorData",function($scope,$uibModalInstance,$http,VendorService,OperationService,VendorData) {
        $scope.vendorData = VendorData;
        $scope.availabilityOptions = [];
        $scope.availability = {
            available: 1,
            vendorId: $scope.vendorData.id,
            option:{},
            reason:{},
            date: new Date(),
            otherReason:'',
            shift:{},
            location:{}

        }
        $scope.dataMapper = [];
        $scope.init = function(){
            VendorService.getVendorAvailabilityOptions().then(function(response){
                $scope.availabilityOptions = response.data;
            });
            VendorService.getVendorAvailabilityMapper().then(function(response){
                $scope.dataMapper = response.data;
            });
        }
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
        $scope.available = true;
        $scope.updateAvailability = function(){
            /// TODO: call to save availability data
            $uibModalInstance.close();
        }
        $scope.updateVendorLocality = function(){
            VendorService.updateVendorAvailability($scope.vendorData.id, $scope.availability).then(function(response){
                if(response.status){
                    $uibModalInstance.close(response);
                    window.location.reload();
                }
            });
        }
        $scope.init();
        $scope.$watch('availability.available',function(newValue,oldValue){
            if(oldValue!=newValue){
                $scope.availability.option = {};
                $scope.availability.reason = {};
                $scope.availability.otherReason = "";
            }
        })
        $scope.onChangeAvailability = function(oldParam, param){
            if(param!=oldParam){
                console.log("availability changed");
                $scope.availability.option = {};
                $scope.availability.reason = {};

            }

        }
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
    .controller('VendorEditController', ["$scope","$timeout","$uibModal", "VendorService","OperationService", function ($scope,$timeout,$uibModal, VendorService, OperationService) {
        $scope.mappedData = {};
        $scope.trainingNotAttendedReason = [];
        $scope.vendorDocumentTypes = [];


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
            userId:'',
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
            task:{},
            bank_account: {
                name:'',
                accountNo:'',
                bankName:'',
                ifsc:''
            },
            has_smart_phone:'',
            has_bank_account:'',
            training_attended:'',
            voter:'',
            aadhar:'',

            validationData:{}
        }



        $scope.vendorDocuments = [];
        $scope.uploadDocument = function(){
            console.log('uplaodDocument');
            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'vendorDocumentModalTemplate.html',
                controller: 'UploadDocumentModalCtrl',
                size: 'md',
                resolve: {
                    VendorDocumentTypeList: function () {
                        return $scope.vendorDocumentTypes;
                    },
                    Vendor: function(){
                        return $scope.vendorData;
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                if(responseData){
                    $scope.vendorDocuments.push({
                        'document':responseData,
                        'selected':true
                    })
                }
            }, function () {

            });
        }
        $scope.deleteDocument = function(vendorDocument){
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'deleteVendorDocumentModalTemplate.html',
                controller: 'DeleteVendorDocumentModalCtrl',
                size: 'md',
                resolve: {
                    VendorDocument: function () {
                        return vendorDocument.document;
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                if(responseData.status){
                    vendorDocument.deleted = true;
                }
            }, function () {

            });
        }
        $scope.init =function () {
            $scope.message = {
                body:'',
                type:'',
                show:false
            }
            VendorService.getVendorDetail(vendorId).then(function (response) {
                $scope.vendorData = response.data;

                // todo :
                $scope.vendorData.trainingDate = new Date($scope.vendorData.trainingDate);
                // convert document into formatted way
                $scope.vendorDocuments = [];
                for(var i=0; i<$scope.vendorData.documents.length; i++){
                    $scope.vendorDocuments.push({
                        'document': $scope.vendorData.documents[i],
                        'selected':true,
                        'deleted':false
                    })
                }
                VendorService.getTaskListForVendor(vendorId).then(function(response){
                    $scope.vendorData.validationData = response.data;
                });

            });


            OperationService.getCreateLeadDataMapped().then(function(response){
                $scope.mappedData = response.data;
            });

            OperationService.trainingNotAttendedReasons().then(function(response){
                $scope.trainingNotAttendedReason = response.data;
            });
            VendorService.getVendorDocumentTypes().then(function(response){
                if(response.status){
                    $scope.vendorDocumentTypes = response.data;
                }

            });
        }
        $scope.init();
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

        $scope.validationData = {};

        $scope.range = function(min, max, step) {
            step = step || 1;
            var input = [];
            for (var i = min; i <= max; i += step) {
                input.push(i);
            }
            return input;
        };

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
            },
            "voter": {
                "valid": true,
                "message": "Voter id is required"
            },
            "aadhar": {
                "valid": true,
                "message": "AAdhaar id is required and must be 12 digit long"
            },
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
            if($scope.vendorData.source==null || !$scope.vendorData.source.id || $scope.vendorData.source.id==''){
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

                    }
                }else{
                    $scope.vendorDataValidation.agency.valid=true;

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
                //valid=false;
            }else{
                $scope.vendorDataValidation.address.valid=true;
            }
            if($scope.vendorData.locality==''){
                $scope.vendorDataValidation.locality.valid=false;
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
            if(!$scope.vendorData.gender || $scope.vendorData.gender==''){
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
            if($scope.vendorData.religion == null || !$scope.vendorData.religion.id || $scope.vendorData.religion.id==''){
                $scope.vendorDataValidation.religion.valid=false;
                valid=false;
            }else{
                $scope.vendorDataValidation.religion.valid=true;
            }
            if(!$scope.vendorData.shift || $scope.vendorData.shift==''){
                $scope.vendorDataValidation.shift.valid=false;
                valid=false;
            }else{
                $scope.vendorDataValidation.shift.valid=true;
            }

            if($scope.vendorData.voter==''&& $scope.vendorData.aadhar==''){
                $scope.vendorDataValidation.voter.valid=false;
                valid=false;
                $scope.vendorDataValidation.aadhar.valid=false;
                valid=false;
            }
            else{
                $scope.vendorDataValidation.voter.valid=true;
                $scope.vendorDataValidation.aadhar.valid=true;
            }
            if($scope.vendorData.aadhar == null || $scope.vendorData.aadhar=='' || $scope.vendorData.aadhar.length!=12){
                $scope.vendorDataValidation.aadhar.valid=false;
            }
            // task validation
            // var taskSelected = [];
            // for(var i=0; i<$scope.vendorData.validationData.length;i++){
            //     for(var j=0;j<($scope.vendorData.validationData[i].tasks).length;j++){
            //         if($scope.vendorData.validationData[i].tasks[j].selected==true){
            //             taskSelected.push($scope.vendorData.validationData[i].tasks[j]);
            //             break;
            //         }
            //     }
            // }
            // if(taskSelected.length==0){
            //     $scope.vendorDataValidation.task.valid=false;
            //     valid=false;
            // }else{
            //     $scope.vendorDataValidation.task.valid=true;
            // }


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
        $scope.updateVendor= function () {

            var valid = $scope.validateVendorBeforeUpdate();
            $scope.firstTimePosted = true;
            if (!valid) {
                return;
            }
            VendorService.updateVendor($scope.vendorData).then(function (response) {
                if (response.status && response.data.id) {
                    $scope.message.body = response.message;
                    $scope.message.type = response.type;
                    $scope.message.show = true;
                    $scope.message.timeout = $timeout(function () {
                        $scope.message.show = false;
                    }, 3000);
                } else {
                    $scope.message.type = response.type;
                    $scope.message.body = response.message;
                    $scope.message.show = true;
                    $timeout(function () {
                        $scope.message.show = false;
                    }, 3000);
                }
            });
        }

        $scope.$watch('vendorData',function(newValue,oldValue){
            if($scope.firstTimePosted){
                $scope.validateVendorBeforeUpdate();
            }

        }, true);

        $scope.range = function(min, max, step) {
            step = step || 1;
            var input = [];
            for (var i = min; i <= max; i += step) {
                input.push(i);
            }
            return input;
        };


    }])
    .controller('VendorViewController', function($scope, $uibModal, OperationService,VendorService) {

        $scope.init =function () {
            VendorService.getVendorDetail(vendorId).then(function (response) {
                $scope.vendorData = response.data;
            });
        }
        $scope.init();
        $scope.editVendorAvailability = function(){
            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'vendorAvailabilityModalTemplate.html',
                controller: 'vendorAvailabilityModalCtrl',
                size: 'md',
                resolve: {
                    VendorData: function(){
                        return $scope.vendorData;
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

        /*$scope.addVendor= function () {

            VendorService.submitVendor($scope.vendorData).then(function(response){
                if(response.status && response.data.id){
                    //  window.location.href = baseUrl+"/vendor/"+response.data.id;
                }else{
                    // TODO: error
                }
            });
        }*/

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

        $scope.deleteVendor = function(){
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'deleteVendorModalTemplate.html',
                controller: 'DeleteVendorModalCtrl',
                size: 'md',
                resolve: {
                    VendorObject: function () {
                        return $scope.vendorData;
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                window.location.href = baseUrl+'/admin/caregivers'
            }, function () {

            });
        }

    })
    .controller('DeleteVendorModalCtrl', ["$scope","$uibModalInstance","$http","VendorService","VendorObject",function($scope,$uibModalInstance, $http, VendorService, VendorObject) {

        $scope.vendorData = VendorObject;
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
        $scope.deleteVendor = function(){
            VendorService.deleteVendor($scope.vendorData).then(function(response){
                $uibModalInstance.close(response);
                if(response.status){

                }
            });
        }

    }])
    .controller('DeleteVendorDocumentModalCtrl', ["$scope","$uibModalInstance","$http","VendorService","VendorDocument",function($scope,$uibModalInstance, $http, VendorService, VendorDocument) {

        $scope.vendorDocument = VendorDocument;
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
        $scope.deleteDocument = function(){
            VendorService.deleteVendorDocument($scope.vendorDocument.id).then(function(response){
                $uibModalInstance.close(response);
                if(response.status){
                    $scope.vendorDocument.deleted = true;
                }
            });
        }

    }])
    .controller('UploadDocumentModalCtrl', ["$scope","$uibModalInstance","$http", 'Upload', '$timeout',"VendorService","VendorDocumentTypeList","Vendor",function($scope,$uibModalInstance,$http, Upload, $timeout,VendorService,VendorDocumentTypeList,Vendor) {
        $scope.documentTypeList = VendorDocumentTypeList;
        $scope.vendor = Vendor;

        $scope.vendorDocument ={
            "type":{},
            'caption':'',
            'vendor_id':null
        }
        if(angular.isDefined($scope.vendor) && angular.isDefined($scope.vendor.id)){
            $scope.vendorDocument.vendor_id = $scope.vendor.id
        }
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
        $scope.uploadfile  = function(){
            var urlToUse = baseUrl+'/api/v1/vendor/operation/document/upload';
            var file = $scope.inputfile;
            var fd = new FormData();
            fd.append('file', file.file);
            fd.append('data',JSON.stringify($scope.vendorDocument))

            $scope.updating = true;
            $http.post(urlToUse, fd, {
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined}
            })
                .success(function(response){
                    $uibModalInstance.close(response.data);
                    $scope.updating = false;
                })
                .error(function(){
                    $uibModalInstance.dismiss('cancel');
                    $scope.updating = false;
                });
            return;
        }


    }])
    .controller('VendorListController', function($scope,$uibModal,uiGridConstants, OperationService,VendorService) {
        $scope.employeeList = [];

        $scope.userToAssign = {
            user:null,
            leadId: $scope.leadId
        };

        $scope.getVendorLink = function(id){
            return baseUrl+"/vendor/"+id;
        }
        var deploymentType = [
            { value: 1, label: 'Deployed' },
            { value: 0, label: 'Not Deployed'}
        ];
        var genderType = [
            { value: 'Male', label: 'Male' },
            { value: 'Female', label: 'Female'}
        ];
        var flaggedType = [
            { value: 1, label: 'Yes' },
            { value: 0, label: 'No'}
        ];
        var paginationOptions = {
            pageNumber: 1,
            pageSize: 50,
            sort: null
        };
        $scope.gridOptions = {
            enableSorting: true,
            enableRowSelection: true,
            enableSelectAll: true,
            columnDefs: [
                { field: 'name', displayName:'Names',  cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block"><a ng-href="{{grid.appScope.getVendorLink(row.entity.id)}}">{{row.entity[col.field]}}</a></span></div>' },
                { field: 'mobile', displayName:'Mobile', cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents grid-clickable display-block"><a ng-href="{{grid.appScope.getVendorLink(row.entity.id)}}">{{row.entity[col.field]}}</a></span></div>'},
                { field: 'age', displayName:'Age' },
                { field: 'gender', displayName:'Gender'},
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
                    cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents display-block"><span ng-show="row.entity.isFlagged==1">Yes</span><span ng-show="row.entity.isFlagged!=1">No</span> <button class="btn btn-success btn-xs" ng-click="grid.appScope.changeFlag(row, row.entity.id, row.entity.isFlagged)">Change</button> </span></div>'
                },
                { field: 'addedByUser.name', displayName:'Added By'},
                { field: 'entryDate.date', displayName:'Entry Date',cellTemplate: '<div class="ngCellText" ng-class="col.colIndex()"><span ng-cell-text class="ui-grid-cell-contents display-block">{{row.entity.entryDate | carbondate}}</span></div>' }
            ],
            enableGridMenu: true,
            exporterCsvFilename: 'caregiver_list.csv',
            exporterMenuPdf: false,
            exporterCsvLinkElement: angular.element(document.querySelectorAll(".custom-csv-link-location")),
            enableFiltering: true,
            paginationPageSizes: [50, 100, 500,1000],
            paginationPageSize: 50,
            useExternalPagination: true,
            useExternalSorting: true,
            rowModelType: 'pagination',
            onRegisterApi: function( gridApi ) {
                $scope.grid1Api = gridApi;              
            }
        };

        $scope.selectAll = function() {
            $scope.gridApi.selection.selectAllRows();
        };
        $scope.gridApi;
        $scope.gridOptions.multiSelect = true;
        $scope.gridOptions.onRegisterApi = function(gridApi){
            //set gridApi on scope
            $scope.gridApi = gridApi;
            gridApi.selection.on.rowSelectionChanged($scope,function(row){
                var msg = 'row selected ' + row.isSelected;
                console.log(row.entity.id);
            });
            gridApi.selection.on.rowSelectionChangedBatch($scope,function(rows){
                var msg = 'rows changed ' + rows.length;
                console.log(msg);

            });
            $scope.gridApi.core.on.sortChanged($scope, function(grid, sortColumns) {
                if (sortColumns.length == 0) {
                  paginationOptions.sort = null;
                } else {
                  paginationOptions.sort = sortColumns[0].sort.direction;
                }
                getPage();
              });
              gridApi.pagination.on.paginationChanged($scope, function (pageNumber, pageSize) {
                paginationOptions.pageNumber = pageNumber;
                paginationOptions.pageSize = pageSize;
                getPage();
              });
        };

        $scope.showUpdatedRow = function(row) {
            if($scope.gridOptions.data[$scope.gridOptions.data.indexOf(row.entity)].isFlagged == 1){
                $scope.gridOptions.data[$scope.gridOptions.data.indexOf(row.entity)].isFlagged = 0;
            } else {
                $scope.gridOptions.data[$scope.gridOptions.data.indexOf(row.entity)].isFlagged = 1;
            }
        };

        $scope.deleteVendors = function(){
            var currentSelection = $scope.gridApi.selection.getSelectedRows();
            
            if (currentSelection.length < 1) {
                return;
            }

            $scope.vendorIds = [];
            angular.forEach(currentSelection, function(row, key) {
                this.push(row.id);
            }, $scope.vendorIds);
            
            var modalInstance = $uibModal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'deleteVendorsModalTemplate.html',
                controller: 'DeleteVendorsModalCtrl',
                size: 'md',
                resolve: {
                    VendorIds: function () {
                        return $scope.vendorIds;
                    }
                }
            });

            modalInstance.result.then(function (responseData) {
                window.location.href = baseUrl+'/admin/caregivers'
            }, function () {

            });
        }

        $scope.changeFlag = function(row,vendorId,currentFlag){
            $scope.requestData = {
                vendorId: vendorId,
                currentFlag: currentFlag
            }

            OperationService.changeFlag($scope.requestData).then(function(response){
                //window.location.reload();
                $scope.showUpdatedRow(row);
            });
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
            getPage();
            //OperationService.getVendorListToAssign($scope.leadId).then(function(response){
                //$scope.gridOptions.data = response.data;
            //});
        };
        var getPage = function() {
            OperationService.getPaginatedVendorListToAssign($scope.leadId,paginationOptions).then(function(response){
                $scope.gridOptions.data = response.data;
                $scope.gridOptions.totalItems = response.count;
                var firstRow = (paginationOptions.pageNumber - 1) * paginationOptions.pageSize;
                //$scope.gridOptions.data = response.data.slice(firstRow, firstRow + paginationOptions.pageSize);
            });
        };
        $scope.toggleEmployeeDetail = function(){
            $scope.showMoreEmployeeDetail = !$scope.showMoreEmployeeDetail;
        };

        $scope.init();
    })
    .controller('DeleteVendorsModalCtrl', ["$scope","$uibModalInstance","$http","VendorService","VendorIds",function($scope,$uibModalInstance, $http, VendorService, VendorIds) {

        $scope.vendorIds = VendorIds;
        $scope.cancel = function(){
            $uibModalInstance.dismiss();
        }
        $scope.deleteVendors = function(){
            VendorService.deleteVendors($scope.vendorIds).then(function(response){
                $uibModalInstance.close(response);
                if(response.status){

                }
            });
        }

    }]);


