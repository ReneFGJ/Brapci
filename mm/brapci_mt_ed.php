<? require("db.php"); ?>
<?
if (strlen($cookie_lib) == 0) { require($include."sisdoc_cookie.php"); }

$user_id = read_cookie('nw_log');
$user_nome = read_cookie('nw_user_nome');
$user_nivel = intval('0'.read_cookie('nw_nivel'));
$user_log = read_cookie('nw_user');
?>
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
array_push($cp,array('$Q bmt_descricao:bmt_codigo:select * from brapci_metodologias where bmt_ativo=1 and (bmt_tipo ='.chr(39).'MF'.chr(39).' or  bmt_tipo ='.chr(39).'M'.chr(39).') order by bmt_ordem, bmt_descricao ','at_metodo_1','Quanto aos fins',True,True,''));
array_push($cp,array('$Q bmt_descricao:bmt_codigo:select * from brapci_metodologias where bmt_ativo=1 and (bmt_tipo ='.chr(39).'MO'.chr(39).' or  bmt_tipo ='.chr(39).'M'.chr(39).') order by bmt_ordem, bmt_descricao ','at_metodo_2','Quanto aos meios',True,True,''));
array_push($cp,array('$Q bmt_descricao:bmt_codigo:select * from brapci_metodologias where bmt_ativo=1 and (bmt_tipo ='.chr(39).'ME'.chr(39).' or  bmt_tipo ='.chr(39).'MQ'.chr(39).' or  bmt_tipo ='.chr(39).'M'.chr(39).') order by bmt_ordem, bmt_descricao ','at_metodo_3','Enfoque',True,True,''));

array_push($cp,array('$Q bmt_descricao:bmt_codigo:select * from brapci_metodologias where bmt_ativo=1 and bmt_tipo='.chr(39).'T'.chr(39).' order by bmt_ordem, bmt_descricao ','at_tecnica_1','T�cnica',True,True,''));
array_push($cp,array('$Q bmt_descricao:bmt_codigo:select * from brapci_metodologias where bmt_ativo=1 and bmt_tipo='.chr(39).'T'.chr(39).' order by bmt_ordem, bmt_descricao ','at_tecnica_2','T�cnica',True,True,''));
array_push($cp,array('$Q bmt_descricao:bmt_codigo:select * from brapci_metodologias where bmt_ativo=1 and bmt_tipo='.chr(39).'T'.chr(39).' order by bmt_ordem, bmt_descricao ','at_tecnica_3','T�cnica',True,True,''));

array_push($cp,array('$Q bmt_descricao:bmt_codigo:select * from brapci_metodologias where bmt_ativo=1 and bmt_tipo='.chr(39).'TA'.chr(39).' order by bmt_ordem, bmt_descricao ','at_analise_1','An�lise',True,True,''));
array_push($cp,array('$Q bmt_descricao:bmt_codigo:select * from brapci_metodologias where bmt_ativo=1 and bmt_tipo='.chr(39).'TA'.chr(39).' order by bmt_ordem, bmt_descricao ','at_analise_2','An�lise',True,True,''));

array_push($cp,array('$O :Sem classifica��o&1:D�vida sobre classifica��o&2:Para revisar&7:Artigo revisado&8:Amostra Metodol�gica (revisar)&9:Amostra Metodol�gica (OK)&6:Amostra de qualifica��o','at_mt','Status',False,True,''));

array_push($cp,array('$U8','at_metodologia_data','Atualiza��o',False,True,''));
array_push($cp,array('$HV','at_metodologia_log',uppercase($user_log),False,True,''));

require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');

$http_edit = 'brapci_mt_ed.php';
$http_redirect = 'close.php';
?><TABLE width="<?=$tab_max?>" align="center"><TR><TD><?
editar();
?></TD></TR></TABLE>
