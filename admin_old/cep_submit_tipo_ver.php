<?
require("cab.php");
require('../_class/_class_cep_submit_field.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_data.php');

$file = '../messages/msg_cep_submit_tipo_ver.php';
if (file_exists($file)) { require($file); }

if (strlen($dd[0]) > 0)
	{ $_SESSION['id_sub'] = $dd[0]; } 
	else {$dd[0] = $_SESSION['id_sub']; }
/* redireciona caso não encontre dd0 */
if (strlen($dd[0]) == 0) { redirecina('cep_submit_tipo.php'); }


$clx = new cep_submit_field;
$clx->sub_projeto_tipo = $dd[0];
$tabela = $clx->tabela;
	
$label = msg("title");
$http_edit = 'cep_submit_field_ed.php'; 
$editar = True;
	
	$http_ver = '';
	$http_redirect = 'cep_submit_tipo_ver.php?dd0='.$dd[0].'&dd90='.$dd[90];
	
	$cdf = array('id_sub','sub_descricao','sub_pag','sub_ordem','sub_pos', 'sub_field','sub_codigo','sub_ativo','sub_id');
	$cdm = array('cod',msg('nome'),msg('pag'),msg('ordem'),msg('pos'),msg('field'),msg('codigo'),msg('ativo'),msg('id'));
	$masc = array('','','#','#','','','SN','','');
	$busca = true;
	$offset = 20;
	//$pre_where = " e_mailing = '".$cl->mail_codigo."' ";
	
	$order  = "sub_pag, sub_ordem, sub_pos, sub_descricao";
	//exit;
	echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
	require($include.'sisdoc_row.php');	
	echo '</table>';	

require("../foot.php");		
?>