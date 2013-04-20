var app = angular.module("app", []);

app.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
  $routeProvider
  .when('/',{
      templateUrl:"/api/views/index"
    })
  .when('/info',{
      templateUrl:"/api/views/info"
    })
  .when('/travian',{
      controller: TravianCtrl,
      templateUrl:"/api/views/travian"
    })
  .when('/travian/:server/:x/:y',{
      controller: VilSearchCtrl,
      templateUrl:"/api/views/villageSearch"
    })
  .when('/admin',{
      controller: AdminCtrl,
      templateUrl:"/api/views/admin"
  })  
  .when('/admin/addserver',{
      controller: AddServerCtrl,
      templateUrl:"/api/views/addserver"
  })
  .when('/admin/edit/:server',{
      controller: ServerEditCtrl,
      templateUrl:"/api/views/editServer"      
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