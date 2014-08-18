<?
/** NetWork **/
$snt = '';
if (strlen($op3) > 0)
	{
	$sql = " select * from (";
	$sql .= " select count(*) as total, autor_alias as autores from brapci_article ";
	$sql .= " inner join brapci_article_author on ar_codigo = ae_article ";
	$sql .= " inner join brapci_autor as t1 on t1.autor_codigo = ae_author ";
	$sql .= " inner join brapci_journal on jnl_codigo = ar_journal_id  ";
	$sql .= " inner join brapci_edition on ar_edition = ed_codigo  ";
	$sql .= " where ".$op2;
	$sql .= " group by autor_alias ";
	$sql .= " ) as t5 ";
	$sql .= " inner join brapci_autor on autores = autor_codigo ";
	$sql .= " order by total desc, autor_nome ";

	$rlt = db_query($sql);
	while ($line = db_read($rlt))
		{
		$snt .= '<TR '.coluna().'>';
		$snt .= '<TD>'.$line['autor_nome'].'</TD>';
		$snt .= '<TD align="center">'.$line['total'].'</TD>';
		$snt .= '</TR>';
		}
	}
?>
<center><font class="lt3">Colaborações - Cooperações</font></center>
<Table width="100%" class="lt1">
<TR><TH>Nome do colaborador(es)</TH><TH width="3%">Nº col.</TH></TR>
<?=$snt;?>
</TABLE>
</TD>
</TD></TR>
</table>