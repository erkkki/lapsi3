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
  .when('/admin',{
      controller: AdminCtrl,
      templateUrl:"/views/admin.html"
  })
  .when('/admin/edit/:server',{
      controller: ServerEditCtrl,
      templateUrl:"/views/editServer.html"      
  });

}]);