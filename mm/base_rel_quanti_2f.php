<?
$xsql = "select * from bp_section_tipo where st_ativo = 1 order by st_codigo ";
$xrlt = db_query($xsql);

while ($xline = db_read($xrlt))
	{
	$op .= '&';
	$op .= $xline['st_codigo'];
	$op .= ':';
	$op .= trim($xline['st_descricao']);
	}
// ar_section - ar_tipo

$tabela = "";
$cp = array();
array_push($cp,array('$H8','','',False,False,''));
array_push($cp,array('$H8','','',False,False,''));
array_push($cp,array('$S4','','Ano do cálculo',True,True,''));
array_push($cp,array('$Q jnl_nome:jnl_codigo:select * from brapci_journal where jnl_status = '.chr(39).'A'.chr(39).' and jnl_tipo='.chr(39).'J'.chr(39).' order by jnl_nome','Publicação',True,True,''));
$dd[1] = '1972';
$dd[2] = date("Y");
require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');

$tab_max = 450;
$http_redirect = '';
$titu[0] = 'Relatórios de Indicador '; 
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><CENTER>

<TABLE width="900" align="center"><TR><TD><?
editar();
?></TD></TR></TABLE>

<?
if ($saved > 0)
{
	$id = new indice_h;
	
	$sql = "select * from brapci_journal where jnl_status = 'A' order by jnl_nome ";
	$xrlt = db_query($sql);
	echo '<table width="800">';
	echo '<TR><TH colspan="4">Índice H - Por periódico</TH></TR>';
	while ($xline = db_read($xrlt))
		{
		echo '<TR>';
		echo '<TD>';
		echo $xline['jnl_nome'];
		echo '<TD>';
		echo $id->calcular_idh_journal($xline['jnl_codigo']);
	}
	echo '</table>';
	
	$sql = "
	select autor_nome, autor_codigo, count(*) as ids, sum(total) as total
	from 
	( SELECT 
	`ic_artigo` , `ic_autor`, count(*) as ids, sum(`ic_total`) as total
	FROM `indicador_citacao` 
	WHERE `ic_ano` = '2010'
	group by `ic_artigo` , `ic_autor`
	order by ids desc
	) as tabela
	inner join brapci_autor on ic_autor = autor_codigo
	group by autor_nome, autor_codigo
	order by ids desc
	limit 30
	";
	$xrlt = db_query($sql);
	echo '<table width="800">';
	echo '<TR><TH colspan="4">Índice H - Por autor</TH></TR>';
	while ($xline = db_read($xrlt))
		{
		echo '<TR>';
		echo '<TD>';
		echo $xline['autor_nome'];
		echo '<TD>';
		echo $id->calcular_idh($xline['autor_codigo']);
	}
	echo '</table>';

}
?>
