<?
$tit[0] = 'Relat�rio';
$menu = array();
array_push($menu,array('Peri�dicos','brapci_rel_periodicos.php'));
array_push($menu,array('Autores(DiR-IS)','diris.php'));
if ($user_nivel == 1) { array_push($menu,array('Log de acesso','brapci_rel_log.php')); }
//array_push($menu,array('Artigos','brapci_rel_artigos.php'));
//array_push($menu,array('Acervo','brapci_rel_acervo.php'));
array_push($menu,array('Pend�ncias','brapci_rel_pendencia.php'));
array_push($menu,array('Gest�o','gestao.php'));
array_push($menu,array('Gest�o de uso','brapci_rel_gestao.php'));
array_push($menu,array('Relat�rios Quantitativos','brapci_rel_quanti.php'));
require("menu_mostrar.php");
?>
