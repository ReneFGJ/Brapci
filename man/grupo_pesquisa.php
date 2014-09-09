<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/finan/index.php','Financeiro'));

require("cab_cip.php");
require($include."sisdoc_menus.php");
$estilo_admin = 'style="width: 200; height: 30; background-color: #EEE8AA; font: 13 Verdana, Geneva, Arial, Helvetica, sans-serif;"';
$menu = array();
/////////////////////////////////////////////////// MANAGERS
array_push($menu,array('Grupos e Linhas de pesquisa','Relação dos Grupos de pesquisa','grupo_de_pesquisa_lista.php'));
array_push($menu,array('Grupos e Linhas de pesquisa','Grupos de pesquisa (editar)','grupo_de_pesquisa.php')); 
array_push($menu,array('Grupos e Linhas de pesquisa','Linha de pesquisa','linha_de_pesquisa.php'));

array_push($menu,array('Pós-Graduação','Programas de Pós-Graduação','programa_pos.php'));
array_push($menu,array('Pós-Graduação','Linhas de pesquisa dos programas','programa_pos_linhas.php')); 

array_push($menu,array('Pesquisadores','Docentes','docentes.php')); 

//array_push($menu,array('Manutenção','Criar Tabelas','create_table.php')); 
///////////////////////////////////////////////////// redirecionamento
if ((isset($dd[1])) and (strlen($dd[1]) > 0))
	{
	$col=0;
	for ($k=0;$k <= count($menu);$k++)
		{
		 if ($dd[1]==CharE($menu[$k][1])) {	header("Location: ".$menu[$k][2]); } 
		}
	}
?>

<TABLE width="710" align="center" border="0">
<TR><TD colspan="4">
<FONT class="lt3">
</FONT><FORM method="post" action="index.php">
</TD></TR>
</TABLE>
<TABLE width="710" align="center" border="0">
<TR>
<?
	$tela = menus($menu,"3");
?>
<? require("../foot.php");	?>