<?php

namespace Project\Travian;

class serverDataService {
    private $conn;
    private $tables;
    private $servers;
    
    public function __construct($conn,tableServise $tablesServise, activeServers $activeServers){
        $this->conn = $conn;
        $this->tables = $tablesServise;
        $this->servers = $activeServers;
    }
    public function updateServerData($id){
        $server = $this->servers->getServerID($id);
        $sqlTable = str_replace('.','',$server['address']);
        $map = $this->getMapsql($server['address']);
        
        if($this->tables->tableExists($sqlTable)){
            $this->tables->DropTable($sqlTable);
            $this->tables->createXworld($sqlTable); 
        } else {
            $this->tables->createXworld($sqlTable); 
        }
        $this->insertVillages($map,$sqlTable);
        //$statement = $this->conn->prepare('update activeservers set update = ? where id = ?');
        //$statement->execute(array(time(), $server['id']));
        
        return true;
    }
    private function getMapsql($address){
        $map = explode("\n", file_get_contents("http://" . $address . "/map.sql"));
        $returnAr = array();
        
        foreach ($map as $line) {
            $line = explode(",",substr($line, 30, -2));
            foreach ($line as &$elem) {
                $elem = trim($elem, "'");                
            }
            // id x  y tid vid village uid player aid  | alliance | population | pophistory | idle |

            if(@$line[4] == null)
                return $returnAr;
            $result = "('$line[0]','$line[1]','$line[2]','$line[3]','$line[4]',
                        '$line[5]','$line[6]','$line[7]','$line[8]','$line[9]',
                        '$line[10]','','')";            
            array_push($returnAr, $result);
            
            
                
            /*
             *['id'] = $line[0];
             *['x'] = $line[1];
             *['y'] = $line[2];
             *['tid'] = $line[3];
             *['vid'] = $line[4];
             *['village'] = $line[5];
             *['uid'] = $line[6]; 
             *['player'] = $line[7]; 
             *['aid'] = $line[8]; 
             *['alliance'] = $line[9]; 
             *['population'] = $line[10]; 
             */
        }
        return $returnAr;
    }
    
    private function insertVillages($map,$table){
        $sql = "";
        foreach ($map as $vil){
           $sql .= "$vil,";
        }
        $sql = substr($sql, 0, -1);
        
        $statement = $this->conn->prepare('insert into '.$table.' values ' . $sql);
        $statement->execute(); 
        return true;
    }
}
?>
