function testCtrl($scope, $http, $window){
  $scope.window = $window;
  
  $scope.servers = function(){
    $http.get('api/travian/server/list/')
      .success(function(data) {
        $scope.servers = data;
    });
  };
  
  $scope.search = function(){
    if($scope.opt.server == "" || $scope.opt.server == "Select server") return;
    if(!$scope.settings) {$scope.results = true; return;}
    $scope.down = false; // downloading
    
    $http.post('api/travian/search', $scope.opt)
      .success(function(data) {
         $scope.villages = data;
         $scope.down = true;
         $scope.results = false;
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