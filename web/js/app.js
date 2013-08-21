var app = angular.module("app", []);

app.config(['$routeProvider', function($routeProvider) {
  $routeProvider
    .when('',{
      templateUrl:"/views/villages.html",
      controller: villageCtrl
    })
    .when('/players',{
      templateUrl:"/views/players.html"
    })
    .when('/guilds',{
      templateUrl:"/views/guilds.html"
    }
  );
}]);