<?php
require("cab.php");

/*
 * $breadcrumbs
 */
$breadcrumbs = array();
array_push($breadcrumbs,array(http.'admin/index.php',msg('principal')));
array_push($breadcrumbs,array(http.'admin/index.php',msg('menu')));
echo '<div id="breadcrumbs">'.breadcrumbs().'</div>';


$file = '../messages/msg_index.php';
if (file_exists($file)) { require($file); }

require($include."sisdoc_menus.php");
$estilo_admin = 'style="width: 200; height: 30; background-color: #EEE8AA; font: 13 Verdana, Geneva, Arial, Helvetica, sans-serif;"';

$menu = array();
/////////////////////////////////////////////////// MANAGERS
array_push($menu,array(msg('Ged'),msg('Documentos de Bonificação'),'ged_bonificacao.php'));
array_push($menu,array(msg('Ged'),msg('Documentos de Artigos Bonificados'),'ged_artigos.php'));  

array_push($menu,array(msg('LABS'),'Laboratório','ged_laboratorio_documento.php'));
array_push($menu,array(msg('LABS'),'Equipamento','ged_equipamento_documento.php'));

array_push($menu,array(msg('IC'),msg('Tipos de Documentos'),'ged_pibic_ged_documento.php'));

array_push($menu,array(msg('Submissão'),msg('Tipos de Documentos'),'ged_submit_files.php'));

array_push($menu,array(msg('Ciência sem Fronteiras'),msg('Tipos de Documentos'),'ged_submit_csf_files.php'));


//array_push($menu,array('Manutenção','Criar Tabelas','create_table.php')); 
///////////////////////////////////////////////////// redirecionamento
if ((isset($dd[1])) and (strlen($dd[1]) > 0))
	{
	$col=0;
	for ($k=0;$k <= count($menu);$k++)
		{
		 if ($dd[1]==CharE($menu[$k][1])) {	header("Location: ".$menu[$k][2]); } 
		}
	}
?>

<TABLE width="710" align="center" border="0">
<TR><TD colspan="4">
<FONT class="lt3">
</FONT><FORM method="post" action="index.php">
</TD></TR>
</TABLE>
<TABLE width="710" align="center" border="0">
<TR>
<?php
	$tela = menus($menu,"3");

require("../foot.php");	
?>