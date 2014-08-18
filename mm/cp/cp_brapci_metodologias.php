
<?
$tabela = "brapci_metodologias";
$cp = array();
array_push($cp,array('$H8','id_bmt','id_bmt',False,True,''));
array_push($cp,array('$H8','bmt_codigo','',False,True,''));
array_push($cp,array('$S100','bmt_descricao','Nome do método/técnica',True,True,''));
array_push($cp,array('$Q bmtf_descricao:bmtf_codigo:select * from brapci_metodologias_tp','bmt_tipo','Tipo',True,True,''));
array_push($cp,array('$T60:10','bmt_content','Descrição do M/T',False,True,''));
//array_push($cp,array('$O M:Metodologia&T:Técnica de pesquisa&Z:Referência','bmt_tipo','Tipo',True,True,''));
array_push($cp,array('$O 1:SIM&0:NÃO','bmt_ativo','Nome para citação',True,True,''));
array_push($cp,array('$[1-99]','bmt_ordem','Ordem',True,True,''));
/// Gerado pelo sistem "base.php" versao 1.0.5
?>