
<?
$tabela = "brapci_autor";
$cp = array();
array_push($cp,array('$H8','id_autor','id_autor',False,True,''));
array_push($cp,array('$S7','autor_codigo','Codigo',False,False,''));
array_push($cp,array('$S120','autor_nome','Nome referência',False,True,''));
array_push($cp,array('$HV','autor_nome_asc',UpperCaseSql($dd[2]),False,True,''));
array_push($cp,array('$S120','autor_nome_citacao','Nome para citação',False,True,''));
array_push($cp,array('$S40','autor_nome_abrev','Nome abreviado',False,True,''));
array_push($cp,array('$S8','autor_nasc','Nascimento',False,True,''));
array_push($cp,array('$S8','autor_fale','Falecimento',False,True,''));
array_push($cp,array('$S100','autor_lattes','Lattes (link)',False,True,''));
array_push($cp,array('$S7','autor_alias','Remissiva',False,True,''));
array_push($cp,array('$O A:Pessoa Física&I:Instituição&X:Exclusão','autor_tipo','Tipo do autor',False,True,''));
$dd[3] = UpperCaseSql($dd[2]);
/// Gerado pelo sistem "base.php" versao 1.0.5
?>