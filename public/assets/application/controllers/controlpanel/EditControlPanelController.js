(function () {

    'use strict';

    angular.module('app.main')

        .controller('EditControlPanelController', function ($scope, $resource, $routeParams, Website, ControlPanel, $location) {
            $scope.legend = "Update Control Panel Login Credentials";
            $scope.back_url = "/website/details/"+$routeParams.website_id;
            $scope.website = Website.get({id:$routeParams.website_id});
            $scope.controlpanel = ControlPanel.get({id:$routeParams.id, website_id:$routeParams.website_id});
            $scope.errors = {};
            $scope.resetValidation = function (name) {
                delete $scope.errors[name];
                if (!$scope.errors[name]) {
                    $scope.formControlPanel[name].$setValidity('remote', true);
                }
            };
            $scope.saveControlPanel = function(){
                if($scope.formControlPanel.$valid){
                    $scope.controlpanel.$update(function(updated_record){
                        $scope.controlpanel = updated_record;
                        $location.path('/website/details/'+updated_record.website_id).replace();
                    }, function(response){
                        if(response.status == 422){
                            $scope.errors = response.data;
                            angular.forEach(response.data, function(errors, name){
                                $scope.formControlPanel[name].$setValidity('remote', false);
                            });
                        }
                    });
                }
            };
        });

}());
