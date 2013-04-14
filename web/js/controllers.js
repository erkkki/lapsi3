function TravianCtrl($scope, $http){
    $http({method: 'GET', url: 'api/travian/tx3/search/'}).
    success(function(data, status, headers, config) {
      $scope.alerts = data;
    }).
    error(function(data, status, headers, config) {
      console.log("Failed in TravianCtrl");
      $scope.alerts.destination = "Error";
  });
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

