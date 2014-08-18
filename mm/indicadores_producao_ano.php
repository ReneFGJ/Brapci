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

if (strlen($dd[1]) == 0) { $dd[1] = date("Y"); }
$ano = $dd[1];

/* RN01 A data não pode ser superior ao data atual */ 
if ($ano < 1900) { $dd[2] = ''; $ano = ''; }

/* RN02	A data não pode ser inferior a de 01/01/1900 */
if ($ano > date("Y")) { $dd[2] = ''; $ano = ''; }

/* Parametros de entrada de dados */
$tabela = "";
$cp = array();
array_push($cp,array('$H8','','',False,False,''));
array_push($cp,array('$S4','','Ano para calculo',True,True,''));

require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');

$tab_max = 450;
$http_edit = 'indicadores_producao_ano.php';
$http_redirect = '';
$tit = $titu[0];
$metodologia = "<B>Metodologia:</B> São selecionados todos os autores que produziram trabalhos no ano selecionando, realizando uma contagem do número de trabalhos e as revistas que publicaram. O mesmo cálculo é utilizado para identificar a produção das revistas.";
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


/* Zera a tabela atual */
$sql = "delete from indicador_producao where ip_ano = '".$ano."' ";
$rlt = db_query($sql);


/* Cálculo de produção do autor */
/* RN03	Somente a modalidade de artigos de ser considerada */
$sql = "
select 
ae_author, count(*) as total, ed_ano, ar_journal_id
from brapci_edition
inner join brapci_article on ed_codigo = ar_edition
inner join brapci_article_author on ae_article = ar_codigo
inner join brapci_section on se_codigo = ar_section 
where ed_ano = '".$ano."' and ar_status <> 'X' and se_tipo = 'B' 
group by ae_author, ed_ano, ar_journal_id
order by ae_author
";
$rlt = db_query($sql);

/* Grava dados na base */
$sql = "";
$sql = "insert into indicador_producao (ip_journal,ip_autor,ip_artigos,ip_ano,ip_tipo) values ";
$tot = 0;
while ($line = db_read($rlt))
	{
	$sql .= chr(13).chr(10);
	if ($tot > 0) { $sql .= ', '; }
	$sql .= "('".$line['ar_journal_id']."',";
	$sql .=  "'".$line['ae_author']."',";
	$sql .=  "'".$line['total']."',";
	$sql .=  "'".$line['ed_ano']."',";
	$sql .=  "'A'";
	$sql .=  ")";
	$tot++;
	}
$rlt = db_query($sql);

/** PRODUÇÂO DE INDICADORES PARA OS PERIÓDICOS **/

/* Cálculo de produção do periódico */
/* RN03	Somente a modalidade de artigos de ser considerada */

$sql = "
select 
ar_journal_id, count(*) as total, ed_ano
from brapci_edition
inner join brapci_article on ed_codigo = ar_edition
inner join brapci_section on se_codigo = ar_section 
where ed_ano = '".$ano."' and ar_status <> 'X' and se_tipo = 'B'
group by ar_journal_id, ed_ano
";
$rlt = db_query($sql);

/* Grava dados na base */
$sql = "";
$sql = "insert into indicador_producao (ip_journal,ip_autor,ip_artigos,ip_ano,ip_tipo) values ";
$tot = 0;
while ($line = db_read($rlt))
	{
	$sql .= chr(13).chr(10);
	if ($tot > 0) { $sql .= ', '; }
	$sql .= "('".$line['ar_journal_id']."',";
	$sql .=  "'',";
	$sql .=  "'".$line['total']."',";
	$sql .=  "'".$line['ed_ano']."',";
	$sql .=  "'J'";
	$sql .=  ")";
	$tot++;
	}
$rlt = db_query($sql);

?>
<center><H2>Dados gerados com sucesso !</H2></center>
</DIV>
<? } 
require("foot.php"); ?>
