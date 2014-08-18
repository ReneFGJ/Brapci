
<?
$tabela = "mar_journal";
$cp = array();
array_push($cp,array('$H8','id_mj','id_mj',False,True,''));
array_push($cp,array('$S100','mj_nome','Nome',False,True,''));
array_push($cp,array('$S100','mj_abrev','Nome (Abrev.)',False,True,''));
array_push($cp,array('$H7','mj_codigo','Codigo',False,True,''));
array_push($cp,array('$O 1:Sim&0:Não','mj_ativo','Ativo',False,True,''));
array_push($cp,array('$Q mt_descricao:mt_codigo:select * from mar_tipo where mt_ativo=1 order by mt_descricao','mj_tipo','Tipo',False,True,''));
array_push($cp,array('$S7','mj_use','Use',False,True,''));
array_push($cp,array('$O N:Não&S:Sim','m_processar','Processar',False,True,''));

/// Gerado pelo sistem "base.php" versao 1.0.5
?>