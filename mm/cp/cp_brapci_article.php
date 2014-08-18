<?
$tabela = "brapci_article";
$cp = array();
array_push($cp,array('$H8','id_ar','id_ar',False,True,''));

if (strlen($dd[0]) == 0) 
	{ array_push($cp,array('$HV','ar_position','-1',False,True,'')); } else
	{ array_push($cp,array('$H1','ar_position','',False,True,'')); } 
if (strlen($dd[0]) == 0) 
	{ array_push($cp,array('$HV','ar_status','A',False,True,'')); } else
	{ array_push($cp,array('$H1','ar_status','',False,True,'')); if (strlen($dd[2]) == 0) { $dd[2] = 'A'; }} 
echo '>>>'.$jid;
array_push($cp,array('$A1','','Ediзгo',False,True,''));
array_push($cp,array('$Q jnl_nome:jnl_codigo:SELECT * FROM brapci_journal where jnl_codigo = '.chr(39).strzero($jid,7).chr(39),'ar_journal_id','Revista/Journal',True,True,''));
array_push($cp,array('$Q ed_periodo:ed_codigo:SELECT * FROM brapci_edition where ed_journal_id = '.chr(39).strzero($jid,7).chr(39).' order by ed_ano desc, ed_vol desc, ed_nr desc','ar_edition','Ediзгo',True,True,''));
//array_push($cp,array('$O COMUN:Comunicaзгo&ARTIG:Artigo cientнfico&RELAT:Relato de caso&PONTO:Ponto de vista&EDITO:Editorial','ar_section','Seзгo',True,True,''));
array_push($cp,array('$Q se_descricao:se_cod:select * from brapci_section where se_ativo = 1 order by se_descricao','ar_section','Seзгo',True,True,''));

array_push($cp,array('$A1','','Tнtulo principal',False,True,''));
array_push($cp,array('$T70:3','ar_titulo_1','Tнtulo',False,True,''));
array_push($cp,array('$HV','ar_titulo_1_asc',UpperCaseSql($dd[9]),False,True,''));
array_push($cp,array('$T70:12','ar_resumo_1','Resumo',False,True,''));
array_push($cp,array('$Q ido_descricao:ido_codigo:select * from ajax_idioma order by ido_ordem, ido_descricao','ar_idioma_1','Idioma',False,True,''));

array_push($cp,array('$A1','','Tнtulo alternativo',False,True,''));
array_push($cp,array('$T70:3','ar_titulo_2','Tнtulo (alt)',False,True,''));
array_push($cp,array('$T70:12','ar_resumo_2','Resumo',False,True,''));
array_push($cp,array('$Q ido_descricao:ido_codigo:select * from ajax_idioma order by ido_ordem, ido_descricao','ar_idioma_2','Idioma',False,True,''));

array_push($cp,array('$H8','ar_doi','D.O.I.',False,True,''));
// area do conhecimento

array_push($cp,array('$H8','ar_data_envio','Data envio',False,True,''));
array_push($cp,array('$H8','ar_data_aceite','Data aceite',False,True,''));
array_push($cp,array('$H8','ar_data_aprovado','Data aprovado',False,True,''));

array_push($cp,array('$H8','ar_brapci_id','',False,True,''));
array_push($cp,array('$H8','ar_resumo_3','',False,True,''));
array_push($cp,array('$H8','ar_titulo_3','',False,True,''));

array_push($cp,array('$A1','','Pбginaзгo',False,True,''));
array_push($cp,array('$S8','ar_pg_inicial','pбg inicial',False,True,''));
array_push($cp,array('$S8','ar_pg_final','pбg final',False,True,''));

array_push($cp,array('$T50:5','ar_notas','notas',False,True,''));

array_push($cp,array('$H8','ar_codigo','',False,True,''));

array_push($cp,array('$U8','at_obs_data','',False,True,''));
array_push($cp,array('$HV','at_obs_log','0',False,True,''));
array_push($cp,array('$H8','at_obs','',False,True,''));

/// Gerado pelo sistem "base.php" versao 1.0.5
?>