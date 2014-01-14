<?
class Input{
    static $SINGLETON = null;

    var $GET;
    var $POST;

    // ## KONSTRUKTOR ## ------------------------------------------------------------
    private function __construct(){
        // echo "new User()";
        $this->GET  = $_GET;
        $this->POST = $_POST;
    }    
    
    // Gibt die Klasse zurück, wenn sie schon initialisiert worden ist.
    public static function INIT(){
	// echo "init<br>";
        if(Input::$SINGLETON==null){
            Input::$SINGLETON = new Input();
        }
        return Input::$SINGLETON;
    } // ENDE: Konstruktor ----------------------------------------------------------

    
    

	/** Gibt einen Wert nach einer Typen-Prüfung zurück */
	public static function GET($param){		
		$type	= "text";
		if(func_num_args()>=1){
			$type	= func_get_arg(0);
		}
		
		// Werte auslesen
		$val	= false;
        if(isset($_GET[$param])){		// GET-Request
        	$val	= $_GET[$param];
        }else{
            if(isset($_POST[$param])){	// POST-Request
            	$val	= $_POST[$param];
            }
        }
		
		// Typen-Erkennung (!todo)
		$val	= $val;
		
		return $val;
	} // ENDE: GET
}
?>