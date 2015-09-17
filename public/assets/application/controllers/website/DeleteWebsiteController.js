(function () {

    'use strict';

    angular.module('app.main')

        .controller('DeleteWebsiteController', function ($scope, $resource, $routeParams, Website, $location) {
            $scope.website = Website.get({id:$routeParams.id});
            $scope.deleteWebsite = function(){
                $scope.website.$delete(function(){
                    $location.path('/').replace();
                });
            };
        });

}());
