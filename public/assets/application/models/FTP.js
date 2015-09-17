(function () {

    'use strict';

    angular.module('app.main')

        .factory('FTP', function ($resource) {
            return $resource('/api/ftp/:website_id/:id', {id: '@id', website_id:'@website_id'}, {update:{method:'PUT'}});
        });

}());
