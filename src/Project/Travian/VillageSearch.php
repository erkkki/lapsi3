<?php
namespace Project\Travian;
 
class VillageSearch {

    
    protected $conn;
    protected $server;
    protected $x;
    protected $y;
    protected $limit;
    protected $serService;
    protected $villages;
    protected $vilowners;

    public function __construct($conn, ServerList $serverservice){
        $this->conn = $conn;
        $this->serService = $serverservice;
    }
    
    public function getVillages(){
      try {
        $this->queryVillages();
        $this->vilTilastot();
        if($this->villages == '[]'){
          $this->villages = $this->getVillages($this->setCount($this->limit -20));
        }
        return json_encode($this->villages);    
      } catch (Exception $e){
          return 'Shit happens';
      }
    }
    
    public function queryVillages(){
      $statement = $this->conn->prepare('SELECT * , TRUNCATE(abs(sqrt(pow(x-?,2) + pow(y-?,2))),1) AS dist FROM ' . $this->server->{'name'} . ' ORDER BY dist LIMIT '.$this->limit.',20');
      $statement->execute(array($this->x, $this->y));
      $this->villages = $statement->fetchAll();
    }
    
    private function vilTilastot(){
      foreach ($this->villages as $value) {
          $uids .= "'". $value['uid'] . "',"; 
      }
      $uids = substr($uids, 0, -1);
      $statement = $this->conn->prepare('SELECT * FROM ' . $this->server->{'name'} . 'tilastot where uid in ( '. $uids .')');
      $statement->execute();
      $this->vilowners = $statement->fetchAll();
      for($i = 0; $i < 20; $i++){
         //$this->villages[$i]['kokpop'] = $this->vilowners[]['kokpop'];
         //echo array_search($this->villages[$i]['uid'], $this->vilowners[0]);
      }
      /*echo "<pre>";
      var_dump($this->vilowners);
      echo "</pre>";*/
    }

    public function setServer($server){
      $this->server = json_decode($this->serService->getServer($server));
    }
    public function setX($x){
        $this->x = $x;
    }
    public function setY($y){
        $this->y = $y;
    }
    public function setCount($count){
        if(is_int(intval($count))){
          $this->limit = intval($count);
        }
        else {
          $this->limit = 0;
        }
    }    
}


/*  TOIMIVA
SELECT * , TRUNCATE(abs(sqrt(pow(x-0,2) + pow(y-0,2))),1) AS matka FROM ts7 ORDER BY matka LIMIT 0, 40;
*/
?>