(function () {

    'use strict';

    angular.module('app.main')

        .controller('AddWebsiteController', function ($scope, $resource, Website, $location, $routeParams) {
            $scope.legend = "Add Website";
            $scope.back_url = "/";
            $scope.website = new Website({});
            $scope.errors = {};
            $scope.resetValidation = function (name) {
                delete $scope.errors[name];
                if (!$scope.errors[name]) {
                    $scope.formWebsite[name].$setValidity('remote', true);
                }
            };
            $scope.saveWebsite = function () {
                if ($scope.formWebsite.$valid) {
                    $scope.website.$save({website_id: $routeParams.website_id}, function (added_website) {
                        $scope.website = added_website;
                        $location.path('/website/details/' + added_website.id).replace();
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
