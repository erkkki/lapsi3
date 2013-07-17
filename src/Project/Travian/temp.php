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
      if($this->options->x == null)$this->options->x = 0;
      if($this->options->y == null)$this->options->y = 0;
      $statement = $this->conn->prepare("SELECT * , TRUNCATE(abs(sqrt(pow(x-?,2) + pow(y-?,2))),1) AS dist FROM ".$ser.
                                        " WHERE ".  $this->tribequery() . " " . $this->guilds() . " " .$this->players() . " " . 
                                        $this->vilPopulationquery() . " " . $this->accountPopulationquery() . " " .
                                        $this->villageCountquery() . " " .
                                        "ORDER BY ". $this->orderby() . $this->limitby());
      $statement->execute(array($this->options->x,$this->options->y));
      $this->villages = $statement->fetchAll();
    }
    private function vilPopulationquery(){
        if($this->options->vilminpop == "" && $this->options->vilmaxpop == "") return;
        if($this->options->vilminpop == ""){ $min = 0; } else $min = $this->options->vilminpop; 
        if($this->options->vilmaxpop == ""){ $max = 2000; } else $max = $this->options->vilmaxpop;
        
        return "AND population BETWEEN " . $min . " AND " . $max;
    }
    private function accountPopulationquery(){
        if($this->options->accominpop == "" && $this->options->accomaxpop == "") return;
        if($this->options->accominpop == ""){ $min = 0; } else $min = $this->options->accominpop; 
        if($this->options->accomaxpop == ""){ $max = 100000; } else $max = $this->options->accomaxpop;
        
        return "AND uidPopulation BETWEEN " . $min . " AND " . $max;
    }
    private function villageCountquery(){
        if($this->options->vilcountmin == "" && $this->options->vilcountmax == "") return;
        if($this->options->vilcountmin == ""){ $min = 1; } else $min = $this->options->vilcountmin; 
        if($this->options->vilcountmax == ""){ $max = 200; } else $max = $this->options->vilcountmax;
        
        return "AND villagecount BETWEEN " . $min . " AND " . $max;
    }
    public function getPlayer($name, $server){
      $ser = str_replace('.','',$server);
      $statement = $this->conn->prepare("select * from $ser where player LIKE ? group by player");
      $statement->execute(array($name . '%'));
      return $statement->fetchAll();       
    }
    public function getGuild($name, $server){
      $ser = str_replace('.','',$server);
      $statement = $this->conn->prepare("select * from $ser where alliance LIKE ? group by alliance");
      $statement->execute(array($name . '%'));
      return $statement->fetchAll();       
    }
    private function limitby(){
        if($this->options->limit == null)$this->options->limit = 0;
        if($this->options->count == null)$this->options->count = 20;
        $query = " LIMIT ". $this->options->limit . "," . $this->options->count;
        return $query;
    }
    private function orderby(){
        if($this->options->orderby == null) return "dist";
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
      return "tid in ('1','2','3','5')";
      $tribes = "tid in ('0'";
      if($this->options->{'1'} == "Hide") $tribes .= ",'1'";
      if($this->options->{'2'} == "Hide") $tribes .= ",'2'";
      if($this->options->{'3'} == "Hide") $tribes .= ",'3'";
      if($this->options->{'5'} == "Hide") $tribes .= ",'5'";
      return $tribes . ")";
    }    
    public function getVillages(){
        try {
          $this->queryVillages();
        } catch (Exception $e){
          return 'No villages :(';
        }
        if($this->villages != null) 
        return $this->villages;        
    }
 
}
?>