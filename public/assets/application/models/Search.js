(function () {

    'use strict';

    angular.module('app.main')

        .factory('Search', function () {
            return {text:'', sort: 'name', dir:"ASC", page: 1, limit: 20, next: false};
        });

}());
