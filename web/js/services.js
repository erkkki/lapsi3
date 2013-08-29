app.service('LocalStorage', function(){
  this.add = function(name,value){
    localStorage.setItem(name,angular.toJson(value));
  };
  this.get = function(name){
    if(!this.isset(name)) return false;
    return angular.fromJson(localStorage.getItem(name));
  };
  this.isset = function(name){
    if (localStorage.getItem(name) === null || localStorage.getItem(name) ===undefined) {
      return false;
    }
    return true;
  };
  this.remove = function(name){
    localStorage.removeItem(name);
  };
  this.update = function(name,newValue){
    this.remove(name);
    this.add(name,newValue);    
  }
  this.isSupported = function(){
    try{
      this.add('test','test123');
    } catch(e){
      return false;
    }
    if(this.get('test') == 'test123'){
      this.remove('test');
      return true;
    }
    this.remove('test');
    return false;
  };
});

angular.module('app.l3api', ['ngResource'])
  .factory('Servers', function($resource){
    return $resource('/api/travian/server/list', {}, {});
  });
  


