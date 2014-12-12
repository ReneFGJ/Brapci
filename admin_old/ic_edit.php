<?
$debug = true;
require("cab.php");
	$cpn = $dd[99];
	require($include.'sisdoc_colunas.php');
	require($include.'sisdoc_form2.php');
	require($include.'sisdoc_data.php');
	require($include.'sisdoc_debug.php');	
	require($include.'cp2_gravar.php');
	
	require("../_class/_class_ic.php");
	$ic = new ic;
	$cp = $ic->cp();
	$tabela = $ic->tabela;
		
	$http_edit = page();
	?><TABLE width="<?=$tab_max?>" align="center"><TR><TD><?
	editar();
	?></TD></TR></TABLE><?	
require("foot.php");		
?>