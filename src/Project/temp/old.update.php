<?php
/*
class tra_sql{
	protected $mapsql;
	protected $serveri;
	protected $nimi;
	protected $uudet = array();
	protected $dbtunnus = "erkki";
	protected $dbsalasana = "kakaka54321";
	protected $yhteys;
	protected $vanhat_uid = array();
	protected $vanhat_kokpop = array();
	protected $vanhat_kylia = array();
	protected $vanhat_idle = array();
	
	public function __construct($osote, $nimi) {
		$this->serveri = $osote;
		$this->nimi = $nimi;
		try {
			$this->yhteys = new 
PDO("mysql:host=localhost;dbname=erkki", "$this->dbtunnus", 
"$this->dbsalasana");
		} catch (PDOException $e) {
			die("VIRHE: " . $e->getMessage());
		}
		$this->yhteys->setAttribute(PDO::ATTR_ERRMODE, 
PDO::ERRMODE_EXCEPTION);
		$this->yhteys->exec("SET NAMES utf8");
	}
	
	public function map_sql(){
		$this->mapsql = file_get_contents($this->serveri);
		$this->mapsql = explode("\n", $this->mapsql); 	  

	}
	function table(){
		$this->yhteys->query('create table if not exists ' . 
$this->nimi . 'tilastot (uid INT, kokpop INT, kylia INT,idle INT)');
		$this->yhteys->query('drop table if exists ' . 
$this->nimi);
		$this->yhteys->query('drop table if exists x_world');
		$this->yhteys->query('CREATE TABLE x_world (id INT, x 
INT, y INT, tid INT, vid INT, village TEXT, uid INT, player TEXT, aid 
INT, alliance TEXT, population INT)');
		$monta = count($this->mapsql);
		for ($u=0;$u<$monta-1;$u++){
			$this->yhteys->query($this->mapsql[$u]);
		}
		$this->yhteys->query('rename table x_world to ' . 
$this->nimi);
	}
	
	public function paivitystiedot(){
		$sql = "SELECT uid, SUM(population) AS kokpop, 
COUNT(population) AS kylia FROM " . $this->nimi . " GROUP BY uid";
		$kysely = $this->yhteys->prepare($sql);
		$kysely->execute();
		$this->uudet = $kysely->fetchAll();
		
	}
	public function vanhat(){
		$sql = "SELECT * from " . $this->nimi . "tilastot";
		$kysely = $this->yhteys->prepare($sql);
		$kysely->execute();
		$y = 0;
			while ($rivi = $kysely->fetch()) {
				$this->vanhat_uid[$y] = $rivi["uid"];
				$this->vanhat_kokpop[$y] = 
$rivi["kokpop"];
				$this->vanhat_kylia[$y] = 
$rivi["kylia"];
				$this->vanhat_idle[$y] = $rivi["idle"];
				$y++;
		}
	}
	public function paivita(){
		$monta = count($this->uudet);
		for($i=0;$i<=$monta-1;$i++){
			$idle = 0;
			if(in_array($this->uudet[$i]['uid'], 
$this->vanhat_uid)){
				$key = 
array_search($this->uudet[$i]['uid'], $this->vanhat_uid);
				if($this->uudet[$i]['kokpop'] == 
$this->vanhat_kokpop[$key]){
					$kysely = 
$this->yhteys->prepare("UPDATE " . $this->nimi . "tilastot SET kokpop = 
? , kylia = ? , idle = ? WHERE uid = ?");
					$idle = $this->vanhat_idle[$key] 
+1;
					
$kysely->execute(array($this->uudet[$i]['kokpop'], 
$this->uudet[$i]['kylia'], $idle, $this->uudet[$i]['uid']));
					}
				else {
					$kysely = 
$this->yhteys->prepare("UPDATE " . $this->nimi . "tilastot SET kokpop = 
? , kylia = ? , idle = ? WHERE uid = ?");
					
$kysely->execute(array($this->uudet[$i]['kokpop'], 
$this->uudet[$i]['kylia'], $idle, $this->uudet[$i]['uid']));
					}
				}
			else {
				$kysely = $this->yhteys->prepare("insert 
into " . $this->nimi . "tilastot (uid,kokpop,kylia,idle) values 
(?,?,?,'0')");
				
$kysely->execute(array($this->uudet[$i]['uid'],$this->uudet[$i]['kokpop'],$this->uudet[$i]['kylia']));
				}
			}
	
	}
	public function disconnect() { 
		$this->yhteys = null;
	} 
	
}


	$joku = new tra_sql('http://tx3.travian.fi/map.sql', 'tx3');
	$joku->map_sql();
	$joku->table();
	$joku->paivitystiedot();
	$joku->vanhat();
	$joku->paivita();
	$joku->disconnect();
*/
?>



