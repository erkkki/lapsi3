

var app = angular.module("app", ['app.l3api','angulartics', 'angulartics.google.analytics']);

app.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider, $analyticsProvider) {
  $routeProvider
    .when('',{
      templateUrl:"/views/villages.html",
      controller: villageCtrl
    })
    .when('/',{
      templateUrl:"/views/villages.html",
      controller: villageCtrl
    })
    .when('/villages',{
      templateUrl:"/views/villages.html",
      controller: villageCtrl
    })
    .when('/game',{
      templateUrl:"/views/game.html",
      controller: gameCtrl
    })
    .when('/game2',{
      templateUrl:"/views/game2.html",
      controller: gameCtrl2
    })
    .when('/servers',{
      templateUrl:"/views/servers.html",
      controller: serversCtrl
    })
   ;
    
  $locationProvider.html5Mode(true);
}]);
