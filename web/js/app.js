var app = angular.module("app", ['analytics']);

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
    .when('/players',{
      templateUrl:"/views/players.html",
      controller: playerCtrl
    })
    .when('/servers',{
      templateUrl:"/views/servers.html",
      controller: serversCtrl
    })
    .when('/guilds',{
      templateUrl:"/views/guilds.html",
      controller: guildCtrl
    }
  );
    
  $locationProvider.html5Mode(true);
}]);
