<?php
namespace Project\Travian;

class PlayerService{
   
    protected $conn;

    public function __construct($conn){
      $this->conn = $conn;
    }
    
    public function FindPlayer($server, $id){
      $statement = $this->conn->prepare('SELECT * FROM ' . $server . 'tilastot where uid = ?');
      $statement->execute(array($id));
      return $statement->fetchAll();
    }

    public function FindPlayersById($server, $ids){
      foreach ($ids as $value) {
        $uids .= "'". $value . "',"; 
      }
      $uids = substr($uids, 0, -1);
      $statement = $this->conn->prepare('SELECT * FROM ' . $server . 'tilastot where uid in ( '. $uids .')');
      $statement->execute();
      return $statement->fetchAll();
    }
    
}
?>
