<?php
abstract class Controller{
	
	protected $templates	= array();
	private $statics		= array();
	
	protected $queue;

	/** Zentrale Routing-Funktion */
	public function route($queue){
		$this->queue	= $queue;
		
		// (1) - Basis-Aufbau des Moduls laden
		$file		= Core::$APP.$queue['mod_base']."/config/.structure.json";
		$structure	= File::READ($file,"json");
		
		// Modul auswählten (Default-Modul)
		if($queue['mod']=="default"){
			$queue['mod']	= $structure[$queue['mod']];
		}		
		$structure	= $structure[$queue['mod']];
		
		
		// -- Laden erfolgreiche -> Verarbeitung
		if($structure){			
			$base	= $this->queue['mod_base'];
			$task	= "";
			
			// 2 - Check Sequence
			if($structure['sequence']){
				$task	= $this->chkSequence($structure['sequence']);
			}else{
				$task	= Request::GET('t');
			}
			
			if(!$task){
				$task	= $structure['tasks']['default'];
			}
			
			// 3 - (Class) Klassen registrieren
			Loader::REGISTER_LIST($structure['tasks'][$task]['classes']);
			
			
			// 4 - (Config) Konfig-Datei für angeforderte Seite laden
			$fn2			= ".".$queue['mod'].".".$task.".json";
			$config_file	= Core::$APP.$queue['mod_base']."/config/".$fn2;
			$config			= File::READ($config_file,"json");
			
			// 5 - (Load) DB-Daten laden
			$class	= ucfirst($base).'Item';			
			$items	= $class::LOAD_ITEMS($config['load']);
			
			// 6 - (Load) Templates laden
			View::LOAD_TEMPLATES($base,$config['tpl']);
			
			// 7 - (Mapping) Daten kombinieren und Seite erstellen
			$this->statics	= $config['statics'];
			$this->process($config['structure']);
			
			// 8 - (Hooks) Call Function (Dyn. Aktion aufrufen)
			$func	= $structure['tasks'][$task]['function'];
			$this->{$func}($queue,$config,$items);
			
			// 9 - (show) Ausgabe
			View::SHOW();
		}else{ Log::SET_ERROR('Fehler: Struktur für Modul '.$modul.' konnte nicht geöffnet werden!'); }
		
	}
	
	// ----------
	/** Komponiert aus den Einzelelementen die fertige Website
	 * @param array $structure - Prozessschritte zur Erstellung des Outputs */
	private function process($structure){
		
		foreach ($structure AS $step){
			
			switch($step['type']){
				case "tpl":	// Template hinzufügen
					switch($step['action']){
						case "set":	// Schreibt direkt in Slot
							View::SET_TPL($step['slot'],$step['ref']);
							break;
							
						case "add": // Fügt zu bestehenden Slot-Daten hinzu
							View::ADD_TPL($step['slot'],$step['ref'],$step['target']);
							break;
							
						case "append": // Fügt zu bestehenden Slot-Daten hinzu
							View::ADD_TPL($step['slot'],$step['ref'],$step['target']);
							break;
							
					}
					break;
					
				case "static": // Fügt statische Variablen zu einem Template hinzu
					foreach($this->statics[$step['ref']] AS $tag=>$value){
						View::ADD_TAG($step['slot'],$tag,$value);
					}
					break;
					
				case "slot":
					break;
					
				case "app":
					break;
					
				default:
					Log::SET_ERROR('Fehler: Prozesstyp '.$step['type'].' ist nicht bekannt!');
			}
		}
	}
	
	
	/** Prüft, nach welcher Regel bzw. welchem URL-Parameter
	 * die aktive Funktion ausgesucht werden soll */
	private function chkSequence($seq){
		$num	= sizeof($seq);
		
		for($i=0;$i<$num;$i++){
			
			if($seq[$i]['param']=='t'&&Request::GET('t')){	// Auf Task-Parameter prüfen
				return Request::GET('t');
			}else{				// Alternative Parameter prüfen
				
				// Alternativer Parameter ist gesetzt
				if(Request::GET($seq[$i]['param'])){
					return ($seq[$i]['func']);
				}
			}
		}
		
		return "default";
	}
}
?>