(function () {

    'use strict';

    angular.module('app.main')

        .controller('AddAdminController', function ($scope, $resource, $routeParams, Website, Admin, $location) {
            $scope.legend = "Add Admin Login Credentials";
            $scope.back_url = "/website/" + $routeParams.website_id;
            $scope.website = Website.get({id: $routeParams.website_id});
            $scope.admin = new Admin({website_id: $routeParams.website_id});
            $scope.errors = {};
            $scope.resetValidation = function (name) {
                delete $scope.errors[name];
                if (!$scope.errors[name]) {
                    $scope.formAdmin[name].$setValidity('remote', true);
                }
            };
            $scope.saveAdmin = function () {
                if ($scope.formAdmin.$valid) {
                    $scope.admin.$save(function (added_record, headers) {
                        $scope.admin = added_record;
                        $location.path('/website/details/' + added_record.website_id).replace();
                    }, function (response) {
                        if (response.status == 422) {
                            $scope.errors = response.data;
                            angular.forEach(response.data, function (errors, name) {
                                $scope.formAdmin[name].$setValidity('remote', false);
                            });
                        }
                    });
                }
            };
        });

}());
