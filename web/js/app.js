var app = angular.module("app", []);

app.config(['$routeProvider', function($routeProvider) {
  
  $routeProvider.when('/',
    {
      templateUrl:"/views/index.html",
      controller:"AppCtrl"
    }
  ).
    when('/info',
    {
      templateUrl:"/views/info.html"
    }
  ).
  when('/travian/:what',
    {
      templateUrl:"/views/travian.html"
    }
  );
}]);