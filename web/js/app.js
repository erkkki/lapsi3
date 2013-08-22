var app = angular.module("app", []);

app.config(['$routeProvider', function($routeProvider) {
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
}]);