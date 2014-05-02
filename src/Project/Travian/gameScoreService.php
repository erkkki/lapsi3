<?php

namespace Project\Travian;


class gameScoreService {
    private $conn;
    private $tables;
    private $name;
    private $killFactory;
    private $version;
    private $resSum;
    private $kills;
    
    public function __construct($conn,tableServise $tablesServise){
        $this->conn = $conn;
        $this->tables = $tablesServise;
        
        if(!$this->tables->tableExists('gamescores')){
            $this->tables->createGameScores();
        }
    } 
    public function getScores(){
        $statement = $this->conn->prepare("SELECT name,score,version,time FROM gamescores ORDER BY score DESC LIMIT 0,10");
        $statement->execute();
        $result = $statement->fetchAll();
        return $result;
    }
    public function safeScore(){
        // ? = killfactory, ressum, score, name ,version
        $score = $this->calcScore();
        if($score <=  1000){
            return false;
        }
        
        $statement = $this->conn->prepare('insert into gamescores values(?,?,?,?,?,?,?)');
        $statement->execute(array('',$this->killFactory, $this->resSum,$score,$this->name,$this->version,time()));
        return true;
    }
    private function calcScore(){
        return floor($this->resSum * $this->killFactory);
    }
    public function setData($data){
        if(isset($data['name'])){
            if(!$this->setName($data['name'])){
                return false;
            }
        }
        if(isset($data['killFactor'])){
            if(!$this->setKillFactory($data['killFactor'])){
                return false;
            }
        }
        if(isset($data['resSum'])){
            if(!$this->setResSum($data['resSum'])){
                return false;
            }
        }
        if(isset($data['version'])){
            if(!$this->setVersion($data['version'])){
                return false;
            }
        }
        if(!isset($data['kills'])){
            return false;
        }
        return true;
    }
    public function setName($param){
        if(strlen($param)<20){
            $this->name = $param;
            return true;
        } else {
           return false;
        }
    }
    public function setResSum($param) {
        if(is_int($param)){
            $this->resSum = $param;
            return true;
        } else {
            return false;
        }
    }
    public function setKillFactory($param) {
        if(is_float(floatval($param))){
            $this->killFactory = floatval($param);
            return  true;
        } else {
            return false;
        }
    }
    public function setVersion($param) {
        if(strlen($param)< 10){
            $this->version = $param;
            return true;
        } else {
            return false;
        }
        
    }
    
    
    
}