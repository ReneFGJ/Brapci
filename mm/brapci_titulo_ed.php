<? require("db.php"); ?>
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

require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');

$http_edit = 'brapci_titulo_ed.php';
$http_redirect = 'close.php';
?><TABLE width="<?=$tab_max?>" align="center"><TR><TD><?
editar();
?></TD></TR></TABLE>
