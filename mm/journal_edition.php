<?
$sql = "select * from ( SELECT count(*) as total, ar_edition ";
$sql .= " FROM  brapci_article WHERE ar_journal_id = '".strzero($jid,7)."' ";
$sql .= " group by ar_edition ";
$sql .= ") as tabela inner join brapci_edition on ar_edition = ed_codigo ";
$sql .= " order by ed_ano desc, ed_vol desc, ed_nr desc ";
$rlt = db_query($sql);
$ano = 0;
$nr = '';
$sr = '';
while ($line = db_read($rlt))
	{
	$stia = trim($line['ed_status']);
	$sti = '';
	$link = '';
	if ($stia == '') { $sti = '<img src="img/icone_operador.png" width="32" height="32" alt="" align="left">'; }
	if ($stia == 'A') { $sti = '<img src="img/icone_operador.png" width="32" height="32" alt="" align="left">'; }
	if ($stia == 'B') { $sti = '<img src="img/icone_revisao.png" width="32" height="32" alt="" align="left">'; }
	if ($stia == 'C') { $sti = '<img src="img/icone_checar.png" width="32" height="32" alt="" align="left">'; }
	if ($stia == 'F') { $sti = '<img src="img/icone_ok.png" width="32" height="32" alt="" align="left">'; }

	
	$link = '<A HREF="journal_ed_sel.php?dd0='.$line['ed_codigo'].'">';
	$xano = $line['ed_ano'];
	$xvol = $line['ed_vol'];
	$xnr = $line['ed_nr'];
	
	if ($staia == 'A') { 	$sti = '<img src="img/icone_checar.png" width="32" height="32" alt="" align="left">'; }
	if ($staia == 'B') { 	$sti = '<img src="img/icone_ok.png" width="32" height="32" alt="" align="left">'; }
	
	if ($xano != $ano)
		{
		$sr .= '<TR><TD colspan=10 class="lt3"><hr>';
//		$sr .= '<img src="img/icone_ok.png" width="32" height="32" alt="" align="left">';
		$sr .= $xano;
		$sr .= '<TR>';
		$ano = $xano;
		$nr = '99';
		}
	if ($nr != $xnr)
		{
		$sr .= '<TD>';
		$sr .= $sti;
		$sr .= $link;
		$sr .= 'v. '.$xvol.', n. '.$xnr;
		$sr .= '</A>';
		$sr .= ' <font class="lt0"><BR>';
		$sr .= '('.$line['total'].' artigo(s))';
		$nr = $xnr;
		}
	}
?>
<table width="100%" cellpadding="5" cellspacing="0">
<TR bgcolor="#999999">
<TH>
<TH>
<TH>
<TH>
<TH>
<TH>
<?=$sr;?>
</table>
