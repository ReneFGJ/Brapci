<? require("db.php");
require($include."sisdoc_cookie.php");
$jidsel = strzero(read_cookie("journal_sel"),7);
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
array_push($cp,array('$Q '."title:ed_codigo:select ed_codigo,concat('v. ',ed_vol,' no ',ed_nr,', ',ed_ano,' ',ed_tematica_titulo) as title  from brapci_edition where ed_ativo <> 0 and ed_journal_id = ".$jidsel." order by ed_ano desc , ed_vol, ed_nr",'ar_edition','Edição',True,True,''));
array_push($cp,array('$S100','ar_titulo_1','Título',False,False,''));

require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');

$http_edit = 'brapci_edition_ed.php';
$http_redirect = 'close.php';
?><TABLE width="<?=$tab_max?>" align="center"><TR><TD><?
editar();
?></TD></TR></TABLE>
