<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('relatorio','main.php'));
array_push($mnpos,array('periódico','brapci_rel_periodico.php'));

require("cab.php");
require($include."google_chart.php");
$tit[0] = "Resumo das publicações";
$sx = 'Publicações';
//$sql = "update brapci_article set ar_disponibilidade = '20090701' ";
//$rlt = db_query($sql);


$sql = "select count(*) as total, log_data from log ";
$sql .= " where log_data >= ".date("Ym").'00';
$sql .= " group by log_data ";
$rlt = db_query($sql);

$v1 = array();
$v2 = array();

for ($r=1;$r < 32;$r++)
	{
	array_push($v1,0);
	array_push($v2,$r);
	}
while ($line = db_read($rlt))
	{
	$dia = intval(substr($line['log_data']-1,6,2));
	$v1[$dia] = $line['total'];
	}


$sql = "select * from log ";
$sql .= " left join brapci_usuario on id_usuario = log_user ";
$sql .= " order by id_log desc ";
$sql .= " limit 100 ";
$rlt = db_query($sql);

while ($line = db_read($rlt))
	{
	$sx .= '<TR>';
	$sx .= '<TD>'.$line['log_ip'].'</TD>';
	$sx .= '<TD>'.stodbr($line['log_data']).'</TD>';
	$sx .= '<TD>'.$line['log_hora'].'</TD>';
	$sx .= '<TD>'.$line['log_user'].'</TD>';
	$sx .= '<TD>'.$line['usuario_nome'].'</TD>';
	$sx .= '</TR>';
	}
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$tit[0];?>
<BR><BR>
<font class="lt5">Acesso ao módulo manutenção da BRAPCI</font>
<BR>
<IMG SRC="<?=GoogleBarLine($v1,$v2);?>"><BR>
<font class="lt0">escala do gráfico 1:100</font>


<table width="90%" class="lt0">
	<TR align="center" class="lt0" bgcolor="#c0c0c0">
	<TH>IP</TH>
	<TH>data</TH>
	<TH>hora</TH>
	<TH>cod</TH>
	<TH>usuário</TH>
	<?
	echo $sx;
	?>
</table>
</CENTER>
</div>
<?
require("foot.php"); ?>