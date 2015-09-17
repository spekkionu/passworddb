(function () {

    'use strict';

    angular.module('app.main')

        .factory('ControlPanel', function ($resource) {
            return $resource('/api/controlpanel/:website_id/:id', {website_id:'@website_id', id: '@id'}, {update:{method:'PUT'}});
        });

}());
