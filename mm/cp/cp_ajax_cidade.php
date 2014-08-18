
<?
$tabela = "ajax_cidade";
$cp = array();
$ajax_sql = "select * from ajax_estado as estado order by estado_nome";

array_push($cp,array('$H8','id_cidade','id_cidade',False,True,''));
array_push($cp,array('$S100','cidade_nome','Nome',False,True,''));
array_push($cp,array('$S3','cidade_sigla','Sigla',False,True,''));
array_push($cp,array('$H7','cidade_codigo','Codigo',False,True,''));
array_push($cp,array('$O pt_BR:Portugues','cidade_idioma','Idioma',False,True,''));
array_push($cp,array('$S7','cidade_use','Use',False,True,''));
array_push($cp,array('$Q estado_nome:estado_codigo:'.$ajax_sql,'cidade_estado','Pais',True,True));
array_push($cp,array('$O 1:Sim&0:Não','cidade_ativo','Ativo',False,True,''));

/// Gerado pelo sistem "base.php" versao 1.0.5
?>