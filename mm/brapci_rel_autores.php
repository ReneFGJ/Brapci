<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('relatorio','main.php'));
array_push($mnpos,array('periódico','brapci_rel_periodico.php'));

require("cab.php");
require($include."google_chart.php");
require($include."sisdoc_colunas.php");
$tit[0] = "Resumo das publicações";
$sx = 'Publicações';
//$sql = "update brapci_article set ar_disponibilidade = '20090701' ";
//$rlt = db_query($sql);

$sql = "select * from (";
$sql .= "select ae_author, count(*) as total from brapci_article_author ";
$sql .= " group by ae_author ";
$sql .= ") as tabela ";
$sql .= " left join brapci_autor on ae_author = autor_codigo ";
$sql .= " order by total desc ";
$sql .= " limit 50 ";
$rlt = db_query($sql);

$v1 = array();
$v2 = array();
$v3 = array();

$ini = 0;
while ($line = db_read($rlt))
	{
	$nome = trim($line['autor_nome']);
	if (strlen($nome) > 0)
		{
		$nome = troca($nome,',',' ');
		$nome = troca($nome,' ',' ');
		array_push($v2,$nome);
		array_push($v1,$line['total']);	
		}
	}
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$tit[0];?>
<BR><BR>
<font class="lt5">Crescimento da Base de Dados BRAPCI</font>
<BR>
<IMG SRC="<?=GoogleChat2d($v1,$v2);?>"><BR>
<font class="lt0">escala do gráfico 1:100</font>


<table width="50%" class="lt2">
	<TR align="center" class="lt0" bgcolor="#c0c0c0">
	<TH>mes/trabalhos</TH>
	<TH>mes/trabalhos</TH>
	<TH>mes/trabalhos</TH>
	<TH>mes/trabalhos</TH>
	<?
	$col = 99;
	for ($r=0;$r < count($v2);$r++)
		{
		echo '<TR '.coluna().'>';
		echo '<TD>';
		echo $v2[$r];
		echo ' (';
		echo $v1[$r];
		echo ')';
		echo '</TD>';
		$col++;
		}
	?>
</table>
</CENTER>
</div>
<?
require("foot.php"); ?>