function testCtrl($scope){
    $scope.range = {};
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