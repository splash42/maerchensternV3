<?php 
class ArticleController extends Controller{
	public function __construct(){
		echo "# Modul: ArticleC<br>"; 
	}
	
	/** Listet alle Items eines bestimmten 
	 * Artikel-Typs (?m=) auf */
	protected function listItems($queue){
	}
	
	/** Zeigt ein bestimmtes Item entweder anhand 
	 * seiner ID (&id=) oder seines Filenames (&fn=) */
	protected function showItem(){
		
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