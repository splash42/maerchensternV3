<?
class Item{
	
	protected $store_engine	= "pdo";
	
	// Alle Persistenten Variablen
	public $attr	= array();
	
	// Key der Datenbank-Tabelle
	protected $key	= "id";
	
	// Speichertabelle
	protected static $TAB;
	
	
	/** INSERT/UPDATE: Speichert das Objekt. Falls das Objekt noch nicht existiert, 
	 * wird es neu angelegt, ansonsten aktualisiert */
	public function store(){
		$db	= DBpdo::INIT();
		$con	= $db->open();
	}
	
	public function setDbData($dbdata){
		$this->attr	= $dbdata;		
	}
	
	/** INSERT: Fügt ein Item in der Datenbank ein, sofern es noch nicht 
	 * existiert. Andernfalls wird das Item ignoriert */
	public function add(){
		
	}
	
	/** UPDATE: Aktualisiert ein bestehendes Item in der Datenbank */
	public function update(){
		
	}
	
	
	/** Mapping von DB-Werte entsprechend einer Konfigurationsdatei auf Template-Felder */
	protected function map2tpl(){
		
	}
	
	
	/** Mapping von Eingabe-Werte entsprechend einer Konfigurationsdatei auf DB-Felder */
	protected function map2db(){
		
	}
}
?>