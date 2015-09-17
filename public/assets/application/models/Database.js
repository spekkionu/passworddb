(function () {

    'use strict';

    angular.module('app.main')

        .factory('Database', function ($resource) {
            return $resource('/api/database/:website_id/:id', {website_id:'@website_id', id: '@id'}, {update:{method:'PUT'}});
        });

}());
