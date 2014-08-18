<?
require("cab.php");
require($include.'sisdoc_debug.php');
require($include.'sisdoc_windows.php');
require($include.'sisdoc_debug.php');

?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right">
EXPORTAÇÃO PARA PRODUÇÂO
<BR><BR><BR>
<?           

global $ed_editar;
$sqlq = '';

	/////////////////////////////////// AUTOR
	$sqla = "select ae_article,autor_nome from brapci_article_author ";
	$sqla .= " inner join brapci_autor on autor_codigo = ae_author ";
	$sqla .= " order by ae_article ";
	$rlta = db_query($sqla);
	$aut1 = '';
	$aut2 = '';
	$myFile = "_search_autores.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$arr = "X";
	while ($aline = db_read($rlta))
		{
		$art = $aline['ae_article'];
		if ($art != $arr)
			{
				$ini++;
				$sss = $aline['ae_article'].';'.UpperCaseSql($aline['autor_nome']);
				fwrite($fh, chr(255).$sss);
				echo '<BR>'.$sss;
				$arr = $art;
			} else {
				$sss = ';'.UpperCaseSql($aline['autor_nome']);
				fwrite($fh,$sss);
				echo $sss;
			}
		}
	fclose($fh);
echo '<HR>Total '.$ini;
?>
<BR><BR>
<? if (intval($ini/50) == ($ini/50))
    { ?>
    <A HREF="http://www.brapci.ufpr.br/mm/export_tipo_1_txt.php?dd0=<?=$ini;?>">Proxima</a>  
    <META HTTP-EQUIV="Refresh" CONTENT="3333;URL=export.php?dd0=<?=$ini;?>">
    <? } ?>
<BR><BR>
xxxxxxxx FIM OK xxxxxxxxxxxxx
</div>
