<?php 
class Log{
    static $SINGLETON = null;

    private function __construct(){}    
    
    // Gibt die Klasse zurück, wenn sie schon initialisiert worden ist.
    public static function INIT(){
        if(Log::$SINGLETON==null){
            Log::$SINGLETON = new Log();
        }
        return Log::$SINGLETON;
    }	
}
?>