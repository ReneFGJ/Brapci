<?
$termo = "Termo 1";
$termo_pref = $ttt[0][0];


//////////////////////////////////////////////////////////////////// MATRIX

$mtx = array();
for ($r=0;$r < 20;$r++)
	{
	$ta = array('<TD width="197" height="59">&nbsp;</TD>','<TD width="145">&nbsp;</TD>','','<TD width="145">&nbsp;</TD>','<TD width="197" height="59">&nbsp;</TD>');
	array_push($mtx,$ta);
	}
$td = '<TD rowspan="2" background="'.$img_ext.'img/skos_prefterm.png" width="150" height="94" alt="" border="0" align="center" >'.$termo_pref.'</TD>' ;
$mtx[0][2] = $td;

$td = '<TD valign="top" rowspan="20">Termos alternativos</TD>' ; 
$mtx[3][2] = $td;

////////////////////////////////////////////////////////////////////////////////////////////////// COMCEPÇÂO
$cc1 = count($ttg); if ($cc1 > 1) { $cc1 = 1; }
$cc2 = count($tte); if ($cc2 > 1) { $cc2 = 1; }
$cpc = '<img src="'.$img_ext.'img/skos_concept_'.$cc1.$cc2.'.png" width="150" height="53" alt="" border="0">';
$mtx[2][2] = '<TD>'.$cpc.'</TD>';


///////////////////////////////////////////// REGRAS TERMOG GRAL
$tq = count($ttg);
if ($tq == 1)
	{
	$mtx[2][0] = '<TD background="'.$img_ext.'img/skos_borrow.png" align="center">'.$ttg[0][0].'</TD>';
	$mtx[2][1] = '<TD><img src="'.$img_ext.'img/skos_arrow_10.png" width="145" height="59" alt="" border="0"></TD>';
	}
if ($tq == 2)
	{
	$mtx[2][0] = '<TD background="'.$img_ext.'img/skos_borrow.png" align="center">'.$ttg[0][0].'</TD>';
	$mtx[2][1] = '<TD><img src="'.$img_ext.'img/skos_arrow_12.png" width="145" height="59" alt="" border="0"></TD>';

	$mtx[3][0] = '<TD background="'.$img_ext.'img/skos_borrow.png" align="center">'.$ttg[1][0].'</TD>';
	$mtx[3][1] = '<TD><img src="'.$img_ext.'img/skos_arrow_14.png" width="145" height="59" alt="" border="0"></TD>';
	}
if ($tq == 3)
	{
	$mtx[1][0] = '<TD background="'.$img_ext.'img/skos_borrow.png" align="center">'.$ttg[0][0].'</TD>';
	$mtx[1][1] = '<TD><img src="'.$img_ext.'img/skos_arrow_15.png" width="145" height="59" alt="" border="0"></TD>';

	$mtx[2][0] = '<TD background="'.$img_ext.'img/skos_borrow.png" align="center">'.$ttg[1][0].'</TD>';
	$mtx[2][1] = '<TD><img src="'.$img_ext.'img/skos_arrow_11.png" width="145" height="59" alt="" border="0"></TD>';

	$mtx[3][0] = '<TD background="'.$img_ext.'img/skos_borrow.png" align="center">'.$ttg[2][0].'</TD>';
	$mtx[3][1] = '<TD><img src="'.$img_ext.'img/skos_arrow_14.png" width="145" height="59" alt="" border="0"></TD>';
	}
if ($tq > 3)
	{
	for ($k=0;$k < count($ttg);$k++)
		{ 
		if ($k == 0) { $tg = '<TD><img src="'.$img_ext.'img/skos_arrow_15.png" width="145" height="59" alt="" border="0"></TD>'; }
		if ($k == 1) { $tg = '<TD><img src="'.$img_ext.'img/skos_arrow_16.png" width="145" height="59" alt="" border="0"></TD>'; }
		if ($k == 2) { $tg = '<TD><img src="'.$img_ext.'img/skos_arrow_11.png" width="145" height="59" alt="" border="0"></TD>'; }
		if ($k > 2 ) { $tg = '<TD><img src="'.$img_ext.'img/skos_arrow_13.png" width="145" height="59" alt="" border="0"></TD>'; }
		if ($k == (count($tte)-1) ) { $tg = '<TD><img src="'.$img_ext.'img/skos_arrow_14.png" width="145" height="59" alt="" border="0"></TD>'; }
		$mtx[$k][1] = $tg; 
		$mtx[$k][0] = '<TD background="'.$img_ext.'img/skos_norrow.png" align="center" width="197" height="59">'.$ttg[$k][0].'</TD>'; 
		}
	}

///////////////////////////////////////////// REGRAS TERMOG ESPECÍFOCS
$tq = count($tte);
if ($tq == 1)
	{
	$mtx[2][4] = '<TD background="'.$img_ext.'img/skos_norrow.png" align="center">'.$tte[0][0].'</TD>';
	$mtx[2][3] = '<TD><img src="'.$img_ext.'img/skos_arrow_0.png" width="145" height="59" alt="" border="0"></TD>';
	}
if ($tq == 2)
	{
	$mtx[2][4] = '<TD background="'.$img_ext.'img/skos_norrow.png" align="center">'.$tte[0][0].'</TD>';
	$mtx[2][3] = '<TD><img src="'.$img_ext.'img/skos_arrow_2.png" width="145" height="59" alt="" border="0"></TD>';

	$mtx[3][4] = '<TD background="'.$img_ext.'img/skos_norrow.png" align="center">'.$tte[1][0].'</TD>';
	$mtx[3][3] = '<TD><img src="'.$img_ext.'img/skos_arrow_4.png" width="145" height="59" alt="" border="0"></TD>';
	}
if ($tq == 3)
	{
	$mtx[1][4] = '<TD background="'.$img_ext.'img/skos_norrow.png" align="center">'.$tte[0][0].'</TD>';
	$mtx[1][3] = '<TD><img src="'.$img_ext.'img/skos_arrow_5.png" width="145" height="59" alt="" border="0"></TD>';

	$mtx[2][4] = '<TD background="'.$img_ext.'img/skos_norrow.png" align="center">'.$tte[1][0].'</TD>';
	$mtx[2][3] = '<TD><img src="'.$img_ext.'img/skos_arrow_1.png" width="145" height="59" alt="" border="0"></TD>';

	$mtx[3][4] = '<TD background="'.$img_ext.'img/skos_norrow.png" align="center">'.$tte[2][0].'</TD>';
	$mtx[3][3] = '<TD><img src="'.$img_ext.'img/skos_arrow_4.png" width="145" height="59" alt="" border="0"></TD>';
	}
if ($tq > 3)
	{
	for ($k=0;$k < count($tte);$k++)
		{ 
		if ($k == 0) { $tg = '<TD><img src="'.$img_ext.'img/skos_arrow_5.png" width="145" height="59" alt="" border="0"></TD>'; }
		if ($k == 1) { $tg = '<TD><img src="'.$img_ext.'img/skos_arrow_6.png" width="145" height="59" alt="" border="0"></TD>'; }
		if ($k == 2) { $tg = '<TD><img src="'.$img_ext.'img/skos_arrow_1.png" width="145" height="59" alt="" border="0"></TD>'; }
		if ($k > 2 ) { $tg = '<TD><img src="'.$img_ext.'img/skos_arrow_3.png" width="145" height="59" alt="" border="0"></TD>'; }
		if ($k == (count($tte)-1) ) { $tg = '<TD><img src="'.$img_ext.'img/skos_arrow_4.png" width="145" height="59" alt="" border="0"></TD>'; }
		$mtx[$k][3] = $tg; 
		$mtx[$k][4] = '<TD background="'.$img_ext.'img/skos_norrow.png" align="center" width="197" height="59">'.$tte[$k][0].'</TD>'; 
		}
	}
?>
<table cellpadding="0" cellspacing="0" border="0" align="center" class="lt1">
<?
for ($r=0;$r < 20;$r++)
	{
	if ((strlen(trim($mtx[$r][0].$mtx[$r][4])) > 80) or ($r < 3))
	{
	echo '<TR>';
	for ($y=0;$y < 5;$y++)
		{ echo $mtx[$r][$y]; }
	echo chr(13).chr(10);
	}
	}
?>
</table>