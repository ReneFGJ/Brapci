<?
require("cab.php");
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_data.php');
require($include.'sisdoc_form2.php');

	require("../_class/_class_apoio_pais.php");
	$clx = new apoio_pais;
	$tabela = $clx->tabela;
	
	$label = msg('tit_'.$tabela);
	$http_edit = 'admin_country_ed.php'; 
	$editar = True;
	
	$http_ver = '';
	
	$http_redirect = page().'?dd98='.$dd[98].'&dd97='.$dd[97];
	echo '---';
	$clx->row();
	echo '---';
	$busca = true;
	$offset = 20;
	//$pre_where = " e_mailing = '".$cl->mail_codigo."' ";
	
	$order  = "pais_nome";
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