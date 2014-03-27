<?php 
class HomeItem extends Item{
	
	/** Läd die Anzeige-Elemente für die Startseite */
	public static function LOAD(){		
		$limit	= 10;
		if(Request::GET("limit")){
			$limit	= Request::GET("limit","num");
		}
		
		// $sql	= "SELECT * FROM :tab WHERE type = ':type'";
		$sql	= "SELECT * FROM ".ArticleItem::$TAB;
		
		// Request
		$db		= DBpdo::INIT();
		$con	= $db->open();
		
		
		$stmt	= $con->prepare($sql);
		$stmt->bindParam(':type', $mod);

		$stmt->execute();
		
		$items	= array();
		// Article-Datensätze verarbeiten
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$item	= new ArticleItem();
			$item->setDbData($row);
			
			$items[$row['id']]	= $item;
		}
		
		return $items;
	}
	
	public function __construct(){
		
	}
}
?>