<?
/** Zentrale Routing-Klasse */
class Core{
	
	// SINGLETON
    public static $SINGLETON = null;

	// -- KONFIG --
	// Basispfade
    public static $BASE;
    public static $APP;
    public static $LIB;
    public static $TMP;
    public static $PUBLIC;
	
	// Datenbank
	private static $DB	= array();


	// -- AUSGABE -- 
	private $view;
	private static $SHOW	= true;
	
	
	
	
	// -- DEBUGGING --
	// Debugmode
	public static $DEBUG	= false;
	
	// Allg. Meldungen
    private static $MSG	= array();
	
	// Fehlermeldungen
	private static $MSG_ERROR	= array();
	
		
	
	// ## KONSTRUKTOR ## -----------------------
	private function __construct(){
		// Timezone korrigieren
		date_default_timezone_set('Europe/Berlin');
		
		// Pfade
		Core::$BASE	= $_SERVER['DOCUMENT_ROOT'].'/maerchensternV3/';		
		Core::$LIB	= Core::$BASE.'libs/mframe/';
		Core::$APP	= Core::$BASE.'application/';
		Core::$TMP	= Core::$BASE.'tmp/';
		Core::$PUBLIC	= Core::$BASE.'public/';
		
		// Klassen-Importe
		// Import (conf)
        require_once 'View.php';
        require_once Core::$LIB.'core/Loader.php';
        
		// Header auf UTF-8 setzen
		View::SET_HEADER();
	}

    public static function INIT(){
        if(Core::$SINGLETON==null){
            Core::$SINGLETON = new Core();
        }
        return Core::$SINGLETON;
    } // -----------------------------------------




    /** --- # ROUTING -> MODUL # ---- */
    public function route(){
    	$route	= array();
    	if(func_num_args()>=1){
    		$route	= func_get_arg(0);
    	}
    	
    	// Globale Konfig-Datei einlesen und verarbeiten
		$config		= File::READ(Core::$APP.".conf","json");
		
		// Datenbank-Zugriffe
		Core::$DB	= $config['db'];

		// Struktur der Website
		$structure	= File::READ(Core::$APP.".structure","json");
		View::SET_TEMPLATES($structure['tpl']);
		 
		
		// # (1) - Start: Routing # ------------  
        // - Gewähltes Modul erkennen
		if(Input::GET('m')){
			$route['mod']   = Input::GET('m');
		}
		
		// # (2) - Modul-Aufruf # ------------  
		// - Check: Modul existiert? (Konfiguration aus Datei ".structure")
		$access	= true; // offen: Zugriffskontrolle
		if(isset($structure['moduls'][$route['mod']]['modul'])){
			
			// .structure-Daten verkürzen
			$modul	= $structure['moduls'][$route['mod']]['modul'];			

			// -- Dynamischer Controller-Aufruf --
			if($access){
	            $class   = ucfirst($modul).'Controller';
	            require_once Core::$APP.$modul.'/'.$class.'.php';
				
				// Struktur-Daten des Moduls laden (noch inaktiv)
				$modStructure = File::READ(Core::$APP.$modul."/.structure","json");
				
	            $con    = new $class($modStructure);
			}
		}else{
			// LOG::SET_MSG('Fehler: Modul '.$route['mod'].' ist nicht bekannt!');
		}
		
		
		
		// # (3) - Seite ausgeben # ------------ 
		if($route['intern']){
			return View::$OUTPUT;
		}else{
			if(Core::$SHOW){
				View::SHOW();
			}
		}		
    } // ENDE: route() --------------------------------------------------------------
	
			

	/** Verwaltung der Datenbank-Zugriffe
	 * @param: args[0]: Nutzertyp (read, add, update, master) */
	public static function GET_DB_ACCESS(){
		$user	= 'add';
		if(func_num_args()>0){
			$args	= func_get_args();
			$user	= $args[0];
		}else{
			LOG::SET_MSG('DB-User: add');
		}
		
		$access	= array();
		
		$access['host']		= Core::$DB['host'];
		$access['dbname']	= Core::$DB['db_name'];
		$access['user']		= Core::$DB['user'][$user]['name'];
		$access['pass']		= Core::$DB['user'][$user]['pass'];
		
		return $access;
	}
}
?>