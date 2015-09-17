(function () {

    'use strict';

    angular.module('app.main')

        .controller('EditFTPController', function ($scope, $resource, $routeParams, Website, FTP, $location) {
            $scope.legend = "Update FTP Credentials";
            $scope.back_url = "/website/details/"+$routeParams.website_id;
            $scope.website = Website.get({id:$routeParams.website_id});
            $scope.ftp = FTP.get({id:$routeParams.id, website_id:$routeParams.website_id});
            $scope.types = [{value:'ftp', label:'FTP'}, {value:'sftp', label:'SFTP'}, {value:'ftps', label:'FTPS'}, {value:'webdav', label:'WebDAV'}, {value:'other', label:'Other'}];
            $scope.errors = {};
            $scope.resetValidation = function (name) {
                delete $scope.errors[name];
                if (!$scope.errors[name]) {
                    $scope.formFTP[name].$setValidity('remote', true);
                }
            };
            $scope.saveFTP = function(){
                if($scope.formFTP.$valid){
                    $scope.ftp.$update(function(updated_record){
                        $scope.ftp = updated_record;
                        $location.path('/website/details/'+updated_record.website_id).replace();
                    }, function(response){
                        if(response.status == 422){
                            $scope.errors = response.data;
                            angular.forEach(response.data, function(errors, name){
                                $scope.formFTP[name].$setValidity('remote', false);
                            });
                        }
                    });
                }
            };
        });

}());
