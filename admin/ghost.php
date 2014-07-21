<?
require("cab.php");

require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');
require("../_class/_class_docentes.php");

//$sql = "delete from _messages where 1=1";
//$rlt = db_query($sql);
	echo '111';
	$clx = new docentes;
	
	$tabela = $clx->tabela;
	//$sql = "delete from ".$tabela." where id_us = 99";
	//$rlt = db_query($sql);
	$label = msg('tit_'.$tabela);
	$editar = True;
	echo $tabela;	
	$http_ver = 'ghost_select.php';
	
	$http_redirect = 'ghost.php?dd98='.$dd[98].'&dd97='.$dd[97];
	
	$clx->row();
	
	$busca = true;
	$offset = 20;
	//$pre_where = " e_mailing = '".$cl->mail_codigo."' ";
	

	
	$order  = "pp_nome";
	echo '<div id="content">';
	echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
	require($include.'sisdoc_row.php');	
	echo '</table>';	
	echo '</div>';
?>
<script>
	$("#content").corner();
</script>

<? require("foot.php"); ?>