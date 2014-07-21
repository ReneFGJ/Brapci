<?
require ("cab.php");
require ('../_class/_class_publications.php');
$pb = new publications;
if (strlen($dd[0]) == 0) { $dd[0] = 'J';
}
echo '<div class="nav">';

$type = trim($dd[0]);
echo '<table width="98%" align="center">';
echo '<TR valign="top"><TD width="50%">';
echo '<h1>' . $pb -> type($dd[0]) . '</h1>';
echo $pb -> list_row($type);

$tab_max = '100%';

require($include.'sisdoc_menus.php');
echo '</TD><TD width="25%">';
echo '<h1>Menu principal</h1>';
$menu = array();
array_push($menu,array('Publicações','<NOBR>Revistas Científicas e Profissionais</NOBR>','publications.php?dd0=J'));
array_push($menu,array('Publicações','<NOBR>Teses e Dissertações</NOBR>','publications.php?dd0=B'));
array_push($menu,array('Publicações','<NOBR>Livros didátivos</NOBR>','publications.php?dd0=D'));
array_push($menu,array('Publicações','<NOBR>Livros</NOBR>','publications.php?dd0=L'));
array_push($menu,array('Publicações','<NOBR>Capítulo de livros</NOBR>','publications.php?dd0=C'));
array_push($menu,array('Publicações','<NOBR>Eventos científicos</NOBR>','publications.php?dd0=E'));
array_push($menu,array('Publicações','<NOBR>TCC</NOBR>','publications.php?dd0=T'));
echo menus($menu,3);

echo '</TD><TD width="25%">';
echo '<h1>Cadastro</h1>';
$menu = array();
array_push($menu,array('Publicações','<NOBR>Cadastro de publicações</NOBR>','publications_row.php'));
echo menus($menu,3);
echo '</TD></tr>';
echo '</table>';
echo '</div>';

require ("../foot.php");
?>
