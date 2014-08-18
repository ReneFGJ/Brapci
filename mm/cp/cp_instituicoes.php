
<?
$tabela = "instituicoes";
$cp = array();

array_push($cp,array('$H8','id_inst','id_cidade',False,True,''));
array_push($cp,array('$H8','inst_codigo','Codigo',False,True,''));
array_push($cp,array('$S100','inst_nome','Nome',False,True,''));
array_push($cp,array('$H1','inst_nome_asc','',False,True,''));
array_push($cp,array('$S10','inst_abreviatura','Sigla',False,True,''));
array_push($cp,array('$T60:5','inst_endereco','Endereço',False,True,''));
array_push($cp,array('$Q cidade_nome:cidade_codigo:select * from ajax_cidade order by cidade_nome','inst_cidade','Cidade',True,True));
array_push($cp,array('$S100','inst_site','Site',False,True,''));
array_push($cp,array('$[1-9]','inst_ordem','Ordem',False,True,''));
$dd[3] = UpperCaseSql($dd[2]);
/// Gerado pelo sistem "base.php" versao 1.0.5
?>