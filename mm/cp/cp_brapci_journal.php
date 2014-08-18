<?
$tabela = "brapci_journal";
$cp = array();
array_push($cp,array('$H4','id_jnl','id_jnl',False,False,''));
/////////////////////
array_push($cp,array('$A','','Dados da publicчуo',False,True,''));
array_push($cp,array('$H8','jnl_codigo','codigo',False,True,''));
array_push($cp,array('$S100','jnl_nome','Nome do periѓdico (completo)',True,True,''));
array_push($cp,array('$S40','jnl_nome_abrev','Nome de citaчуo (com local)',True,True,''));

//array_push($cp,array('$Q t_descricao:t_codigo:select * from brapci_tipo order by t_descricao','jnl_tipo','Tipo Publicaчуo',False,True,''));
array_push($cp,array('$Q jtp_descricao:jtp_codigo:select * from brapci_journal_tipo','jnl_tipo','Tipo',False,True,''));

array_push($cp,array('$S20','jnl_issn_impresso','ISSN',False,True,''));
array_push($cp,array('$S20','jnl_issn_eletronico','eISSN',False,True,''));
array_push($cp,array('$S120','jnl_url','URL (http)',False,True,''));
array_push($cp,array('$Q cidade_nome:cidade_codigo:select * from ajax_cidade order by cidade_nome','jnl_cidade','Local de Publicaчуo',True,True,''));
array_push($cp,array('$S120','jnl_name_temp','Nome no pro-cite',False,True,''));
array_push($cp,array('$T60:6','jnl_obs','Notas',False,True,''));

array_push($cp,array('$A','','Caracteristicas',False,True,''));
array_push($cp,array('$Q peri_nome:peri_codigo:select * from brapci_periodicidade order by peri_nome','jnl_periodicidade','Periodicidade',True,True,''));
array_push($cp,array('$Q dp_descricao:dp_codigo:select * from brapci_disponibilidade order by dp_descricao','jnl_disponibilidade','Disponibilidade',True,True,''));
array_push($cp,array('$O 1:SIM&0:NУO&2:Indefinido','jnl_vinc_vigente','Vigente',True,True,''));
//array_push($cp,array('$Q jtp_descricao:jtp_codigo:select * from brapci_journal_tipo','','Tipo',False,True,''));
array_push($cp,array('$H1','','eISSN',False,True,''));
array_push($cp,array('$I8','jnl_ano_inicio','Vigъncia (inicio-ano)',False,True,''));
array_push($cp,array('$I8','jnl_ano_final','Vigъncia (fim-ano)',False,True,''));
array_push($cp,array('$I8','jnl_edicoes','Total de Ediчѕes',False,False,''));
array_push($cp,array('$I8','jnl_artigos','Total de Artigos',False,False,''));
array_push($cp,array('$O A:Ativo&B:Inativo&X:Excluir','jnl_status','Journal',False,True,''));
array_push($cp,array('$T70:6','jnl_content','Descriчуo',False,True,''));

array_push($cp,array('$A','','OAI-PMH',False,True,''));
array_push($cp,array('$S120','jnl_url_oai','URL (OAI-PMH)',False,True,''));
array_push($cp,array('$S20','jnl_token','OAI Token',False,True,''));
array_push($cp,array('$D8','jnl_last_harvesting','кltimo harvesting',False,True,''));
array_push($cp,array('$S20','jnl_meta_prefix','MetaDataPrefix',False,True,''));
array_push($cp,array('$HV','jnl_artigos_indexados','0',False,True,''));
array_push($cp,array('$HV','jnl_update',date("Ymd"),False,True,''));
array_push($cp,array('$HV','jnl_atual','0',False,True,''));



// Gerado pelo sistem "base.php" versao 1.0.2
?>