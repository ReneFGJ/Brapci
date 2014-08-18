<?
require("func_status.php");
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
require($include."sisdoc_tips.php");

//$jedsel

$titu[0] = 'Trabalhos desta Edições - '.$jid_nome;

		
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><?

if (strlen($jedsel) == 0)
	{
	redirecina("journal_edition_mst.php");
	exit;
	}

$tabela = "brapci_edition";
$sql = "select * from ".$tabela." ";
$sql .= " where id_ed = ".$jedsel;
$rlt = db_query($sql);

$tot = 0;
$stta = 0;
$sttb = 0;
$sttc = 0;
$sttd = 0;

if ($line = db_read($rlt))
	{
	$edi = $line['id_ed'];
	$ed_status = $line['ed_status'];
	$ed_codigo = $line['ed_codigo'];
	$ed_ano    = $line['ed_ano'];
	$ed_vol    = $line['ed_vol'];
	$ed_nr     = $line['ed_nr'];
	$ed_journal= $line['ed_journal_id'];
	$ed_mes_i  = $line['ed_mes_inicial'];
	$ed_mes_f  = $line['ed_mes_final'];
	$ed_perio  = $line['ed_periodo'];
	$ed_codigo = $line['ed_codigo'];
	$sx .= '<TR><TD colspan="6">';
	$sx .= '<font class="lt3"><B>'.$jid_nome.' v.'.$ed_vol.', n.'.$ed_nr.', '.$ed_ano.'</B></font>';
	$sx .= '<BR>';
	$sx1 .= '<A HREF="brapci_ed_edit.php?&dd99=brapci_article&dd5='.$ed_codigo.'">';
	$sx1 .= '<IMG SRC="include/img/bt_novo.png" border="0">';
	$sx1 .= '</A>';
	$sx .= tips($sx1,'Inserir novo artigo');
	}

//	require("bracpi_article_edtion_cab.php");
//	echo $sx;
//s	exit;
	$sx .= '<CENTER><H1><B>SUMÁRIO</B></H1></CENTER>';

	////////// recupera os artigos
	$sql = "select * from brapci_article ";
	$sql .= " left join brapci_status on st_codigo = ar_status ";
	$sql .= " left join brapci_section on ar_section = se_codigo ";
	$sql .= " left join brapci_journal on ar_journal_id = jnl_codigo ";
	$sql .= " where ar_journal_id = '".$ed_journal."' and ar_edition='".$ed_codigo."' ";
	$sql .= " order by se_ordem, lpad(round(ar_pg_inicial),5,0), ar_titulo_1 ";	
	$rlt = db_query($sql);

	$setor = "";
	
	while ($line = db_read($rlt))
		{
		$sta      = $line['ar_status'];
		
		$jnome = $line['jnl_nome'];
		$jnome_abrev = $line['jnl_nome_abrev'];

		$titulo_1 = trim($line['ar_titulo_1']);
		if (strlen($titulo_1) == 0) { $titulo_1 = '{sem título definido}'; }
		$titulo_2 = $line['ar_titulo_2'];
		$titulo_3 = $line['ar_titulo_3'];
		
		$idioma_1 = $line['ar_idioma_1'];
		$idioma_2 = $line['ar_idioma_2'];
		$idioma_3 = $line['ar_idioma_3'];
		
		$pgini    = $line['ar_pg_inicial'];
		$pgfim    = $line['ar_pg_final'];
		
		$secao    = $line['ar_section'];
		$secao_no = $line['se_descricao'];
		$conhe    = $line['ar_area_conhecimento'];
		
		$status   = $line['st_descricao'];
		
		$tot++;
		if ($sta == 'A') { $stta++; }
		if ($sta == 'B') { $sttb++; }
		if ($sta == 'C') { $sttc++; }
		if ($sta == 'D') { $sttd++; }

		$link = '<A HREF="brapci_article_select.php?dd0='.$line['id_ar'].'" class="lt2">';
		
		if ($secao != $setor)
			{ 
			$setor = $secao;
			if (strlen($secao) == 0)
				{ $secao_no = $secao; }
			$sx .= '<TR><TD class="lt4" colspan="6"><HR>'.$secao_no.'<HR></TD></TR>';
			}
			
		/////////////////////////////////////////////////////////
		$stia = $sta;
		if ($stia == '') { $sti = '<img src="img/icone_operador.png" width="32" height="32" alt="">'; }
		if ($stia == 'A') { $sti = '<img src="img/icone_operador.png" width="32" height="32" alt="">'; }
		if ($stia == 'B') { $sti = '<img src="img/icone_revisao.png" width="32" height="32" alt="" >'; }
		if ($stia == 'C') { $sti = '<img src="img/icone_checar.png" width="32" height="32" alt="" >'; }
		if ($stia == 'D') { $sti = '<img src="img/icone_ok.png" width="32" height="32" alt="" >'; }

		////////q titulo original
		$sx .= '<TR valign="top">';
		$sx .= '<TD rowspan="3" width="10"  align="right">'.$sti.'</TD>';
		$sx .= '<TD colspan="4"><B>';
		$sx .= $link;
		$sx .= $titulo_1;
		$sx .= '</A>';
		$sx .= '<TD align="right" colspan="1"><noBR>';
		$sx .= '<font class=lt2>p.'.$pgini.'-'.$pgfim.'</font>';
		
		/////// outras informações
		require("status.php");
		
		$sx .= '<TR><TD colspan="5" class="lt0">';

		$class = 'class="lt0"';
		require("bracpi_article_edtion_ref.php");

		
		$sx .= '<TR>';
		$sx .= '<TD colspan="5" class="lt0">';
		$sx .= '<I>['.$sta.'] '.$status;
		
		/////// status
		$sx .= '<BR>';
		}
	
//exit;
echo '<TABLE width="'.$tab_max.'" align="center" border="0"><TR><TD>';
echo $sx;	
echo '</table>';
?>
<BR><BR><BR><BR>
</DIV>

<?
echo $stta.' '.$sttb.' '.$sttc.' = '.$tot.' ['.$ed_status.']';

if ($stta > 0) { $ed_sta = 'A'; }
if (($stta == 0) and ($sttb > 0)) { $ed_sta = 'B'; }
if (($stta == 0) and ($sttc > 0)) { $ed_sta = 'C'; }
if ($sttc == $tot) { $ed_sta = 'C'; }
if ($sttd == $tot) { $ed_sta = 'F'; }

if ($ed_sta != $ed_status)
	{
	$sql = "update brapci_edition set ed_status = '".$ed_sta."' ";
	$sql .= " where id_ed = 0".$edi;
	$rlt = db_query($sql);
	}
	
?>