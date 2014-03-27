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
		Loader::$PATHS['View']	= array("type"=>"root","path"=>"/");
		
		// Lib:Core
		Loader::$PATHS['Log']	= array("type"=>"lib","path"=>"core/");
		Loader::$PATHS['Cache']	= array("type"=>"lib","path"=>"core/");
		Loader::$PATHS['Item']	= array("type"=>"lib","path"=>"core/");
		Loader::$PATHS['Controller']	= array("type"=>"lib","path"=>"core/");
		
		// Lib:IO
		Loader::$PATHS['File']		= array("type"=>"lib","path"=>"io/");
		Loader::$PATHS['Request']	= array("type"=>"lib","path"=>"io/");
		Loader::$PATHS['DBpdo']		= array("type"=>"lib","path"=>"io/");
		Loader::$PATHS['Url']		= array("type"=>"lib","path"=>"io/");
		
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
					
				case "app": // Library-Klasse
					$file	= Core::$APP.Loader::$PATHS[$cn]["path"].$cn.".php";
					break;
			
				case "root": // Zugriff von Root
				case "default":
					$file	= Loader::$PATHS[$cn]["path"].$cn.".php";
						
			}
			
			// Klasse laden
			include_once $file;
		}else{
			if(!include_once $cn.".php"){
				echo "Klasse ".$cn." ist nicht registriert";
			}
		}
		
	}
	
	/** Registriert eine weitere Klasse für die Autoloader-Funktion
	 * @param: $cn => Klassenname
	 * @param: $path => Pfad, wo die Klasse zu finden ist */
	public static function REGISTER($cn,$path,$type){
		Loader::$PATHS[$cn]	= $path;
	}
	
	
	/** Registriert eine Liste von Klassen, entweder in
	 * Form einer assoc oder als JSON-Array */
	public static function REGISTER_LIST($list){
		$type	= "assoc";
		if(func_num_args()>1){
			$type	= func_get_arg(1);
		}
		
		if($type=='json'){
			$list	= json_decode($list,true);
		}
		
		/** Klassenpfade anlegen */
		foreach ($list AS $cn => $path){
			$c = array();
			$c['type']	= $path[0];
			$c['path']	= $path[1];
			Loader::$PATHS[$cn]	= $c;
		}
	}
}
?> 