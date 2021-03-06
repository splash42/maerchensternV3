<?
// Importiert Daten über URL-Aufruf
class File{
    
    static $SINGLETON   = null;

    private $vz;


    // ## KONSTRUKTOR ## ----------------
    /* Initialisiert Datenbank */
    private function __construct() {}

    public static function INIT(){
	// echo "init<br>";
        if(File::$SINGLETON==null){
            File::$SINGLETON = new File();
        }
        return File::$SINGLETON;
    }
    // ------------------------------------

    
    /* Verzeichnis-Inhalt auslesen */
    function getToc($url){
        $this->vz   = $url;

        $ordner	= opendir($url);
        $toc    = array();

        // Im Verzeichnis enthaltene Dateien auslesen
        $i=0;
        while($datei = readdir($ordner)){
            if($i>1){
                array_push($toc,$datei);
            }
            $i++;
        }

        return $toc;
    }
	
    
    
    
    
    // ------------------------------------
    // STATICS
	
	/** Kopiert eine Datei von @param: $source nach @param $target */
	public static function COPY($source,$target){		
        if(copy($source,$target)){
            array_push(Core::$MSG, "Kopiervorgang erfolgreich");
        }else{            
            array_push(Core::$MSG, "Datei ".$source." konnte nicht kopiert werden");
        }
	}
	
	
	
	/** Statisches Pendant zu load() */
	public static function READ($url){
		$type	= "arr";
		if(func_num_args()>1){
			$type	= func_get_arg(1);
		}
		
		// Zeiterfassung
        $out;		
		switch($type){
			case "str":
			case "json":
            	$out    = "";
				break;
			case "arr":
			default:
            	$out    = array();
		}

        // Datei wird geöffnet
		$handle = @fopen($url, "r");
		
        // Daten werden zu Output-String formatiert
        if($handle!=false){
            $tmp    = " ";
            $i      = 0;
			
            // Daten zeilenweise auslesen
            while($tmp!=null){
                // Dateizeile auslesen
                $tmp = fgets($handle);
				
				switch($type){
					case "str": // Zusammenhängender Text
					case "json": // JSON-Daten
						$out    = $out.$tmp;
						break;
						
					case "assarr": // Assoziatives Array
						if($tmp!=""){ $out[trim($tmp)]	= 'true'; };
						break;
						
					case "arr-skip": // Array ohne Leer-Einträge
						if($tmp!=""){ $out[$i]	= trim($tmp); };
						break;
						
					case "arr": // Array
					default:
                    	$out[$i]	= trim($tmp);
				}
                $i++;
            }

            // Datei wird geschlossen
            fclose($handle);
        }else{
        	Log::SET_ERROR('Fehler: Datei '.$url.' konnte nicht geöffnet werden!');
            return false;
        }
		
		if($type=='json'){
			$out	= json_decode($out,true);        
		}
		
        return $out;
	}


    
	/** Dateien schreiben 
	 * @param @url: Ziel - 
	 * @param $data: String - 
	 * @param $mode: fputscode */
    public static function WRITE($url,$data,$mode){
    	// echo "# ".$url."<br>";
		
        $handle = fopen($url, $mode);
        flock($handle, 2 );
        fputs($handle,$data);
        flock($handle, 3 );
        fclose($handle);
    }

    // Datei umbenennen
    public function rename($alt,$neu){
        echo $alt." -> ".$neu."<br/>";
        rename($this->vz."/".$alt,$this->vz."/".$neu);
    }
	
	/** Komprimiert einen Datensatz */
	public function compress($filename,$zipname){
		
	}
}
?>