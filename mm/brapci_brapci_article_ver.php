<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('artigos','brapci_brapci_articles.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Cadastro de usuários';
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><?

$tabela = "brapci_article";
$idcp = "ar";
$label = "Manuscrito";

$sql = "select * from brapci_article ";
$sql .= "inner join brapci_edition_article on ea_article=ar_codigo ";
$sql .= "inner join brapci_journal on ar_journal_id=jnl_codigo ";
$sql .= "inner join brapci_edition on ea_edition=ed_codigo ";
$sql .= " where id_ar = ".$dd[0];

$rlt = db_query($sql);
if ($line = db_read($rlt))
	{
	$ref_autor = "";
	$journal_title = $line['jnl_nome'];
	$journal_issn  = $line['jnl_issn_impresso'];
	$journal_eissn = $line['jnl_issn_eletronico'];
	$cod_artigo    = $line['ar_codigo'];
	
	$titulo_1 = $line['ar_titulo_1'];
	$idioma_1 = $line['ar_idioma_1'];
	$resumo_1 = $line['ar_resumo_1'];
	
	$titulo_2 = $line['ar_titulo_2'];
	$idioma_2 = $line['ar_idioma_2'];
	$resumo_2 = $line['ar_resumo_1'];
	
	$titulo_3 = $line['ar_titulo_3'];
	$idioma_3 = $line['ar_idioma_3'];
	$resumo_3 = $line['ar_resumo_1'];
	
	$status = $line['ar_status'];
	$tipo   = $line['ar_tipo'];
	$pgi    = $line['ar_pg_inicial'];
	$pgf    = $line['ar_pg_final'];
	$atuali = $line['ar_disponibilidade'];
	
	////////// recupera edição
	$ano = $line['ed_ano'];
	$vol = $line['ed_vol'];
	$num = $line['ed_nr'];
	$dted= $line['ed_data_publicacao'];
	$edpe= $line['ed_periodo'];
	
	////////// recupera autores
	$sql = "select * from brapci_article_author ";
	$sql .= " inner join brapci_autor on ae_author = autor_codigo ";
	$sql .= " where ae_article = '".$cod_artigo."' ";
	$sql .= " order by ae_position ";
	$autor = array();
	$rla = db_query($sql);
	while ($rline = db_read($rla))
		{
		array_push($autor,array($rline['ae_author'],$rline['autor_nome_citacao'],$rline['ae_bio'],
				$rline['ae_doutorado'],$rline['ae_mestrado'],$rline['ae_contact']));
		}
	
	////////// recupera keywords
	$sql = "select * from brapci_article_keyword ";
	$sql .= " inner join brapci_keyword on kw_codigo = kw_keyword ";
	$sql .= " where kw_article = '".$cod_artigo."' ";
	$sql .= " order by kw_ord ";
	$keyword = array();
	$rla = db_query($sql);
	while ($rline = db_read($rla))
		{
		array_push($keyword,array($rline['kw_word'],$rline['kw_idioma'],$rline['kw_use']));
		}
		
	
	}
	
if (strlen($titulo_1) > 0)
	{
	$sr .= '<div align="justify" style=" margin: 0px 10px 30px 10px; width: 100%;">';	
	$sr .= '<font size=5><center>'.$titulo_1.'</center></font>';
	$sr .= '</DIV>';
	////// Autores
	$sf .= '<table width="98%" align="center"><TR><TD align="left"><font size=1>';
	$sr .= '<table width="98%" align="center"><TR><TD align="right"><font size=2>';
	for ($k=0; $k < count($autor);$k++)
		{
		if ($k > 0) { $sr .= '<BR>'; }
		$sr .= '<font size=2>';
		$sr .= $autor[$k][1];
		$sr .= '<SUP>';
		$sr .= ' ['.chr(65+$k).']';
		$sr .= '</SUP>';
		
		////// Nomes para referência 
		$ref_autor .= $autor[$k][1].'. ';
		///// Rodapé
		if ($k > 0) { $sf .= '<BR>'; }
		$sf .= '<SUP>['.chr(65+$k).']</SUP> ';
		$sf .= $autor[$k][2];
		if (strlen(trim($autor[$k][5])) > 0)
			{ $sf .= ', e-mail '.$autor[$k][5]; }
		}
	$sf .= '</table>';
	$sr .= '</table>';
	
	////// Resumo
	$sr .= '<div align="justify" style=" margin: 30px 30px 30px 30px; width: 100%;">';
	$tipo = "RESUMO";
	$key  = "Palavras-chave: ";
	if ($idioma_1 == 'en') { $tipo = 'ABSTRACT'; $key = "Keywords: ";}
	$sr .= $resumo_1;
	$sr .= '<BR><BR>';
	$sr .= $key;
	
	for ($k=0; $k < count($keyword);$k++)
		{
		$plv = $keyword[$k][0];
		$plv = UpperCase(substr($plv,0,1)).substr($plv,1,strlen($plv));
		$sr .= $plv.'. ';
		}	
	$sr .= '</div>';
	
	$sa .= '';
	////// Referência Bibliográfica
	$sa .= '<div align="justify" style=" margin: 0px 10px 30px 10px; width: 100%;">';	
	$sa .= '<font size=2>';
	$sa .= $ref_autor;
	$sa .= $titulo_1.'. ';
	$sa .= '<B>';
	$sa .= $journal_title;
	$sa .= '</B>';
	$sa .= '. v.'.$vol.' n. '.$num.', ';
	if (strlen($edpe) > 0)
		{ $sa .= $edpe; }
		else { $sa .= $ano; }
	$sa .= '</div>';
	}
echo $sa;
echo $sr;

echo $sf;