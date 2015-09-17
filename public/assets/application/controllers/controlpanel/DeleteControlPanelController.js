(function () {

    'use strict';

    angular.module('app.main')

        .controller('DeleteControlPanelController', function ($scope, $resource, $routeParams, Website, ControlPanel, $location) {
            $scope.website = Website.get({id:$routeParams.website_id});
            $scope.controlpanel = ControlPanel.get({id:$routeParams.id, website_id:$routeParams.website_id});
            $scope.deleteControlPanel = function(){
                $scope.controlpanel.$delete(function(){
                    $location.path('/website/details/'+$routeParams.website_id).replace();
                });
            };
        });

}());
