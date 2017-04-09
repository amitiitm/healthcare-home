angular.module('frontModule', ['ngAnimate','ui.bootstrap','front.services','google.places','slick'])
    .config(function() {

    })

    .controller('welcomeCtrl',["$scope", "$http","$q","$timeout","FrontService", function($scope, $http, $q, $timeout, FrontService) {

        $scope.uploadFile = function(files) {
            var fd = new FormData();
            //Take the first selected file
            fd.append("file", files[0]);

            $http.post(uploadUrl, fd, {
                withCredentials: true,
                headers: {'Content-Type': undefined },
                transformRequest: angular.identity
            }).success('allright').error('damn');

        };

        $scope.slickObject = $('#front-form').slick({
            cssEase: 'linear',
            fade: true
        });
        $scope.callImage = baseUrl+"/static/images/ripple_white.gif";
        $scope.mappedData = {};
        FrontService.getCreateLeadDataMapped().then(function(response){
            $scope.mappedData = response.data;
        });
        $scope.ailments= [];
        $scope.patientDetailRequiredArr = [1,2,3];
        $scope.serviceRequirementArr = [1,2];
        $scope.physiotherapy = 3;
        $scope.medicine = 6;

        $scope.oneAtATime = true;
        $scope.accordionHandler = {
            open : 0,
            firstOpen: true
        }
        $scope.isCollapsed = false;
        $scope.status = {
            isCustomHeaderOpen: false,
            isFirstOpen: true,
            open: 'start',
            isFirstDisabled: false
        };
        $scope.leadId = null;
        FrontService.getAilmentByService(1).then(function(response) {
            $scope.ailments = response.data;
        });
        $scope.leadDataValidation = {
            "customerName": {
                "valid": true,
                "message": "Please provide customer name"
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
        $scope.patientInfoValidation = {
            "gender": {
                "valid": true,
                "message": "Please provide patient gender"
            },
            "age": {
                "valid": true,
                "message": "Please provide patient age"
            },
            "weight": {
                "valid": true,
                "message": "Please provide patient weight"
            },
            "ailments": {
                "valid": true,
                "message": "Patient ailment is required"
            },
            "shift": {
                "valid": true,
                "message": "Shift information is required"
            }
        }
        $scope.physioPatientInfoValidation = {
            "gender": {
                "valid": true,
                "message": "Please provide patient gender"
            },
            "age": {
                "valid": true,
                "message": "Please provide patient age"
            },
            "weight": {
                "valid": true,
                "message": "Please provide patient weight"
            },
            "ailments": {
                "valid": true,
                "message": "Patient ailment is required"
            },
            "shift": {
                "valid": true,
                "message": "Shift information is required"
            }
        }
        $scope.requirementValidation = {
            "shift": {
                "valid": true,
                "message": "Please provide patient gender"
            }
        }

        $scope.otp= "";
        $scope.uploadedPrescription = {};
        $scope.leadData = {
            leadSource: {
                id: 1
            },
            serviceId: null,
            customerInfo: {
                name: '',
                email:'',
                phone:'',
                locality:''
            },
            patientInfo: {
                name:'',
                gender:'',
                age:'',
                weight:'',
                ailments: [],
                ailmentOther: false,
                prescription:''
            },
            physioPatientInfo: {
                name:'',
                gender:'',
                age:'',
                weight:'',
                condition: '',
                presentCondition:'',
                prescriptionList : []
            },
            requirements: {
                shift: '',
                tasks: []
            },
            request: {
                religion:false,
                religionPreferred:'',
                age: false,
                agePreferred: '',
                food: false,
                foodPreferred: '',
                gender: false,
                genderPreferred: '',
                language: false,
                languagePreferred: '',
                startDate: new Date(),
                serviceDuration: '',
                remark: ''


            }
        }

        $scope.leadDataCloned = angular.copy($scope.leadData);
        $scope.backToHome = function(param){
            if(param==true){
                $scope.leadData = angular.copy($scope.leadDataCloned);
            }
            $scope.status.open = 'start';
        }
        $scope.selectService = function(serviceId){
            if(serviceId =='4' && false) {
                $scope.status.open = 'medicine-info';
            }else {
                $scope.status.open = 'customer-detail';
            }
            /*$('#front-form').slick('slickGoTo',1);*/
            $scope.leadData.serviceId = serviceId;
            FrontService.getAilmentByService(serviceId).then(function(response) {
                $scope.ailments = response.data;
            });
        }
        $scope.verifyOtp = function(){
            FrontService.verifyOtp($scope.leadData.id,$scope.otp).then(function(response){
                if(response.status){
                    $scope.status.open = 'call-screen';
                    FrontService.callToLead($scope.leadData.id).then(function(response){

                    });
                }
            });
        }
        $scope.processingCallMeNowSubmission =false;
        $scope.callMeNow = function(){
            // validation
            var valid = $scope.validateCustomerInfoForm(true);
            if($scope.processingCallMeNowSubmission){
                return;
            }

            $scope.customerFormSubmitted = true;
            if(!valid){
                return;
            }
            if($scope.leadId!=null){
                $scope.status.open = 'otp-screen';
                return;
            }
            $scope.processingCallMeNowSubmission = true;


            FrontService.submitEnquiryForCall($scope.leadData).then(function(response){
                $scope.status.open = 'otp-screen';
                $scope.leadData.id = response.data;
                $scope.otp= "";
                $scope.leadId = response.data;
                $scope.processingCallMeNowSubmission=false;

                FrontService.sendNotificationAboutLead($scope.leadId,$scope.leadData).then(function(responseNotification){

                });
                $timeout(function(){
                   // $scope.status.open = 'start';
                },3000);
            });
        }
        $scope.fillTask = function () {
            $scope.status.open = 'task-required';
        }
        $scope.formEnd = function () {
            $scope.status.open = 'form-submitted';
        }
        $scope.validateCustomerInfoForm = function(param){
            if(!param){
                return;
            }
            var valid = true;
            if($scope.leadData.customerInfo.name==''){
                $scope.leadDataValidation.customerName.valid=false;
                valid=false;
            }else{
                $scope.leadDataValidation.customerName.valid=true;
            }
            if($scope.leadData.customerInfo.phone==''){
                $scope.leadDataValidation.customerPhone.valid=false;
                valid=false;
            }else{
                $scope.leadDataValidation.customerPhone.valid=true;
            }
            if(angular.isUndefined($scope.leadData.serviceId)){
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
        $scope.customerFormSubmitted = false;
        $scope.processingLeadSubmission = false;
        $scope.submitCustomerDetail = function(){
            // validation
            var valid = $scope.validateCustomerInfoForm(true);
            if( $scope.processingLeadSubmission ){
                return;
            }
            $scope.processingLeadSubmission = true;
            $scope.customerFormSubmitted = true;
            if(!valid){
                return;
            }

            if($scope.leadId!=null){
                $scope.redirectToServiceForm();
                return;
            }
            FrontService.submitEnquiry($scope.leadData).then(function(response){
                $scope.processingLeadSubmission = false;
                if(response.status){
                    $scope.leadId = response.data;
                    $scope.redirectToServiceForm();
                    FrontService.sendNotificationAboutLead($scope.leadId,$scope.leadData).then(function(responseNotification){

                    });
                }

            });
        }
        $scope.redirectToServiceForm = function(){
            if($scope.leadData.serviceId==$scope.physiotherapy){
                $scope.status.open = 'patient-info-physio';
            }else if($scope.patientDetailRequiredArr.indexOf($scope.leadData.serviceId)>=0){
                $scope.status.open = 'patient-info';
            }else{
                $scope.status.open = 'lead-success';
                $timeout(function(){
                    $scope.status.open = 'start';
                    $scope.leadData = angular.copy($scope.leadDataCloned);

                },3000);
            }
        }
        $scope.fillDetail = function(){
            $scope.status.open = 'patient-info';
        }
        $scope.gotoCustomerInformation = function(){
            $scope.status.open = 'customer-detail';
        }
        $scope.validatePatientInfoForm = function(param){
            if(!param){
                return;
            }
            var valid=true;
            if($scope.leadData.patientInfo.gender==''){
                $scope.patientInfoValidation.gender.valid=false;
                valid=false;
            }else{
                $scope.patientInfoValidation.gender.valid=true;
            }
            if($scope.leadData.patientInfo.age==''){
                $scope.patientInfoValidation.age.valid=false;
                valid=false;
            }else{
                $scope.patientInfoValidation.age.valid=true;
            }
            if($scope.leadData.patientInfo.weight==''){
                $scope.patientInfoValidation.weight.valid=false;
                valid=false;
            }else{
                $scope.patientInfoValidation.weight.valid=true;
            }
            return valid;
        }
        $scope.patientInfoSubmitted = false;
        $scope.submitPatientInfo = function(){
            $scope.patientInfoSubmitted = true;
            // validation
            var valid = $scope.validatePatientInfoForm($scope.patientInfoSubmitted);

            if(!valid){
                return;
            }

            $scope.leadData.patientInfo.ailments=[];
            for(var i=0; i<$scope.ailments.length; i++){
                if(angular.isDefined($scope.ailments[i].selected) && $scope.ailments[i].selected==true){
                    $scope.leadData.patientInfo.ailments.push($scope.ailments[i].id);
                }
            }

            FrontService.submitPatientInfo($scope.leadId,$scope.leadData.patientInfo).then(function(response){
                if($scope.leadData.serviceId==$scope.physiotherapy){
                    $scope.status.open = 'physio-requirement';
                }else if($scope.serviceRequirementArr.indexOf($scope.leadData.serviceId)>=0){
                    $scope.gotoServiceRequirement();
                }else{
                    $scope.allStepsDone();
                }
            });


            return;
            $scope.status.open = 'service-requirement';
            $scope.status.open = 'physio-requirement';
        }
        $scope.validatePhysioPatientInfoForm = function(param){
            if(!param){
                return;
            }
            var valid=true;
            if($scope.leadData.physioPatientInfo.gender==''){
                $scope.physioPatientInfoValidation.gender.valid=false;
                valid=false;
            }else{
                $scope.physioPatientInfoValidation.gender.valid=true;
            }
            if($scope.leadData.physioPatientInfo.age==''){
                $scope.physioPatientInfoValidation.age.valid=false;
                valid=false;
            }else{
                $scope.physioPatientInfoValidation.age.valid=true;
            }
            if($scope.leadData.physioPatientInfo.weight==''){
                $scope.physioPatientInfoValidation.weight.valid=false;
                valid=false;
            }else{
                $scope.physioPatientInfoValidation.weight.valid=true;
            }
            return valid;
        }
        $scope.patientInfoSubmitted = false;
        $scope.submitPhysioPatientInfo = function(){
            $scope.physioPatientInfoSubmitted = true;
            // validation
            var valid = $scope.validatePhysioPatientInfoForm($scope.physioPatientInfoSubmitted);

            if(!valid){
                return;
            }


            FrontService.submitPhysioPatientInfo($scope.leadId,$scope.leadData.physioPatientInfo).then(function(response){
                if(response.status){
                    $scope.allStepsDone();
                }
            });


            return;
            $scope.status.open = 'service-requirement';
            $scope.status.open = 'physio-requirement';
        }
        $scope.allStepsDone = function(){
            $scope.status.open = 'lead-success';
            $timeout(function(){
                $scope.status.open = 'start';
            },3000);
        }
        $scope.gotoServiceRequirement = function(){
            $scope.status.open = 'service-requirement';
            $scope.updateTaskList();
        }
        $scope.updateTaskList = function(){
            $scope.tasks = [];
            for(var i=0; i<$scope.ailments.length; i++){
                if(angular.isDefined($scope.ailments[i].selected) && $scope.ailments[i].selected==true){
                    $scope.tasks = $scope.tasks.concat($scope.ailments[i].tasks);
                }
            }
        }


        $scope.specialReqValidation = {
            "gender": {
                "valid": true,
                "message": "Please provide preferred gender"
            },
            "age": {
                "valid": true,
                "message": "Please provide preferred age range"
            },
            "religion": {
                "valid": true,
                "message": "Please provide preferred religion"
            },
            "language": {
                "valid": true,
                "message": "Patient preferred language"
            },
            "food": {
                "valid": true,
                "message": "Patient preferred food"
            }
        }
        $scope.validateSpecialRequest = function(){
            var valid=true;
            if($scope.leadData.request.gender==true && $scope.leadData.request.genderPreferred==null){
                $scope.specialReqValidation.gender.valid=false;
                valid=false;
            }else{
                $scope.specialReqValidation.gender.valid=true;
            }
            if($scope.leadData.request.religion==true && $scope.leadData.request.religionPreferred==null){
                $scope.specialReqValidation.religion.valid=false;
                valid=false;
            }else{
                $scope.specialReqValidation.religion.valid=true;
            }
            if($scope.leadData.request.age==true && $scope.leadData.request.agePreferred==null){
                $scope.specialReqValidation.age.valid=false;
                valid=false;
            }else{
                $scope.specialReqValidation.age.valid=true;
            }
            if($scope.leadData.request.language==true && $scope.leadData.request.languagePreferred==null){
                $scope.specialReqValidation.language.valid=false;
                valid=false;
            }else{
                $scope.specialReqValidation.language.valid=true;
            }
            if($scope.leadData.request.food==true && $scope.leadData.request.foodPreferred==null){
                $scope.specialReqValidation.food.valid=false;
                valid=false;
            }else{
                $scope.specialReqValidation.food.valid=true;
            }
            return valid;
        }
        $scope.submitSpecialRequest = function(){

            var valid = true;
            // validation on fields
            valid = $scope.validateSpecialRequest();
            if(!valid){
                return;
            }


            FrontService.submitSpecialRequest($scope.leadId,$scope.leadData.request).then(function(response){
                if(response.status){
                    $scope.allStepsDone();
                }
            });
        }
        $scope.serviceTaskFormSubmitted = false;

        $scope.serviceTaskFormValidation = function(param){
            var valid = true;
            if(!param){
                return;
            }
            if($scope.leadData.requirements.shift==''){
                $scope.patientInfoValidation.shift.valid=false;
                valid=false;
            }else{
                $scope.patientInfoValidation.shift.valid=true;
            }
            return valid;
        }
        $scope.submitServiceTask = function(){
            var valid = true;
            $scope.serviceTaskFormSubmitted = true;
            valid = $scope.serviceTaskFormValidation($scope.serviceTaskFormSubmitted);
            if(!valid){
                return;
            }
            $scope.leadData.requirements.tasks=[];
            for(var i=0; i<$scope.tasks.length; i++){

                if(angular.isDefined($scope.tasks[i].selected) && $scope.tasks[i].selected==true){
                    $scope.leadData.requirements.tasks.push($scope.tasks[i].id);
                }
            }
            FrontService.submitLeadTaskDetail($scope.leadId,$scope.leadData.requirements).then(function(response){
                if(response.status){
                    $scope.status.open = 'service-request';
                }
            });


        }
        $scope.bookService=function(ev){
            ev.preventDefault();
            $mdDialog.show({
                controller: BookNowDialogController,
                templateUrl: baseUrl+'/static/js/front/templates/bookNowDialog.tmpl.html',
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
    .controller('contactCtrl', ["$scope","$timeout","FrontService",function($scope,$timeout,FrontService) {
        $scope.contactData = {
            name:'',
            email:'',
            phone:'',
            message:''
        };
        $scope.contactFormValidation = {
            "name": {
                "valid": true,
                "message": "Please provide your name"
            },
            "email": {
                "valid": true,
                "message": "Please provide your email address"
            },
            "phone": {
                "valid": true,
                "message": "Please provide your contact number"
            }
        }
        $scope.submittedSuccess =false;
        $scope.formSubmitted= false;
        $scope.validateContactForm = function(param){
            if(!param){
                return;
            }
            var valid = true;
            if($scope.contactData.name==''){
                $scope.contactFormValidation.name.valid=false;
                valid=false;
            }else{
                $scope.contactFormValidation.name.valid=true;
            }
            if($scope.contactData.phone==''){
                $scope.contactFormValidation.phone.valid=false;
                valid=false;
            }else{
                $scope.contactFormValidation.phone.valid=true;
            }
            if($scope.contactData.email==''){
                $scope.contactFormValidation.email.valid=false;
                valid=false;
            }else{
                $scope.contactFormValidation.email.valid=true;
            }
            return valid;
        }
        $scope.submitMessage = function(){
            $scope.formSubmitted= true;
            var valid = $scope.validateContactForm($scope.formSubmitted);
            if(!valid){
                return;
            }
            FrontService.submitContactForm($scope.contactData).then(function(response){
                $scope.submittedSuccess = true;
                $scope.contactData = {
                    name:'',
                    email:'',
                    phone:'',
                    message:''
                };
                $timeout(function(){
                    $scope.submittedSuccess = false;
                },3000);

            });
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

    .controller('careFinderCtrl', ["$scope","$element","$mdDialog","FrontService",function($scope,$element, $mdDialog,FrontService) {

        $scope.selectedTab = 0;

        $scope.selectServiceClickHandler = function(){
            $scope.selectedTab = 1;
        }
        $scope.gotoTab = function(index){
            $scope.selectedTab = index;
        }

        $scope.autocompleteOptions = {
            componentRestrictions: {
                country: 'in'
            }
        }
        $scope.careFinderData = {
            service: {},
            enquiry: {
                name: '',
                email: '',
                phone: '',
                city: [],
                references: []
            },
            patientInfo: {
                name: '',
                gender:'',
                age: 0,
                weight: 0,
                ailment: [],
                equipmentSupport: false,
                prescriptionList : [],
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

        if(angular.isDefined(window.localStorage['enquiry_id_generated'])){
            $scope.careFinderData.enquiryId = JSON.parse(window.localStorage['enquiry_id_generated']);
        }

        $scope.minServiceStartDate = new Date();
        $scope.staticData = {};
        $scope.taskList = [];
        FrontService.getCreateLeadDataMapped().then(function(response){
            $scope.staticData = response.data
        });
        $scope.serviceSelected = function(service){
            FrontService.getTaskListByService(service.id).then(function(response){
                $scope.taskList = response.data;
                $scope.taskList.push({
                    id: -1,
                    label:"Other"
                })
            });
        }
        $scope.searchTermAilment;
        $scope.clearAilmentSearchTerm = function(){
            $scope.searchTermAilment = '';
        }
        $scope.getSelectedText = function() {
            if ($scope.selectedItem !== undefined){
                return "You have selected: Item " + $scope.selectedItem;
            } else {
                return "Please select an item";
            }
        };
        $element.find('input').on('keydown', function(ev) {
            ev.stopPropagation();
        });
        $scope.genders = [
            {
                'label':'Male',
                'value':'M'
            },
            {
                'label':'Female',
                'value':'F'
            }
        ];
        $scope.submitRequest = function(){
            angular.forEach($scope.careFinderForm.$error.required, function(field) {
                field.$setDirty();
            });
            if($scope.careFinderForm.$invalid){
                return;
            }
            /*$scope.careFinderData.$setSubmitted();
            angular.forEach($scope.myForm.$error.required, function(field) {
                field.$setDirty();
            });
             $scope.careFinderForm.$valid;
            */
            if($scope.careFinderData.enquiryId==null){
                $mdDialog.show({
                    controller: BookNowDialogController,
                    templateUrl: baseUrl+'/static/js/front/templates/bookNowDialog.tmpl.html',
                    parent: angular.element(document.body),
                    clickOutsideToClose:true
                })
                    .then(function(answer) {
                        $scope.status = 'You said the information was "' + answer + '".';
                    }, function() {
                        $scope.status = 'You cancelled the dialog.';
                    });
                return;
            }
            FrontService.submitRequest($scope.careFinderData).then(function(){
                console.log('Request submitted');
            });
        }

    }]);


function BookNowDialogController($scope, $mdDialog, FrontService) {
    $scope.svgUrl = baseUrl+"/static/images/";
    $scope.bookRequestData = {
        name:'',
        email:'',
        phone:''
    }
    $scope.serviceEnquiry = function(ev){
        if(!$scope.bookRequest.$valid){
            return;
        }
        FrontService.submitEnquiry($scope.bookRequestData).then(function(response){
            $mdDialog.hide();
        });
    }
    $scope.bookService = function(ev){
        if(!$scope.bookRequest.$valid){
            return;
        }
        FrontService.submitEnquiry($scope.bookRequestData).then(function(response){
            window.localStorage['enquiry_id_generated'] = response.data;
            var urlToRedirect = baseUrl+"/care-finder";
            window.location.href = urlToRedirect;
        });
    }
    $scope.hide = function() {
        $mdDialog.hide();
    };
    $scope.cancel = function() {
        $mdDialog.cancel();
    };
    $scope.answer = function(answer) {
        $mdDialog.hide(answer);
    };
}