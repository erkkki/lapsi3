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
    public function addServer($server, $address){
      $statement = $this->conn->prepare('insert into servers values(?, ?, ?, ?)');
      $statement->execute(array('', $server, $address, 1));
      return true;
    }
    public function getServers(){
      //return '<pre>' . var_dump($this->servers) . '</pre>';
      return  json_encode($this->servers);
    }
    public function getServer($id){
        foreach ($this->servers as $value) {
            if($value['id'] == $id)
                return json_encode($value);
        }
      return false;
    }
    public function updateName($server, $newName) {
      $statement = $this->conn->prepare('update servers set name = ? where id = ?');
      $statement->execute(array($newName, $server)); 
      return true;
    }
    public function updateAddress($server, $newAdd) {
      $statement = $this->conn->prepare('update servers set address = ? where id = ?');
      $statement->execute(array($newAdd, $server));        
      return true;
    }
    public function deleteServer($server) {
      $statement = $this->conn->prepare('delete from servers where id = ?');
      $statement->execute(array($server));        
      return true;
    }
}
?>