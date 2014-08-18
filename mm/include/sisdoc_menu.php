<?
///////////////////////////////////////////
// BIBLIOTECA DE FUNÇÕS PHP ///////////////
////////////////////////////// criado por /
////////////////// Rene F. Gabriel Junior /
/////////////////    rene@sisdoc.com.br   /
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0b                       08/11/2010 //
// 0.0a                       20/05/2008 //
///////////////////////////////////////////

//$menu = array();
/////////////////////////////////////////////////// MANAGERS
//array_push($menu,array('Projetos','Titulação do pesquisador','admin_titulacao.php')); 

///////////////////////////////////////////////////// redirecionamento
if ((isset($dd[1])) and (strlen($dd[1]) > 0))
	{
	$col=0;
	for ($k=0;$k <= count($menu);$k++)
		{ if ($dd[1]==CharE($menu[$k][1])) { header("Location: ".$menu[$k][2]); } }
	}
?>
<TABLE width="710" align="center" border="0">
<TR><TD colspan="4">
<FONT class="lt3">
</FONT><FORM method="post" action="<?=$http_form?>">
</TD></TR>
</TABLE>
<TABLE width="<?=($tab_max-40)?>" align="center" border="0">
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
				echo '<TR><TD class="lt3" width="1%"><NOBR><B><font color="#C0C0C0">'.$xseto.'&nbsp;</TD>';
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
