<?
require("cab.php");
require('../_class/_class_calendario.php');

global $acao,$dd,$cp,$tabela;
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');

	$clx = new calendario;
	$cp = $clx->cp();
	$tabela = $clx->tabela;
	
	$label = msg("mensagens");
	$http_edit = 'calendario_ed.php'; 
	$editar = True;
	
	//$http_ver = 'cliente_ver.php';
	
	$http_redirect = 'calendario.php';
	
	$cdf = array('id_cal','cal_nome','cal_data','cal_hora');
	$cdm = array('cod',msg('nome'),msg('data'),msg('hora'));
	$masc = array('','','','','','','');
	$busca = true;
	$offset = 20;
	//$pre_where = " e_mailing = '".$cl->mail_codigo."' ";
	
	$order  = "cal_data, cal_hora";
	//exit;
	echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
	require($include.'sisdoc_row.php');	
	echo '</table>';	

require("../foot.php");		
?> 