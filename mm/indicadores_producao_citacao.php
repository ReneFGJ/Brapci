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
$http_edit = 'indicadores_producao_citacao.php';
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
$sql = "delete from indicador_citacao where ic_ano = '".$ano."' ";
$rlt = db_query($sql);

$sql = "
SELECT count(*) as total, ed_ano, ba2.ar_codigo as art1,ba1.ar_codigo as art2, 
ba1.ar_journal_id as journal , ae_author, m_bdoi
FROM `mar_works`
inner join brapci_article as ba1 on m_bdoi = ba1.ar_bdoi
inner join brapci_article as ba2 on m_work = ba2.ar_codigo
inner join brapci_article_author on ba1.ar_codigo = ae_article
inner join brapci_edition on ed_codigo = ba2.ar_edition
WHERE `m_bdoi` <> '' and m_status = 'F'
and ed_ano = '".$ano."'
group by ba1.ar_codigo, ba1.ar_journal_id, ae_author
order by total desc
";
$rlt = db_query($sql);
/* ed_ano 	ar_codigo 	ar_codigo 	ar_journal_id 	ae_author */
$sql = "insert into indicador_citacao ";
$sql .= "(ic_ano, ic_ano_citado, ic_artigo, ic_citado, ic_journal, ic_autor, ic_total) ";
$sql .= " values ";
$tot = 0;
while ($line = db_read($rlt))
	{
	if ($tot > 0) { $sql .= ", "; }
	$sql .= "(";
	$sql .= "'".$line['ed_ano']."',";
	$sql .= "'".substr($line['m_bdoi'],0,4)."',";
	$sql .= "'".$line['art1']."',";
	$sql .= "'".$line['art2']."',";
	$sql .= "'".$line['journal']."',";
	$sql .= "'".$line['ae_author']."',";
	$sql .= "'".$line['total']."'";
	$sql .= ")";
	$tot++;
	}
if ($tot > 0)
	{
	$rlt = db_query($sql);
	}
	
	
/* PERIODICO */
$sql = "
SELECT count(*) as total, ed_ano,
ba1.ar_journal_id as journal ,left(m_bdoi,4) as ano
FROM `mar_works`
inner join brapci_article as ba1 on m_bdoi = ba1.ar_bdoi
inner join brapci_article as ba2 on m_work = ba2.ar_codigo
inner join brapci_edition on ed_codigo = ba2.ar_edition
WHERE `m_bdoi` <> '' and m_status = 'F'
and ed_ano = '".$ano."'
group by ba1.ar_journal_id, ed_ano, ano
order by total desc
";
$rlt = db_query($sql);
/* ed_ano 	ar_codigo 	ar_codigo 	ar_journal_id 	ae_author */
$sql = "insert into indicador_citacao ";
$sql .= "(ic_ano, ic_ano_citado, ic_artigo, ic_citado, ic_journal, ic_autor, ic_total) ";
$sql .= " values ";
$tot = 0;
while ($line = db_read($rlt))
	{
	if ($tot > 0) { $sql .= ", "; }
	$sql .= "(";
	$sql .= "'".$line['ed_ano']."',";
	$sql .= "'".substr($line['ano'],0,4)."',";
	$sql .= "'".$line['art1']."',";
	$sql .= "'".$line['art2']."',";
	$sql .= "'".$line['journal']."',";
	$sql .= "'".$line['ae_author']."',";
	$sql .= "'".$line['total']."'";
	$sql .= ")";
	$tot++;
	}
if ($tot > 0)
	{
	$rlt = db_query($sql);
	}
?>
<center><H2>Dados gerados com sucesso !</H2></center>
</DIV>
<? } 
require("foot.php"); ?>
