<?
$tabela = "";
$cp = array();
$ms1 = 'Nome';
$ms2 = 'idioma';
$ms3 = 'Valor';
$op = ' : ';
$op .= '&prefTerm:Termo preferencial';
$op .= '&altTerm:Termo alternativo';
$op .= '&hidden:Termo oculto';
///
$oi = '&pt_BR:Portugues (Brasil)&en:Inglês';
if ($idioma == 'en')
	{
	}
array_push($cp,array('$H8','','',False,True,''));
array_push($cp,array('$H8','','',False,True,''));
array_push($cp,array('$A8','','Entrada do Conceito',False,True,''));
array_push($cp,array('$S50','','Nome do conceito',True,True,''));
array_push($cp,array('$H','','URI',False,True,''));
array_push($cp,array('$O : '.$oi,'','Idioma',True,True,''));
//array_push($cp,array('$Q tci_descricao:tci_codigo:select * from tci_thema where tci_codigo = '.chr(39).$tema.chr(39),'','Tema',False,True,''));
array_push($cp,array('$Q tci_descricao:tci_codigo:select * from tci_thema','','Tema (thema)',False,True,''));

?>