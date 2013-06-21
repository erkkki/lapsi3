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
              `country` varchar(100) NOT NULL,
              `name` varchar(100) NOT NULL,
              `update` BIGINT NOT NULL,
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
              `tid` int(11) NULL,
              `vid` int(11) NULL,
              `village` varchar(100) NULL,
              `uid` int(11) NULL,
              `player` varchar(100) NULL,
              `aid` varchar(100) NULL,
              `alliance` varchar(100) NULL,
              `population` int(11) NULL,
              `pophistory` char NULL,
              `idle` INT(11) NULL,
              PRIMARY KEY (`id`)
              ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8";
      return $this->conn->exec($sql);
    }
    function tableExists($name){
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