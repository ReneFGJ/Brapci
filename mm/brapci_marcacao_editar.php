<?
require("func_status.php");
global $ed_editar;
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
array_push($mnpos,array('sumário','brapci_brapci_article_edition.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_tips.php");
require($include."sisdoc_message.php");
//require($include."sisdoc_data.php");
$titu[0] = 'Trabalho dentro da Base';

$jid = read_cookie("journal_sel");
$jedsel = read_cookie("journal_ed");

if (strlen($dd[52]) > 0) { setcookie("journal_ed" ,$dd[52]); $jedsel = $dd[52]; }
if (strlen($dd[51]) > 0) { setcookie("journal_sel",$dd[51]); $jid = $dd[51];    }

if (strlen($jid) == 0) { msg_erro('Journal não selecionado'); exit; }
//if (strlen($jedsel) == 0) { msg_erro('Edição não selecionada'); exit; }

?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><?

	////////// recupera os artigos
	$sql = "select * from brapci_article ";
	$sql .= " left join brapci_status on st_codigo = ar_status ";
	$sql .= " left join brapci_edition on ed_codigo = ar_edition ";
	$sql .= " left join brapci_section on ar_section = se_codigo ";
	$sql .= " where ar_journal_id = '".strzero($jid,7)."' and id_ar= '".$dd[0]."' ";

	$rlt = db_query($sql);
	if (!($line = db_read($rlt)))
		{
				$sx = msg_erro('Artigo cancelando');
				echo '<CENTER>'.$sx;
				exit;
		}
		else
		{
		$stm = $line['ar_mt'];
		
		$ida      = $line['id_ar'];
		$cod      = $line['ar_codigo'];
		$titulo_1 = $line['ar_titulo_1'];
		$titulo_2 = $line['ar_titulo_2'];
		$titulo_3 = $line['ar_titulo_3'];
		
		$idioma_1 = $line['ar_idioma_1'];
		$idioma_2 = $line['ar_idioma_2'];
		$idioma_3 = $line['ar_idioma_3'];

		$resumo_1 = $line['ar_resumo_1'];
		$resumo_2 = $line['ar_resumo_2'];
		$resumo_3 = $line['ar_resumo_3'];
		
		$mt_1 = trim($line['at_metodo_1']);
		$mt_2 = trim($line['at_metodo_2']);
		$mt_3 = trim($line['at_metodo_3']);

		$te_1 = trim($line['at_tecnica_1']);
		$te_2 = trim($line['at_tecnica_2']);
		$te_3 = trim($line['at_tecnica_3']);

		
		
		$obs = $line['at_obs'];
		$obsdt = $line['at_obs_data'];

		$sta      = $line['ar_status'];
		$stc      = trim($line['ar_status']);
		///////////////////////////////////////////////////
		if ($stc == 'A') { $ed_editar = 1; }
		if ($stc == 'B') { $ed_editar = 1; }
		if ($stc == 'C') { $ed_editar = 1; }
		
		$pgini    = $line['ar_pg_inicial'];
		$pgfim    = $line['ar_pg_final'];
		
		$secao    = $line['ar_section'];
		$secao_no = $line['se_descricao'];
		
		$conhe    = $line['ar_area_conhecimento'];
		
		$status   = $line['st_descricao'];
		$journal  = $line['ar_journal_id'];
		
		$ed_vol   = $line['ed_vol'];
		$ed_nr    = $line['ed_nr'];
		$ed_ano   = $line['ed_ano'];
		$ed_mi    = nomemes_short($line['ed_mes_inicial']);
		$ed_mf    = nomemes_short($line['ed_mes_final']);
		$ed_mes   = $ed_mi.'/'.$ed_mf;
		}		
		
		$sql = "select * from brapci_journal ";
		$sql .= " where jnl_codigo = '".$journal."' ";
		$jrlt = db_query($sql);
		if ($jline = db_read($jrlt))
			{
			$jnome = $jline['jnl_nome'];
			$jnome_abrev = $jline['jnl_nome_abrev'];
			}
		////////////////////// Identificação da Publicação
		require("bracpi_article_edtion_cab.php");

		////////q titulo original
		$sx .= '<font class=lt4><center>'.$titulo_1;
		$sa1 = '';
		if ($ed_editar == 1) 
			{
			$linke = 'onclick="newxy2('.chr(39).'brapci_titulo_ed.php?dd0='.$ida.chr(39).',750,350);"'; 
			$sa1 = '<img src="img/icone_editar.gif" width="20" height="19" alt="Editar página" border="0" '.$linke.' >';			
			}
		$sx .= $sa1.'</center></font>';
		$sx .= '<BR>';
		/////// titulo alternativo
		if (strlen($titulo_2) > 0) { 
			$sx .= '<font class=lt3><center><I>'.$titulo_2.$sa1.'</I></center></font>';
			$sx .= '<BR>';
			}
		/////// titulo alternativo
		if (strlen($titulo_3) > 0) { 
			$sx .= '<font class=lt3><center><I>'.$titulo_3.$sa1.'</I></center></font>';
			$sx .= '<BR>';
			}
		echo $sx;
		$sx = '';
require("mar_marcacao.php");
?></DIV>			
