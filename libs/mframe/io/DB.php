<?php 
class DB{
	
	/** Erkennt und ersetzt bestimmte Variablen im Code
	 * @param (String) $cond - WHERE-Clause
	 * @return (String) $cond */
	protected function formatCondition($cond){
		// Vars erkennen
		$pattern	= "/<<(.*:.*)>>/U";
		preg_match_all($pattern,$cond,$matches);
		
		// Var durchlaufen
		foreach ($matches[1] AS $x){
			$tmp	= explode(":",$x);
			$var	= null;
			
			// Variablen-Typ
			switch($tmp[0]){
				case "url":	// URL-Parameter
					try{
						$var	= Request::GET($tmp[1]);
					}catch(Exception $e){}
					break;
					
				case "session":	// Session-Variable
					try{
						$var	= $_SESSON[$tmp[1]];
					}catch(Exception $e){}					
					break;
			}
			
			$cond	= preg_replace("/<<".$x.">>/U", $var, $cond);
		}
		
		return $cond;
	}
}
?>