<?
class View{
	// Template-Puffer
	private static $TPL		= array();
	
	// Ausgabe-Puffer
	private static $BUFFER	= array();
	
	private static $MIME		= 'text/html';
	private static $ENC			= 'utf-8';
	private static $BASE_ENC	= 'utf-8';
	private static $FILENAME	= 'export.csv';
	
	
	/** Setzt die Werte für Ziel- und BasisEncoding
	 * @param: $enc -> Ziel-Encoding
	 * @param: arg[1] -> Basis-Encoding */
	public static function SET_ENCODING($enc){
		View::$ENC	= $enc;
		
		if(func_num_args()>1){
			View::$BASE_ENC == func_get_arg(1);
		}
	}
	
	/** HTTP-Header setzen */
	public static function SET_HEADER(){
		switch(func_num_args()){
			case 4:
				View::$BASE_ENC	= func_get_arg(3);
			case 3:
				View::$FILENAME	= func_get_arg(2);
				View::FORMAT_FILENAME();
				header('Content-Disposition: attachment; filename="'.View::$FILENAME.'"');
			case 2:
				View::$ENC	= func_get_arg(1);
			case 1:
				View::$MIME	= func_get_arg(0);
				break;				
		}
		header("Content-Type: ".View::$MIME."; charset=".View::$ENC);
	}
	
	
	/** Ausgabefunktion für den $OUTPUT-Puffer */
	public static function SHOW(){
		if(View::$ENC!=View::$BASE_ENC){
			echo iconv(View::$BASE_ENC,View::$ENC."//TRANSLIT",View::$BUFFER['root']);
		}else{
			echo View::$BUFFER['root'];
		}
	}
	
	/** Gibt den View-Speicher $OUTPUT aus */
	public static function GET_OUT(){
		if(View::$ENC!=View::$BASE_ENC){
			return iconv(View::$BASE_ENC,View::$ENC."//TRANSLIT",View::$OUTPUT);
		}else{
			return View::$OUTPUT;
		}
	}
	
	/** Print-Ausgabe von Werte 
	 * @param $msg: Ausgabewert */
	public static function OUT($msg){
		if(View::$ENC!=View::$BASE_ENC){
			$msg	= iconv(View::$BASE_ENC,View::$ENC."//TRANSLIT",$msg);
		}
		echo $msg;
	}




	
	/** Print-Ausgabe von Werte mit Format-Konvertierung
	 * @param $msg: Ausgabewert
	 * @param $in: Format der Eingabe
	 * @param $out: Format der Ausgabe */
	public static function OUTC($msg,$in,$out){
		$txt;
		
		if(View::$ENC!=View::$BASE_ENC&&gettype($msg)=='string'){
			$msg	= iconv(View::$BASE_ENC,View::$ENC."//TRANSLIT",$msg);			
		}
		
		switch($in){
			case "json":
				switch($out){
					case "str":		// JSON-Arr -> String (horizontal)
						$txt	= $msg;
						break;
					case "arr":		// JSON-Arr -> String (vertikal)
						$txt	= $msg;
						break;
					default:
						$txt	= $msg;
				}
				break;
				
			case "arr":
				switch($out){
					case "str":		// ARR -> STRING
						foreach($msg AS $v){
							$txt	.= $v."\n";
						}
						break;
						
					case "html":		// ARR -> STRING
						foreach($msg AS $v){
							$txt	.= $v."<br />";
						}
						break;
						
					case "list":		// ARR -> LIST (HTML-Liste)
						$txt	= '<ul>';
						foreach($msg AS $v){
							$txt	.= "\n<li>".$v."</li>";
						}
						$txt	.= "\n</ul>";
						break;
						
					case "json":	// ARR -> JSON
						$txt	= json_encode($msg);
						break;
						
					default:
						$txt	= $msg;
				}
				break;
				
			default: // Keine Formatierung
				$txt	= $msg;
		}
		echo $txt;
	}
	
	/** Templates aus Konfigurations-Datei laden */
	public static function LOAD_TEMPLATES($mod,$config){
		foreach ($config as $key => $value) {
			// URI für TPL ermitteln
			$fn	= "";			
			if(isset($value['mod'])){
				$fn	= Core::$APP.$value['mod']."/tpl/";
			}else{
				$fn	= Core::$APP.$mod."/tpl/";
			}
			$fn	.= $value['file'];
			
			// Laden und in TPL-Puffer speichern
			View::$TPL[$key]	= File::READ($fn,"str");
		}
	}
	
	/** Füllt einen Puffer-Slot mit einem Template */
	public static function SET_TPL($slot,$tpl){
		View::$BUFFER[$slot]	= View::$TPL[$tpl];
	}	
	
	/** Fügt dem Puffer-Slot ein Template hinzu */
	public static function ADD_TPL($slot,$tpl,$tag){
		$s	= '/##'.$tag.'##/i';		
		View::$BUFFER[$slot] = preg_replace($s,View::$TPL[$tpl],View::$BUFFER[$slot]);
	}
	
	/** Schleifenfunktion für mehrere gleichartige Einträge
	 * @param str $slot - Puffer-Slot, der die Items sammeln soll
	 * Info: Slot muss später in Haupt-Slot eingebaut werden!
	 * @param str $tpl - Template zum formatieren der Einträge
	 * @param array $items - Daten-Array mit den DB-Einträgen */
	public static function APPEND_ITEMS($slot,$items,$tpl){
		// Slot instanzieren
		if(!View::$BUFFER[$slot]){
			View::$BUFFER[$slot]	= '';
		}
		
		foreach($items AS $item){
			// Template füllen und an Slot anhängen			
			View::$BUFFER[$slot] .= View::COMBINE(View::$TPL[$tpl],$item->tags);
		}
	}
	
	/** Ersetzt in einem Slot einen TAG durch einen Wert */
	public static function ADD_TAG($slot,$tag,$value){
		$s	= '/##'.$tag.'##/i';		
		View::$BUFFER[$slot] = preg_replace($s,$value,View::$BUFFER[$slot]);
	}
	
	/** Ersetzt in einem Template ein oder mehrere Tags und gibt das Ergebnis zurück
	 * @param str $slot - Slot, in dem die Tags ersetzt werden sollen
	 * @param assoc $data ($tag => $value) - Daten, die in das Template integriert werden sollen */
	public static function ADD_TAGS($slot,$data){		
		foreach ($data AS $tag=>$value){
			View::ADD_TAG($slot,$tag,$value);
		}
	}
	
	/** Löscht alle noch nicht verwendeten TAGs im Puffer */
	public static function CLEAR_TAGS($slot){
		$s	= '/##.*##/i';
		View::$BUFFER[$slot] = preg_replace($s,'',View::$BUFFER[$slot]);
	}
	
	/** Kombiniert Templates mit Daten */
	public static function COMBINE($tpl,$tags){		
		foreach ($tags AS $tag => $value){
			$s	= '/##'.$tag.'##/i';
			$tpl	= preg_replace($s,$value,$tpl);
		}
		return $tpl;
	}
	
	
	/** Fügt zwei Slots zusammen
	 * @param str $slot - Basis- bzw. Ziel-Slot
	 * @param str $ref - Kind-Slot, der in den Zielslot eingebunden werden soll
	 * @param str $tag - Name des Tags, in den der Kind-Slot eingebunden werden soll */
	public static function COMBINE_SLOTS($slot,$ref,$tag){
		$s	= '/##'.$tag.'##/i';
		View::$BUFFER[$slot] = preg_replace($s,View::$BUFFER[$ref],View::$BUFFER[$slot]);
	}
	
	/** Ersetzt evt. TAGs in den Dateinamen  */
	private static function FORMAT_FILENAME(){
		$time	= Time::INIT();
		
		// Zeitstempel setzen
		$s	= '##DATETIME##';
		$r	= $time->getDatum('JJJJMMTT_hh-mm-ss');		
		View::$FILENAME = str_replace($s, $r, View::$FILENAME);
		
		// Datum setzen
		$s	= '##DATE##';
		$r	= $time->getDatum('JJJJMMTT');	
		View::$FILENAME = str_replace($s, $r, View::$FILENAME);
	}
}
?>