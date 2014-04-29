

var app = angular.module("app", ['analytics', 'app.l3api']);

app.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
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
    .when('/servers',{
      templateUrl:"/views/servers.html",
      controller: serversCtrl
    })
   ;
    
  $locationProvider.html5Mode(true);
}]);
