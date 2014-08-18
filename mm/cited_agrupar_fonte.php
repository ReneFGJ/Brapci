<?
 /**
  * Calcular o indicador de produção do autor
  * @author Rene Faustino Gabriel Junior  (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2011 - sisDOC.com.br
  * @access public
  * @version v0.11.38
  * @package Classe
  * @subpackage UC0003 - Calcular o indicador de produção do autor
 */

////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('produção de indicadores','indicadores_producao_ano.php'));
////////////////////////////
$titu[0] = 'Cálculo de indicadores de produção por ano'; 

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");

/* Regras de negócio */

/* Parametros de entrada de dados */
$tabela = "";
$cp = array();
array_push($cp,array('$H8','','',False,False,''));
array_push($cp,array('$S7','','Código da Fonte (de)',True,True,''));
array_push($cp,array('$S7','','Código da Fonte (para)',True,True,''));

require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');

$tab_max = 450;
$http_edit = 'cited_agrupar_fonte.php';
$http_redirect = '';
$tit = $titu[0];
$metodologia = "<B>Metodologia:</B> .";
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><CENTER>
<BR><BR><font class="Lt3"><?=$tit;?></font>
<TABLE width="<?=$tab_max?>" align="center">
<TR><TD><?
editar();
?></TD></TR>
<TR><TD class="lt1"><?=$metodologia;?></TD></TR>
</TABLE>

<?
/* Se o formulário foi validado */
if ($saved > 0) {
/** PRODUÇÂO DE INDICADORES PARA OS AUTORES **/
	require("../_class/_class_cited.php");
	$ct = new cited;
	$ct->change_cited($dd[1],$dd[2]);
	echo 'Modificado';
?>
<center><H2>Dados gerados com sucesso !</H2></center>
</DIV>
<? } 
require("foot.php"); ?>
