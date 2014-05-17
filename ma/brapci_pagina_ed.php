<? require("db.php"); ?>
<head>
<link rel="STYLESHEET" type="text/css" href="css/letras_popup.css">
</head>
<font class="lt5"><font color="white">
<CENTER><?=$dd[2];?></CENTER>
</font></font>
<?
if (strpos($dd[1],'-') > 0)
	{
	$dd[2] = substr($dd[1],strpos($dd[1],'-')+1,strlen($dd[1]));
	$dd[1] = substr($dd[1],0,strpos($dd[1],'-'));
	}

$tabela = "brapci_article";
$cp = array();
array_push($cp,array('$H8','id_ar','id_ar',True,True,''));
array_push($cp,array('$S8','ar_pg_inicial','Pág inicial',True,True,''));
array_push($cp,array('$S8','ar_pg_final','Pág Final',False,True,''));

require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');
$tab_max = "99%";
$http_edit = 'brapci_pagina_ed.php';
$http_redirect = 'close.php';
?><TABLE width="<?=$tab_max?>" align="center"><TR><TD><?
editar();
?></TD></TR></TABLE>
