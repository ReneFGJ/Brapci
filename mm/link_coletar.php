<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$timeout = 3;

?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><CENTER>
<META HTTP-EQUIV="Refresh" CONTENT="2;URL=link_coletar.php?dd0=<?=$dd[0];?>">

<?
if( !ini_get('safe_mode') )
		{
			echo 'Ativado timeout de '.$timeout.' seg';
            set_time_limit($timeout);
        } else {
			echo 'Modo de segurança';
		}
		
if (strlen($dd[0]) > 0)
	{ $journal = strzero($dd[0],7); } else { echo 'Não foi informado a publicação para coleta'; exit; }

$sql = "select * from brapci_article_suporte ";
$sql .= " inner join brapci_article on bs_article = ar_codigo ";
$sql .= " where bs_status = '@' and bs_type = 'URL' ";
$sql .= " and bs_journal_id = '".$journal."' ";
$sql .= " and ar_status <> 'X' ";
$sql .= " order by bs_update  ";
$sql .= " limit 1 ";
$rlt = db_query($sql);
require("link_coletar_cab.php");

if (!($line = db_read($rlt)))
	{
	redirecina("main.php");
	} else {
	$ok = 1;
	$flink = trim($line['bs_adress']);
	$article = trim($line['bs_article']);
	$id = $line['id_bs'];
	$update = trim($line['bs_update']);
	}
	
	$flink = troca($flink,'164.41.105.3/portalnesp/ojs-2.1.1','www.tempusactas.unb.br');
	$flink = troca($flink,'164.41.122.25/portalnesp/ojs-2.1.1','www.tempusactas.unb.br');
//                         http://164.41.105.3/portalnesp/ojs-2.1.1/index.php/RBB/
//164.41.122.25
	echo '<TT>';
	echo 'Link: '.$flink.'<BR>';
	echo 'Codigo: '.$article.'<BR>';
	echo 'ID: '.$id.'/'.$tp.'<BR>';
	echo 'Atualizado em: '.stodbr($update).'<BR>';
//	exit;	
////////////// Verificar se não existe o PDF
$sql = "select * from brapci_article_suporte ";
$sql .= " where bs_article = '".$article."' and bs_type = 'PDF' ";
$rlt = db_query($sql);
if ($xline = db_read($rlt))
	{
	$sql = "update brapci_article_suporte set bs_status = 'X' ";
	$sql .= " where bs_article = '".$article."' and bs_type = 'URL'; ";
	$rlt = db_query($sql);
	echo 'Já existe PDF neste artigo';
	exit;
	}

	{
	//////////////////////////////////////////// Ajustes
	$tp = 0;

	if (strpos($flink,'/download/') > 0) { $tp = 1; }
	if (strpos($flink,'mode=pdf') > 0) { $tp = 1; }

	
	if (strpos($flink,'/view/') > 0)
		{
		$tp = 2;
		$flink = troca($flink,'/view/','/viewArticle/');
		}

	echo 'Link (Coleta): '.$flink.'<BR>';
		
		{
		require("link_coletar_E.php");
		
		if ($tp == 1) { require("link_coletar_1.php"); exit; }
		if ($tp == 2) { require("link_coletar_2.php"); exit; }
		
		if ($tp < 1) { require("link_coletar_0.php"); }
		}
	}
?>
</DIV>
