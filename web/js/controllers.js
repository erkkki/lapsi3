function playerCtrl($scope, $http, $window, LocalStorage, analytics, Servers){
  $scope.LocalS = LocalStorage;
  
  
}

function guildCtrl($scope, $http, $window, LocalStorage, analytics){
  $scope.LocalS = LocalStorage;
  
}
function serversCtrl($scope, $http, $location, $window, LocalStorage, analytics, Servers){
  $scope.LocalS = LocalStorage;
  $scope.serversSum = {"players": 0, "villages": 0, "servers": 0};
  $scope.servers = Servers.query();

  $scope.stats = function(servers){
    var count = servers.length;
    $scope.serversSum.servers = count;
    for(var i = 0; i< count;i++){
      $scope.serversSum.players += parseInt(servers[i].accountcount);
      $scope.serversSum.villages += parseInt(servers[i].villagecount);
    }
  };
}
function villageCtrl($scope, $http, $window, LocalStorage, analytics, Servers){
  $scope.window = $window;
  $scope.LocalS = LocalStorage;
  $scope.isStorage = $scope.LocalS.isSupported();
  $scope.servers = Servers.query();
    
  $scope.init = function(){
    if($scope.LocalS.get('table')){
      $scope.table = $scope.LocalS.get('table');
    } else {
      $scope.table = {"openAllLink": true,"LocalVil":[],"nextDisable":false,
                      "unit": {
                            "race":"r",
                            "t1":0,"t2":0,"t3":0,
                            "t4":0,"t5":0,"t6":0,
                            "t7":0,"t8":0,"t9":0,
                            "c": 2
                       },
                       "setRows": {
                         "pop": true,
                         "table": false,
                         "players": false,
                         "guilds": false
                       },
                       "elems":{
                         "settings": true, "results": false
                       }
                     };
    }
    if($scope.LocalS.get('lastServer')){
      $scope.opt = $scope.LocalS.get('lastServer');
    } else {
      $scope.opt = {
        "server":"Select server",
        "addressend":"Select domain",
        "x": 0,
        "y": 0,
        "count": 10,
        "limit": 0,
        "players": [],
        "guilds": [],
        "vilminpop": 0,
        "vilmaxpop": 2500,
        "accominpop": 0,
        "accomaxpop": 200000,
        "vilcountmin": 1,
        "vilcountmax": 200,
        "idlemin": 0,
        "idlemax": 50
      };
    }
  };
  
  $scope.AddPersonlaVil = function(name){
    if(name == null) return;
    if(!$scope.LocalS.isset(name)){
      $scope.LocalS.add(name, $scope.opt);
      $scope.table.LocalVil.push({name:name});
      $scope.newLocalVil = '';
      return;
    }
  };
  
  $scope.openVil = function(name){
    if($scope.LocalS.get(name)){
      $scope.opt = $scope.LocalS.get(name);
    }
  };
  
  $scope.serversToFilter = function(){
    indexedServes = [];
    return $scope.servers;
  };
  
  $scope.filterGroupbyEnd = function(server){
    var newServer = indexedServes.indexOf(server.addressend) == -1;
    if (newServer) {
      indexedServes.push(server.addressend);
    }
    return newServer;
  }
  
  $scope.$watch('table', function() {
    $scope.LocalS.add('table',$scope.table);
  }, true);
  
  $scope.$watch('opt', function() {
    $scope.LocalS.add('lastServer',$scope.opt);
    if($scope.opt.limit < 0) $scope.opt.limit = 0;
    if($scope.table.elems.settings) $scope.search();
  }, true);
  
  $scope.search = function(){
    if($scope.table.elems.settings != true) return;
    if($scope.opt.server == "" || $scope.opt.server == "Select server") return;
    $http.post('api/travian/search', $scope.opt)
      .success(function(data) {
        if(data == '\"No villages\"') {
          $scope.villages = '';
          $scope.opt.limit = 0;
          return;
        }
        if(data.length < $scope.opt.count) $scope.table.nextDisable = true;
        else $scope.table.nextDisable = false;
        $scope.villages = data;
        $scope.table.elems.results = false;
      });
  };
  $scope.openall = function(address,what){
    var i = 0;
    if(what == 'x'){
      while($scope.villages[i]){
        $scope.window.open('http://'+ $scope.opt.server + address + $scope.villages[i].x + '&y=' + $scope.villages[i].y);
        i++;
      }
      return;
    }
    if(what == 'id'){
      while($scope.villages[i]){
        $scope.window.open('http://'+ $scope.opt.server + address + $scope.villages[i][what] + 
          '&t1=' + $scope.table.unit.t1 + '&t2=' + $scope.table.unit.t2 + '&t3=' + $scope.table.unit.t3 + 
          '&t4=' + $scope.table.unit.t4 + '&t5=' + $scope.table.unit.t5 + '&t6=' + $scope.table.unit.t6 + 
          '&t7=' + $scope.table.unit.t7 + '&t8=' + $scope.table.unit.t8 + '&t9=' + $scope.table.unit.t9);
        i++;
      }
      return;
    }
    var temp = [];
    while($scope.villages[i]){
      if(temp.indexOf($scope.villages[i][what]) == -1){
        $scope.window.open('http://'+ $scope.opt.server + address + $scope.villages[i][what]);
        temp.push($scope.villages[i][what]);
      }
      i++;
    } 
  };     
}