<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");

echo '<h1>Selecionar ano de publicação (Fase Iy)</h1>';

require("../_class/_class_cited.php");
$cited = new cited;
//$cited->cited_alterar_status('Z','@');
//$cited->cited_alterar_status('Y','@');
echo $cited->resumo();

$proc = $cited->cited_Iy_recover_year($dd[0]);
echo '<BR><BR>Processadas '.$proc.' referências';
if ($proc > 0)
	{
			echo '<meta http-equiv="refresh" content="2;'.page().'" />';
	}

require("../foot.php");
exit;


//$sql = "update mar_works set m_status = 'F' where m_bdoi <> ''";
//$rlt = db_query($sql);

$titu[0] = 'Processar Linguagem de Marcação - Phase I'; 
?><div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<?

$path = "cited_process_01.php";

$sql = "select * from mar_works where m_status = '@' order by m_ref limit 1  ";
$rlt = db_query($sql);
$refs = array();
$anos = array();
$loop = 0;
$online = 0;

if ($line = db_read($rlt))
	{
	require('mar_marcacao_dados.php');
	$m_ano = trim($line['m_ano']);
	$lx = ' '.trim($line['m_ref']);
	$opx = array('Available from','Acesso em');
	for ($rc=0;$rc < count($opx);$rc++)
		{
		if (strpos($lx,$opx[$rc]) > 0)
			{
			$lx = substr($lx,0,strpos($lx,$opx[$rc]));
			$online = 1;
			}
		}
	for ($ra=date("Y");$ra > 1850;$ra--)
		{
		$max = 0;
		$ano = $ra;
		for ($rt = 0;$rt < strlen($lx);$rt++)
			{
			if (substr($lx,$rt,4) == $ano) { $max = $rt; }
			}
		if ($max > 0)
			{
			array_push($anos,$ano);
			array_push($refs,$max);
			}
		}
	$lx = troca($lx,'<','&lt;');
	$lx = troca($lx,'>','&gt;');
	echo '<BR><B>'.$lx.'</B>';
	echo '<HR>';
	if (count($anos) == 1)
		{
		echo 'Ano: '.$anos[0];
		}
	} else {
		echo 'FIM';
		exit;
	}
/////////////////////////////////////////////////////// ONLINE e SEM ANO
if (strlen($m_ano) == 4) { $anos = array($m_ano); $msg = 'utilizado anteriro'; }
if ((count($anos) == 0) and ($online == 1))
	{
	$lx = ' '.trim($line['m_ref']);
	$lx = substr($lx,strpos($lx,'Acesso em'),100);
	for ($ra=date("Y");$ra > 1990;$ra--)
		{
		$max = 0;
		$ano = $ra;
		for ($rt = 0;$rt < strlen($lx);$rt++)
			{
			if (substr($lx,$rt,4) == $ano) { $max = $rt; }
			}
		if ($max > 0)
			{
			array_push($anos,$ano);
			array_push($refs,$max);
			}
		}	
	}
//////////////////////////////////////////////////////////////////////////
if (count($anos) == 1)
	{
	$sql = "update mar_works set m_status = 'B', m_ano = ".round($anos[0])." where id_m = ".$line['id_m'];
	$rlt = db_query($sql);
	$loop = 1;
	} else {
	if (count($anos) > 1)
		{
		for ($ra = 0;$ra < count($anos);$ra++)
			{
			echo '<a href="mar_marcacao_i.php?dd0='.$line['id_m'].'&dd1='.$anos[$ra].'&dd2=A">'.$anos[$ra].'</a>';
			echo ' ';
			}
			echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="mar_marcacao_i.php?dd0='.$line['id_m'].'&dd1=0&dd2=Z">(Erro na referência)</a>';
			$work = $line['m_work'];
			$link = '<A href="http://www.brapci.ufpr.br/mm/brapci_article_select.php?dd1='.$work.'" target="new">';
			echo '<BR><BR><BR>';
			echo $link.'Editar lista de referências</a>';
		} else {
			echo '<a href="mar_marcacao_i.php?dd0='.$line['id_m'].'&dd1=0&dd2=Z">(Erro na referência)</a>';
			echo ' ';
			echo '<BR><font color=green>'.$msg.'</font>';

			$sql = "update mar_works set m_status = 'Z', m_ano = 0 where id_m = ".$line['id_m'];
			$rlt = db_query($sql);
			$loop = 1;
		}
	}
?>
</TD>
</TR>
</table>
<BR>
</DIV>
<? require("foot.php"); ?>
<?
if ($loop == 1)
	{
	echo '<META HTTP-EQUIV="Refresh" Content = "1; URL=mar_marcacao_i.php?dda='.date("dmYHms").'">';
	}
?>
