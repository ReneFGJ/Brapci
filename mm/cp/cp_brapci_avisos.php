
<?
$tabela = "brapci_avisos";
$cp = array();

array_push($cp,array('$H8','id_news','id_cidade',False,True,''));
array_push($cp,array('$HV','news_de',$user_id,True,True,''));
array_push($cp,array('$S100','news_assunto','Título',True,True,''));
array_push($cp,array('$T60:5','news_text','Texto',False,True,''));
array_push($cp,array('$O A:Aviso&M:Mensagem&D:Dicas','news_tipo','Tipo',False,True,''));
array_push($cp,array('$HV','news_data',date("Ymd"),False,True,''));
array_push($cp,array('$HV','news_hora',date("H:i"),False,True,''));
array_push($cp,array('$D8','news_data_ate','Mostrar até',True,True,''));
array_push($cp,array('$O 1:Sim&0:Não','news_ativo','Ativo',True,True,''));

/// Gerado pelo sistem "base.php" versao 1.0.5
?>