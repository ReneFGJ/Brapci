<?
if (($stc != 'X'))
{
$mtt = array('','','');
$tec = array('','','');
if (strlen($mt_1.$mt_2.$mt_3.$te_1.$te_2.$te_3.$an_1.$an_2) > 0)
	{
	$sql = "select * from brapci_metodologias ";
	$sql .= " where ";
	$sql .= "    bmt_codigo = '".$mt_1."' ";
	$sql .= " or bmt_codigo = '".$mt_2."' ";
	$sql .= " or bmt_codigo = '".$mt_3."' ";
	$sql .= " or bmt_codigo = '".$te_1."' ";
	$sql .= " or bmt_codigo = '".$te_2."' ";
	$sql .= " or bmt_codigo = '".$te_3."' ";
	$sql .= " or bmt_codigo = '".$an_1."' ";
	$sql .= " or bmt_codigo = '".$an_2."' ";
	$rlt = db_query($sql);
	while ($line = db_read($rlt))
		{
		$tcod = trim($line['bmt_codigo']);
		$tdes = trim($line['bmt_descricao']);
		if ($mt_1 == $tcod) { $mtt[0] = $tdes; }
		if ($mt_2 == $tcod) { $mtt[1] = $tdes; }
		if ($mt_3 == $tcod) { $mtt[2] = $tdes; }
		if ($te_1 == $tcod) { $tec[0] = $tdes; }
		if ($te_2 == $tcod) { $tec[1] = $tdes; }
		if ($te_3 == $tcod) { $tec[2] = $tdes; }
		if ($an_1 == $tcod) { $ana[0] = $tdes; }
		if ($an_2 == $tcod) { $ana[1] = $tdes; }
		}
	}


$sx .= '<fieldset><legend><font class="lt3">Opções metodológicas de pesquisa</font></legend>';
$sx .= '<table width="98%" align="center">';
$sx .= '<TR><TH>Metodologia</TH><TH>Técnicas</TH><TH>Análises</TH></TR>';
$sx .= '<TR><TD width="33%">1. '.$mtt[0].'</TD>';
$sx .=     '<TD width="33%">1. '.$tec[0].'</TD>';
$sx .=     '<TD width="33%">1. '.$ana[0].'</TD>';
$sx .= '<TR><TD>2. '.$mtt[1].'</TD>';
$sx .=     '<TD>2. '.$tec[1].'</TD>';
$sx .=     '<TD>2. '.$ana[1].'</TD>';
$sx .= '<TR><TD>3. '.$mtt[2].'</TD>';
$sx .=     '<TD>3. '.$tec[2].'</TD>';
$sx .= '</TR>';
$sx .= '</table>';

if ($ed_editar == 1) 
	{ 
	$linke = 'onclick="newxy2('.chr(39).'brapci_mt_ed.php?dd0='.$ida.chr(39).',650,250);"'; 
	$sa1 = '<img src="img/icone_editar.gif" width="20" height="19" alt="Editar página" border="0" '.$linke.' >';
	$sx .= tips($sa1,'Opções metodológicas da pesquisa');
	}
}
?>