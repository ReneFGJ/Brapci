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

$tabela = "brapci_keyword";
$sql = "select * from ".$tabela." where id_kw = ".$dd[0];
$rlt = db_query($sql);
if ($line = db_read($rlt))
	{
	$ed_codigo = $line['kw_codigo'];
	$jid_nome    = $line['kw_word'];
	
	$sx .= '<font class="lt3"><B>'.$jid_nome.'</B></font>';
	}

//	require("bracpi_article_edtion_cab.php");
//	echo $sx;
//s	exit;
	$sx .= '<CENTER><H1><B>SUMÁRIO</B></H1></CENTER>';

	////////// recupera os artigos
	$sql = "select * from brapci_article_keyword ";
	$sql .= " inner join brapci_article on kw_article = ar_codigo ";
	$sql .= " left join brapci_edition on ar_edition = ed_codigo ";
	$sql .= " left join brapci_status on st_codigo = ar_status ";
	$sql .= " left join brapci_section on ar_section = se_codigo ";
	$sql .= " left join brapci_journal on ar_journal_id = jnl_codigo ";	
	
	$sql .= " where kw_keyword = '".$ed_codigo."' ";
	$sql .= " order by ed_ano desc, ed_vol desc, ed_nr desc, se_descricao ";
//	echo $sql;
	$rlt = db_query($sql);
	
	$setor = "";
	while ($line = db_read($rlt))
		{
		$jnome = $line['jnl_nome'];
		$jnome_abrev = $line['jnl_nome_abrev'];

		$ed_ano    = $line['ed_ano'];
		$ed_vol    = $line['ed_vol'];
		$ed_nr     = $line['ed_nr'];
		$ed_mes_i  = $line['ed_mes_inicial'];
		$ed_mes_f  = $line['ed_mes_final'];
		$ed_perio  = $line['ed_periodo'];
	
		$titulo_1 = $line['ar_titulo_1'];
		$titulo_2 = $line['ar_titulo_2'];
		$titulo_3 = $line['ar_titulo_3'];
		
		$idioma_1 = $line['ar_idioma_1'];
		$idioma_2 = $line['ar_idioma_2'];
		$idioma_3 = $line['ar_idioma_3'];
		
		$sta      = $line['ar_status'];
		
		$pgini    = $line['ar_pg_inicial'];
		$pgfim    = $line['ar_pg_final'];
		
		$secao    = $line['ar_section'];
		$secao_no = $line['se_descricao'];
		$conhe    = $line['ar_area_conhecimento'];
		
		$status   = $line['st_descricao'];
		
		$link = '<A HREF="brapci_article_select.php?dd0='.$line['ar_codigo'].'" class="lt2">';
		
		if ($secao != $setor)
			{ 
			$setor = $secao;
			if (strlen($secao) == 0)
				{ $secao_no = $secao; }
			$sx .= '<TR><TD class="lt4" colspan="5"><HR>'.$secao_no.'<HR></TD></TR>';
			}

		////////q titulo original
		$sx .= '<TR valign="top"><TD colspan="4"><B>';
		$sx .= $link;
		$sx .= $titulo_1;
		$sx .= '</A>';
		$sx .= '<TD align="right" colspan="1"><noBR>';
		$sx .= '<font class=lt2>p.'.$pgini.'-'.$pgfim.'</font>';
		
		///////////////////
		$sx .= '<TR><TD colspan="2" class="lt0">';		
		$class = 'class="lt0"';
		require("bracpi_article_edtion_ref.php");
		
		
		/////// outras informações
		require("status.php");
		
		$sx .= '<TR>';
		$sx .= '<TD colspan="2" class="lt0">';
		$sx .= '<I>['.$sta.'] '.$status;
		
		/////// status
		$sx .= '<BR>';
		}
	
//exit;
echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
echo $sx;	
echo '</table>';
?>
<BR><BR><BR><BR>
</DIV>