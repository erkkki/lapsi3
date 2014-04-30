function playerCtrl($scope, $http, $window, LocalStorage, analytics, Servers){
  $scope.LocalS = LocalStorage;
  
  
}


function gameCtrl($scope,$http,$window,$timeout,LocalStorage){
    window.requestAnimFrame = (function(){
        return  window.requestAnimationFrame       ||
            window.webkitRequestAnimationFrame ||
            window.mozRequestAnimationFrame    ||
            window.oRequestAnimationFrame      ||
            window.msRequestAnimationFrame     ||
            function(/* function */ callback, /* DOMElement */ element){
                window.setTimeout(callback, 1000 / 60);
            };
    })();    
    
    var game = function(){
        this.canvas;
        this.ctx;
        this.resources = {'stone':0,'clay':0,'wood':0,'wheat':0,'sum':0};
        this.swingerCount = 1;
        this.killsFactor = 1;
        this.killedEnemies = {'rat':0,'spider':0,'snake':0,'bat':0,'boar':0,
                   'wolf':0,'bear':0,'crocodile':0,'tiger':0,'elephant':0};
        this.swinger = [];
        this.res = [];
        this.enemys = [];
        this.gainText = [];
        this.pause = false;
        this.deathCount = 0;
        this.deathEnd = 100;
        this.endState = false;
        
        
        this.init = function(){
            this.canvas = document.getElementById('game');
            this.ctx = this.canvas.getContext('2d');
            this.state();
            this.resizeCanvas();
            this.swinger.push(new swinger(this.canvas,this.ctx));
            this.generateEnemies();
            this.generateRes();
            animate();
            
        };
        this.resizeCanvas = function() {
            var h = $window.innerHeight - 100,
                w = $window.innerWidth -10;
            this.ctx.canvas.width  = w;
            this.ctx.canvas.height = h;
        };
        this.state = function(state){
            var state = 'pause' || end;
            if(state === 'pause'){
                this.pause = !this.pause;  
                $scope.pause = $scope.game.pause;
                return;
            }
                        
        };
        this.keyDown = function(e){
            if(this.endState)
                return;
            switch (e.keyCode){
                case 32: // space
                    this.state();
                    break;
                case 37: // left
                    if(this.pause)
                       break;
                    this.swinger[0].dir("left");
                    break;
                case 38: // up
                    if(this.pause)
                       break;
                    this.swinger[0].dir("up");
                    break;
                case 39: // right
                    if(this.pause)
                       break;
                    this.swinger[0].dir("right");
                    break;
                case 40: // down
                    if(this.pause)
                       break;
                    this.swinger[0].dir("down");
            }
        };
        this.render = function(){
            if(this.pause)
                return;
            var time = new Date();
            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
            
            for(var res in this.res){
                
                if(this.collDe(this.swinger[0].position, this.res[res].position,16,20)){
                    var value = this.res[res].getValue(this.swingerCount);
                    this.resources[this.res[res].type] += value;
                    this.resources.sum += value;
                    this.gainText.push(new gainText(this.canvas,this.ctx,value,this.res[res].position));
                    this.res.splice(res,1);
                } else if (time > this.res[res].creationTime + 6333){
                    this.res.splice(res,1);
                } else {
                    this.res[res].draw();
                }
            }
            for(var text in this.gainText){
                if (time > this.gainText[text].creationTime + 1000)
                    this.gainText.splice(text,1);
                else
                    this.gainText[text].draw();
            }
            for(var enemy in this.enemys){
                var time = new Date();
                if(this.collDe(this.swinger[0].position, this.enemys[enemy].position,16,20)){
                    var value = this.enemys[enemy].getValue();
                    this.swingerCount -= Math.floor(value/40);
                    this.deathCount += Math.floor(value/40);
                    for (var i = Math.floor(value/40); i > 0;i--){
                        var len = this.swinger.length;
                        this.swinger.splice(len-1,1);
                    }
                    this.killsFactor += value/1000;
                    this.killedEnemies[this.enemys[enemy].type]++;
                    this.gainText.push(new gainText(this.canvas,this.ctx,(value/1000),this.enemys[enemy].position,'blue'));
                    this.enemys.splice(enemy,1);
                } else if (time > this.enemys[enemy].creationTime + 10000){
                    this.enemys.splice(enemy,1);
                } else {
                    this.enemys[enemy].draw();
                }
            }
            var len = this.swinger.length;
            for(var i = len-1; i > -1; i--){
                if(i === 0){
                    if(this.canMove())
                        this.swinger[i].draw(0);
                    else {
                        this.swingerCount -= 1;
                        this.deathCount++;
                        this.swinger.splice(len-1,1);
                    }
                } else { 
                    this.swinger[i].position.x = this.swinger[i-1].position.x;
                    this.swinger[i].position.y = this.swinger[i-1].position.y;
                    this.swinger[i].dir = this.swinger[i-1].direction;
                    this.swinger[i].draw(1);
                }
            }
            if(this.deathCount > this.deathEnd || this.swingerCount < 1){
                this.state();
                this.endState = true;
            }
        };
        this.canMove = function(){
            var newPos = this.swinger[0].getNewPosition();
            for(var unit in this.swinger){
                if(this.collDe(newPos, this.swinger[unit].position, 2,2)){
                    return false;
                }
            }
            return true;         
        };
        this.addUnit = function(){
            var len = this.swinger.length;
            var temp = this.swinger[len-1];
            var x,y;
            switch (temp.direction){
                case ('left'):
                    x = temp.position.x + 16;
                    y = temp.position.y;
                    break;
                case ('right'):
                    x = temp.position.x - 16;
                    y = temp.position.y;
                    break;
                case ('up'):
                    x = temp.position.x;
                    y = temp.position.y + 20;
                    break;
                case ('down'):
                    x = temp.position.x;
                    y = temp.position.y - 20;
                    break;
            }
            var newunit = new swinger(this.canvas,this.ctx, x,y, temp.direction);
            this.swinger.push(newunit);
        };
        this.enoughtRes = function(i){
            var i = i || 1;
            var res = this.resources;
            if(res.wood < 95 * i)
                return false;
            if(res.clay < 75 * i)
                return false;
            if(res.stone < 40 * i)
                return false;
            if(res.wheat < 40 * i)
                return false;
            return true;
        };
        this.duyUnit = function(i){
            var i = i || 1;
            var res = this.resources;
            
            if(!this.enoughtRes(i))
              return;
            res.wood -= 95 * i;
            res.clay -= 75 * i;
            res.stone -= 40 * i;
            res.wheat -= 40 * i;
            this.swingerCount += i;
            for(var u = 0; u < i; u++){
                this.addUnit();
            }
        };
        this.generateRes = function(){
            var self = this;
            var newRes = new resource(this.canvas,this.ctx);
            this.res.push(newRes);
            setTimeout(function(){self.generateRes();}, 1000);
        }; 
        this.generateEnemies = function(){
            var self = this;
            var newEnemy = new enemy(this.canvas,this.ctx);
            this.enemys.push(newEnemy);
            setTimeout(function(){self.generateEnemies();}, 5000);
        };
        this.collDe = function(a,b,w,h){
            var bottom = a.x + h < b.x;
            var top = a.x > b.x + h;
            var left = a.y > b.y + w;
            var right = a.y + w < b.y;
            return !(bottom || top || left || right);
        };
        this.duyMaxDeaths = function(){
            var cost = this.deathEnd/100 * 10000;
            var res = this.resources;
            if(res.wood > cost && res.clay > cost && res.stone > cost && res.wheat > cost){
                res.wood -= cost;
                res.clay -= cost;
                res.stone -= cost;
                res.wheat -= cost;
                this.deathEnd += 10;
            }
            
        };
        
    };
    var gainText = function(canvas,ctx,value,position,color){
        this.canvas = canvas;
        this.ctx = ctx;
        this.value = value;
        this.position = position;
        this.color = color || 'gold';
        this.creationTime = new Date().getTime();        
    };
    gainText.prototype.draw = function(){
        var ctx = this.ctx;
        ctx.fillStyle = this.color;
        ctx.font = "bold 16px Arial";
        ctx.fillText('+'+this.value, this.position.x, this.position.y);
    };    
    var enemy = function(canvas,ctx){
        this.canvas = canvas;
        this.ctx = ctx;
        this.creationTime = new Date().getTime();
        this.position = this.randPlace();
        this.type = this.randType();
        this.sprite = {
            img: this.loadSprite("img/units.png"),
            type: {'rat':0,'spider':18,'snake':36,'bat':56,'boar':76,
                   'wolf':94,'bear':114,'crocodile':132,'tiger':150,'elephant':170},
            imgcount: 10,
            w: 18,
            h: 16
        };
        
    };
    enemy.prototype.randPlace = function(){
        var place = {'x':0,'y':0};
        place.x = Math.floor((Math.random()*(this.canvas.width - 40))+20);        
        place.y = Math.floor((Math.random()*(this.canvas.height - 40))+20);
        return place;
    };
    enemy.prototype.draw = function(){
        var img = this.sprite;
        this.ctx.drawImage(img.img,img.type[this.type],64,img.w,img.h,this.position.x,this.position.y,20,20);
    };
    enemy.prototype.randType = function(){
        var type = Math.floor(Math.random() * 10);
        var types = ['rat','spider','snake','bat','boar','wolf','bear','crocodile','tiger','elephant'];
        return types[type];
    };
    enemy.prototype.getValue = function(){
        var defPoints = {'rat':25,'spider':35,'snake':40,'bat':66,'boar':70,
                        'wolf':80,'bear':140,'crocodile':380,'tiger':170,'elephant':440};
        return defPoints[this.type];
    };
    enemy.prototype.loadSprite = function(name){
        var image = new Image();
        image.src = name;
        return image;
    };
    
    var resource = function(canvas,ctx){
        this.canvas = canvas;
        this.ctx = ctx;
        this.creationTime = new Date().getTime();
        this.position = this.randPlace();
        this.type = this.randType();
        this.typeValue = {'wood':95,'clay':75,'stone':40,'wheat':40};
        this.sprite = {
            img: this.loadSprite("img/units.png"),
            type: {'wood':18,'clay':36,'stone':54,'wheat':78},
            imgcount: 3,
            w: 18,
            h: 16
        };
        
    };
    resource.prototype.loadSprite = function(name){
        var image = new Image();
        image.src = name;
        return image;
    };
    resource.prototype.randType = function(){
        var rand = Math.random();
        switch (true){
            case (rand < 0.25):
                return 'wood';
            case (rand > 0.25 && rand < 0.50):
                return 'clay';
            case (rand > 0.50 && rand < 0.75):
                return 'stone';
            case (rand > 0.75):
                return 'wheat';
        };
    };
    resource.prototype.draw = function(){
        var img = this.sprite;
        this.ctx.drawImage(img.img,img.type[this.type],48,img.w,img.h,this.position.x,this.position.y,20,20);
    };
    resource.prototype.randPlace = function(){
        var place = {'x':0,'y':0};
        place.x = Math.floor((Math.random()*(this.canvas.width - 40))+20);        
        place.y = Math.floor((Math.random()*(this.canvas.height - 40))+20);
        return place;
    };
    resource.prototype.getValue = function(k){
        k = k * 0.2 + 1 || 1;
        return Math.floor(k * this.typeValue[this.type]);
    };

    var swinger = function(canvas,ctx,x,y,dir){
        this.canvas = canvas;
        this.ctx = ctx;
        this.position = {"x": x || 100,"y":y || 100};
        this.direction = dir || "left";
        this.sprite = {
            img: this.loadSprite("img/gameNuti.png"),
            imgcount: 6,
            images: {'type': Math.floor((Math.random() * 6)), 'buf':0},
            w: 16,
            h: 20
        };
    };
    swinger.prototype.loadSprite = function(name){
        var image = new Image();
        image.src = name;
        return image;
    };
    swinger.prototype.dir = function(dir){
        switch (dir){
            case "left":
                this.direction = (this.direction === "right")? this.direction: dir;
                break;
            case "right":
                this.direction = (this.direction === "left")? this.direction: dir;
                break;
            case "up":
                this.direction = (this.direction === "down")? this.direction: dir;
                break;
            case "down":
                this.direction = (this.direction === "up")? this.direction: dir;
                break;
        }
    };
    swinger.prototype.getNewPosition = function(){
        var dir = this.direction;
        var speed = 10; 
        var newPosition = {'x':this.position.x, 'y':this.position.y};
        switch (dir){
            case "left":
                newPosition.x = this.position.x - speed;
                if(newPosition.x < -6)
                    newPosition.x = this.canvas.width -16;
                break;
            case "right":
                newPosition.x = this.position.x + speed;
                if(newPosition.x >  this.canvas.width - 16)
                    newPosition.x = 0;
                break;
            case "up":
                newPosition.y = this.position.y - speed;
                if(newPosition.y < -6)
                    newPosition.y = this.canvas.height -16;
                break;
            case "down":
                newPosition.y = this.position.y + speed;
                if(newPosition.y > this.canvas.height - 16)
                    newPosition.y = 0;
                break;
        }
        return newPosition;
    };
    swinger.prototype.move = function(){
        this.position = this.getNewPosition();
    };   
    swinger.prototype.draw = function(move){
        if(move === 0){
            this.move();
        }
        
        var img = this.sprite;
        
        img.images.buf++;
        if(img.images.buf > 10){
            img.images.buf = 0;
            if(img.images.type > img.imgcount)
                img.images.type = 0;
            else
                img.images.type++;
        }
        this.ctx.drawImage(img.img,img.images.type * 16,0,img.w,img.h,this.position.x,this.position.y,20,20);
        
    };
    
    $scope.game = new game();
    $scope.game.init();
    $window.onresize = (function(){ $scope.game.resizeCanvas(); });
    $window.addEventListener("keydown", (function(e){ $scope.game.keyDown(e); }), true);
    
    $scope.getSum = function(res){
        return parseInt(res.wood) + parseInt(res.clay) + parseInt(res.stone) + parseInt(res.wheat);
    };
    
    (function updater(){
        $timeout(function(){
            $scope.res = $scope.game.resources;
            $scope.swingerCount = $scope.game.swingerCount;
            $scope.deathCount = $scope.game.deathCount;
            $scope.deathEnd = $scope.game.deathEnd;
            $scope.killsFactor = $scope.game.killsFactor.toFixed(3);
            $scope.score = Math.floor($scope.game.killsFactor * $scope.game.resources.sum);
            $scope.killed = $scope.game.killedEnemies;
            if($scope.game.endState)
                $scope.tab = 'end';
            updater();
        },250);
    }());
    
    function animate() {
        requestAnimFrame( animate );
        $scope.game.render();
    }
    
};
function guildCtrl($scope, $http, $window, LocalStorage, analytics){
  $scope.LocalS = LocalStorage;
  
}
function serversCtrl($scope, $http, $location, $window, LocalStorage, analytics, Servers){
  $scope.LocalS = LocalStorage;
  $scope.serversSum = {"players": 0, "villages": 0, "servers": 0};
  $scope.servers = Servers.query({}, function(){
    $scope.stats($scope.servers);
  });

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
        "accomaxpop": 10000000,
        "vilcountmin": 1,
        "vilcountmax": 10000000,
        "idlemin": 0,
        "idlemax": 1000
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