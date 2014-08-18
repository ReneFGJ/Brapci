<?
require("db.php");
require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');

$tabela = "mar_works";
$cp = array();
array_push($cp,array('$H4','id_m','id_m',False,False,''));
array_push($cp,array('$HV','m_status','@',True,True,''));
array_push($cp,array('$O : &SIM:SIM','','Confirma exclusão',True,True,''));
array_push($cp,array('$H8','','',True,True,''));
$tit = "Referência";
	echo '<CENTER><font class=lt1>Exclusão de referências</font></CENTER>';
	?><TABLE width="98%" align="center"><TR><TD><?
	editar();
	?></TD></TR></TABLE>
<?
$sql = "select count(*) as total from mar_works where m_work = '".$dd[3]."' ";
$rlt = db_query($sql);
$line = db_read($rlt);
$total = $line['total'];
echo '<TT>Total de '.$total.' referências cadastradas';
if ($saved > 0)
	{ 
	$sql = "delete from mar_works where m_work = '".$dd[3]."' ";
	echo $sql;
	$rlt = db_query($sql);
	require("close.php"); 
	}
?>