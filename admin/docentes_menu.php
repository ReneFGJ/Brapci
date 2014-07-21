<?php
require("cab.php");

/*
 * $breadcrumbs
 */
$breadcrumbs = array();
array_push($breadcrumbs,array(http.'admin/index.php',msg('principal')));
array_push($breadcrumbs,array(http.'admin/index.php',msg('menu')));
echo '<div id="breadcrumbs">'.breadcrumbs().'</div>';


$file = '../messages/msg_index.php';
if (file_exists($file)) { require($file); }

require($include."sisdoc_menus.php");
$estilo_admin = 'style="width: 200; height: 30; background-color: #EEE8AA; font: 13 Verdana, Geneva, Arial, Helvetica, sans-serif;"';

$menu = array();
/////////////////////////////////////////////////// MANAGERS
array_push($menu,array(msg('docentes'),'Relação de docentes com e-mail','docentes_email.php'));

array_push($menu,array(msg('docentes'),'Higienização dos nome dos cursos (professores)','docentes_higienizacao_curso.php'));

array_push($menu,array(msg('docentes'),'Cadastro de docentes','docentes.php'));
array_push($menu,array(msg('docentes'),'__professores sem código de cracha','docentes_sem_codigo.php'));

array_push($menu,array(msg('docentes'),'Importa lista do DRH','docentes_importacao.php'));
array_push($menu,array(msg('docentes'),'Processar lista importada','docentes_importacao_simples.php'));    

?>

<TABLE width="710" align="center" border="0">
<TR><TD colspan="4">
<FONT class="lt3">
</FONT><FORM method="post" action="index.php">
</TD></TR>
</TABLE>
<TABLE width="710" align="center" border="0">
<TR>
<?php
	$tela = menus($menu,"3");

require("../foot.php");	
?>