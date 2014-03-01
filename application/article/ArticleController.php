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
	 * Artikel-Typs (?m=) in einer A-Z-Liste auf */
	protected function azItems($queue){
	
		// Datensätze laden
		$items	= ArticleItem::LOAD($queue['mod']);
	
		echo "items: ";
		print_r($items);
	}
	
	/** Zeigt ein bestimmtes Item entweder anhand 
	 * seiner ID (&id=) oder seines Filenames (&name=) */
	protected function showItem(){
		$item	= new ArticleItem(Request::GET("name"),"name");
	}
	
	
	
	// -- Restricted! --
	// Nur für eingeloggte Nutzer mit Update-Rechten
	
	/** Restricted!
	 * Zeigt das Editier-Formular für ein bestimmtes Item
	 * - Passt sich an den Artikel-Typ an
	 * - Unterscheidet zwischen neu (leer) und Update (befüllt) */
	protected function showForm(){
		
	}
	
	/** Restricted!
	 * Speichert ein Item  */
	protected function add(){
		
	}
	
	/** Restricted!
	 * Aktualisiert ein Item  */
	protected function update(){
		
	}
	
	/** Restricted!
	 * Löscht ein Item  */
	protected function delete(){
		
	}
}
?>