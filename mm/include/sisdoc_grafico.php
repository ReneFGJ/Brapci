<?
///////////////////////////////////////////
// BIBLIOTECA DE FUNÇÕS PHP ///////////////
////////////////////////////// criado por /
////////////////// Rene F. Gabriel Junior /
/////////////////    rene@sisdoc.com.br   /
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0a                       20/05/2008 //
///////////////////////////////////////////
if ($mostar_versao == True) {array_push($sis_versao,array("sisDOC (Gráfico)","0.0a",20080520)); }


	if (strlen($estilo) ==0)
		{ 
		$estilo = '<style>'.chr(13).chr(10);
		$estilo .= '.gr_lt5 { font-family: Georgia; font-size: 20px; color:#808080; text-decoration: none; }'.chr(13).chr(10);
		$estilo .= '.gr_lt0 { font-family: tahoma; font-size: 11px; color:#303030; text-decoration: none; }'.chr(13).chr(10);
		$estilo .= '</style>'.chr(13).chr(10);
		}

function gr_resumo($vlr,$hed,$title)
	{
	global $estilo;

	$sc = '';
//	for ($kh=0;$kh < count($hed);$kh++)
		{
		$sc .= '<TR><TD align="center" colspan="30" class="gr_lt5">'.$title.'</TD>';
		for ($kz=0;$kz < count($vlr);$kz++)
			{
			$sc .= '<TR><TD align="center">'.$hed[$kz].'</TD>';
			$v2 = $vlr[$kz];
			for ($kk=0;$kk < count($v2);$kk++)
				{
				$sc .= '<TD align="right" width="54">'.$v2[$kk].'</TD>';
				}
			}
		}
	$st = $estilo.'<TABLE class="gr_lt0" cellpadding="1" cellspacing="0" border="1">';
	$st .= '<TR>';
	$st .= $sc;
	$st .= '</TABLE>';
	return($st);
	}

function gr_barras($vlr,$title,$altura)
	{
	global $estilo,$max;
	
	$i1 = array();
	$i2 = array();
	$i3 = array('#00ccff','#0099ff','#0066ff','#0033ff','#0000ff',
		'#000099','#0033cc','#006699','#009999','#00cc99','#00ff99',
		'#000033','#003333','#006633','#009933','#00cc33','#00ff33',
		'#cc0033','#cc3333','#cc6633','#cc9933','#cccc33','#ccff33',
		'#330033','#333333','#336633','#339933','#33cc33','#33ff33',
		'#330066','#333366','#336666','#339966','#33cc66','#33ff66',
		);
	$i4 = array();
	if (strlen($max) ==0) { $max = 0.1; }
	if (strlen($altura) ==0) { $altura = 220; }
	for ($kk=0;$kk < count($vlr);$kk++)
		{
			array_push($i1,$vlr[$kk][0]);
			array_push($i2,$vlr[$kk][1]);
			if (strlen($vlr[$kk][2]) > 0) { $i3[$kk] =$vlr[$kk][2]; }
			array_push($i4,$vlr[$kk][3]);
			if ($max < $vlr[$kk][0])
				{ $max = $vlr[$kk][0]; }
		}
	$sr = '<TR valign="bottom" class="gr_lt5">';
	$sc = '<TR valign="top" class="gr_lt0">';
	$sv = '<TR valign="top" class="gr_lt0">';
	$max_co =$altura / $max;
	$wh = 68;
	if (count($i1) ==0)
		{
		$wht = 704;
		} else {
		$wht = intval(704/count($i1));
		}
	if ($wht < $wh) { $wh = $wht+1; }
	
	for ($kk=0;$kk < count($i1);$kk++)
		{
		$he = intval($i1[$kk]*$max_co);
		$hei = $altura - $he;
		$sr .= '<TD bgcolor="'.$i3[$kk].'"><img src="img/nada_white.gif" width="'.$wh.'" height="'.$hei.'" alt="" border="0"><BR><img src="img/grafico-top.gif" width="'.$wh.'" height="14" alt="" border="0"><BR><img src="img/grafico-body.gif" width="'.$wh.'" height="'.$he.'" alt="" border="0">';
		$sc .= '<TD align="center">'.$i1[$kk].'</TD>';
		$sv .= '<TD align="center"><B>'.$i2[$kk].'</TD>';
		}
//	$st = $estilo.'<TABLE cellpadding="0" cellspacing="0" width="'.intval($wh*count($i1)).'">';
	$st = $estilo.'<TABLE cellpadding="0" cellspacing="0" width="100">';
	if (strlen($title) > 0) 
		{ $st .= '<TR><TD align="center" colspan="20"><font class="gr_lt5">'.$title.'</font></TD></TR>'; }
	$st .= $sr;
	$st .= '<TR><TD align="center" bgcolor="#000000" height="1" colspan="20"></TD></TR>';
	$st .= $sv;
	$st .= $sc;
	$st .= '</TABLE>';
//	echo '<HR>'.$max.'<HR>'.$max_co.'<HR>'.$altura.'<HR>';
	return($st);
	}
function grafico_demo()
	{
	$max = 200;
	$vv = array();
	array_push($vv,array(123,'jan'));
	array_push($vv,array(154,'fev'));
	array_push($vv,array(172,'mar'));
	array_push($vv,array(112,'abr'));
	echo gr_barras($vv,'Gráfico demo',200);
	}
	
?>
