<?php
namespace Project\Travian;
 
class VillageSearch {

    
    protected $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }
    
    public function getSome(){
      $statement = $this->conn->prepare('SELECT * FROM tx3');
      $statement->execute();
      $users = $statement->fetchAll();
    
      return  json_encode($users);
    }
}
?>
