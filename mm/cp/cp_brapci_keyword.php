
<?
$tabela = "brapci_keyword";
$cp = array();
array_push($cp,array('$H8','id_kw','id_kw',False,True,''));
array_push($cp,array('$S100','kw_word','Palavra',False,True,''));
array_push($cp,array('$HV','kw_word_asc',UpperCaseSql($dd[1]),False,True,''));
array_push($cp,array('$S10','kw_codigo','C�digo',False,False,''));
array_push($cp,array('$S10','kw_use','Remissiva',False,True,''));
array_push($cp,array('$O N:NC&T:Termo Indexador&D:Data&G:Geogr�fico&A:Autoridade&W:N�o usado','kw_tipo','Tipo',False,True,''));
array_push($cp,array('$Q ido_descricao:ido_codigo:select * from ajax_idioma order by ido_ordem, ido_descricao','kw_idioma','Idioma',False,True,''));
array_push($cp,array('$O 0:Vis�vel&1:Oculto','kw_hidden','Descritor',False,True,''));
$dd[2] = UpperCaseSql($dd[1]);
/// Gerado pelo sistem "base.php" versao 1.0.5
?>