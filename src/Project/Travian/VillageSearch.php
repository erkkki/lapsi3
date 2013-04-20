<?php
namespace Project\Travian;
 
class VillageSearch {

    
    protected $conn;
    protected $server;
    protected $kord = array("x" => "", "y" => "");
    protected $limit;
    protected $serService;
    protected $playerService;
    protected $villages;

    public function __construct($conn, ServerList $serverservice, PlayerService $playerService){
        $this->conn = $conn;
        $this->serService = $serverservice;
        $this->playerService = $playerService;
    }
    
    public function getVillages(){
      try {
        $this->queryVillages();
        if($this->villages == '[]'){
          $this->villages = $this->getVillages($this->setCount($this->limit -20));
        }
        $this->getOwners();
        return json_encode($this->villages);    
      } catch (Exception $e){
          return 'Shit happens';
      }
    }
    private function getOwners(){
      $uids = array();
      foreach ($this->villages as $value) {
        array_push($uids, $value['uid']); 
      }
      $owners = $this->playerService->FindPlayersById($this->server->{'name'}, $uids);
      $keys = array();
      foreach ($owners as $key => $value){
          array_push($keys, $value['uid']);
      }
      foreach ($this->villages as $vikey => $value){
        $key = array_search($value['uid'], $keys);
        $this->villages[$vikey]['kokpop'] = $owners[$key]['kokpop'];
        $this->villages[$vikey]['villagecount'] = $owners[$key]['kylia'];
        $this->villages[$vikey]['idle'] = $owners[$key]['idle'];
      }
    }
    
    private function queryVillages(){
      $statement = $this->conn->prepare('SELECT * , TRUNCATE(abs(sqrt(pow(x-?,2) + pow(y-?,2))),1) AS dist FROM ' . $this->server->{'name'} . ' ORDER BY dist LIMIT '.$this->limit.',20');
      $statement->execute(array($this->kord['x'],$this->kord['y']));
      $this->villages = $statement->fetchAll();
    }

    public function setServer($server){
      $this->server = json_decode($this->serService->getServer($server));
    }
    public function setX($x){
        $this->kord['x'] = $x;
    }
    public function setY($y){
        $this->kord['y'] = $y;
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
?>