<? 
require("db.php"); 
require($include."sisdoc_autor.php");
require($include."sisdoc_cookie.php");
$jidsel = strzero(read_cookie("journal_sel"),7);
$jedsel = read_cookie("journal_ed");
?>
<head>
<link rel="STYLESHEET" type="text/css" href="css/letras_popup.css">
</head>
<font class="lt5"><font color="white">
<CENTER><?=$dd[2];?></CENTER>
</font></font>
<?
$tabela = "brapci_article_suporte";
$cp = array();
array_push($cp,array('$H8','id_bs','id_bs',False,True,''));
array_push($cp,array('$HV','bs_article',$dd[1],False,True,''));
array_push($cp,array('$HV','',$dd[2],False,True,''));
array_push($cp,array('$H1','bs_journal_id','',False,True,''));
array_push($cp,array('$HV','',$jidsel,False,True,''));
array_push($cp,array('$O URL:Link de Internet','bs_type','Tipo',True,True,''));
array_push($cp,array('$S100','bs_adress','Endereço',True,True,''));
array_push($cp,array('$O @:Verificar&A:Testado&X:Cancelado&E:Erro de Link&V:Validado PDF OK!','bs_status','Status',True,True,''));
array_push($cp,array('$U8','bs_update','',False,True,''));


require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');

$http_edit = 'brapci_suporte_ed.php';
$http_redirect = 'close.php';
?><TABLE width="<?=$tab_max?>" align="center"><TR><TD><?
editar();
?></TD></TR></TABLE>

<?
if ($saved > 0)
	{	
	echo $sql;

	}
