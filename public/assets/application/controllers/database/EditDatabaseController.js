(function () {

    'use strict';

    angular.module('app.main')

        .controller('EditDatabaseController', function ($scope, $resource, $routeParams, Website, Database, $location) {
            $scope.legend = "Update Database Login Credentials";
            $scope.back_url = "/website/details/"+$routeParams.website_id;
            $scope.website = Website.get({id:$routeParams.website_id});
            $scope.database = Database.get({id:$routeParams.id, website_id:$routeParams.website_id, type: 'mysql'});
            $scope.types = [{value:'mysql', label:'MySQL'}, {value:'sqlite', label:'SQLite'}, {value:'mssql', label:'MSSQL'}, {value:'oracle', label:'Oracle Database'}, {value:'pgsql', label:'Postgres'}, {value:'access', label:'Microsoft Access'}, {value:'other', label:'Other'}];
            $scope.errors = {};
            $scope.resetValidation = function (name) {
                delete $scope.errors[name];
                if (!$scope.errors[name]) {
                    $scope.formDatabase[name].$setValidity('remote', true);
                }
            };
            $scope.saveDatabase = function(){
                if($scope.formDatabase.$valid){
                    $scope.database.$update(function(updated_record){
                        $scope.database = updated_record;
                        $location.path('/website/details/'+updated_record.website_id).replace();
                    }, function(response){
                        if(response.status == 422){
                            $scope.errors = response.data;
                            angular.forEach(response.data, function(errors, name){
                                $scope.formDatabase[name].$setValidity('remote', false);
                            });
                        }
                    });
                }
            };
        });

}());
