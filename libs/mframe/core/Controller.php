<?php 
abstract class Controller{
	
	/** Zentrale Routing-Funktion */
	public function route(){
		$config	= File::READ(".config","json");
	}
}
?>