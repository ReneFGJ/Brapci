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
array_push($cp,array('$A8','','Entrada do Termo',False,True,''));
array_push($cp,array('$O'.$op,'','Nome',True,True,''));
array_push($cp,array('$O : '.$oi,'','Idioma',True,True,''));
array_push($cp,array('$T40:5','','Value',False,True,''));
?>