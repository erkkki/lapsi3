<?php
namespace Project\Travian;
 
class ServerList {

    protected $conn;
    protected $servers;
    
    public function __construct($conn){
        $this->conn = $conn;
        $this->serverlist();
        $this->changeTime();
    }
    
    private function serverlist(){
      $statement = $this->conn->prepare('SELECT * FROM servers');
      $statement->execute();
      $this->servers = $statement->fetchAll();  
    }
    
    // Unix timestamp to human time
    private function changeTime(){
        foreach($this->servers as &$value ){
            $value['lastupdate'] = date('H:i d.m.o', $value['lastupdate']);
        }
    }
    public function getServers(){
      //return '<pre>' . var_dump($this->servers) . '</pre>';
      return  json_encode($this->servers);
    }
    public function updateName($server, $newName) {
      $statement = $this->conn->prepare('update servers set name = ? where name = ?');
      $statement->execute(array($newName, $server)); 
      return 'ok';
    }
    public function updateAddress($server, $newAdd) {
      $statement = $this->conn->prepare('update servers set address = ? where name = ?;');
      $statement->execute(array($newAdd, $server));        
      return 'ok';
    }
}
?>