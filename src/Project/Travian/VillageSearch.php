<?php
namespace Project\Travian;
 
class VillageSearch {

    
  protected $conn;
  protected $villages;
  protected $post;

  public function __construct($conn){
    $this->conn = $conn;
  }
  public function setData($data){
    $this->post = $data;
    $this->post->table = str_replace('.','',$this->post->server);
    
    $this->post->vilminpop = (is_int($data->x) ? $data->x : 0);
    $this->post->vilminpop = (is_int($data->y) ? $data->y : 0);
    
    $this->post->count = (is_int($data->count) ? $data->count : 20);
    $this->post->limit = (is_int($data->limit) ? $data->limit : 0);
    
    $this->post->roman = @($data->tribes->roman ? '' : '1');
    $this->post->teuton = @($data->tribes->teuton ? '' : '2');
    $this->post->gaul = @($data->tribes->gaul ? '' : '3');
    $this->post->natar = @($data->tribes->natar ? '' : '5');
    
    $this->post->vilminpop = (is_int($data->vilminpop) ? $data->vilminpop : '0');
    $this->post->vilmaxpop = (is_int($data->vilmaxpop) ? $data->vilmaxpop : '2000');
    
    $this->post->accominpop = (is_int($data->accominpop) ? $data->accominpop : '0');
    $this->post->accomaxpop = (is_int($data->accomaxpop) ? $data->accomaxpop : '50000');   
    
    $this->post->vilcountmin = (is_int($data->vilcountmin) ? $data->vilcountmin : '1');
    $this->post->vilcountmax = (is_int($data->vilcountmax) ? $data->vilcountmax : '100');     
    
    $this->post->idlemin = (is_int($data->idlemin) ? $data->idlemin : '0');
    $this->post->idlemax = (is_int($data->idlemax) ? $data->idlemax : '50');  
    
    $this->post->playersquery = @$this->inquerry('uid',$data->onlyplayers, $data->players);
    
    $this->post->quildquery = @$this->inquerry('aid',$data->onlyguilds, $data->guilds);
    
  }
  public function searchVillages(){
    return $this->squery();
  }
  private function squery(){
    $opt = &$this->post;
    $statement = $this->conn->prepare("SELECT * , TRUNCATE(abs(sqrt(pow(x-:x,2) + pow(y-:y,2))),1) AS dist FROM ".$opt->table." 
                                      WHERE tid IN (:tid1,:tid2,:tid3,:tid5)
                                      AND population BETWEEN :vilminpop AND :vilmaxpop  
                                      AND uidPopulation BETWEEN :accminpop AND :accmaxpop
                                      AND villagecount BETWEEN :vilmin AND :vilmax
                                      AND idle BETWEEN :idlemin AND :idlemax
                                      ".$opt->playersquery . $opt->quildquery."
                                      ORDER BY dist
                                      LIMIT ".$opt->limit.",".$opt->count);
    
    $statement->execute(array(':x' => $opt->x,
                              ':y' => $opt->y,
                              ':tid1' => $opt->roman,
                              ':tid2' => $opt->teuton,
                              ':tid3' => $opt->gaul,
                              ':tid5' => $opt->natar,
                              ':vilminpop' => $opt->vilminpop,
                              ':vilmaxpop' => $opt->vilmaxpop,
                              ':accminpop' => $opt->accominpop,
                              ':accmaxpop' => $opt->accomaxpop,
                              ':vilmin' => $opt->vilcountmin,
                              ':vilmax' => $opt->vilcountmax,
                              ':idlemin' => $opt->idlemin,
                              ':idlemax' => $opt->idlemax
                              ));
    $result = $statement->fetchAll();
    if(count($result) == 0){
      return 'No villages';
    }
    return $result;
  }
  private function inquerry($id,$not,$values){
    $query = 'AND ' . $id;
    $query .= ($not ? ' IN (' : ' NOT IN (');
    if(count($values) > 0){
      $uids = array();
      foreach($values as $players){
          array_push($uids, $players->$id);
      }
      $query .= implode(',', $uids) . ')';
    } else {
      $query = '';
    }
    return $query;
  }
    
    
}