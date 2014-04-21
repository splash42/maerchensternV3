<?
require_once '../../application/Core.php';


/** Autoloader für Klassen */
function __autoload($cn){
	Loader::GET_CLASS($cn);
} // ENDE: Autoloader --------

$c	= Core::INIT();
$c->route();
?>