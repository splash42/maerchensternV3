<?
class Item{
	
	// Key der Datenbank-Tabelle
	protected static $KEY	= "id";
	
	// Zwischenspeicher um Mehrfach-Request zu vermeiden und Daten zu mergen
	public static $BUFFER	= array();
	
	// Alle Persistenten Variablen
	public $attr	= array();
	
	// TAG-Buffer
	public $tags	= array();
	
	
	
	/** Liest die in der Config definierten Items aus der DB 
	 * und übergibt sie an die entsprechende Funktion
	 * @param assoc $query - Parameter für die DB-Abfrage
	 * @param str arg[1] - Mapping-Daten der DB-Abfrage
	 * @param assoc arg[2] - TAG-Name */
	public static function LOAD_ITEMS($query){
		
		// Name der Kind-Klasse, die Item als extend nutzt
		$class = get_called_class();
			
		// Puffer-Slot
		$slot	= 'root';
		if(func_num_args()>1){
			$slot	= func_get_arg(1);
		}
		
		// Mapping auf TAG-Felder
		$tags	= array();
		if(func_num_args()>2){
			$tags	= func_get_arg(2);
		}
		
		// Request noch nicht im Puffer
		if(!$class::$BUFFER[$slot]){
			$db		= DBpdo::INIT();
			
			// Request
			$class::$BUFFER[$slot]	= $db->jsonSelect($query,$class,$tags);
		}
	}
	
	/** Fügt die DB-Daten den Item-Attributen hinzu
	 * @param assoc $dbdata - Ass. Array mit den Daten, die dem Item hinzugefügt werden sollen */
	public function setDbData($dbdata){
		$this->attr	= array_merge($this->attr,$dbdata);
	}
	
	/** Attributen auf TAG-Namen mappen */
	public function mapTags($tags){
		foreach ($tags AS $tag => $field){
			$lvl	= explode(".",$field);
			$num	= sizeof($lvl);
			
			if($num>1){	// JSON in DB-Feld
				$tmp	= null;
				for($i=0;$i<$num;$i++){
					if($i==0){	// DB-Feld
						$json	= $this->attr[$lvl[0]];
						$tmp	= json_decode($json,true);
					}else{		// JSON-Feld
						$tmp	= $tmp[$lvl[$i]];
					}
				}
				$this->tags[$tag]	= $tmp;
			}else{	// Nur DB
				$this->tags[$tag]	= $this->attr[$field];
			}
			
		}
	}
	
	/** Mapping von DB-Werte entsprechend einer Konfigurationsdatei auf Template-Felder
	 * @param assoc $mapping - Array mit den Mapping-Daten 
	 * @return assoc $tags - Array mit den TAG-Namen als Key*/
	public function map2tpl($mapping){
		$tags	= array();
		
		return $tags;
	}
	
	
	/** Mapping von Eingabe-Werte entsprechend einer Konfigurationsdatei auf DB-Felder */
	protected function map2db(){
		
	}
	
	/** INSERT/UPDATE: Speichert das Objekt. Falls das Objekt noch nicht existiert, 
	 * wird es neu angelegt, ansonsten aktualisiert */
	public function store(){
		$db	= DBpdo::INIT();
		$con	= $db->open();
	}
	
	/** INSERT: Fügt ein Item in der Datenbank ein, sofern es noch nicht 
	 * existiert. Andernfalls wird das Item ignoriert */
	public function add(){
		
	}
	
	/** UPDATE: Aktualisiert ein bestehendes Item in der Datenbank */
	public function update(){
		
	}
	
	/** Gibt einen definierte Buffer-Slot zurück */
	public static function GET_BUFFER(){
		$class = get_called_class();
		
		$slot	= 'root';
		if(func_num_args()>0){
			$slot	= func_get_arg(0);
		}
		return $class::$BUFFER[$slot];
		
	}
	
	public static function CLEAR_BUFFER($slot){
		unset($class::$BUFFER[$slot]);
	}
}
?>