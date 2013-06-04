function TravianCtrl($scope, $http, $routeParams, Servers){
    $scope.servers = Servers.getAll();
}
function VilSearchCtrl($scope, $http, $routeParams, $location, Servers){
    $scope.servers = Servers.getAll();
    $scope.villages = {};
    
    $scope.opt = {"1":"Hide", "2":"Hide", "3":"Hide", "5":"Hide", 
                  "x":"0", "y":"0", 
                  "server": $routeParams.server,
                  "limit": 0,
                  "count": 20,
                  "orderby":"dist",
                  "notGuilds":"",
                  "notPlayers":""};
     
    $scope.search = function(){
      $http.post('api/travian/search', $scope.opt)
        .success(function(data, status, headers, config) {
          $scope.villages = data;
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
    
    $scope.removePlayer = function(player){
        $scope.opt["notPlayers"] += "," + player;
        $scope.search();
    };

    $scope.removeGuild = function(guild){
        $scope.opt["notGuilds"] += "," + guild;
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
      $scope.addOpt = {"server":"Select Server",
                       "name":"Select Country"};
  }
  $scope.editinit = function(server){
      $scope.getAllServers();
      $scope.countryCodes();
      $scope.editOpt = server;
  }
  $scope.editServer = function(){
      $scope.delServer($scope.editOpt.id);
      $scope.addServer($scope.editOpt);
  };  
  
  $scope.getAllServers = function(){
    $http.get('/api/travian/server/list/all/')
    .success(function(data, status, headers, config) {
      $scope.serverList = data;
    });
  };
  
  $scope.selectServer = function(server){
      $scope.addOpt.server = server;
  };
  $scope.selectCountry = function(country,name){
      $scope.addOpt.country = country;
      $scope.addOpt.name = name;
  };
  $scope.editServer = function(server){
      $scope.editOpt.server = server;
  };
  $scope.editCountry = function(country,name){
      $scope.editOpt.country = country;
      $scope.editOpt.name = name;
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