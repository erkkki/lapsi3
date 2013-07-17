var app = angular.module("app", ['ui.bootstrap']);

app.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
  $routeProvider
  .when('/',{
      templateUrl:"/views/info.html"
    })
  .when('/info',{
      templateUrl:"/views/info.html"
    })
  .when('/test',{
      templateUrl:"/views/test.html"
    })
  .when('/travian/',{
      templateUrl:"/views/villageSearch.html"
    })
  .when('/admin',{
      controller: AdminCtrl,
      templateUrl:"/views/admin.html"     
  });

}]);

angular.module('app')
  .factory('Servers', function($http) {
    var Servers = function(data) {
      angular.extend(this, data);
    }
    Servers.getAll = function() {
      return $http.get('/api/travian/server/list/').then(function(response) {
        return new Servers(response.data);
      });
    };
    Servers.get = function(id) {
      return $http.get('/api/travian/server/by/' + id).then(function(response) {
        return new Servers(response.data);
      });
    };
    return Servers;
  });