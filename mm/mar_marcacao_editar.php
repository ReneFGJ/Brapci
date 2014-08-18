<?
require("db.php");
require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');

$tabela = "mar_works";
$cp = array();
array_push($cp,array('$H4','id_m','id_m',False,False,''));
array_push($cp,array('$HV','m_status','@',True,True,''));
array_push($cp,array('$T60:5','m_ref','Ref.',False,True,''));
if (strlen($dd[3]) > 0) 
	{ array_push($cp,array('$H8','m_work',$dd[3],True,True,'')); }
	else
	{ array_push($cp,array('$H8','','',False,True,'')); }
$tit = "Referência";
array_push($cp,array('$S40','m_bdoi','BDOI',False,True,''));
array_push($cp,array('$S4','m_ano','Ano',False,True,''));
array_push($cp,array('$S8','m_tipo','Tipo',False,True,''));
array_push($cp,array('$HV','m_journal','',False,True,''));
 
if (strlen(trim($dd[2])) == 0) { $dd[1] = 'X'; }

	echo '<CENTER><font class=lt1>Cadastro de '.$tit.'</font></CENTER>';
	?><TABLE width="<?=$tab_max?>" align="center"><TR><TD><?
	editar();
	?></TD></TR></TABLE>
<?
if ($saved > 0)
	{ 
	redirecina("updatex.php?dd0=mar_works");
	}
?>