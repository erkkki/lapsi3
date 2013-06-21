<?php

namespace Project\Travian;

class activeServers {
    private $conn;
    private $tables;
    
    public function __construct($conn,tableServise $tablesServise){
        $this->conn = $conn;
        $this->tables = $tablesServise;
    } 
    public function addServer($data){
        $statement = $this->conn->prepare('insert into activeservers values(?, ?, ?, ?, ?)');
        $statement->execute(array('', $data->server, $data->country, $data->name, 1));
        return true;        
    }
    public function deleteServer($id){
        if($this->getServerID($id)){
            $statement = $this->conn->prepare('delete from activeservers where id = ?');
            $statement->execute(array($id)); 
            return true;
        }
        return false;
    }
    public function updateServer($data){
        if($this->getServerID($data->id)){
            $statement = $this->conn->prepare('update activeservers set country = ? , name = ? , address = ? where id = ?');
            $statement->execute(array($data->country, $data->name, $data->address, $data->id)); 
            return true;
        }
        return false;
    }
    public function getServers(){
        $statement = $this->conn->prepare('SELECT * FROM activeservers');
        $statement->execute();
        return $statement->fetchAll(); 
    }               
    public function getServerID($id){
        $servers = $this->getServers();
        foreach ($servers as $server) {
            if($server['id'] == $id)
                return $server;
        }
        return false;
    }
    public function getServerName($name){
        $servers = $this->getServers();
        foreach ($servers as $server) {
            if($server['name'] == $name)
                return $server;
        }
        return false;
    }    
    
}
?>
