<? 
require("db.php"); 
require($include."sisdoc_autor.php");
require($include."sisdoc_cookie.php");
$jidsel = strzero(read_cookie("journal_sel"),7);
$jedsel = read_cookie("journal_ed");

if (strlen($dd[11]) > 0)
	{
	require("brapci_keywors_ed_process.php");
	exit;
	}

///////////////////////////////////////////////////
if ((strlen($dd[0]) == 0) and (strlen($dd[5]) > 0))
	{
		$dd5 = $dd[5];
		$dd5 = troca($dd5,'.',';');
		
		echo $dd5;
		exit;
		$dd5c = substr($dd5,strlen($dd5)-1,1);
		
		while (($dd5c == '.') or ($dd5c == ',') or ($dd5c == ';') or ($dd5c == '/') or ($dd5c == '-'))
			{
			$dd5 = substr($dd5,0,strlen($dd5)-1);
			$dd5c = substr($dd5,strlen($dd5)-1,1);
			}
			
		$dd5 = LowerCase($dd5);
		$dd5a = UpperCaseSql($dd5);
		
		require("brapci_keywors_gr.php");
		$dd[6] = $xcod;
	}
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
array_push($cp,array('$H8','id_kw','id_kw',False,True,''));
array_push($cp,array('$HV','kw_article',$dd[1],False,True,''));
array_push($cp,array('$HV','',$dd[2],False,True,''));
array_push($cp,array('$HV','',$dd[3],False,True,''));
array_push($cp,array('$HV','',$jidsel,False,True,''));
if (strlen($dd[0]) == 0)
	{ array_push($cp,array('$S100','','Palavra chave',True,True,'')); } else 
	{ array_push($cp,array('$Q kw_word:kw_codigo:select * from brapci_keyword where kw_codigo = '.chr(39).$dd[3].chr(39),'','Palavra-chave',False,False,'')); }
	
array_push($cp,array('$HV','kw_keyword',$dd[6],True,True,''));
array_push($cp,array('$[1-20]','kw_ord','Ordem para mostrar',True,True,''));
array_push($cp,array('$A1','',$dd[6],False,True,''));
array_push($cp,array('$A','','Palavras para processar',False,True,''));
array_push($cp,array('$H8','','',False,True,''));
array_push($cp,array('$T50:6','','separe por ";" ou ",","."',False,True,''));


require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');

$http_edit = 'brapci_keywors_ed.php';
$http_redirect = 'close.php';
?><TABLE width="<?=$tab_max?>" align="center"><TR><TD><?
editar();
?></TD></TR></TABLE>

<?
if ($saved > 0)
	{	
	echo $sql;

	}
