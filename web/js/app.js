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