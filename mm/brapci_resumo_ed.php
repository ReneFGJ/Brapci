<? require("db.php"); ?>
<head>
<link rel="STYLESHEET" type="text/css" href="css/letras.css">
</head>
<font class="lt5"><font color="white">
<CENTER><?=$dd[2];?></CENTER>
</font></font>
<?
$tabela = "brapci_article";
$cp = array();
array_push($cp,array('$H8','id_ar','id_ar',False,True,''));
array_push($cp,array('$HV','',$dd[1],False,True,''));
array_push($cp,array('$HV','',$dd[2],False,True,''));
array_push($cp,array('$HV','',$dd[3],False,True,''));
array_push($cp,array('$HV','',$dd[4],False,True,''));
array_push($cp,array('$T70:12','ar_resumo_'.$dd[3],'Resumo',False,True,''));
array_push($cp,array('$Q ido_descricao:ido_codigo:select * from ajax_idioma order by ido_ordem, ido_descricao','ar_idioma_'.$dd[3],'Idioma',False,True,''));

require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');

$http_edit = 'brapci_resumo_ed.php';
$http_redirect = 'close.php';
?><TABLE width="<?=$tab_max?>" align="center"><TR><TD><?
editar();
?></TD></TR></TABLE>
