<?
require("db.php");
require($include.'sisdoc_debug.php');
$dr = 'brapci_'.$dd[0].'.php';

if ($dd[0] == 'diris')
		{
			require("../db_diris.php");
		}

if ($dd[0] == 'diris') 
	{$dx1 = "dis_codigo";	$dx2 = "dis"; 	$dx3 = "7"; $dr = "diris.php"; }	

///////////////	SUBMISSAO
if ($dd[0] == 'instituicoes') 
	{$dx1 = "inst_codigo";	$dx2 = "inst"; 	$dx3 = "7"; }
                           

if ($dd[0] == 'brapci_journal_tipo')
	{$dx1 = ""; $dx2 = ""; $dx3 = ""; $dr = 'brapci_journal_tipo.php'; }
					 
if ($dd[0] == 'brapci_metodologias') 
	{$dx1 = "bmt_codigo";	$dx2 = "bmt"; 	$dx3 = "7"; }					 
	
if ($dd[0] == 'mar_works') 
	{$dx1 = "m_codigo";	$dx2 = "m"; $dx3 = 10; $dr = 'close.php'; }
    
if ($dd[0] == 'brapci_section') 
	{$dx1 = "se_cod";	$dx2 = "se"; 	$dx3 = 3; }
	
if ($dd[0] == 'brapci_article') 
	{$dx1 = "ar_codigo";	$dx2 = "ar"; 	$dx3 = "10"; }

if ($dd[0] == 'brapci_keyword') 
	{$dx1 = "kw_codigo";	$dx2 = "kw"; 	$dx3 = "10"; }	

if ($dd[0] == 'brapci_edition') 
	{$dx1 = "ed_codigo";	$dx2 = "ed"; 	$dx3 = "7"; }

if ($dd[0] == 'brapci_usuario') 
	{$dx1 = "usuario_codigo";	$dx2 = "usuario"; 	$dx3 = "5"; }

if ($dd[0] == 'brapci_usuario_perfil') 
	{$dx1 = "perfil_codigo";	$dx2 = "perfil"; 	$dx3 = "5"; }
	
if ($dd[0] == 'brapci_periodicidade') 
	{$dx1 = "peri_codigo";	$dx2 = "peri"; 	$dx3 = "5"; }	
	
if ($dd[0] == 'ajax_cidade') 
	{$dx1 = "cidade_codigo";	$dx2 = "cidade"; 	$dx3 = "7"; }		

if ($dd[0] == 'ajax_estado') 
	{$dx1 = "estado_codigo";	$dx2 = "estado"; 	$dx3 = "7"; }
	
if ($dd[0] == 'ajax_pais') 
	{$dx1 = "pais_codigo";	$dx2 = "pais"; 	$dx3 = "7"; }		
	
if ($dd[0] == 'brapci_journal') 
	{$dx1 = "jnl_codigo";	$dx2 = "jnl"; 	$dx3 = "7"; }	
	
if ($dd[0] == 'brapci_autor') 
	{$dx1 = "autor_codigo";	$dx2 = "autor";	$dx3 = "7"; $dr = 'updatex.php?dd0=brapci_use_autor';}

if ($dd[0] == 'brapci_use_autor') 
	{$dd[0] = 'brapci_autor'; $dx1 = "autor_alias";	$dx2 = "autor";	$dx3 = "7"; $dr = 'brapci_brapci_autor.php'; }
	
if ($dd[0] == 'mar_journal') 
	{
	$dx1 = "mj_codigo";	$dx2 = "mj"; 	$dx3 = "7"; 
	$sql = "update ".$dd[0]." set ".$dx1."=lpad(id_".$dx2.",'".$dx3."','0') where (length(".$dx1.") < ".$dx3.");";
	$rlt = db_query($sql);
	
	$dx1 = "mj_use";	$dx2 = "mj"; 	$dx3 = "7"; 
	}	

if (strlen($dx1) > 0)
	{
	$sql = "update ".$dd[0]." set ".$dx1."=trim(to_char(id_".$dx2.",'".strzero(0,$dx3)."')) where (length(trim(".$dx1.")) < ".$dx3.");";
	
	$sql = "update ".$dd[0]." set ".$dx1."=lpad(id_".$dx2.",'".$dx3."','0') where (length(".$dx1.") < ".$dx3.");";
	echo $sql.'<HR>';
	//echo $sql;
	$rlt = db_query($sql);
	}
	
header("Location: ".$dr);
echo 'Stoped'; exit;
?>