<?
require ("cab.php");
require($include.'sisdoc_colunas.php');
require ('../_class/_class_journals.php');
$clx = new journals;

	$tabela = $clx->tabela;
	
	/* Não alterar - dados comuns */
	$http_edit = 'publications_ed.php'; 
	$http_ver = 'publications_ed.php'; 
	$editar = True;
	$http_redirect = page();
	$clx->row();
	$busca = true;
	$offset = 20;
	
	if ($order == 0) { $order  = $cdf[1]; }
	$tab_max = '100%';
	echo '<TABLE width="98%" align="center"><TR><TD>';
	require($include.'sisdoc_row.php');	
	echo '</table>';
?>
