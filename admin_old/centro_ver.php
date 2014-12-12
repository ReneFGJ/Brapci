<?
/*** Modelo ****/
require("cab.php");
global $acao,$dd,$cp,$tabela;
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');

require("../_class/_class_docentes.php");
$dc = new docentes;

$dc->docente_escolas($dd[0]);


require("../foot.php");		
?> 