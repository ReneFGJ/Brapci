<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");

$http_redirect = '';
$titu[0] = "Palavras-chaves dos artigos";
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><CENTER>
<?
$sql = "select count(*) as total, kw_idioma from brapci_keyword group by kw_idioma ";
$rlt = db_query($sql);

while ($line = db_read($rlt))
	{
	echo $line['kw_idioma'].' ('.$line['total'].')&nbsp;&nbsp;&nbsp;';
	}
echo '<BR>';
$sql = "select * from (
select count(*) as total, kw_keyword from brapci_article_keyword group by kw_keyword
) as tabela 
inner join brapci_keyword on kw_keyword = kw_codigo
order by total desc limit 200 ";
$rlt = db_query($sql);

$tp = 'X';
while ($line = db_read($rlt))
	{
	$tipo = trim($line['kw_tipo']);
	if ($tipo == 'A') { $tipo = 'Descritor onom�stico'; }
	if ($tipo == 'G') { $tipo = 'Descritor geogr�fico'; }
	if ($tipo == 'H') { $tipo = 'Descritor cronol�gico'; }
	if ($tipo == 'T') { $tipo = 'Descritor'; }
	if ($tipo == 'N') { $tipo = '-n�o classificado-'; }
	$s .= '<TR '.coluna().'>';
	$s .= '<TD>'.$line['kw_word'].'</TD>';
	$s .= '<TD>'.$line['kw_idioma'].'</TD>';
	$s .= '<TD>'.$line['total'].'</TD>';
	$s .= '<TD>'.$tipo.'</TD>';	
	$s .= '</TR>';
	}
?>
<table width="<?=$tab_max;?>" align="center" class="lt1">
	<TR>
	<TH>Palavra-chave</TH>
	<TH>Idioma</TH>
	<TH>incid�ncia</TH>
	</TR>
<?=$s;?>
</table>
</DIV>
