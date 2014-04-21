<?php 
class ArticleItem extends Item{
	protected static $TAB	= "ms3_article";


	// ## KONSTRUKTOR ##
	/** */
	public function __construct(){
		// Läd einen Datensatz aus der DB
		if(func_num_args()>0){			
			echo "article";
		}
	}
	
	/** Gibt den Titel mit Anfangsbuchstaben zurück, 
	 * wobei Artikel (Der, Die, Das) für den Anfangsbuchstaben ignoriert werden */
	public function getTagEntry(){
		$tag	= array();
		
		$title	= "";
		switch(substr(ucfirst($this->attr['title']),0,3)){
			case "Der":
			case "Die":
			case "Das": // Artikel filtern				
				// Anfangsbuchstaben finden
				$tag['letter']	= substr(strtoupper($this->attr['title']),4,1);
				
				// Title formatieren
				$title	= "(";
				$title .= substr(ucfirst($this->attr['title']),0,3);
				$title .= ") ";
				$title .= substr($this->attr['title'],3);				
				$tag['title']	= $title;
				break;
				
			default: 	// Normal				
				$tag['letter']	= substr($this->attr['title'],0,1);
				
				// Title formatieren
				$title	= ucfirst($this->attr['title']);				
				$tag['title']	= $title;
		}
		
		$tag['elem']	= '<li><a href="/'.$this->attr['type'].'/'.$this->attr['filename'].'.php">'.$title.'</a></li>';	
		
		return $tag;
	}
}
?>