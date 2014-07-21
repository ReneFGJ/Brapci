<?php
require("cab.php");

require("_class/_class_ic.php");
$ic = new ic;

echo 'MENU';

$menu = array();
array_push($menu,array('sobre a base','Sobre a base','menu.php?dd1=0'));
array_push($menu,array('sobre a base','<I>Corpus</I>','menu.php?dd1=1'));
array_push($menu,array('sobre a base','Citações','menu.php?dd1=2'));
array_push($menu,array('sobre a base','Palavras-chave','menu.php?dd1=3'));
array_push($menu,array('sobre a base','Autores','menu.php?dd1=4'));

echo '<table width="100%" class="tabela00" cellpadding=0 cellspacing=0 >';
echo '<TR valign="top">
		<TD width="120" class="lt1">';

echo '<TD ROWSPAN=40 class="menu_nav_content" >';
$pos = round('0'.$dd[1]);
/*

*/
switch($pos)
	{
	case '0':
		$ms = $ic->ic('pg_about_project');
		echo '<div class="text_info">';
		echo '<h3>'.utf8_encode($ms['nw_titulo']).'</h3>';
		echo '<BR><BR>';
		echo utf8_encode($ms['nw_descricao']);
		echo '</div>';
		break;
	case '3':
		require("keywords.php");
		break;
	case '4':
		require("authors.php");
		break;		
	}
echo '</td>';

if (strlen($dd[1])==0) { $dd[1] = 0; }

$sel = 'menu_nav_item_sel';
$cla = 'menu_nav_item';
for ($r=0;$r < count($menu);$r++)
	{
		$class = $cla;
		if ($r==$pos) { $class = $sel; }
		echo '<TR valign="top">
			<TD width="120" height="15" 
					class="'.$class.'" >
			<A HREF="'.$menu[$r][2].'" class="menu_nav_href">
			'.utf8_encode($menu[$r][1]).'
			</A>
			</TD>
			';
	}

echo '<TR valign="top">
		<TD width="120" 
		style="border-right: 1px solid #404040; height: 400px;"
		>&nbsp;</td>
		';

echo '</table>';


require("foot.php");
?>

