var app = angular.module("app", []);

app.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
  $routeProvider
  .when('/',{
      templateUrl:"/views/index.html"
    })
  .when('/info',{
      templateUrl:"/views/info.html"
    })
  .when('/travian',{
      controller: TravianCtrl,
      templateUrl:"/views/travian.html"
    })
  .when('/travian/:server/:x/:y',{
      controller: VilSearchCtrl,
      templateUrl:"/views/villageSearch.html"
    })
  .when('/admin',{
      controller: AdminCtrl,
      templateUrl:"/views/admin.html"
  })  
  .when('/admin/addserver',{
      controller: AddServerCtrl,
      templateUrl:"/views/addserver.html"
  })
  .when('/admin/edit/:server',{
      controller: ServerEditCtrl,
      templateUrl:"/views/editServer.html"      
  });

}]);

angular.module('app').factory('Servers', function($http) {
  var Servers = function(data) {
    angular.extend(this, data);
  }
  Servers.getAll = function() {
    return $http.get('api/travian/serverlist/').then(function(response) {
      return new Servers(response.data);
    });
  };
  Servers.get = function(id) {
    return $http.get('api/travian/serverlist/' + id).then(function(response) {
      return new Servers(response.data);
    });
  };
  return Servers;
});