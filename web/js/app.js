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

app.filter('addressEnd', function(){
  return function (text){
    return temp = text.split(".")[(text.split(".").length)-1]; 
  }
})
app.filter('unique', function(){
  return function(text){
    var i,
        len=text.length,
        out=[],
        obj={};

    for (i=0;i<len;i++) {
      obj[text]=0;
    }
    for (i in obj) {
      out.push(i);
    }
    console.log(out);
    return out;
  }
})