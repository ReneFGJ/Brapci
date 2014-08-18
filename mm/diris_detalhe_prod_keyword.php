<?
/** Keywords */
if (strlen($op1) >0)
{
$sql = "SELECT count(*) as total, kw_keyword, kw_word, kw_idioma ";
$sql .= "FROM brapci_article_keyword "; 
$sql .= "inner join brapci_keyword on kw_keyword = kw_codigo ";
$sql .= "WHERE (".$op1.") and (kw_idioma = 'pt_BR') ";
$sql .= "group by kw_keyword, kw_word, kw_idioma ";
$sql .= "order by total desc ";
$sql .= " limit 16 ";
$rlt = db_query($sql);
$sk1 = '';
$sk2 = '';
$ti=0;
while ($line = db_read($rlt))
	{
	$sk = '<TR '.coluna().'>';
	$sk .= '<TD align="right">'.($ti+1).'</TD>';
	$sk .= '<TD>'.trim($line['kw_word']).'</TD>';
	$sk .= '<TD align="center">'.trim($line['total']).'</TD>';
	$sk .= '</TR>';
	if ($ti < 8)
		{ $sk1 .= $sk; } else {$sk2 .= $sk; }
	$ti++;
	}
$hr = '<TH width="5%">Pos.</TH><TH width="85%">Palavra-chave</TH><TH width="10%">Incidência</TH>';
?>
<center><font class="lt3">Temática pela incidência das palavras-chave</font></center>
<Table width="100%" class="lt1" border="1" cellpadding="0" cellspacing="1">
<TR valign="top">
	<TD width="50%"><Table width="100%" class="lt1"><?=$hr;?><?=$sk1;?></table></TD>
	<TD width="50%"><Table width="100%" class="lt1"><?=$hr;?><?=$sk2;?></table></TD>
</TR>
</TABLE>
<? } ?>