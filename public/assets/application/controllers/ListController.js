(function () {

    'use strict';

    angular.module('app.main')

        .controller('ListController', function ($scope, $http, Website, Search) {
            $scope.search = Search;

            $scope.loadData = function(){
                var data = {page: $scope.search.page, limit: $scope.search.limit, search: $scope.search.text, sort:$scope.search.sort, dir:$scope.search.dir};
                $http.get('/api/website', {params: data}).
                    then(function(response) {
                        // this callback will be called asynchronously
                        // when the response is available
                        if(response.data.next_page_url){
                            // There is another page
                            $scope.search.next = true;
                        } else {
                            $scope.search.next = false;
                        }
                        $scope.websites = response.data.data;
                    }, function(response) {
                        // called asynchronously if an error occurs
                        // or server returns response with an error status.
                    });
            };
            $scope.previous = function(){
                if($scope.search.page > 1){
                    $scope.search.page--;
                    $scope.loadData();
                }
            };
            $scope.next = function(){
                if($scope.search.next){
                    $scope.search.page++;
                    $scope.loadData();
                }
            };
            $scope.doSearch = function(){
                $scope.search.next = false;
                $scope.search.page = 1;
                $scope.loadData();
            }
            $scope.loadData();
        });

}());
