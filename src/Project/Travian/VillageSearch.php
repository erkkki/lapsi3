<?php
namespace Project\Travian;
 
class VillageSearch {

    
    protected $conn;
    protected $server;
    protected $x;
    protected $y;
    protected $limit;
    protected $serService;

    public function __construct($conn, ServerList $serverservice){
        $this->conn = $conn;
        $this->serService = $serverservice;
    }
    
    public function getVillages(){
      $statement = $this->conn->prepare('SELECT * , TRUNCATE(abs(sqrt(pow(x-?,2) + pow(y-?,2))),1) AS dist FROM ' . $this->server->{'name'} . ' ORDER BY dist LIMIT '.$this->limit.',20');
      $statement->execute(array($this->x, $this->y));
      $villages = json_encode($statement->fetchAll());
      if($villages == '[]'){
          $villages = $this->getVillages($this->setCount($this->limit -20));
      }
      return  $villages;

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