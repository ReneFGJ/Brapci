
<?
$tabela = "brapci_metodologias";
$cp = array();
array_push($cp,array('$H8','id_bmt','id_bmt',False,True,''));
array_push($cp,array('$H8','bmt_codigo','',False,True,''));
array_push($cp,array('$S100','bmt_descricao','Nome do m�todo/t�cnica',True,True,''));
array_push($cp,array('$Q bmtf_descricao:bmtf_codigo:select * from brapci_metodologias_tp','bmt_tipo','Tipo',True,True,''));
array_push($cp,array('$T60:10','bmt_content','Descri��o do M/T',False,True,''));
//array_push($cp,array('$O M:Metodologia&T:T�cnica de pesquisa&Z:Refer�ncia','bmt_tipo','Tipo',True,True,''));
array_push($cp,array('$O 1:SIM&0:N�O','bmt_ativo','Nome para cita��o',True,True,''));
array_push($cp,array('$[1-99]','bmt_ordem','Ordem',True,True,''));
/// Gerado pelo sistem "base.php" versao 1.0.5
?>