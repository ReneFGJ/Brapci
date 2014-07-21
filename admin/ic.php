<?
///////////////// Versao 0.0.1f de 02/08/2008
require("cab.php");
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_data.php');
$label = "Cadastro Informações";
$cpi = "nw";
$tabela = "ic_noticia";
$journal_id = 0;

if ($user_nivel >= 0)
	{	
	$http_edit = 'ic_edit.php';
	$editar = true;
	}
	$http_redirect = 'ic.php';
//	$http_ver = $tabela.'.php';
	$cdf = array('id_'.$cpi,$cpi.'_ref',$cpi.'_titulo',$cpi.'_dt_de',$cpi.'_dt_ate',$cpi.'_idioma');
	$cdm = array('Código','Ref.','Título','De','até','idioma');
	$masc = array('','','','D','D','');
	$busca = true;
	$offset = 20;
	$pre_where = " nw_journal = '".$journal_id."' ";
	$order  = "";
	require($include."sisdoc_row.php");
require("foot.php");	
?>