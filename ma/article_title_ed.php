<?
$include = '../'; 
require("../db.php");
require($include.'sisdoc_debug.php'); 
?>
<head>
<link rel="STYLESHEET" type="text/css" href="css/letras_popup.css">
</head>
<font class="lt5"><font color="white">
<CENTER><?=$dd[2];?></CENTER>
</font></font>
<?
$tabela = "brapci_article";
$cp = array();
array_push($cp,array('$H8','id_ar','id_ar',True,True,''));
array_push($cp,array('$T60:3','ar_titulo_1','Título principal',True,True,''));
array_push($cp,array('$Q ido_descricao:ido_codigo:select * from ajax_idioma order by ido_ordem, ido_descricao','ar_idioma_1','Idioma',False,True,''));
array_push($cp,array('$T60:3','ar_titulo_2','Título 2º idioma',False,True,''));
array_push($cp,array('$Q ido_descricao:ido_codigo:select * from ajax_idioma order by ido_ordem, ido_descricao','ar_idioma_2','Idioma',False,True,''));

array_push($cp,array('$Q se_descricao:se_codigo:select * from brapci_section order by se_descricao','ar_tipo',msg("session"),True,True));

array_push($cp,array('$S8','ar_pg_inicial','Pág inicial',True,True,''));
array_push($cp,array('$S8','ar_pg_final','Pág Final',False,True,''));

require('../include/sisdoc_colunas.php');
require($include.'_class_form.php');
$form = new form;
$tela = $form->editar($cp,$tabela);

if ($form->saved > 0)
	{
		require("../close.php");
	} else {
		echo $tela;
	}

?>