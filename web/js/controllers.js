function TravianCtrl($scope, $http, $routeParams, Servers){
    $scope.servers = Servers.getAll();
}
function VilSearchCtrl($scope, $http, $routeParams, Servers){
    $scope.servers = Servers.getAll();
    $scope.villages = {};
    $scope.limit = 0;
    
    $scope.search = function(){
      $http.get('api/travian/search/' + $routeParams.server + '/' + $routeParams.x + '/' + $routeParams.y + '/' + $scope.limit)
        .success(function(data, status, headers, config) {
          $scope.villages = data;
        })
    };
    
    $scope.next = function(){
        $scope.limit += 20;
        $scope.search();
    };
    $scope.back = function(){
        if($scope.limit == 0) return;
        $scope.limit -= 20;
        $scope.search();
    };

    $scope.search();
}


function AdminCtrl($scope, Servers, $http, $location){
  $scope.servers = Servers.getAll();
  
  $scope.delServer = function(server){
    $http.get('api/travian/delete/'+server)
    .success(function(data, status, headers, config) {
      $scope.servers = Servers.getAll();
    });
  };
}
function AddServerCtrl($scope, $location, $http){
  $scope.addserver = function(server) {
    //console.log(server.name);
    //console.log(server.address);
    $http.get('api/travian/addserver/' + server.name + '/' + server.address)
    .success(function(data, status, headers, config) {
      $location.url('admin');
    });
  };
}
function ServerEditCtrl($scope, $routeParams, $http, Servers, $location) {
  $scope.server = Servers.get($routeParams.server);  
  
  $scope.updatename = function(name) {
    $http.get('api/travian/' + $routeParams.server + '/setname/' + name)
    .success(function(data, status, headers, config) {
      $scope.reset();
    });
  };
  $scope.updateadd = function(add) {
    $http.get('api/travian/' + $routeParams.server + '/setaddress/' + add)
    .success(function(data, status, headers, config) {
      $scope.reset();    
    });
  };
 
  $scope.reset = function() {
    $scope.server = Servers.get($routeParams.server);  
  };

}

