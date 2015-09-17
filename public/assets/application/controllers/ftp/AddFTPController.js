(function () {

    'use strict';

    angular.module('app.main')

        .controller('AddFTPController', function ($scope, $resource, $routeParams, Website, FTP, $location) {
            $scope.legend = "Add FTP Credentials";
            $scope.back_url = "/website/"+$routeParams.website_id;
            $scope.website = Website.get({id:$routeParams.website_id});
            $scope.ftp = new FTP({website_id: $routeParams.website_id, type: 'ftp'});
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
                    $scope.ftp.$save(function(added_record){
                        $scope.ftp = added_record;
                        $location.path('/website/details/'+added_record.website_id).replace();
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
