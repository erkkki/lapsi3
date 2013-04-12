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

function AdminCtrl($scope, $http){
  $http({method: 'GET', url: 'api/travian/serverlist/'}).
    success(function(data, status, headers, config) {
      console.log("onnistu");
      $scope.servers = data;
    }).
    error(function(data, status, headers, config) {
      console.log("epäonnistui");
      $scope.alerts.destination = "Error";
  });
}

function ServerEditCtrl($scope, $routeParams) {
  $scope.params = $routeParams;
}