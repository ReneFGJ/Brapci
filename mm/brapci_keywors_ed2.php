<? 
require("db.php"); 
require($include."sisdoc_cookie.php");
$jidsel = strzero(read_cookie("journal_sel"),7);
$jedsel = read_cookie("journal_ed");

///////////////////////////////////////////////////
////////////////////////////////////////////////////////
?>
<head>
<link rel="STYLESHEET" type="text/css" href="css/letras_popup.css">
</head>
<font class="lt5"><font color="white">
<CENTER><?=$dd[2];?></CENTER>
</font></font>
<?
$tabela = "brapci_article_keyword";
$cp = array();
array_push($cp,array('$H8','id_ak','id_ak',False,True,''));
array_push($cp,array('$HV','',$dd[1],False,True,''));
//array_push($cp,array('$Q kw_word:kw_codigo:select * from brapci_keyword where kw_codigo = '.chr(39).$dd[2].chr(39),'','Palavra-chave',False,False,''));
array_push($cp,array('$HV','',$dd[1],False,True,''));
array_push($cp,array('$HV','',$dd[2],False,True,''));
array_push($cp,array('$HV','',$dd[3],False,True,''));
array_push($cp,array('$HV','',$jidsel,False,True,''));
array_push($cp,array('$O 1:1&2:2&3:3&4:4&5:5&6:6&7:7&8:8&9:9&10:10&-1:EXCLUIR','kw_ord','Ordem para mostrar',True,True,''));

require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');

$http_edit = 'brapci_keywors_ed2.php';
$http_redirect = '';
?><TABLE width="<?=$tab_max?>" align="center"><TR><TD><?
editar();
?></TD></TR></TABLE>

<?
if ($saved > 0)
	{	
	$sql = "delete from brapci_article_keyword where kw_ord = -1 ";
	$rlt = db_query($sql);
	$http_redirect = 'close.php';
	redirecina($http_redirect);
	}
