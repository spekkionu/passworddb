(function () {

    'use strict';

    angular.module('app.main')

        .controller('DeleteAdminController', function ($scope, $resource, $routeParams, Website, Admin, $location) {
            $scope.website = Website.get({id:$routeParams.website_id});
            $scope.admin = Admin.get({id:$routeParams.id, website_id:$routeParams.website_id});
            $scope.deleteAdmin = function(){
                $scope.admin.$delete(function(){
                    $location.path('/website/details/'+$routeParams.website_id).replace();
                });
            };
        });

}());
