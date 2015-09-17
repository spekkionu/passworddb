(function () {

    'use strict';

    angular.module('app.main')

        .controller('DeleteFTPController', function ($scope, $resource, $routeParams, Website, FTP, $location) {
            $scope.website = Website.get({id:$routeParams.website_id});
            $scope.ftp = FTP.get({id:$routeParams.id, website_id:$routeParams.website_id});
            $scope.deleteFTP = function(){
                $scope.ftp.$delete(function(){
                    $location.path('/website/details/'+$scope.website.id).replace();
                });
            };
        });

}());
