<?
$tabela = "brapci_edition";
$cp = array();
array_push($cp,array('$H8','id_ed','id_ed',False,True,''));
array_push($cp,array('$Q jnl_nome:jnl_codigo:SELECT * FROM brapci_journal where jnl_codigo = '.chr(39).strzero($jid,7).chr(39),'ed_journal_id','Revista/Journal',True,True,''));
array_push($cp,array('$H8','ed_codigo','Codigo',False,True,''));
array_push($cp,array('$S10','ed_ano','Ano',False,True,''));
array_push($cp,array('$S10','ed_vol','Volume',False,True,''));
array_push($cp,array('$S10','ed_nr','Numero',False,True,''));
array_push($cp,array('$S20','ed_periodo','Edi��o (jan./abr. 2009)',False,True,''));
array_push($cp,array('$[0-12]','ed_mes_inicial','M�s incial',False,True,''));
array_push($cp,array('$[0-12]','ed_mes_final','M�s final',False,True,''));
array_push($cp,array('$S100','ed_tematica_titulo','T�tulo tem�tico',False,True,''));
array_push($cp,array('$O 9:N�o definido &1:SIM&0:N�O','ed_biblioteca','Acervo da biblioteca',False,True,''));
array_push($cp,array('$H8','ed_obs','',False,True,''));
array_push($cp,array('$O -1:Em preparo&1:Dispon�vel&0:Inativo&','ed_ativo','<I>Status</I> atual',True,True,''));
array_push($cp,array('$H8','ed_editor','',False,True,''));
array_push($cp,array('$H8','ed_coeditor','',False,True,''));
array_push($cp,array('$H8','ed_qualis','',False,True,''));
array_push($cp,array('$T60:6','ed_notas','Notas sobre edi��o',False,True,''));
array_push($cp,array('$H8','ed_data_publicacao','',False,True,''));
array_push($cp,array('$U8','ed_data_cadastro','',False,True,''));
array_push($cp,array('$T60:3','ed_oai_issue','OAI Source',False,True,''));
array_push($cp,array('$O A:Preparo&B:Revis�o&D:Ready of print&E:Dispon�vel','ed_status','OAI Source',False,True,''));


/// Gerado pelo sistem "base.php" versao 1.0.5
?>