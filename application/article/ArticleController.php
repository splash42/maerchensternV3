<?php 
class ArticleController extends Controller{
	public function __construct(){
		echo "# Modul: ArticleC<br>";
	}
	
	/** Listet alle Items eines bestimmten 
	 * Artikel-Typs (?m=) auf */
	private function listItems(){
		
	}
	
	/** Zeigt ein bestimmtes Item entweder anhand 
	 * seiner ID (&id=) oder seines Filenames (&fn=) */
	private function showItem(){
		
	}
	
	
	
	// -- Restricted! --
	// Nur für eingeloggte Nutzer mit Update-Rechten
	
	/** Restricted!
	 * Zeigt das Editier-Formular für ein bestimmtes Item
	 * - Passt sich an den Artikel-Typ an
	 * - Unterscheidet zwischen neu (leer) und Update (befüllt) */
	private function showForm(){
		
	}
	
	/** Restricted!
	 * Speichert ein Item  */
	private function add(){
		
	}
	
	/** Restricted!
	 * Aktualisiert ein Item  */
	private function update(){
		
	}
	
	/** Restricted!
	 * Löscht ein Item  */
	private function delete(){
		
	}
}
?>