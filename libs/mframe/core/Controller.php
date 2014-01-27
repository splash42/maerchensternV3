<?php 
abstract class Controller{
	
	/** Zentrale Routing-Funktion */
	public function route($modul){
		$m	= $modul;
		if(func_num_args()>1){
			$m	= func_get_arg(1);
		}
		
		// (1) - Basis-Aufbau des Moduls laden
		$structure	= File::READ(Core::$APP.$modul."/config/.structure.json","json");
		
		// (2) - Laden erfolgreiche -> Verarbeitung
		if($structure){
			print_r($structure[$m]);
		}else{ Log::SET_ERROR('Fehler: Struktur für Modul '.$modul.' konnte nicht geöffnet werden!'); }
	}
}
?>