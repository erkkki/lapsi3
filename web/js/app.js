var app = angular.module("app", []);

app.config(function ($routeProvider){
  $routeProvider.when('/',
    {
      templateUrl:"/views/index.html",
      controller:"AppCtrl"
    }
  )
  $routeProvider.when('/info',
    {
      templateUrl:"/views/info.html"
    }
  )
  $routeProvider.when('/travian/:what',
    {
      templateUrl:"/views/travian.html"
    }
  )
})

app.controller("AppCtrl", function ($scope){
  
})