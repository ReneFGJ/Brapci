<?
///////////////// Versao 0.0.1f de 02/08/2008
require("cab.php");
require($include.'sisdoc_colunas.php');

$label = 'Instituições cadastradas';
$cpi = "";
$tabela = "instituicoes";
//if ($user_nivel == 9)
	{	
	$http_edit = 'brapci_ed_edit.php';
	$http_edit_para = '&dd99='.$tabela; 
	$editar = true;
	}
	$http_redirect = 'brapci_instituicoes.php';
//	$http_ver = 'producao_manuscrito.php';
	$cdf = array('id_inst','inst_nome','inst_abreviatura','inst_codigo','inst_ordem');
	$cdm = array('Código','Nome','Sigla','codigo','oed');
	$masc = array('','','','','','','');
	$busca = true;
	$offset = 60;
//	$pre_where = "journal_id = '".$journal_id."' ";
//	$order  = " doc_status, doc_data dec";
	$order  = " inst_nome_asc";
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><?	
	require($include."sisdoc_row.php");
	?></div>
<?
require("foot.php");	

//$sql = "update instituicoes set inst_nome_asc = Upper(asc7(inst_nome))";
//$rlt = db_query($sql);
?>