<?
/*** Modelo ****/
require("cab.php");

require("../_class/_class_captacao.php");
$cap = new captacao;

global $acao,$dd,$cp,$tabela;
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');

$programa_pos_anoi = 2010;
$programa_pos_anof = date("Y");

echo '<a href="captacao_excel.php">Exportar para Excel</A><BR>';
echo $cap->mostra_captacao_todos($programa_pos_anoi,$programa_pos_anof);	


require("../foot.php");		
?> 