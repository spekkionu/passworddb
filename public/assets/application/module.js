(function () {

    'use strict';

    angular.module('app.main', ['ngRoute', 'ngResource', 'wu.masonry'])
        .constant("Modernizr", Modernizr)

        .config(function ($locationProvider, $routeProvider) {
            $routeProvider.
                when('/', {controller: "ListController", templateUrl: '/assets/application/views/list.html'}).
                // Website Routes
                when('/website/add', {
                    controller: "AddWebsiteController",
                    templateUrl: '/assets/application/views/form.html'
                }).
                when('/website/details/:id', {
                    controller: "DetailsController",
                    templateUrl: '/assets/application/views/details.html'
                }).
                when('/website/edit/:id', {
                    controller: "EditWebsiteController",
                    templateUrl: '/assets/application/views/form.html'
                }).
                when('/website/delete/:id', {
                    controller: "DeleteWebsiteController",
                    templateUrl: '/assets/application/views/delete.html'
                }).
                // FTP Routes
                when('/ftp/:website_id/add', {
                    controller: "AddFTPController",
                    templateUrl: '/assets/application/views/ftp/form.html'
                }).
                when('/ftp/:website_id/:id/edit', {
                    controller: "EditFTPController",
                    templateUrl: '/assets/application/views/ftp/form.html'
                }).
                when('/ftp/:website_id/:id/delete', {
                    controller: "DeleteFTPController",
                    templateUrl: '/assets/application/views/ftp/delete.html'
                }).
                // Database Routes
                when('/database/:website_id/add', {
                    controller: "AddDatabaseController",
                    templateUrl: '/assets/application/views/database/form.html'
                }).
                when('/database/:website_id/:id/edit', {
                    controller: "EditDatabaseController",
                    templateUrl: '/assets/application/views/database/form.html'
                }).
                when('/database/:website_id/:id/delete', {
                    controller: "DeleteDatabaseController",
                    templateUrl: '/assets/application/views/database/delete.html'
                }).
                // Admin Routes
                when('/admin/:website_id/add', {
                    controller: "AddAdminController",
                    templateUrl: '/assets/application/views/admin/form.html'
                }).
                when('/admin/:website_id/:id/edit', {
                    controller: "EditAdminController",
                    templateUrl: '/assets/application/views/admin/form.html'
                }).
                when('/admin/:website_id/:id/delete', {
                    controller: "DeleteAdminController",
                    templateUrl: '/assets/application/views/admin/delete.html'
                }).
                // Control Panel Routes
                when('/controlpanel/:website_id/add', {
                    controller: "AddControlPanelController",
                    templateUrl: '/assets/application/views/controlpanel/form.html'
                }).
                when('/controlpanel/:website_id/:id/edit', {
                    controller: "EditControlPanelController",
                    templateUrl: '/assets/application/views/controlpanel/form.html'
                }).
                when('/controlpanel/:website_id/:id/delete', {
                    controller: "DeleteControlPanelController",
                    templateUrl: '/assets/application/views/controlpanel/delete.html'
                }).

                otherwise({redirectTo: '/'});

            $locationProvider.html5Mode(Modernizr.history);
            if (!Modernizr.history) {
                $locationProvider.hashPrefix = '!';
            }
        });

}());
