var imageuploadmodule = angular.module('infrastructure.imageupload', [])
    .directive('image', function($q) {
        'use strict'

        var URL = window.URL || window.webkitURL;

        var getResizeArea = function () {
            var resizeAreaId = 'fileupload-resize-area';

            var resizeArea = document.getElementById(resizeAreaId);

            if (!resizeArea) {
                resizeArea = document.createElement('canvas');
                resizeArea.id = resizeAreaId;
                resizeArea.style.visibility = 'hidden';
                document.body.appendChild(resizeArea);
            }

            return resizeArea;
        }

        var resizeImage = function (origImage, options) {
            var maxHeight = options.resizeMaxHeight || 300;
            var maxWidth = options.resizeMaxWidth || 250;
            var quality = options.resizeQuality || 0.7;
            var type = options.resizeType || 'image/jpg';

            var canvas = getResizeArea();

            var height = origImage.height;
            var width = origImage.width;

            // calculate the width and height, constraining the proportions
            if (width > height) {
                if (width > maxWidth) {
                    height = Math.round(height *= maxWidth / width);
                    width = maxWidth;
                }
            } else {
                if (height > maxHeight) {
                    width = Math.round(width *= maxHeight / height);
                    height = maxHeight;
                }
            }

            canvas.width = width;
            canvas.height = height;

            //draw image on canvas
            var ctx = canvas.getContext("2d");
            ctx.drawImage(origImage, 0, 0, width, height);

            // get the data from canvas as 70% jpg (or specified type).
            return canvas.toDataURL(type, quality);
        };

        var createImage = function(url, callback) {
            var image = new Image();
            image.onload = function() {
                callback(image);
            };
            image.src = url;
        };

        var fileToDataURL = function (file) {
            var deferred = $q.defer();
            var reader = new FileReader();
            reader.onload = function (e) {
                deferred.resolve(e.target.result);
            };
            reader.readAsDataURL(file);
            return deferred.promise;
        };


        return {
            restrict: 'A',
            scope: {
                image: '=',
                resizeMaxHeight: '@?',
                resizeMaxWidth: '@?',
                resizeQuality: '@?',
                resizeType: '@?'
            },
            link: function postLink(scope, element, attrs, ctrl) {

                var doResizing = function(imageResult, callback) {
                    createImage(imageResult.url, function(image) {
                        var dataURL = resizeImage(image, scope);
                        imageResult.resized = {
                            dataURL: dataURL,
                            type: dataURL.match(/:(.+\/.+);/)[1],
                        };
                        callback(imageResult);
                    });
                };

                var applyScope = function(imageResult) {
                    scope.$apply(function() {
                        //console.log(imageResult);
                        if(attrs.multiple)
                            scope.image.push(imageResult);
                        else
                            scope.image = imageResult;
                    });
                };


                element.bind('change', function (evt) {
                    //when multiple always return an array of images
                    if(attrs.multiple)
                        scope.image = [];

                    var files = evt.target.files;
                    for(var i = 0; i < files.length; i++) {
                        //create a result object for each file in files
                        var imageResult = {
                            file: files[i],
                            url: URL.createObjectURL(files[i])
                        };

                        fileToDataURL(files[i]).then(function (dataURL) {
                            imageResult.dataURL = dataURL;
                        });

                        if(scope.resizeMaxHeight || scope.resizeMaxWidth) { //resize image
                            doResizing(imageResult, function(imageResult) {
                                applyScope(imageResult);
                            });
                        }
                        else { //no resizing
                            applyScope(imageResult);
                        }
                    }
                });
            }
        };
    });
imageuploadmodule.directive('uploadDialog',["$uibModal",function($uibModal){
    return {
        restrict: 'EA',
        replace:true,
        scope: {
            'model' : '=',
            'media' :'=ngModel'
        },
        //templateUrl: staticUrl+'/js/infrastructure/partials/fileuploader.html',
        link: function (scope, element, attrs, ngModel) {
            scope.showDialog = true;
            scope.modalInstance = scope.modalInstance || {};

            scope.model = scope.model || {};
            scope.model.uplodertitle = scope.model.uplodertitle || "Upload file";
            scope.$watch('model.open',function(newValue,oldValue){
                if(newValue && newValue==true){
                    scope.modalInstance = $uibModal.open({
                        templateUrl:  baseUrl+'/static/js/templates/fileuploader.html',
                        controller: 'uploadDialogController',
                        size: 'md',
                        resolve: {
                            UploadModel: function () {
                                return scope.model;
                            }
                        }
                    });
                    scope.modalInstance.result.then(function (data) {
                        scope.model.open = false;
                        scope.media = data.data;
                    }, function () {
                        scope.model.open = false;
                    });
                }
            });



        }
    }
}]);
imageuploadmodule.controller('uploadDialogController',["$scope","$uibModal","$http","$uibModalInstance","UploadModel",function($scope,$uibModal,$http,$uibModalInstance,UploadModel){
    $scope.model = UploadModel;
    $scope.cancelButton = function(){
        $uibModalInstance.dismiss('cancel');
    }
    $scope.updating = false;
    $scope.uploadfile  = function(){
        if(angular.isUndefined($scope.model.url) && $scope.model.url == ''){
            return;
        }

        var urlToUse = baseUrl+'/api/v1/'+$scope.model.url;
        var file = $scope.inputfile;
        var fd = new FormData();
        fd.append('file', file.file);
        fd.append('data',JSON.stringify($scope.model.data))

        $scope.updating = true;
        $http.post(urlToUse, fd, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
        })
            .success(function(data){
                $uibModalInstance.close(data);
                $scope.updating = false;
            })
            .error(function(){
                $uibModalInstance.dismiss('cancel');
                $scope.updating = false;
            });
        return;
    }
}]);
imageuploadmodule.directive('fallbackSrc', function () {
    var fallbackSrc = {
        link: function postLink(scope, iElement, iAttrs) {
            iElement.bind('error', function() {
                angular.element(this).attr("src", iAttrs.fallbackSrc);
            });
        }
    }
    return fallbackSrc;
});
imageuploadmodule.directive('fileModel', ['$parse', function ($parse) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            var model = $parse(attrs.fileModel);
            var modelSetter = model.assign;

            element.bind('change', function(){
                scope.$apply(function(){
                    modelSetter(scope, element[0].files[0]);
                });
            });
        }
    };
}]);
