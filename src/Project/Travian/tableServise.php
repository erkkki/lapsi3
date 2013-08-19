<?php
namespace Project\Travian;
 
class tableServise {

    protected $conn;
    
    public function __construct($conn){
      $this->conn = $conn;
    }
    
    public function createServerTable(){
      $this->DropTable("servers");
        
      $sql = "CREATE TABLE `servers` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `address` varchar(100) NOT NULL,
              PRIMARY KEY (`id`)
              ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8";
      return $this->conn->exec($sql);
    }

    public function createActiveServers(){
      $this->DropTable("activeservers");
        
      $sql = "CREATE TABLE `activeservers` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `address` varchar(100) NOT NULL,
              `updatetime` BIGINT NOT NULL,
              PRIMARY KEY (`id`)
              ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8";
      return $this->conn->exec($sql);
    }
    public function createXworld($name){
      $this->DropTable($name);
        
      $sql = "CREATE TABLE `".$name."` (
              `id` int(11) NOT NULL,
              `x` int(11) NOT NULL,
              `y` int(11) NOT NULL,
              `tid` int(11) NOT NULL,
              `vid` int(11) NOT NULL,
              `village` varchar(100) NOT NULL,
              `uid` int(11) NOT NULL,
              `player` varchar(100) NOT NULL,
              `aid` varchar(100) NOT NULL,
              `alliance` varchar(100) NOT NULL,
              `population` int(11) NOT NULL,
              `uidPopulation` int(11),
              `villagecount` int(11),
              `idle` int(11) null,
              PRIMARY KEY (`vid`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
      return $this->conn->exec($sql);
    }
    public function tableExists($name){
        $results = $this->conn->query("SHOW TABLES LIKE '$name'");
        if($results->rowCount()>0)
            return 1;
        return 0;
    }
    
    public function DropTable($table){
        return $this->conn->exec("DROP TABLE IF EXISTS `" . $table . "`");
    }
}
?>