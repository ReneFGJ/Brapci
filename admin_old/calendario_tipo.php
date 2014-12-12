<?
require("cab.php");
require('../_class/_class_calendario_tipo.php');

global $acao,$dd,$cp,$tabela;
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');

	$clx = new calendario_tipo;
	$cp = $clx->cp();
	$tabela = $clx->tabela;
	
	$label = msg("calendario_tipo");
	$http_edit = 'calendario_tipo_ed.php'; 
	$editar = True;
	
	//$http_ver = 'cliente_ver.php';
	
	$http_redirect = 'message.php?dd98='.$dd[98].'&dd97='.$dd[97];
	
	$cdf = array('id_ct','ct_descricao','ct_codigo','ct_ativo');
	$cdm = array('cod',msg('descricao'),msg('codigo'),msg('ativo'));
	$masc = array('','','','','','','');
	$busca = true;
	$offset = 20;
	$pre_where = " ct_ativo = 1 ";
	
	if (strlen($dd[98]) > 0)
		{ $pre_where = ""; }
	$order  = "ct_descricao";
	//exit;
	echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
	require($include.'sisdoc_row.php');	
	echo '</table>';	

require("../foot.php");		
?> 