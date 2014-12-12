<?php
require("cab.php");
$estilo_admin = 'style="width: 230; height: 40; background-color: #EEE8AA; font: 13 Verdana, Geneva, Arial, Helvetica, sans-serif;"';
$path = "cadastro.php";
require($include."sisdoc_menus.php");
$menu = array();

	array_push($menu,array('Discentes','Discentes','')); 
	array_push($menu,array('Discentes','__limpar turno dos cursos discentes','dd_limpar_curso.php'));
	array_push($menu,array('Discentes','__processar cursos discentes','dd_curso_processar.php'));
	array_push($menu,array('Escolas','__escolas_cursos','escolas_cursos.php'));
	array_push($menu,array('Professores','__Importar lista DPRH','docentes_importacao.php')); 
///////////////////////////////////////////////////// redirecionamento
$tab_max = "98%";
?>
<TABLE width="98%" align="center" border="0">
<TR>
<?
menus($menu,'3');
?>
</TABLE>
<? require("../foot.php");	?>