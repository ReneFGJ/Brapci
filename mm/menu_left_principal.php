<?
$tit[0] = 'Menu Principal';
$menu = array();
array_push($menu,array('Usuário','brapci_brapci_usuario.php'));
if ($user_nivel == 1) { array_push($menu,array('Perfil','brapci_brapci_usuario_perfil.php')); }
require("menu_mostrar.php");
?>
