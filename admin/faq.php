<?
/*** Modelo ****/
require("cab.php");
global $acao,$dd,$cp,$tabela;
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');

if (strlen($dd[80]) > 0)
	{ $_SESSION['faqtp'] = $dd[80]; }
else
	{ $dd[80] = $_SESSION['faqtp']; }

	/* Dados da Classe */
	require('../_class/_class_faq.php');
	//$sql = "update pibic_mirror set mr_status = 'A' ";
	//$rlt = db_query($sql);

	$clx = new faq;
	$tabela = $clx->tabela;
	
	/* Mensagens */
	$link_msg = '../messages/msg_'.$tabela.'.php';
	if (file_exists($link_msg)) { require($link_msg); }
	
	/* Não alterar - dados comuns */
	$label = msg($tabela);
	$http_edit = $tabela.'_ed.php'; 
	//$http_ver = 'pibic_bolsa_tipo_detalhe.php'; 
	$editar = True;
	$http_redirect = $tabela.'.php';
	$clx->row();
	$busca = true;
	$offset = 20;
	$pre_where = " faq_seccao = '".$dd[80]."' ";
	if ($order == 0) { $order  = $cdf[1]; }
	
	echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
	require($include.'sisdoc_row.php');	
	echo '</table>';	

require("../foot.php");		
?> 