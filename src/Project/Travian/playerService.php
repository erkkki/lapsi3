<?php
namespace Project\Travian;
 
class playerService {

    
  protected $conn;

  public function __construct($conn){
    $this->conn = $conn;
  }

  public function playerByName($server,$name){
    $server = str_replace('.','',$server);
    $statement = $this->conn->prepare("select * from $server where player LIKE ? group by player");
    $statement->execute(array($name . '%'));
    $data = $statement->fetchAll();
    if(count($data)) return 'No players';
    return $data;
  }
}
?>