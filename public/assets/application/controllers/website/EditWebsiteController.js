(function () {

    'use strict';

    angular.module('app.main')

        .controller('EditWebsiteController', function ($scope, $resource, $routeParams, Website, $location) {
            $scope.legend = "Edit Website";
            $scope.back_url = "/website/details/" + $routeParams.id;
            $scope.website = Website.get({id: $routeParams.id});
            $scope.errors = {};
            $scope.resetValidation = function (name) {
                delete $scope.errors[name];
                if (!$scope.errors[name]) {
                    $scope.formWebsite[name].$setValidity('remote', true);
                }
            };
            $scope.saveWebsite = function () {
                if ($scope.formWebsite.$valid) {
                    $scope.website.$update({website_id: $routeParams.website_id}, function (updated_website) {
                        $scope.website = updated_website;
                        $location.path('/website/details/' + updated_website.id).replace();
                    }, function (response) {
                        if (response.status == 422) {
                            $scope.errors = response.data;
                            angular.forEach(response.data, function (errors, name) {
                                $scope.formWebsite[name].$setValidity('remote', false);
                            });
                        }
                    });
                }
            };
        });

}());
