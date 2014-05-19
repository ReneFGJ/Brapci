<?
require("cab.php");
require('../_class/_class_publications.php');
require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
$pb = new publications;

$jnl = trim($dd[2]);

require("../_class/_class_issue.php");
$clx = new issue;
$tabela = $clx->tabela;
$cp = $clx->cp();

echo '<table>';
editar();
echo '</table>';

if ($saved > 0)
	{
		$clx->updatex();
		redirecina('publications_details.php?dd0='.$dd[2]);
	}
require("../foot.php");
?>
