
<?
$tabela = "brapci_article";
$cp = array();
array_push($cp,array('$H8','id_ar','id_ar',False,True,''));

array_push($cp,array('$A1','','Edi��o',False,True,''));
array_push($cp,array('$Q jnl_nome:jnl_codigo:SELECT * FROM brapci_journal where jnl_codigo = '.chr(39).strzero($jid,7).chr(39),'ar_journal_id','Revista/Journal',True,True,''));
array_push($cp,array('$Q ed_periodo:ed_codigo:SELECT * FROM brapci_edition where ed_journal_id = '.chr(39).strzero($jid,7).chr(39).' order by ed_ano desc, ed_vol desc, ed_nr desc','ar_edition','Edi��o',True,True,''));
array_push($cp,array('$O COMUN:Comunica��o&ARTIG:Artigo cient�fico&RELAT:Relato de caso&PONTO:Ponto de vista','ar_section','Se��o',True,True,''));

array_push($cp,array('$A1','','T�tulo principal',False,True,''));
array_push($cp,array('$T70:3','ar_titulo_1','T�tulo',False,True,''));
array_push($cp,array('$T70:12','ar_resumo_1','Resumo',False,True,''));
array_push($cp,array('$Q ido_descricao:ido_codigo:select * from ajax_idioma order by ido_ordem, ido_descricao','ar_idioma_1','Idioma',False,True,''));

array_push($cp,array('$A1','','Segundo t�tulo',False,True,''));
array_push($cp,array('$T70:3','ar_titulo_2','T�tulo (alt)',False,True,''));
array_push($cp,array('$T70:12','ar_resumo_2','Resumo',False,True,''));
array_push($cp,array('$Q ido_descricao:ido_codigo:select * from ajax_idioma order by ido_ordem, ido_descricao','ar_idioma_2','Idioma',False,True,''));

array_push($cp,array('$A1','','Digital Identifica��o',False,True,''));
array_push($cp,array('$S15','ar_doi','D.O.I.',False,True,''));
// area do conhecimento

array_push($cp,array('$A1','','Data da publica��o',False,True,''));
array_push($cp,array('$D8','ar_data_envio','Data envio',False,True,''));
array_push($cp,array('$D8','ar_data_aceite','Data aceite',False,True,''));
array_push($cp,array('$D8','ar_data_aprovado','Data aprovado',False,True,''));

array_push($cp,array('$H8','ar_brapci_id','',False,True,''));
array_push($cp,array('$H8','ar_resumo_3','',False,True,''));
array_push($cp,array('$H8','ar_titulo_3','',False,True,''));

array_push($cp,array('$A1','','P�gina��o',False,True,''));
array_push($cp,array('$S8','ar_pg_inicial','p�g inicial',False,True,''));
array_push($cp,array('$S8','ar_pg_final','p�g final',False,True,''));
array_push($cp,array('$HV','ar_position','-1',False,True,''));


/// Gerado pelo sistem "base.php" versao 1.0.5
?>