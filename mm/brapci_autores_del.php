<? 
require("db.php"); 
require($include."sisdoc_autor.php");
require($include."sisdoc_cookie.php");
$jidsel = strzero(read_cookie("journal_sel"),7);
$jedsel = read_cookie("journal_ed");

///////////////////////////////////////////////////

$tabela = "brapci_article_author";
$cp = array();
array_push($cp,array('$H8','id_ae','id_ae',False,True,''));
array_push($cp,array('$HV','ae_article',$dd[1],False,True,''));
array_push($cp,array('$HV','',$dd[2],False,True,''));
array_push($cp,array('$HV','',$dd[3],False,True,''));
array_push($cp,array('$HV','ae_journal_id',$jidsel,False,True,''));
if (strlen($dd[0]) == 0)
	{ array_push($cp,array('$S100','','Nome do autor (completo)',True,True,'')); } else 
	{ array_push($cp,array('$Q autor_nome:autor_codigo:select * from brapci_autor where autor_codigo = '.chr(39).$dd[3].chr(39),'','Nome do autor (completo)',False,False,'')); }

array_push($cp,array('$O NÃO:NÃO&SIM:SIM','','Confirma Exclusão',False,True,''));

require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');

$http_edit = 'brapci_autores_del.php';
$http_redirect = '';
?><TABLE width="<?=$tab_max?>" align="center"><TR><TD><?
editar();
?></TD></TR></TABLE>

<?
if ($saved > 0)
	{	
	print_r($dd);
	if ($dd[6] == 'SIM')
		{
		$sql = "delete from brapci_article_author where id_ae = ".$dd[0];
		$rlt = db_query($sql);
		}
	redirecina("close.php");
	}
