<?php
abstract class Controller{
	
	protected $templates	= array();
	private $statics		= array();
	
	protected $queue;		// Befehls-Queue
	protected $base;		// Name des Basis-Moduls
	protected $config;		// Konfigurationsdaten für die Seite
	protected $itemClass;	// Name der zugehörigen Item-Klasse
	protected $mapping		= array();
	protected $items		= array();

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
			$this->base	= $this->queue['mod_base'];
			$this->itemClass	= ucfirst($this->base).'Item';
			$task	= false;
			
			// 2 - Check Sequence
			if($structure['sequence']){
				$task	= $this->chkSequence($structure['sequence']);
			}else{
				$task	= Request::GET('t');
			}
			
			if(!$task||$task=="default"){
				$task	= $structure['tasks']['default'];
			}
			
			// 3 - (Class) Klassen registrieren
			Loader::REGISTER_LIST($structure['tasks'][$task]['classes']);
			
			
			// 4 - (Config) Konfig-Datei für angeforderte Seite laden
			$fn2			= ".".$queue['mod'].".".$task.".json";
			$config_file	= Core::$APP.$queue['mod_base']."/config/".$fn2;
			$this->config	= File::READ($config_file,"json");
			
			// 5 - (Load) Templates laden
			View::LOAD_TEMPLATES($base,$this->config['tpl']);	
			
			// 6 - (Mapping) Daten kombinieren und Seite erstellen
			$this->build();
			
			// 8 - (show) Ausgabe
			View::SHOW();
		}else{ Log::SET_ERROR('Fehler: Struktur für Modul '.$modul.' konnte nicht geöffnet werden!'); }
		
	}
	
	

	
	
	// ----------
	/** Komponiert aus den Einzelelementen die fertige Website
	 * @param array $structure - Prozessschritte zur Erstellung des Outputs */
	private function build(){
		
		foreach ($this->config['structure'] AS $step){
			switch($step['type']){
				case "tpl":	// Template hinzufügen
					switch($step['action']){
						case "set":	// Schreibt direkt in Slot
							View::SET_TPL($step['slot'],$step['tpl']);
							break;
							
						case "add": // Fügt ein Template in einen TAG ein
							View::ADD_TPL($step['slot'],$step['tpl'],$step['target']);
							break;
							
					}
					break;
					
				case "static": // Fügt statische Variablen zu einem Template hinzu
					switch($step['action']){
						case "clear":
							View::CLEAR_TAGS($step['slot']);
							break;
						default:
							View::ADD_TAGS($step['slot'],$this->config['statics'][$step['ref']]);
					}					
					break;
					
				case "db":	// Daten aus der Datenbank					
					// Zugehörige Item-Klasse
					$itemClass	= $this->itemClass;
					
					// Daten laden (@return : item)					
					$db	= $this->config['db'][$step['ref']];	// Verkürzung (DB-Slot in der Konfig)
					$itemClass::LOAD_ITEMS($db['query'],$step['ref'],$db['mapping']);
					
					
					switch($step['action']){
						case "item":
							$item	= $itemClass::GET_BUFFER($step['ref']);
							View::ADD_TAGS($step['slot'],$item->tags);
							break;
							
						case "loop":
							$items	= $itemClass::GET_BUFFER($step['ref']);
							View::APPEND_ITEMS($step['slot'],$items,$step['tpl']);
							break;
					}
					break;
					
				case "mapping":	// Gemappte Daten integrieren		
					// Zugehörige Item-Klasse
					$itemClass	= $this->itemClass;
					
					switch($step['action']){
						case "db":	// Daten aus der Datenbank in Puffer-Slot laden
							// Daten laden und im Slot $step['ref'] speichern				
							$db	= $this->config['db'][$step['ref']];	// Verkürzung (DB-Slot in der Konfig)
							$itemClass::LOAD_ITEMS($db['query'],$step['ref'],$db['mapping']);
							break;
					}
					break;
					
				case "func":
					// Setzt einen Funktionsaufruf gemäß der Angaben in der Konfig-Struktur um
					// $step['class']->$step['call']();
					switch($step['class']){
						case "self":	// Aktive (Controller-)Klasse
							$tags	= $this->$step['call']();
							View::ADD_TAGS($step['slot'],$tags);
							break;
						default:		// Jede andere Klasse, die vorher instanziert werden muss
							
					}					
					break;
					
				case "slot":
					switch($step['action']){
						case "combine":
							VIEW::COMBINE_SLOTS($step['slot'],$step['ref'],$step['target']);
							break;
					}
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
		$num	= sizeof($seq);	// Länger der Sequenz-Liste
		
		// Sequenzliste iterieren
		for($i=0;$i<$num;$i++){
			
			if($seq[$i]['param']=='t'&&Request::GET('t')){	// Auf Task-Parameter prüfen
				return Request::GET('t');
			}else{				// Alternative Parameter prüfen
				
				// Alternativer Parameter ist gesetzt
				if(Request::GET($seq[$i]['param'])){
					return ($seq[$i]['task']);
				}
			}
		}
		
		return "default";
	}
}
?>