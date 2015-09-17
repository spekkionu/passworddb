(function () {

    'use strict';

    angular.module('app.main')

        .factory('Website', function ($resource) {
            return $resource('/api/website/:id', {id: '@id'}, {update:{method:'PUT'}});
        });

}());
