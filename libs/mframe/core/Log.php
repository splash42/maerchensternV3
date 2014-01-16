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
    	echo "Log";
    	// Log-Objekt initialisieren
    	if(!Log::$SINGLETON){ Log::INIT(); }
    	
    	// Nachricht speichern
    	array_push(Log::$SINGLETON->$msg,'# '.$msg);
    }
    
    /** Fehlermeldung anlegen */
    public static function SET_ERROR($msg){
    	// Log-Objekt initialisieren
    	if(!Log::$SINGLETON){ Log::INIT(); }
    	
    	// Fehler speichern
    	array_push(Log::$SINGLETON->$error,'# '.$msg);
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
}
?>