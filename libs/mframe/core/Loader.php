<?php 
/** Autoloader für Library-Klassen */
class Loader{
	private static $PATHS	= array(
								"Log" 		=> "core/",
								"Cache" 	=> "core/",
								"Item" 		=> "core/",
								"Input" 	=> "io/",
								"File" 		=> "io/",
								"Request" 	=> "io/",
								"DbPdo" 	=> "io/",
								"Time" 		=> "tools/",
								"Validator" => "tools/"
							);
	
	
	/** Läd eine Klasse, sofern diese bekannt ist */
	public function GET_CLASS($cn){
		$file	= Core::$LIB.Loader::$PATHS[$cn].$cn.".php";
		include $file;
	}
}
?> 