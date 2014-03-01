<?php 
class ArticleItem extends Item{
	protected static $TAB	= "ms3_article";
	
	public static function LOAD($mod){
		
		$limit	= 10;		
		if(Request::GET("limit")){
			$limit	= Request::GET("limit","num");
		}
		
		// $sql	= "SELECT * FROM :tab WHERE type = ':type'";
		$sql	= "SELECT * FROM ".ArticleItem::$TAB." WHERE type = :type";
		
		echo $sql." -- <br>";
		
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

	// ## KONSTRUKTOR ##
	/** */
	public function __construct(){
		// Läd einen Datensatz aus der DB
		if(func_num_args()>0){
			
			echo "article";
		}
	}
}
?>