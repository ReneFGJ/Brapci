<?
$complemento = '../';
$include = '../';
require($include.'db.php');
require($include."sisdoc_cookie.php");
?>
<head>
<link rel="STYLESHEET" type="text/css" href="../css/letras.css">
</head>

<?
echo '<title>ListSets</title>';
$debug = true;
//// recupera periódico ativo
$jid = strzero(read_cookie("jid"),7);
$jid_nome = read_cookie("jid_name");

$tabela = "oai_listsets";
$cp = array();
array_push($cp,array('$H8','id_ls','id_ls',False,True,''));
array_push($cp,array('$S50','ls_setspec','setspec',False,False,''));
array_push($cp,array('$S100','ls_setname','Sigla',False,False,''));
array_push($cp,array('$Q se_descricao:se_codigo:select * from brapci_section where se_ativo=1 order by se_descricao','ls_equal','Equivalência a',False,True,''));
array_push($cp,array('$O : &S:Seção do journal&E:Edição do journal','ls_tipo','Tipo',True,True,''));
array_push($cp,array('$Q periodo:ed_codigo:select CONCAT('.chr(39).' v. '.chr(39).', ed_vol, '.chr(39).' No '.chr(39).', ed_nr, ed_periodo ) as periodo ,ed_codigo  from brapci_edition where ed_journal_id = '.chr(39).$jid.chr(39).' order by ed_vol desc, ed_nr desc','ls_equal_ed','Equivalência a',False,True,''));

?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right">
<?	
	$cpn = $dd[99];
	require($include.'sisdoc_colunas.php');
	require($include.'sisdoc_form2.php');

	require($include.'cp2_gravar.php');
	$http_edit = 'listsets_equal.php';
	$http_redirect = '../close.php';
	$tit = 'Associar ListSets (OAI)';
	$tit = strtoupper(substr($tit,0,1)).substr($tit,1,strlen($tit));
	echo '<CENTER><font class=lt1>'.$tit.'</font></CENTER>';
	?><TABLE width="<?=$tab_max?>" align="center"><TR><TD><?
	editar();
	?></TD></TR></TABLE>
</TD>
</TR>
</TABLE>
</DIV>