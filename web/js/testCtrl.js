function testCtrl($scope, $http, $window, LocalStorage, $log){
  $scope.window = $window;
  $scope.LocalS = LocalStorage;
  $scope.isStorage = $scope.LocalS.isSupported();
  
  $scope.init = function(){
    if($scope.LocalS.get('table')){
      $scope.table = $scope.LocalS.get('table');
    } else {
      $scope.table = {"openAllLink": true,"LocalVil":[]};
    };
    if($scope.LocalS.get('lastServer')){
      $scope.opt = $scope.LocalS.get('lastServer');
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
        "vilcountmax": 100
      };
    };
    $scope.showElems = {"settings":true,"results":true};
    $scope.servers();
    $scope.search();
  };  
  
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
  }, true);
  
  $scope.search = function(){
    if($scope.opt.server == "" || $scope.opt.server == "Select server") return;
    if(!$scope.showElems.settings) { $scope.showElems.results = true; return;}
    $scope.showElems.down = false; // downloading
    
    $http.post('api/travian/search', $scope.opt)
      .success(function(data) {
         $scope.villages = data;
         $scope.showElems.down = true;
         $scope.showElems.results = false;
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