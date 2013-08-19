function villageCtrl($scope, $http, $window, LocalStorage, $log){
  $scope.window = $window;
  $scope.LocalS = LocalStorage;
  $scope.isStorage = $scope.LocalS.isSupported();
  
  $scope.init = function(){
    $scope.showElems = {"settings":true,"results":false,"firstTime":false};
    if($scope.LocalS.get('table')){
      $scope.table = $scope.LocalS.get('table');
    } else {
      $scope.table = {"openAllLink": true,"LocalVil":[],"nextDisable":false};
    };
    if($scope.LocalS.get('lastServer')){
      $scope.opt = $scope.LocalS.get('lastServer');
      if($scope.opt.server != "Select server")
        $scope.showElems.firstTime = true;
    } else {
      $scope.opt = {
        "server":"Select server",
        "x": "0",
        "y": "0",
        "count": 10,
        "limit": 0,
        "players": [],
        "guilds": [],
        "vilminpop": 0,
        "vilmaxpop": 2000,
        "accominpop": 0,
        "accomaxpop": 50000,
        "vilcountmin": 1,
        "vilcountmax": 100,
        "idlemin": 0,
        "idlemax": 50
      };
    };
    $scope.servers();
  };
  $scope.AddPersonlaVil = function(name){
    if(name == null) return;
    if(!$scope.LocalS.isset(name)){
      $scope.LocalS.add(name, $scope.opt);
      $scope.table.LocalVil.push({name:name});
      $scope.newLocalVil = '';
      return;
    } else {}
  }
  
  $scope.openVil = function(name){
    if($scope.LocalS.get(name)){
      $scope.opt = $scope.LocalS.get(name);
    }
  };
  $scope.servers = function(){
    $http.get('api/travian/server/list/')
      .success(function(data) {
        $scope.servers = data;
    });
  };
  $scope.$watch('table', function() {
    $scope.LocalS.add('table',$scope.table);
  }, true);
  
  $scope.$watch('opt', function() {
    $scope.LocalS.add('lastServer',$scope.opt);
    if($scope.opt.limit < 0) $scope.opt.limit = 0;
    if($scope.showElems.settings) $scope.search();
  }, true);
  
  $scope.search = function(){
    if($scope.showElems.settings != true) return;
    if($scope.opt.server == "" || $scope.opt.server == "Select server") return;
    //$scope.villages = {};
    $http.post('api/travian/search', $scope.opt)
      .success(function(data) {
        //$scope.opt.limit = $scope.opt.limit - $scope.opt.count;
        if(data == '\"No villages\"') {
          $scope.villages = '';
          $scope.opt.limit = 0;
          return;
        }
        if(data.length < $scope.opt.count) $scope.table.nextDisable = true;
        else $scope.table.nextDisable = false;
        $scope.villages = data;
        $scope.showElems.results = false;
        $scope.showElems.firstTime = true;
      });
  };
  $scope.playerByName = function(name){
    var queryData = {"server":$scope.opt.server,"name":name}
    $http.post('api/travian/search/player/', queryData)
      .success(function(data) {
        $scope.temp = data;
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
    var temp = [];
    while($scope.villages[i]){
      if(temp.indexOf($scope.villages[i][what]) == -1){
        $scope.window.open("http://"+ $scope.opt.server + address + $scope.villages[i][what]);
        temp.push($scope.villages[i][what]);
      }
      i++;
    } 
  };    
    
}