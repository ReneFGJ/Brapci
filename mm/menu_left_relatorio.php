<?
$tit[0] = 'Relatório';
$menu = array();
array_push($menu,array('Periódicos','brapci_rel_periodicos.php'));
array_push($menu,array('Autores(DiR-IS)','diris.php'));
if ($user_nivel == 1) { array_push($menu,array('Log de acesso','brapci_rel_log.php')); }
//array_push($menu,array('Artigos','brapci_rel_artigos.php'));
//array_push($menu,array('Acervo','brapci_rel_acervo.php'));
array_push($menu,array('Pendências','brapci_rel_pendencia.php'));
array_push($menu,array('Gestão','gestao.php'));
array_push($menu,array('Gestão de uso','brapci_rel_gestao.php'));
array_push($menu,array('Relatórios Quantitativos','brapci_rel_quanti.php'));
require("menu_mostrar.php");
?>
