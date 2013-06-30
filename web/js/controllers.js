function testCtrl($scope){
    $scope.range = {};
}
function VilSearchCtrl($scope, $http, $routeParams, $location, Servers){
    $scope.servers = Servers.getAll();
    $scope.villages = {};
    $scope.settings = true;
    $scope.result = true;
    $scope.players = {"show":"","hide":"disabled"};
    $scope.psearch = {};
    $scope.guilds = {"show":"","hide":"disabled"};
    $scope.servername = "Select server";
    $scope.opt = {"1":"Hide", "2":"Hide", "3":"Hide", "5":"Hide",
                  "x":"0", "y":"0", 
                  "server": $routeParams.server,
                  "limit": 0,
                  "count": 20,
                  "orderby":"dist",
                  "guilds" : "0",
                  "notGuilds":[],
                  "players": "0",
                  "notPlayers":[],
                  "vilminpop": 0,
                  "vilmaxpop": 2000,
                  "accominpop": 0,
                  "accomaxpop": 10000,
                  "vilcountmin": 1,
                  "vilcountmax": 20
              };
    
    $scope.addplayer = function(player){
      if($routeParams.server == "") return;
      data = {};
      data.key = player;
      data.server = $routeParams.server;
      $http.post('api/travian/search/playerbyname/', data)
        .success(function(data, status, headers, config) {
            $scope.removePlayer(data[0].uid, data[0].player);
        });
    };
    
    $scope.searchPlayer = function(temp){
      if($routeParams.server == "" || temp.length <= 0) return;
      data = {};
      data.key = temp;
      data.server = $routeParams.server;
      $http.post('api/travian/search/player/', data)
        .success(function(data, status, headers, config) {
          $scope.psearch = data;
        });
    };
    
    $scope.onlyPlayers = function(){
        if($scope.players.hide == "disabled"){
           $scope.players = {"show":"disabled","hide":""};
           $scope.opt.players = "1";
        } else {
           $scope.players = {"show":"","hide":"disabled"};
           $scope.opt.players = "0";
        }
    };
    $scope.onlyGuilds = function(){
        if($scope.guilds.hide == "disabled"){
           $scope.guilds = {"show":"disabled","hide":""};
           $scope.opt.guilds = "1";
        } else {
           $scope.guilds = {"show":"","hide":"disabled"};
           $scope.opt.guilds = "0";
        }
    };
    $scope.showPlayerCol = function(){
        $scope.settings = !$scope.settings;
        
        
        if($scope.settings && $scope.opt.server != ""){
            $scope.result = false;
            $scope.search();
        } else {
            $scope.result = true;
        }
        
    };
    $scope.search = function(){
      if($routeParams.server == "") return;
      $http.post('api/travian/search', $scope.opt)
        .success(function(data, status, headers, config) {
          $scope.villages = data;
          if(data.length <= 1 || $scope.settings == false){
              $scope.result = true;
          } else {
            $scope.result = false;
          }
          $scope.servername = $scope.opt.server;
        });
    };
    $scope.rtribe = function(id){
        if(angular.equals($scope.opt[id],"Hide")){
            $scope.opt[id] = "Show";
        } else {
            $scope.opt[id] = "Hide"
        }
        $scope.search();
    };    
    $scope.rPinArray = function(uidid){
        var index= $scope.opt.notPlayers.indexOf(uidid)
        $scope.opt.notPlayers.splice(index,1); 
    };
    $scope.rGuildinArray = function(aidid){
        var index= $scope.opt.notGuilds.indexOf(aidid)
        $scope.opt.notGuilds.splice(index,1); 
    };
    $scope.removePlayer = function(uidid,player){
        $scope.opt.notPlayers.push({uid:uidid, name:player});
        $scope.search();
    };
    $scope.removeGuild = function(aidid,guild){
        $scope.opt.notGuilds.push({aid:aidid, name:guild});
        $scope.search();
    };
    
    $scope.setVilCount = function(count){
        $scope.opt["count"] = count;  
        $scope.search();
    }    
    
    $scope.getTribeClass = function(id){
        if(angular.equals($scope.opt[id],"Hide")){
            return "btn-success";
        } else {
            return "btn-danger";
        }
    };
    
    $scope.next = function(){
        $scope.opt["limit"] += 20;
        $scope.search();
    };
    $scope.back = function(){
        if($scope.opt["limit"] == 0) return;
        $scope.opt["limit"] -= 20;
        $scope.search();
    };
    
    $scope.orderby = function(order){
        if(angular.equals($scope.opt["orderby"],order)){
           $scope.opt["orderby"] = order + " DESC"; 
        } else {
           $scope.opt["orderby"] = order;    
        }
        $scope.search();
    };
    
    $scope.search();
}

function AdminCtrl($scope, Servers, $http){
  $scope.servers = Servers.getAll();
  
  $scope.addinit = function(){
      $scope.getAllServers();
      $scope.countryCodes();
  }
  $scope.editinit = function(server){
      $scope.getAllServers();
      $scope.countryCodes();
      $scope.edit = server;
  }
  $scope.editServer = function(data){
    $http.post('/api/travian/server/editserver/', data)
      .success(function(data, status, headers, config) {
        $scope.servers = Servers.getAll();
      });
  };  
  
  $scope.getAllServers = function(){
    $http.get('/api/travian/server/list/all/')
    .success(function(data, status, headers, config) {
      $scope.serverList = data;
    });
  };
  
  $scope.countryCodes = function(){
    $http.get('js/countryCodes.json')
    .success(function(data, status, headers, config) {
      $scope.countrys = data;
    });
  };
  
  $scope.addServer = function(data){
    $http.post('/api/travian/server/add/', data)
      .success(function(data, status, headers, config) {
        $scope.servers = Servers.getAll();
      });
  };
  
  $scope.delServer = function(id){
    $http.get('api/travian/server/delete/'+id)
    .success(function(data, status, headers, config) {
      $scope.servers = Servers.getAll();
    });
  };
  
  $scope.fupdate = function(id){
    $http.get('/api/travian/server/update/'+id)
    .success(function(data, status, headers, config) {
      $scope.servers = Servers.getAll();
    });
  };
}