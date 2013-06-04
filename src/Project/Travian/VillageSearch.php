<?php
namespace Project\Travian;
 
class VillageSearch {

    
    protected $conn;
    protected $playerService;
    protected $villages;
    protected $options;

    public function __construct($conn, PlayerService $playerService){
        $this->conn = $conn;
        $this->playerService = $playerService;
    }
    public function setData($data){
        $this->options = $data;
    }
    private function queryVillages(){
      $ser = str_replace('.','',$this->options->{'server'});
      $statement = $this->conn->prepare("SELECT * , TRUNCATE(abs(sqrt(pow(x-?,2) + pow(y-?,2))),1) AS dist FROM ".$ser.
                                        " WHERE ".  $this->tribequery() . " AND uid NOT IN (" . $this->notPlayers().")" . 
                                        " AND aid NOT IN (" . $this->notGuilds() . 
                                        ")ORDER BY ". $this->options->{'orderby'}." LIMIT ". $this->options->{'limit'} . "," . $this->options->{'count'});
      $statement->execute(array($this->options->{'x'},$this->options->{'y'}));
      $this->villages = $statement->fetchAll();
    } 
    
    private function notPlayers(){
        if($this->options->{'notPlayers'} == null) return "''";
        return substr($this->options->{'notPlayers'}, 1);
    }

    private function notGuilds(){
        if($this->options->{'notGuilds'} == null) return "''";
        return substr($this->options->{'notGuilds'}, 1);
    }

    private function tribequery(){
      $tribes = "tid in ('0'";
      if($this->options->{'1'} == "Hide") $tribes .= ",'1'";
      if($this->options->{'2'} == "Hide") $tribes .= ",'2'";
      if($this->options->{'3'} == "Hide") $tribes .= ",'3'";
      if($this->options->{'5'} == "Hide") $tribes .= ",'5'";
      return $tribes . ")";
    }    
    public function getVillages2(){
        try {
          $this->queryVillages();
        } catch (Exception $e){
          return 'No villages :(';
        }
        if($this->villages != null) 
            //$this->getOwners();
        return json_encode($this->villages);
        
        return true;
        
    }
    
    private function getOwners(){
      $uids = array();
      foreach ($this->villages as $value) {
        array_push($uids, $value['uid']); 
      }
      $owners = $this->playerService->FindPlayersById($this->options->{'server'}, $uids);
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
 
}
?>