var app = angular.module("app", []);

app.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
  $routeProvider
  .when('/',{
      templateUrl:"/views/info.html"
    })
  .when('/info',{
      templateUrl:"/views/info.html"
    })
  .when('/test',{
      controller: TravianCtrl,
      templateUrl:"/views/test.html"
    })
  .when('/travian/:server',{
      controller: VilSearchCtrl,
      templateUrl:"/views/villageSearch.html"
    })
  .when('/travian/',{
      controller: VilSearchCtrl,
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