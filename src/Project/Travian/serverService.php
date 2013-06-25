<?php
/*
namespace Project\Travian;

class serverService {
    private $conn;
    private $tables;
    private $activeServers, $allServers;
    
    public function __construct($conn,tableServise $tablesServise){
        $this->conn = $conn;
        $this->tables = $tablesServise;
        $this->loadActiveServers();
        $this->loadServersAll();
    }
    private function loadServersAll(){
        $statement = $this->conn->prepare('SELECT * FROM servers');
        $statement->execute();
        $this->allServers = $statement->fetchAll();        
    }
    private function loadActiveServers(){
        $statement = $this->conn->prepare('SELECT * FROM activeservers');
        $statement->execute();
        $this->activeServers = $statement->fetchAll();        
    }
    public function addServer($data){
        $statement = $this->conn->prepare('insert into activeservers values(?, ?, ?, ?, ?)');
        $statement->execute(array('', $data->{'server'}, $data->{'country'}, $data->{'name'}, 1));
        return true;
    }
    public function updateServer($data) {
      $statement = $this->conn->prepare('update activeservers set country = ? , name = ? , address = ? where id = ?');
      $statement->execute(array($data->{'country'}, $data->{'name'}, $data->{'address'}, $data->{'id'})); 
      return true;
    }
    public function deleteServer($server) { 
        $statement = $this->conn->prepare('delete from activeservers where id = ?');
        $statement->execute(array($server));
        return true;
    }
    public function updateServerlist(){
        $this->tableService->createServerTable();
        $newServers = $this->getNewServers();

        foreach($newServers as $ser){
             $sql .= "('','".$ser."'),";
        }
        $sql = substr($sql, 0, -1);
        $statement = $this->conn->prepare("insert into servers values $sql");
        $statement->execute();       
        return true;
    }
    private function getNewServers(){
        $dom = new \DOMDocument();
        
        try {
            $dom->loadHTML(file_get_contents('http://status.travian.com/'));
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
    public function getServerList(){
        return $this->activeServers;
    }
    public function getAllList(){
        return $this->allServers;
    }
    public function getServerName($name){
        foreach ($this->activeServers as $value) {
            if($value['name'] == $name)
                return $value;
        }
      return false;
    }
    public function getServerId($id){
        foreach ($this->activeServers as $value) {
            if($value['id'] == $id)
                return $value;
        }
      return false;
    }
    public function updateServerData($id){
        $server = $this->getServerId($id);
        $sqlTable = str_replace('.','',$server['address']);
        $map = $this->loadServerData($server['address']);
        
        if($this->tables->tableExists($sqlTable)){
            $this->tables->DropTable($sqlTable);
            $this->tables->createXworld($sqlTable); 
        } else {
            $this->tables->createXworld($sqlTable); 
        }
        foreach ($map as $line) {
            $this->insertNewVil($line,$sqlTable);
        }
        return true;
    }
    private function insertNewVil($line,$table){
        if($line['x'] == null || $line['y'] == null) return 0;
        $statement = $this->conn->prepare('insert into '.$table.' values(?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $statement->execute(array(
                    $line['id'],
                    $line['x'],
                    $line['y'],
                    $line['tid'],
                    $line['vid'],
                    $line['village'],
                    $line['uid'],
                    $line['player'],
                    $line['aid'],
                    $line['alliance'],
                    $line['population'],
                    '0',
                    '0'               
                ));        
    }
    private function loadServerData($address){
        $map = explode("\n", file_get_contents("http://" . $address . "/map.sql"));
        $returnAr = array();
        
        foreach ($map as $line) {
            $line = explode(",",substr($line, 30, -3));
            foreach ($line as &$elem) {
                $elem = trim($elem, "'");                
            }
            $result['id'] = $line[0];
            $result['x'] = $line[1];
            $result['y'] = $line[2];
            $result['tid'] = $line[3];
            $result['vid'] = $line[4];
            $result['village'] = $line[5];
            $result['uid'] = $line[6]; 
            $result['player'] = $line[7]; 
            $result['aid'] = $line[8]; 
            $result['alliance'] = $line[9]; 
            $result['population'] = $line[10]; 
            
            $returnAr[$result['vid']] = $result;
        }
        return $returnAr;
    }
    
}*/
?>
