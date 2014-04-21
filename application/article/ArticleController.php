<?php 
class ArticleController extends Controller{
	public function __construct(){}
	
	
	
	/** Listet alle Items eines bestimmten 
	 * Artikel-Typs (?m=) auf */
	protected function listItems($queue){
		
		// Datensätze laden	
		$items	= ArticleItem::LOAD($queue['mod']);
	}
	
	/** Listet alle Items eines bestimmten
	 * Artikel-Typs (?m=) in einer A-Z-Liste auf 
	 * @return array $tags - list mit Tags und Werten */
	protected function setAz(){
		$tags	= array();
		
		foreach(ArticleItem::$BUFFER['items'] AS $item){
			$tag	= $item->getTagEntry(); 
			if(!$tags[$tag['letter']]){
				$tags[$tag['letter']]	= $tag['elem'];
			}else{				
				$tags[$tag['letter']]	.= "\n".$tag['elem'];
			}
		}
		return $tags;
	}
}
?>