<?
require("mar_function.php");
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_debug.php");
require($include."sisdoc_windows.php");

$titu[0] = 'Processar Linguagem de Marcação - Phase II'; 
?><div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR>
<?
$path = "mar_marcacao_ii.php";

$sql = "select * from mar_autor ";
$sql .= " where a_status = '@' or a_status = 'A' ";
$sql .= " order by a_nome_asc ";
$rlt = db_query($sql);
$loop = 0;
$online = 0;

$x='';
$y='';
$limit = 10;
while (($line = db_read($rlt)) and ($limit > 0))
	{
	$sz = 3;
	$nome = substr($line['a_nome_asc'],0,$sz);
	if (($x == $nome) ) //and ((substr($nome,$sz,1) == ' ') or (substr($nome,$sz,1) == '.')))
		{
		$limit--;
		$cod = $line['a_codigo'];
		$linka = '<A HREF="#" onclick="newxy('.chr(39).'mar_marcacao_autor_use.php?dd0='.$cod.'&dd1='.$c.chr(39).',300,200);">Enviar como remissiva</A> ==>';
		$linkb = '<A HREF="#" onclick="newxy('.chr(39).'mar_marcacao_autor_use.php?dd0='.$c.'&dd1='.$cod.chr(39).',300,200);">Enviar como remissiva</A> ==>';

		$linkc = ' <A HREF="#" onclick="newxy('.chr(39).'mar_marcacao_autor_use.php?dd2=B&dd0='.$c.chr(39).',300,200);">Ignorar</A> ==>';
		$linkd = ' <A HREF="#" onclick="newxy('.chr(39).'mar_marcacao_autor_use.php?dd2=B&dd0='.$cod.chr(39).',300,200);">Ignorar</A> ==>';
		
		$linkf = ' <A HREF="#" onclick="newxy('.chr(39).'mar_marcacao_autor_use.php?dd2=Z&dd0='.$c.chr(39).',300,200);">[ERRO]</A>';
		$linke = ' <A HREF="#" onclick="newxy('.chr(39).'mar_marcacao_autor_use.php?dd2=Z&dd0='.$cod.chr(39).',300,200);">[ERRO]</A>';
		
		echo $linka.$linkf.$x1.'</A> '.$x2.' '.$x3.$linkc.'<BR>';
		
		echo $linkb.$linke.$line['a_codigo'].'</a> ';
		echo $line['a_use'].' ';
		echo $line['a_nome'].$linkd.'<BR>';
		echo '<HR>';
		}
		$x1 = $line['a_codigo'].' ';
		$x2 = $line['a_use'].' ';
		$x3 = $line['a_nome'].' ';
		$y = $line['a_use'];
		$c = $line['a_codigo'];
		$x = $nome;
	}
//////////////////////////////////////////////////////////////////////////
?>
<BR>
</DIV>
<? require("foot.php"); ?>
<?
if ($loop == 1)
	{
	echo '<META HTTP-EQUIV="Refresh" Content = "2; URL=mar_marcacao_ii.php?dda='.date("dmYHms").'">';
	}
?>
