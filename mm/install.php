<?
require("cab.php");
require($include."sisdoc_debug.php");

?>
<TITLE>Instalação do Sistema</TITLE>
<?

$debug = true;


if (strlen($acao) > 0)
	{
	$fp = fopen('sql/'.$acao.'.sql','r');
	$ddd='';
	while ($dados = fread($fp,4096))
	{
		$ddd = $ddd . $dados;
	}	
	$rlt = db_query($ddd);
	fclose($fp);
	echo '<CENTER><HR width="704">Processado '.$acao.'<HR width="704">';
	}
///////////////////////////////////////////////////////
$diretorio = $dir.'dev/sql/';
$d = dir($diretorio);

$files = array();
while (false !== ($entry = $d->read())) 
{
	$arq = trim($entry);
	$type = strtolower(substr($arq,strlen($arq)-3,3));
	if ($type == 'sql')
		{
			array_push($files,substr($arq,0,strlen($arq)-4));
		}
}
$d->close();
?>
<TABLE width="<?=$tab_max;?>" align="center">
<TR><TD align="center" colspan=10>Install DB<HR>
<TR><TD><form method="get" action="install.php">
<?
$col = 99;
for ($k=0;$k < count($files);$k++)
	{
	if ($col >= 4)
		{
		echo '<TR>';
		$col = 0;
		}
	echo '<TD align="center">';
	echo '<input type="submit" name="acao" value="'.$files[$k].'" style="width: 180px;">';
	$col++;
	}
?>
</TABLE>
</form>
<TABLE width="<?=$tab_max;?>" align="center">
<TR><TD align="left" colspan=10><TT><?=mst($ddd);?>
</TABLE>
