<?
class Item{
	
	// Key der Datenbank-Tabelle
	protected static $KEY	= "id";
	
	// Alle Persistenten Variablen
	public $attr	= array();
	
	/** Liest die in der Config definierten Items aus der DB 
	 * und übergibt sie an die entsprechende Funktion */
	public static function LOAD_ITEMS($config){
		foreach($config AS $key => $query){
			$sql	= "SELECT ";
			
			// Felder
			if(isset($query['fields'])){
				$sql .= $query['fields'];
			}else{
				$sql .= "*";
			}
			
			// Tabelle
			$sql .= " FROM ".$query['tab'];
			
			// Bedingungen
			
			// Offset/Limit
			
			
			// Request
			$db		= DBpdo::INIT();
			$con	= $db->open();			
			
			// Statement
			$stmt	= $con->prepare($sql);			
			$stmt->execute();
			
			$class = get_called_class();
			
			$items	= array();
			// Article-Datensätze verarbeiten
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$item	= new $class();
				$item->setDbData($row);
					
				$items[$key][$row['id']]	= $item;
			}
			
			return $items;
		}
	}
	
	/** Erzeugt DB-Querys aus Konfigdaten und läd die Daten in den Puffer */
	public static function LOAD_DATA($mod,$config){
		
		foreach($config AS $key => $value){
			switch($value['type']){
				case "item":
					
					break;
					
				case "list":
					
					break;
			}
		}
		
		/*
		foreach($config AS $key => $value){
			switch($value['type']){
				case "item":
					$item;
					if(isset(Request::GET("id"))){
						$item	= new ucfirst($base).'Item'(Request::GET("id"));
					}else{
						if(isset(Request::GET("fn"))){
							$item	= new ucfirst($base).'Item'(Request::GET("fn"));
						}
					}
					
					break;
					
				case "list":
					
					break;
			}
		}
		*/
	}
	
	
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