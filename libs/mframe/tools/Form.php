<?php 
class Form{
	public function __construct(){}
	
	/** Generiert aus einem JSON-Konfig-File ein vollständiges Formular */
	public function generate($config){
		$code;
		// Start
		$code = '<form action="'.$config['action'].'"';
		
		// Method
		if($config['method']){
			$code .= ' method="'.$config['method'].'"';
		}else{
			$code .= ' method="GET"';
		}
		if($config['target']){
			$code .= ' target="'.$config['target'].'"';
		}
		$code .=	'>';
		
		// Felder durchlaufen
		foreach($config['elements'] AS $elem){
			$code .= "\n";
			$code .= '<label for="'.$elem['name'].'">'.$elem['label'].'</label>';
			
			switch($elem['type']){					
				case "password":
					$code .= $this->getPassword($elem);
					break;
					
				case "textfield":
					$code .= $this->getTextfield($elem);
					break;
					
				case "submit":
					$code .= $this->getSubmit($elem);
					break;
					
				case "text":
				default:
					$code .= $this->getText($elem);
					break;
					
			}
			$code .= "<br/ >";
		}
		
		// End-Tag
		$code .= "\n"."</form>";
		return $code;
	}
	
	/** Validiert ein Formular entsprechend seiner Konfiguration 
	 * und gibt passende Fehlermeldungen aus */
	public function validate($config){
		
	}
	
	// - Formulartypen---------------------
	private function getSubmit($config){
		// Start-Tag und Type
		$code	= '<input type="submit" ';
		
		// Atribut: ID und Name
		if($config['name']){
			$code	.= 'id="'.$config['name'].'" name="'.$config['name'].'"';
		}
		
		// Atribut: Value
		if($config['value']){
			$code	.= 'value="'.$config['value'].'"';
		}
		
		// End-Tag
		$code	.= '/>';
		
		return $code;
	}
	
	private function getText($config){
		// Start-Tag und Type
		$code	= '<input type="'.$config['type'].'" ';
		
		// Atribut: ID und Name
		if($config['name']){
			$code	.= 'id="'.$config['name'].'" name="'.$config['name'].'"';
		}
		
		// Atribut: Value
		if($config['value']){
			$code	.= 'value="'.$config['value'].'"';
		}
		
		// End-Tag
		$code	.= '/>';
		
		return $code;
		
	}
	
	private function getPassword($config){
		// Start-Tag und Type
		$code	= '<input type="password" ';
		
		// Atribut: ID und Name
		if($config['name']){
			$code	.= 'id="'.$config['name'].'" name="'.$config['name'].'"';
		}
		
		// Atribut: Value
		if($config['value']){
			$code	.= 'value="'.$config['value'].'"';
		}
		
		// End-Tag
		$code	.= '/>';
		
		return $code;		
	}
	
	private function getTextfield($config){
		
	}
	
	private function getNumber($config){
		
	}
	
	private function getRadio($config){
		
	}
	
	private function getSelect($config){
		
	}
	
}
?>