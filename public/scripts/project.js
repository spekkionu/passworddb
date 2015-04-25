var app = angular.module('app', ["ngResource"]);
app.config(function($routeProvider, $locationProvider) {
  $routeProvider.
    when('/', {controller: "ListCtrl", templateUrl: 'views/list.html'}).
    // Website Routes
    when('/website/add', {controller: "AddWebsiteCtrl", templateUrl: '/views/form.html'}).
    when('/website/details/:id', {controller: "DetailsCtrl", templateUrl: '/views/details.html'}).
    when('/website/edit/:id', {controller: "EditWebsiteCtrl", templateUrl: '/views/form.html'}).
    when('/website/delete/:id', {controller: "DeleteWebsiteCtrl", templateUrl: '/views/delete.html'}).
    // FTP Routes
    when('/ftp/:website_id/add', {controller: "AddFTPCtrl", templateUrl: '/views/ftp/form.html'}).
    when('/ftp/:website_id/:id/edit', {controller: "EditFTPCtrl", templateUrl: '/views/ftp/form.html'}).
    when('/ftp/:website_id/:id/delete', {controller: "DeleteFTPCtrl", templateUrl: '/views/ftp/delete.html'}).
    // Database Routes
    when('/database/:website_id/add', {controller: "AddDatabaseCtrl", templateUrl: '/views/database/form.html'}).
    when('/database/:website_id/:id/edit', {controller: "EditDatabaseCtrl", templateUrl: '/views/database/form.html'}).
    when('/database/:website_id/:id/delete', {controller: "DeleteDatabaseCtrl", templateUrl: '/views/database/delete.html'}).
    // Admin Routes
    when('/admin/:website_id/add', {controller: "AddAdminCtrl", templateUrl: '/views/admin/form.html'}).
    when('/admin/:website_id/:id/edit', {controller: "EditAdminCtrl", templateUrl: '/views/admin/form.html'}).
    when('/admin/:website_id/:id/delete', {controller: "DeleteAdminCtrl", templateUrl: '/views/admin/delete.html'}).
    // Control Panel Routes
    when('/controlpanel/:website_id/add', {controller: "AddControlPanelCtrl", templateUrl: '/views/controlpanel/form.html'}).
    when('/controlpanel/:website_id/:id/edit', {controller: "EditControlPanelCtrl", templateUrl: '/views/controlpanel/form.html'}).
    when('/controlpanel/:website_id/:id/delete', {controller: "DeleteControlPanelCtrl", templateUrl: '/views/controlpanel/delete.html'}).

    otherwise({redirectTo: '/'});
    $locationProvider.html5Mode(window.history && window.history.pushState);
   $locationProvider.hashPrefix = '!';
});

app.factory('Search',function(){
  return {text:'', sort: 'name', dir:"ASC", page: 1, limit: 20, next: false};
});

/**
 * Website Resource
 */
app.factory('Website', function($resource){
  return $resource('/api/website/:id', {id: '@id'}, {update:{method:'PUT'}});
});

/**
 * FTP Resource
 */
app.factory('FTP', function($resource){
  return $resource('/api/ftp/:website_id/:id', {id: '@id', website_id:'@website_id'}, {update:{method:'PUT'}});
});

/**
 * Admin login Resource
 */
app.factory('Admin', function($resource){
  return $resource('/api/admin/:website_id/:id', {website_id:'@website_id', id: '@id'}, {update:{method:'PUT'}});
});

/**
 * Database Resource
 */
app.factory('Database', function($resource){
  return $resource('/api/database/:website_id/:id', {website_id:'@website_id', id: '@id'}, {update:{method:'PUT'}});
});

/**
 * Control Panel Resource
 */
app.factory('ControlPanel', function($resource){
  return $resource('/api/controlpanel/:website_id/:id', {website_id:'@website_id', id: '@id'}, {update:{method:'PUT'}});
});

/**
 * List Websites
 */
app.controller("ListCtrl", function($scope, $resource, Website, Search) {
  $scope.search = Search;
  $scope.loadData = function(){
    var offset = ($scope.search.page - 1) * $scope.search.limit;
    Website.query({offset: offset, limit: $scope.search.limit + 1, search: $scope.search.text, sort:$scope.search.sort, dir:$scope.search.dir}, function(response){
      if(response.length > $scope.search.limit){
        // There is another page
        response.pop();
        $scope.search.next = true;
      } else {
        $scope.search.next = false;
      }
      $scope.websites = response;
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

/**
 * Website Search
 */
app.controller("SearchCtrl", function($scope, Search, $location) {
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

/**
 * Website Detail Page
 * Show lists of child data
 */
app.controller("DetailsCtrl", function($scope, $resource, $routeParams, Website) {
  $scope.website = Website.get({id:$routeParams.id});
});

/**
 * Add website form
 */
app.controller("AddWebsiteCtrl", function($scope, $resource, Website, $location, $routeParams) {
  $scope.legend = "Add Website";
  $scope.back_url = "/";
  $scope.website = new Website({});
  $scope.saveWebsite = function(){
    if($scope.formWebsite.$valid){
      $scope.website.$save({website_id:$routeParams.website_id}, function(added_website){
        $scope.website = added_website;
        $location.path('/website/details/'+added_website.id).replace();
      }, function(response, postReponseHeaders){
        console.log(response, postReponseHeaders);
      });
    }
  };
});

/**
 * Update website form
 */
app.controller("EditWebsiteCtrl", function($scope, $resource, $routeParams, Website, $location) {
  $scope.legend = "Edit Website";
  $scope.back_url = "/website/details/"+$routeParams.id;
  $scope.website = Website.get({id:$routeParams.id});
  $scope.saveWebsite = function(){
    if($scope.formWebsite.$valid){
      $scope.website.$update({website_id:$routeParams.website_id}, function(updated_website){
        $scope.website = updated_website;
        $location.path('/website/details/'+updated_website.id).replace();
      }, function(response){
        if(response.status == 400){
          angular.forEach(response.data.errors, function(errors, name){
            angular.forEach(errors, function(message, type){
              $scope.formWebsite[name].$setValidity(type, false);
            });
          });
        } else if (response.message){
          console.log(response.message);
        }
      });
    }
  };
});

/**
 * Delete a website
 */
app.controller("DeleteWebsiteCtrl", function($scope, $resource, $routeParams, Website, $location) {
  $scope.website = Website.get({id:$routeParams.id});
  $scope.deleteWebsite = function(){
    $scope.website.$delete(function(){
      $location.path('/').replace();
    });
  };
});

/**
 * Add FTP credentials form
 */
app.controller("AddFTPCtrl", function($scope, $resource, $routeParams, Website, FTP, $location) {
  $scope.legend = "Add FTP Credentials";
  $scope.back_url = "/website/"+$routeParams.website_id;
  $scope.website = Website.get({id:$routeParams.website_id});
  $scope.ftp = new FTP({website_id: $routeParams.website_id, type: 'ftp'});
  $scope.types = [{value:'ftp', label:'FTP'}, {value:'sftp', label:'SFTP'}, {value:'ftps', label:'FTPS'}, {value:'webdav', label:'WebDAV'}, {value:'other', label:'Other'}];
  $scope.saveFTP = function(){
    if($scope.formFTP.$valid){
      $scope.ftp.$save(function(added_record){
        $scope.ftp = added_record;
        $location.path('/website/details/'+added_record.website_id).replace();
      }, function(response){
        if(response.status == 400){
          angular.forEach(response.errors, function(errors, name){
            angular.forEach(errors, function(message, type){
              $scope.formFTP[name].$setValidity(type, false);
            });
          });
        } else if (response.message){
          console.log(response.message);
        }
      });
    }
  };
});

/**
 * Update FTP credentials form
 */
app.controller("EditFTPCtrl", function($scope, $resource, $routeParams, Website, FTP, $location) {
  $scope.legend = "Update FTP Credentials";
  $scope.back_url = "/website/details/"+$routeParams.website_id;
  $scope.website = Website.get({id:$routeParams.website_id});
  $scope.ftp = FTP.get({id:$routeParams.id, website_id:$routeParams.website_id});
  $scope.types = [{value:'ftp', label:'FTP'}, {value:'sftp', label:'SFTP'}, {value:'ftps', label:'FTPS'}, {value:'webdav', label:'WebDAV'}, {value:'other', label:'Other'}];
  $scope.saveFTP = function(){
    if($scope.formFTP.$valid){
      $scope.ftp.$update(function(updated_record){
        $scope.ftp = updated_record;
        $location.path('/website/details/'+updated_record.website_id).replace();
      }, function(response){
        if(response.status == 400){
          angular.forEach(response.data.errors, function(errors, name){
            angular.forEach(errors, function(message, type){
              $scope.formFTP[name].$setValidity(type, false);
            });
          });
        } else if (response.message){
          console.log(response.message);
        }
      });
    }
  };
});

/**
 * Delete FTP Credentials
 */
app.controller("DeleteFTPCtrl", function($scope, $resource, $routeParams, Website, FTP, $location) {
  $scope.website = Website.get({id:$routeParams.website_id});
  $scope.ftp = FTP.get({id:$routeParams.id, website_id:$routeParams.website_id});
  $scope.deleteFTP = function(){
    $scope.ftp.$delete(function(){
      $location.path('/website/details/'+$scope.website.id).replace();
    });
  };
});

/**
 * Add Database login form
 */
app.controller("AddDatabaseCtrl", function($scope, $resource, $routeParams, Website, Database, $location) {
  $scope.legend = "Add Database Credentials";
  $scope.back_url = "/website/"+$routeParams.website_id;
  $scope.website = Website.get({id:$routeParams.website_id});
  $scope.database = new Database({website_id: $routeParams.website_id, type: 'mysql'});
  $scope.types = [{value:'mysql', label:'MySQL'}, {value:'sqlite', label:'SQLite'}, {value:'mssql', label:'MSSQL'}, {value:'oracle', label:'Oracle Database'}, {value:'pgsql', label:'Postgres'}, {value:'access', label:'Microsoft Access'}, {value:'other', label:'Other'}];
  $scope.saveDatabase = function(){
    if($scope.formDatabase.$valid){
      $scope.database.$save(function(added_record){
        $scope.database = added_record;
        $location.path('/website/details/'+added_record.website_id).replace();
      }, function(response){
        if(response.status == 400){
          angular.forEach(response.errors, function(errors, name){
            angular.forEach(errors, function(message, type){
              $scope.formDatabase[name].$setValidity(type, false);
            });
          });
        } else if (response.message){
          console.log(response.message);
        }
      });
    }
  };
});

/**
 * Update database login form
 */
app.controller("EditDatabaseCtrl", function($scope, $resource, $routeParams, Website, Database, $location) {
  $scope.legend = "Update Database Login Credentials";
  $scope.back_url = "/website/details/"+$routeParams.website_id;
  $scope.website = Website.get({id:$routeParams.website_id});
  $scope.database = Database.get({id:$routeParams.id, website_id:$routeParams.website_id, type: 'mysql'});
  $scope.types = [{value:'mysql', label:'MySQL'}, {value:'sqlite', label:'SQLite'}, {value:'mssql', label:'MSSQL'}, {value:'oracle', label:'Oracle Database'}, {value:'pgsql', label:'Postgres'}, {value:'access', label:'Microsoft Access'}, {value:'other', label:'Other'}];
  $scope.saveDatabase = function(){
    if($scope.formDatabase.$valid){
      $scope.database.$update(function(updated_record){
        $scope.database = updated_record;
        $location.path('/website/details/'+updated_record.website_id).replace();
      }, function(response){
        if(response.status == 400){
          angular.forEach(response.data.errors, function(errors, name){
            angular.forEach(errors, function(message, type){
              $scope.formDatabase[name].$setValidity(type, false);
            });
          });
        } else if (response.message){
          console.log(response.message);
        }
      });
    }
  };
});

/**
 * Delete database login
 */
app.controller("DeleteDatabaseCtrl", function($scope, $resource, $routeParams, Website, Database, $location) {
  $scope.website = Website.get({id:$routeParams.website_id});
  $scope.database = Database.get({id:$routeParams.id, website_id:$routeParams.website_id});
  $scope.deleteDatabase = function(){
    $scope.database.$delete(function(){
      $location.path('/website/details/'+$routeParams.website_id).replace();
    });
  };
});

/**
 * Add Admin login form
 */
app.controller("AddAdminCtrl", function($scope, $resource, $routeParams, Website, Admin, $location) {
  $scope.legend = "Add Admin Login Credentials";
  $scope.back_url = "/website/"+$routeParams.website_id;
  $scope.website = Website.get({id:$routeParams.website_id});
  $scope.admin = new Admin({website_id: $routeParams.website_id});
  $scope.saveAdmin = function(){
    if($scope.formAdmin.$valid){
      $scope.admin.$save(function(added_record){
        $scope.admin = added_record;
        $location.path('/website/details/'+added_record.website_id).replace();
      }, function(response){
        if(response.status == 400){
          angular.forEach(response.errors, function(errors, name){
            angular.forEach(errors, function(message, type){
              $scope.formAdmin[name].$setValidity(type, false);
            });
          });
        } else if (response.message){
          console.log(response.message);
        }
      });
    }
  };
});

/**
 * Update admin login form
 */
app.controller("EditAdminCtrl", function($scope, $resource, $routeParams, Website, Admin, $location) {
  $scope.legend = "Update Admin Login Credentials";
  $scope.back_url = "/website/details/"+$routeParams.website_id;
  $scope.website = Website.get({id:$routeParams.website_id});
  $scope.admin = Admin.get({id:$routeParams.id, website_id:$routeParams.website_id});
  $scope.saveAdmin = function(){
    if($scope.formAdmin.$valid){
      $scope.admin.$update(function(updated_record){
        $scope.admin = updated_record;
        $location.path('/website/details/'+updated_record.website_id).replace();
      }, function(response){
        if(response.status == 400){
          angular.forEach(response.data.errors, function(errors, name){
            angular.forEach(errors, function(message, type){
              $scope.formAdmin[name].$setValidity(type, false);
            });
          });
        } else if (response.message){
          console.log(response.message);
        }
      });
    }
  };
});

/**
 * Delete admin login
 */
app.controller("DeleteAdminCtrl", function($scope, $resource, $routeParams, Website, Admin, $location) {
  $scope.website = Website.get({id:$routeParams.website_id});
  $scope.admin = Admin.get({id:$routeParams.id, website_id:$routeParams.website_id});
  $scope.deleteAdmin = function(){
    $scope.admin.$delete(function(){
      $location.path('/website/details/'+$routeParams.website_id).replace();
    });
  };
});

/**
 * Add Control Panel login form
 */
app.controller("AddControlPanelCtrl", function($scope, $resource, $routeParams, Website, ControlPanel, $location) {
  $scope.legend = "Add Control Panel Login Credentials";
  $scope.back_url = "/website/"+$routeParams.website_id;
  $scope.website = Website.get({id:$routeParams.website_id});
  $scope.controlpanel = new ControlPanel({website_id: $routeParams.website_id});
  $scope.saveControlPanel = function(){
    if($scope.formControlPanel.$valid){
      $scope.controlpanel.$save(function(added_record){
        $scope.controlpanel = added_record;
        $location.path('/website/details/'+added_record.website_id).replace();
      }, function(response){
        if(response.status == 400){
          angular.forEach(response.errors, function(errors, name){
            angular.forEach(errors, function(message, type){
              $scope.formControlPanel[name].$setValidity(type, false);
            });
          });
        } else if (response.message){
          console.log(response.message);
        }
      });
    }
  };
});

/**
 * Update control panel login form
 */
app.controller("EditControlPanelCtrl", function($scope, $resource, $routeParams, Website, ControlPanel, $location) {
  $scope.legend = "Update Control Panel Login Credentials";
  $scope.back_url = "/website/details/"+$routeParams.website_id;
  $scope.website = Website.get({id:$routeParams.website_id});
  $scope.controlpanel = ControlPanel.get({id:$routeParams.id, website_id:$routeParams.website_id});
  $scope.saveControlPanel = function(){
    if($scope.formControlPanel.$valid){
      $scope.controlpanel.$update(function(updated_record){
        $scope.controlpanel = updated_record;
        $location.path('/website/details/'+updated_record.website_id).replace();
      }, function(response){
        if(response.status == 400){
          angular.forEach(response.data.errors, function(errors, name){
            angular.forEach(errors, function(message, type){
              $scope.formControlPanel[name].$setValidity(type, false);
            });
          });
        } else if (response.message){
          console.log(response.message);
        }
      });
    }
  };
});

/**
 * Delete control panel login
 */
app.controller("DeleteControlPanelCtrl", function($scope, $resource, $routeParams, Website, ControlPanel, $location) {
  $scope.website = Website.get({id:$routeParams.website_id});
  $scope.controlpanel = ControlPanel.get({id:$routeParams.id, website_id:$routeParams.website_id});
  $scope.deleteControlPanel = function(){
    $scope.controlpanel.$delete(function(){
      $location.path('/website/details/'+$routeParams.website_id).replace();
    });
  };
});
