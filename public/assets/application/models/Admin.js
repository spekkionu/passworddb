(function () {

    'use strict';

    angular.module('app.main')

        .factory('Admin', function ($resource) {
            return $resource('/api/admin/:website_id/:id', {website_id:'@website_id', id: '@id'}, {update:{method:'PUT'}});
        });

}());
