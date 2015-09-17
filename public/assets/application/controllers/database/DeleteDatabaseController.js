(function () {

    'use strict';

    angular.module('app.main')

        .controller('DeleteDatabaseController', function ($scope, $resource, $routeParams, Website, Database, $location) {
            $scope.website = Website.get({id:$routeParams.website_id});
            $scope.database = Database.get({id:$routeParams.id, website_id:$routeParams.website_id});
            $scope.deleteDatabase = function(){
                $scope.database.$delete(function(){
                    $location.path('/website/details/'+$routeParams.website_id).replace();
                });
            };
        });

}());
