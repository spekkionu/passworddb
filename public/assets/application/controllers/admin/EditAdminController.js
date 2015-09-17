(function () {

    'use strict';

    angular.module('app.main')

        .controller('EditAdminController', function ($scope, $resource, $routeParams, Website, Admin, $location) {
            $scope.legend = "Update Admin Login Credentials";
            $scope.back_url = "/website/details/" + $routeParams.website_id;
            $scope.website = Website.get({id: $routeParams.website_id});
            $scope.admin = Admin.get({id: $routeParams.id, website_id: $routeParams.website_id});
            $scope.errors = {};
            $scope.resetValidation = function (name) {
                delete $scope.errors[name];
                if (!$scope.errors[name]) {
                    $scope.formAdmin[name].$setValidity('remote', true);
                }
            };
            $scope.saveAdmin = function () {
                if ($scope.formAdmin.$valid) {
                    $scope.admin.$update(function (updated_record) {
                        $scope.admin = updated_record;
                        $location.path('/website/details/' + updated_record.website_id).replace();
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
