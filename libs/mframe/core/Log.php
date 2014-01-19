<?php 
class Log{
    private static $SINGLETON = null;

    // ## VARIABLEN ##
    // Nachrichtenspeicher
    private $msg	= array();	// Standard-Nachrichten
    private $error	= array();	// Fehler
    private $trace	= array();	// Timings

    
    // ## STATIC_FUNCTIONS ##
    /** Normale Log-Nachricht anlegen  */
    public static function SET_MSG($msg){
    	// Log-Objekt initialisieren
    	$log	= Log::INIT();
    	
    	// Nachricht speichern
    	array_push($log->msg,'# '.$msg);
    }
    
    /** Fehlermeldung anlegen */
    public static function SET_ERROR($msg){
    	// Log-Objekt initialisieren
    	$log	= Log::INIT();
    	
    	// Fehler speichern
    	array_push($log->error,'# '.$msg);
    }
    // ------------------------------------------------
    
    
    
    // ## INIT ##
    public static function INIT(){
        if(Log::$SINGLETON==null){
            Log::$SINGLETON = new Log();
        }
        return Log::$SINGLETON;
    }

    // ## KONSTRUKTOR ##
    private function __construct(){} 
    // ------------------------------------------------
    
    /** Prüft, ob Fehlermeldungen vorliegen */
    public static function HAS_ERROR(){
    	$log	= Log::INIT();
    	if(sizeof($log->error)>0){
    		true;
    	}else{
    		false;
    	}
    }
    
    /** Ausgabe für Log-Meldungen */
    public static function SHOW(){
    	$log	= Log::INIT();
    	
    	$type	= "error";
    	if(func_num_args()>0){
    		$type	= func_get_arg(0);
    	}
    	
    	
    	switch($type){
    		case "msg":
    			print_r($log->msg);
    			break;
    			
    		case "error":
    		default:
    			print_r($log->error);
    			
    	}
    }
}
?>