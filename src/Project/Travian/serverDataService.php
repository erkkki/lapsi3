<?php

namespace Project\Travian;

class serverDataService {
  private $conn;
  private $tablesS;
  private $serversS;
    
  public function __construct($conn,tableServise $tableServise, activeServers $activeServers){
    $this->conn = $conn;
    $this->tablesS = $tableServise;
    $this->serversS = $activeServers;
  }
  
  public function updateServerData($server){
    $data = $this->getMapsql($server);
    $tableName = str_replace('.','',$server);
      
    if(!$this->tablesS->tableExists($tableName)){
      $this->tablesS->createXworld($tableName);
    }
    $this->conn->exec("UPDATE ".$tableName." SET population= 0 ");
    
    $this->insertSqlData($tableName, $data);
    
    $this->conn->exec("DELETE FROM ".$tableName." WHERE population = 0");
    
    $this->updatePopVil($tableName);
    $this->updateTime($server, $tableName);
    return true;
  }
 
  protected function insertSqlData($tableName,$data){
    $temp = [];
    
    $statement = $this->conn->prepare('INSERT INTO '.$tableName.' VALUES 
                                      (:id,:x,:y,:tid,:vid,:village,:uid,:player,:aid,:alliance,:population,:uidPopulation,:villagecount,:idle)
                                      ON DUPLICATE KEY UPDATE village=:newvillage, 
                                        uid=:newuid,player=:newplayer, aid=:newaid, 
                                        alliance=:newalliance, population=:newpopulation');
    //Insert into params
    $statement->bindParam(':id', $temp['id']);
    $statement->bindParam(':x', $temp['x']);
    $statement->bindParam(':y', $temp['y']);
    $statement->bindParam(':tid', $temp['tid']);
    $statement->bindParam(':vid', $temp['vid']);
    $statement->bindParam(':village', $temp['village']);
    $statement->bindParam(':uid', $temp['uid']);
    $statement->bindParam(':player', $temp['player']);
    $statement->bindParam(':aid', $temp['aid']);
    $statement->bindParam(':alliance', $temp['alliance']);
    $statement->bindParam(':population', $temp['population']);
    $statement->bindParam(':uidPopulation', $temp['uidPopulation']);
    $statement->bindParam(':villagecount', $temp['villagecount']);
    $statement->bindParam(':idle', $temp['idle']);
    //Update params
    $statement->bindParam(':newvillage', $temp['village']);
    $statement->bindParam(':newuid', $temp['uid']);
    $statement->bindParam(':newplayer', $temp['player']);
    $statement->bindParam(':newaid', $temp['aid']);
    $statement->bindParam(':newalliance', $temp['alliance']);
    $statement->bindParam(':newpopulation', $temp['population']);
    
    foreach($data as $village){
      $data = $this->parseVillageData($village);
      if(count($data) == 11){
        $temp['id'] = $data[0];
        $temp['x'] = $data[1];
        $temp['y'] = $data[2];
        $temp['tid'] = $data[3]; 
        $temp['vid'] = $data[4]; 
        $temp['village'] = $data[5];
        $temp['uid'] = $data[6];
        $temp['player'] = $data[7];
        $temp['aid'] = $data[8];
        $temp['alliance'] = $data[9];
        $temp['population'] = $data[10];
        $temp['uidPopulation'] = '0';
        $temp['villagecount'] = '0'; 
        $temp['idle'] = '0';
        
        $statement->execute();
      }
    }   
  }
  
  protected function parseVillageData($village){
    $data = explode(",",substr($village, 30, -2));
    foreach ($data as &$elem) {
      $elem = trim($elem, "'");               
    }
    return $data;
  }
  protected function updateTime($address, $table){
    $statement = $this->conn->prepare('update activeservers set updatetime = ? where address = ?');
    $statement->execute(array(time(), $address));
    
    $statement = $this->conn->prepare('
             update activeservers as servers,
             (select count(id) as vcount, 
              count(distinct uid) as pcount, 
              sum(population)/count(id) as vpopavg, 
              sum(population)/count(distinct uid) as ppopavg, 
              count(id)/count(distinct uid) as pvavg from ' . $table . ') as stats
             set servers.villagecount = stats.vcount,
             servers.villagepopavg = stats.vpopavg, 
             servers.accountcount = stats.pcount,
             servers.accountpopavg = stats.ppopavg,
             servers.accountvillageavg = stats.pvavg');
    $statement->execute(array());
    
    /*
             update activeservers as servers,
             (select count(id) as vcount, 
              count(distinct uid) as pcount, 
              sum(population)/count(id) as vpopavg, 
              sum(population)/count(distinct uid) as ppopavg, 
              count(distinct uid)/count(id) as pvavg from tx3travianfi) as stats
             set servers.villagecount = stats.vcount,
             servers.villagepopavg = stats.vpopavg, 
             servers.accountcount = stats.pcount,
             servers.accountpopavg = stats.ppopavg,
             servers.accountvillageavg = stats.pvavg
       */
  }

  protected function updatePopVil($table){
    $this->conn->exec("update " . $table . " as t1, 
                      (select sum(population) as pop, count(population) as vil, uid from " . $table . " group by uid) as t2
                      set t1.uidPopulation = t2.pop, 
                      t1.villagecount = t2.vil,
                      t1.idle = case when t1.uidPopulation = t2.pop then t1.idle+1 else 0 end
                      where t1.uid = t2.uid");   
  }
  
  protected function getMapsql($address){
    return explode("\n", file_get_contents("http://" . $address . "/map.sql"));
  }
}
?>
