<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
require($include."sisdoc_menus.php");
require("updatex_article_asc.php");


$titu[0] = 'Relatórios Quantitativos da Base BRAPCI'; 
?><div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR>
<?
$path = "brapci_rel_quanti.php";

$menu = array();
/////////////////////////////////////////////////// MANAGERS 
array_push($menu,array('Importar','Importar do ProCite 5','brapci_procite_import.php'));
array_push($menu,array('Importar','Importar do EXCEL-XML','brapci_xml_import.php'));
array_push($menu,array('Importar','OAI-PHM',''));
array_push($menu,array('Importar','__Harvesting','oai_harvesting.php?dd1=1'));
array_push($menu,array('Importar','__Coletar','oai_harvesting_get.php'));
array_push($menu,array('Importar','__Coletar por "ListRecords"','oai_harvesting_get_listrecords.php'));
array_push($menu,array('Importar','__Processar','oai_harvesting_proc.php'));
array_push($menu,array('Importar','__Status das coletas','rel_oai_status.php')); 

array_push($menu,array('Links Externos','Coletar link externos','link_coletar.php')); 

array_push($menu,array('Exportação','Exportar dados para módulo pesquisador','export.php'));
array_push($menu,array('Exportação','Exportar autores (TXT)','export_tipo_1_txt.php'));
array_push($menu,array('Exportação','Exportar palavras chave','export_keyword.php'));
array_push($menu,array('Exportação','Exportar dados sobre a base','export_dados.php'));
array_push($menu,array('Exportação','Exportar idicadores sobre produção I','export_dados_i.php'));
array_push($menu,array('Exportação','Exportar idicadores sobre produção II','export_dados_ii.php'));

array_push($menu,array('Exportação Núvem de Tags','Núvem de Tags - Mais consultados (Módulo pesquisador)','export_coluds.php'));
array_push($menu,array('Exportação Núvem de Tags','Núvem de Tags - Maior incidênica das KeysWords (Módulo pesquisador)','export_coluds_keywords.php'));

array_push($menu,array('Brapci-DOI (bDOI)','Brapci-Doi (buscar)','bdoi.php'));
array_push($menu,array('Brapci-DOI (bDOI)','Brapci-Doi (repetidos)','bdoi_repetidos.php'));
array_push($menu,array('Brapci-DOI (bDOI)','Brapci-Doi (numerar)','bdoi_numerar.php'));

array_push($menu,array('Ontologia','Ontologias','concept.php'));

array_push($menu,array('Marcação de Referências','Processar fase I - Ano de Publicação','mar_marcacao_i.php'));
array_push($menu,array('Marcação de Referências','Processar fase II - Autores','mar_marcacao_ii.php'));
array_push($menu,array('Marcação de Referências','Processar fase III - Tipo de Publicação','mar_marcacao_iii.php'));
array_push($menu,array('Marcação de Referências','Processar fase IV - Identificação de periódicos','mar_marcacao_iv.php'));

array_push($menu,array('Marcação de Referências','Processar fase H - Artigo não localizado','mar_marcacao_H.php'));
array_push($menu,array('Marcação de Referências','Processar fase R - Tipo de Publicação não identificado','mar_marcacao_R.php'));
array_push($menu,array('Marcação de Referências','Processar fase Z - Erros de referência','mar_marcacao_z.php'));

array_push($menu,array('Marcação de Referências','Referências',''));
array_push($menu,array('Marcação de Referências','__Lista de referências','mar_marcacao_ref_ii.php'));

array_push($menu,array('Marcação de Referências','Lista de publicações','brapci_mar_journal.php'));
array_push($menu,array('Marcação de Referências','Referências do Sistema','mar_resumo.php'));
array_push($menu,array('Marcação de Referências','Referências do Sistema (por tipo/ano)','mar_marcacao_tipo_ano.php'));


array_push($menu,array('Produção de indicadores','Indicador de produção do ano','indicadores_producao_ano.php'));
array_push($menu,array('Produção de indicadores','Indicador de produção de citação','indicadores_producao_citacao.php'));

array_push($menu,array('Fontes','Agrupar fontes','cited_agrupar_fonte.php'));
array_push($menu,array('Fontes','Citação / Ano','cited_year.php'));


?>
<BR>
<?
	$tela = menus($menu,"3");
?>


</DIV>
