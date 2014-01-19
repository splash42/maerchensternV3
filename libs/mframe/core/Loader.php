<?php 
/** Autoloader für Library-Klassen */
class Loader{
	
	// ---- ## Variablen --------------------------------------------------------
	// Wichtige Basis-Klassen
	private static $PATHS	= array();	
	
	
	// ---- ## Statische Funktionen ---------------------------------------------
	/** Initialisieren des Loaders */
	public static function INIT_VARS(){
		// Lib:Core
		Loader::$PATHS['Log']	= array("type"=>"lib","path"=>"core/");
		Loader::$PATHS['Cache']	= array("type"=>"lib","path"=>"core/");
		Loader::$PATHS['Item']	= array("type"=>"lib","path"=>"core/");
		Loader::$PATHS['Controller']	= array("type"=>"lib","path"=>"core/");
		
		// Lib:IO
		Loader::$PATHS['File']		= array("type"=>"lib","path"=>"io/");
		Loader::$PATHS['Request']	= array("type"=>"lib","path"=>"io/");
		Loader::$PATHS['DbPdo']		= array("type"=>"lib","path"=>"io/");
		Loader::$PATHS['Input']		= array("type"=>"lib","path"=>"io/");
		
		// Lib::Tools
		Loader::$PATHS['Time']	= array("type"=>"lib","path"=>"tools/");
		Loader::$PATHS['Validator']	= array("type"=>"lib","path"=>"tools/");
		
	} // ENDE: INIT_VARS
	
	/** Läd eine Klasse, sofern diese bekannt ist */
	public static function GET_CLASS($cn){
		
		if(Loader::$PATHS[$cn]){			
			switch(Loader::$PATHS[$cn]['type']){
				case "lib": // Library-Klasse
					$file	= Core::$LIB.Loader::$PATHS[$cn]["path"].$cn.".php";
					break;
			
				case "root": // Zugriff von Root
				case "default":
						
			}
			
			// Klasse laden
			include_once $file;
		}else{
			echo "Klasse ".$cn." ist nicht registriert";
		}
		
	}
	
	/** Registriert eine weitere Klasse für die Autoloader-Funktion
	 * @param: $cn => Klassenname
	 * @param: $path => Pfad, wo die Klasse zu finden ist */
	public static function REGISTER($cn,$path,$type){
		Loader::$PATHS[$cn]	= $path;
	}
}
?> 