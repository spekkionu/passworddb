(function () {

    'use strict';

    angular.module('app.main')

        .controller('DetailsController', function ($scope, $resource, $routeParams, Website) {
            $scope.website = Website.get({id:$routeParams.id});
        });

}());
