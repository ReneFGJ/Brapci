<?php
$include = '../';
require("../db.php");
?>
<head>
	<title>::CIP::</title>
	<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<link rel="shortcut icon" type="image/x-icon" href="http://cip.pucpr.br/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="../css/main.css" />

</head>
<?php
require("../_class/_class_message.php");
$ln = new message;

global $acao,$dd,$cp,$tabela;
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_data.php');
require($include.'sisdoc_debug.php');

	require("../db_diris.php");

	/* Dados da Classe */
	require('../_class/_class_programa_pos.php');

	$cl = new programa_pos;
	$cp = $cl->cp_docente();
	$tabela = $cl->tabela;
	
	/* Mensagens */
	$link_msg = '../messages/msg_'.page();
	if (file_exists($link_msg)) { require($link_msg); }	
	
	$http_edit = page();
	$http_redirect = '';
	$tit = msg("titulo");

	/** Comandos de Edição */
	echo '<CENTER><font class=lt5>'.msg('titulo').'</font></CENTER>';
	?><TABLE width="<?=$tab_max;?>" align="center" bgcolor="<?php echo $tab_color;?>"><TR><TD><?php
	editar();
	?></TD></TR></TABLE><?php
	
	/** Caso o registro seja validado */
	if ($saved > 0)
		{
			echo 'Salvo';
			redirecina('../close.php');
		}
require("../foot.php");	
?>

