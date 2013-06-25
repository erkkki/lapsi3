<?php
namespace Project\Travian;
 
class VillageSearch {

    
    protected $conn;
    protected $villages;
    protected $options;

    public function __construct($conn){
        $this->conn = $conn;
    }
    public function setData($data){
        $this->options = $data;
    }
    private function queryVillages(){
      $ser = str_replace('.','',$this->options->server);
      $statement = $this->conn->prepare("SELECT * , TRUNCATE(abs(sqrt(pow(x-?,2) + pow(y-?,2))),1) AS dist FROM ".$ser.
                                        " WHERE ".  $this->tribequery() . " " . $this->guilds() . " " .$this->players() . " " . 
                                        "ORDER BY ". $this->orderby() . $this->limitby());
      $statement->execute(array($this->options->x,$this->options->y));
      $this->villages = $statement->fetchAll();
    }
    public function getPlayer($name, $server){
      $ser = str_replace('.','',$this->options->server);
      $statement = $this->conn->prepare("select player from $ser where player LIKE ? group by player");
      $statement->execute(array($name . '%'));
      return $statement->fetchAll();
    }
    private function limitby(){
        $query = " LIMIT ". $this->options->limit . "," . $this->options->count;
        return $query;
    }
    private function orderby(){
        return $this->options->orderby;
    }
    private function players(){
        if(count($this->options->notPlayers) <= 0) return;
        if($this->options->players){
            $query = "AND uid IN (";
        } else {
            $query = "AND uid NOT IN (";
        }
        $uids = array();
        foreach($this->options->notPlayers as $player){
            array_push($uids, $player->uid);
        }
        return $query . implode(",", $uids) . ")";
   }
    
    private function guilds(){
        if(count($this->options->notGuilds) <= 0) return;
        if($this->options->guilds){
            $query = "AND aid IN (";
        } else {
            $query = "AND aid NOT IN (";
        }
        $aids = array();
        foreach($this->options->notGuilds as $player){
            array_push($aids, $player->aid);
        }
        return $query . implode(",", $aids) . ")";
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
        return json_encode($this->villages);
        
        return true;
        
    }
 
}
?>