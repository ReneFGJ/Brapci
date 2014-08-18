
<?
$tabela = "ajax_estado";
$cp = array();
array_push($cp,array('$H8','id_estado','id_estado',False,True,''));
array_push($cp,array('$S100','estado_nome','Nome',False,True,''));
array_push($cp,array('$S2','estado_uf','Sigla (UF)',False,True,''));
array_push($cp,array('$H7','estado_codigo','Codigo',False,True,''));
array_push($cp,array('$O pt_BR:Portugues','estado_idioma','Idioma',False,True,''));
array_push($cp,array('$S7','estado_use','Use',False,True,''));
array_push($cp,array('$Q pais_nome:pais_codigo:select * from ajax_pais order by (pais_nome)','estado_pais','Pais',True,True));
array_push($cp,array('$O 1:Sim&0:Não','estado_ativo','Ativo',False,True,''));

/// Gerado pelo sistem "base.php" versao 1.0.5
?>