<?
session_start();
if (strlen($dd[4]) == 0)
	{
	$dd[4] = $_SESSION['concept'];
	}

$tabela = "";
$cp = array();
$ms1 = 'Nome';
$ms2 = 'idioma';
$ms3 = 'Valor';
$op = ' : ';
$op .= '&broader:Conceito específico de';
$op .= '&narrower:Conceito genérico';
$op .= '&related:Conceito relacionado';
///
if ($idioma == 'en')
	{
	}
	
$ss = "select * from tci_conceito_relacionamento ";
$ss .= " inner join tci_keyword on kw_codigo = crt_termo ";
$ss .= " where (crt_rela = 'PD' or crt_rela = 'UF') ";
//$ss .= " and kw_idioma = '".$idioma."' ";
$ss .= " order by kw_word_asc ";
array_push($cp,array('$H8','','',False,True,''));
array_push($cp,array('$H8','','',False,True,''));
array_push($cp,array('$A8','','Relacionamento entre Conceito',False,True,''));
array_push($cp,array('$O'.$op,'','Tipo de relacionamento',True,True,''));
array_push($cp,array('$Q kw_word:crt_conceito:'.$ss,'','Conceito',False,True,''));
array_push($cp,array('$Q tci_descricao:tci_codigo:select * from tci_thema','','Tema',False,True,''));

?>