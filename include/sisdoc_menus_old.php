<?php
///////////////////////////////////////////
// BIBLIOTECA DE FUNÇÕS PHP ///////////////
////////////////////////////// criado por /
////////////////// Rene F. Gabriel Junior /
/////////////////    rene@sisdoc.com.br   /
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0a                       21/10/2009 //
///////////////////////////////////////////
if ($mostar_versao == True) { array_push($sis_versao,array("sisDOC (Menus)","0.0a",20091021)); }
if (strlen($include) == 0) { exit; }
//	array_push($menu,array('Edital','Edital Geral','pibic_edital_geral.php')); 
?>
<style>
.menu_tit {
	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size: 14px;
	font-weight: bold;
	text-decoration: none;
}

.menu_li a {
	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size: 12px;
	text-decoration: none;
	color: Blue;
	line-height: 22px;
}

.menu_li a:hover { text-decoration: underline; color: Navy;  }
</style>
<?

function menus($menu,$tipo)
	{
	global $acao,$dd,$tab_max,$uploaddir;
	
///////////////////////////////////////////////////// redirecionamento
if ((isset($dd[1])) and (strlen($dd[1]) > 0))
	{
	$col=0;
	for ($k=0;$k <= count($menu);$k++)
		{
		 if ($dd[1]==CharE($menu[$k][1])) {	header("Location: ".$menu[$k][2]); } 
		}
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	/////////////////////////////////// Tipo 1
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 	
	if ($tipo == '1')
		{
			?>
			<TABLE width="<?=$tab_max;?>" align="center" border="0">
			<TR><TD colspan="4">
				<FONT class="lt3">
				</FONT><FORM method="post" action="">
				</TD></TR>
			</TABLE>
			<TABLE width="<?=$tab_max;?>" align="center" border="0">
			<TR>
			<?
				$xcol=0;
				$seto = "X";
				for ($x=0;$x <= count($menu); $x++)
					{
					if (isset($menu[$x][2]))
						{
							{
							$xseto = $menu[$x][0];
							if (!($seto == $xseto))
								{
								echo '<TR><TD colspan="10">';
								echo '<TABLE width="100%" cellpadding="0" cellspacing="0">';
								echo '<TR><TD class="lt3" width="1%"><BR><NOBR><B><font color="#0000a0">'.$xseto.'&nbsp;</TD>';
								echo '<TD><HR width="100%" size="2"></TD></TR>';
								echo '</TABLE>';
								echo '<TR class="lt3">';
								$seto = $xseto;
								$xcol=0;
								}
							}
						if ($xcol >= 3) { echo '<TR><TD><img src="'.$img_dir.'nada.gif" width="1" height="5" alt="" border="0"></TD><TR>'; $xcol=0; }
						echo '<TD align="center">';
						echo '<input type="submit" name="dd1" value="'.CharE($menu[$x][1]).'" '.$estilo_admin.'>';
						echo '</TD>';
						$xcol = $xcol + 1;
						}
					}
			?>
			</TABLE></FORM>		
			<?
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	/////////////////////////////////// Tipo 2
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 	
	if ($tipo == '2')
		{
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	/////////////////////////////////// Tipo 3
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 	
	if ($tipo == '3')
		{
		$tps = 0;
		$seto = '';
		for ($x=0;$x <= count($menu); $x++)
			{
			$xseto = $menu[$x][0];
			if (!($seto == $xseto)) { $tps++; $seto = $xseto; }
			}
		/////////////////////////////////			
		$col = 0;
		$cola = 0;
		$mcol = intval($tps/2);
		$cm1 = '';
		$cm2 = '';
		$seto = 'x';
		for ($x=0;$x <= count($menu); $x++)
				{
				$xseto = $menu[$x][0];
				if (!($seto == $xseto))
					{
					$cola++;
					if ($cola > $mcol) { $col = 1; }
					$seto = lowercasesql($xseto);
					$seto = troca($seto,' ','_');
					$img_icone = 'img/icone_'.$seto.'.png';
					$image = $uploaddir . 'cep2/'.$img_icone;
					if (!(file_exists($image)))
						{ 	$img_icone = 'img/icone_noimage.png'; 	}
					////////////////////////////////////////////
					$sc = '<TR><TD colspan="10">';
					$sc .= '<TABLE width="100%" cellpadding="0" cellspacing="2" border="0"  class="menu_tit">';
					$sc .= '<TR><TD rowspan="2" width="48"><img src="'.$img_icone.'" width="48" height="48" alt=""></TD>';
					$sc .= '<TD width="80%"><BR><NOBR><B>'.$xseto.'&nbsp;</TD>';
					$sc .= '<TR><TD><HR width="100%" size="2"></TD></TR>';
					$sc .= '</TABLE>';
					$sc .= '<TR class="lt1"><TD><UL>';
					$seto = $xseto;
					$xcol=0;
					} else  { $sc = ''; }
				if (isset($menu[$x][2]))		
					{ 
					if ($col == 0)
						{
						$cm1 .= $sc;
						$cm1 .= '<LI class="menu_li"><A href="'.$menu[$x][2].'" class="menu_item">'.$menu[$x][1].'</A><BR>'; 
						} else {
						$cm2 .= $sc;
						$cm2 .= '<LI class="menu_li"><A href="'.$menu[$x][2].'" class="menu_item">'.$menu[$x][1].'</A><BR>'; 
						}
					}
				}
		
		$sm = '<TABLE width="'.$tab_max.'" border=0 align="center">';
		$sm .= '<TR valign="top">';
		$sm .= '<TD width="48%"><table width="100%">'.$cm1.'</table></TD>';
		$sm .= '<TD width="4%"></TD>';
		$sm .= '<TD width="48%"><table width="100%">'.$cm2.'</table></TD>';
		$sm .= '</TR>';
		$sm .= '</TABLE>';
		echo $sm;
		}		
	}
?>
