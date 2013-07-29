var app = angular.module("app", ['ui.bootstrap']);

app.config(['$routeProvider', function($routeProvider) {
  $routeProvider
  .when('',{
      templateUrl:"/views/villageSearch.html"
    })
  .when('/admin',{
      controller: AdminCtrl,
      templateUrl:"/views/admin.html"     
  });
}]);