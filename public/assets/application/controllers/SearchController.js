(function () {

    'use strict';

    angular.module('app.main')

        .controller('SearchController', function ($scope, Search, $location) {
            $scope.search = Search;
            $scope.globalSearch = '';
            $scope.searchWebsites = function(){
                $scope.search.text = $scope.globalSearch;
                $scope.globalSearch = '';
                $scope.search.page = 1;
                $scope.search.next = false;
                $location.path('/').search({text:$scope.search.text});
            }
        });

}());
