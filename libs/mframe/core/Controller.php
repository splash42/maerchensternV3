<?php
abstract class Controller{	

	/** Zentrale Routing-Funktion */
	public function route($queue){
		
		// (1) - Basis-Aufbau des Moduls laden
		$file		= Core::$APP.$queue['mod_base']."/config/.structure.json";
		$structure	= File::READ($file,"json");
		$structure	= $structure[$queue['mod']];
		
		
		
		// (2) - Laden erfolgreiche -> Verarbeitung
		if($structure){
			$task	= "";
			
			// 2.1 - Check Sequence
			if($structure['sequence']){
				$task	= $this->chkSequence($structure['sequence']);
			}else{
				$task	= Input::GET('t');
			}
			
			$func	= $structure['tasks'][$task]['function'];
			
			// 2.1 - Call Function
			$this->{"listItems"}($queue);
			
		}else{ Log::SET_ERROR('Fehler: Struktur für Modul '.$modul.' konnte nicht geöffnet werden!'); }
		
	}
	
	
	
	
	/** Prüft, nach welcher Regel bzw. welchem URL-Parameter
	 * die aktive Funktion ausgesucht werden soll */
	private function chkSequence($seq){
		$num	= sizeof($seq);
		
		for($i=0;$i<$num;$i++){
			
			if($seq[$i]['param']=='t'&&Input::GET('t')){	// Auf Task-Parameter prüfen
				return Input::GET('t');
			}else{				// Alternative Parameter prüfen
				
				// Alternativer Parameter ist gesetzt
				if(Input::GET($seq[$i]['param'])){
					return ($seq[$i]['func']);
				}
			}
		}
		
		return "default";
	}
}
?>