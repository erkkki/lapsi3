<?php

namespace Project\Travian;

class allServers {
    private $conn;
    private $tables;
    
    public function __construct($conn,tableServise $tablesServise){
        $this->conn = $conn;
        $this->tables = $tablesServise;
    } 
    public function updateServers(){
        $this->createTable();
        $servers = $this->loadServers();
        $this->addServers($servers);
        return $this->getServersList();
    }
    public function getServersList(){
        $statement = $this->conn->prepare('SELECT * FROM servers');
        $statement->execute();
        return $statement->fetchAll();
    }
    
    public function isServerInList($address){
        $statement = $this->conn->prepare('select address from servers where address = ?');
        $statement->execute(array($address));
        return $statement->fetchAll();
    }
    
    private function createTable(){
        return $this->tables->createServerTable();
    }
    private function loadServers(){
        /*
         * Bug in namespaces 
         * Fix: '\'
         * http://goo.gl/VbpUi
         */
        $dom = new \DOMDocument();
        
        try {
            @$dom->loadHTML(file_get_contents('http://status.travian.com/'));
        } catch (Exception $e) {
            return json_encode("Cannot Download status.travian.com");
        }
        $servers = Array();
        foreach ($dom->getElementsByTagName('td') as $td) {
             if(trim($td->nodeValue) != null && strlen($td->nodeValue) < 20 && strpos($td->nodeValue, "travian")){
                array_push($servers, trim($td->nodeValue)); 
             }
        }       
        return $servers; 
    }
    
    private function addServers($data){
        $sql = '';
        foreach($data as $ser){
          $sql .= "('','".$ser."'),";
        }
        $sql = substr($sql, 0, -1);
        $statement = $this->conn->prepare("insert into servers values $sql");
        $statement->execute();       
        return true;
    }      
}
?>
