<?
$idpro = 0;
require($include.'sisdoc_autor.php');
$tmp = $_FILES['userfile']['tmp_name'];
$arq = fopen($tmp,'r');
$sch = '¢';
$s = '';
while (!(feof($arq)))
	{
	$c = fread($arq,512);
	$s .= $c;
	} 

//$s .= substr($s,strpos('"Web Page"',$s),strlen($s));

//$s = troca($s,'"C"¢','#³#');
//$s = troca($s,'#³#',chr(13).'"C"¢');
$txt = '¢';
$sqw = substr($s,strpos($s,$txt)-4,strlen($s)).chr(13);
while (strpos($sqw,chr(13)) > 0)
	{
	$sr = substr($sqw,0,strpos($sqw,chr(13)));
	$sqw = substr($sqw,strpos($sqw,chr(13))+1,strlen($sqw));
	$sr .= $sch.'""'.$sch;
	$sr = troca($sr,"'","´");
	//echo '<HR>'.$sr.'<HR>';
	$id = brapci_recupera($sr,0);  // ID-Prócite
	$sql = "select * from brapci_article where ";
	$sql .= "ar_brapci_id = '".$id."'";
	$rlt = db_query($sql);
/////////////qq ver se já não foi indexado
//	echo '<BR>'.$sql;
	if (!($line = db_read($rlt)))
	{	
		echo "INICIO do processamento<BR>";
		/////////////////////////////////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////////////////// Processar autores
		$autor = brapci_autor($sr);
	
		/////////////////////////////////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////////////////// Processar autores
		$autor = brapci_autor_quali($autor,$sr);
	
		/////////////////////////////////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////////////////// Processar autores
		$autor = brapci_autor_adress($autor, brapci_recupera($sr,37));
		/////////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////// Título
	
		$titulo = brapci_recupera($sr,4);  // Título
		$tipo   = substr(brapci_recupera($sr,5),0,5);  // Tipo do documenti
		$journal= brapci_recupera($sr,10); // Nome do Journal
		$ed_mes = brapci_recupera($sr,20); // Mes da Edição
			$ed_ano = sonumero($ed_mes);
		$ed_ano = substr($ed_ano,strlen($ed_ano)-4,4);
		$ed_vol = trim(troca(brapci_recupera($sr,22),'v.','')); // Volume da Edição
		$ed_nur = trim(troca(brapci_recupera($sr,24),'n.','')); // Numero da Edição
		$ed_pag = trim(troca(brapci_recupera($sr,25),'p.','')); // Página da Edição
	
		$url    = trim(brapci_recupera($sr,38)); // Nome do Journal
		
		$idioma = brapci_recupera($sr,35); // Idioma
		if (strlen($idioma) == 0) { $idioma = 'pt_BR'; }
		$issn = brapci_recupera($sr,40); // ISSN
		$notas = brapci_recupera($sr,42); // Notas
		$abstract = brapci_recupera($sr,43); // Resumo
		$keyword  = brapci_recupera($sr,45); // Palavras chave
		$journal_name = brapci_recupera($sr,10); // Palavras chave
	
		///////////////////////////////////////////// RECUPERA NOME DO PERIÓDICO PELO ISSN
		if ((strlen($issn) > 0) or (strlen($journal_name) > 0))
			{
			$qsql = "select * from brapci_journal where (jnl_issn_impresso='".$issn."') ";
			$qsql .= " or (jnl_name_temp = '".$journal_name."' )";
			echo $qsql;
			$qrlt = db_query($qsql);
			if (!($line = db_read($qrlt)))
				{
				echo '<font color="red">Journal não compatível com os cadastrados na base de dados</font>';
				echo $journal_name;
				exit;
				} else {
				
					$journal_id = $line['jnl_codigo'];
				}
				
			}
	
		$snota = '';
		echo '<center><h1>'.$titulo.'</h1></center><BR>';
		echo '<TABLE width="100%" class="lt1">';
		echo '<TR><TD align="right">';
		for ($nr=0;$nr < count($autor);$nr++)
			{
			echo $autor[$nr][1].' <sup>'.($nr+1).'</sup><BR>';
			$snota .= '<BR><SUP>'.($nr+1).'</SUP> ';
			$snota .= $autor[$nr][5];
			if (strlen($autor[$nr][6]) > 0) 
				{
				$snota .= ' e-mail:'.$autor[$nr][6];
				}
			}
		echo '</TABLE>';
	
		echo '['.$tipo.']<BR>';
		echo $journal.'<BR>';
		echo $ed_mes.' '.$ed_vol.' '.$ed_nur.' '.$ed_pag.'<BR><BR>';
		
		echo '<div align="justify">'.$abstract.'</div>';
		echo '<BR><BR>Keyword:'.$keyword;
		echo '<BR><BR>';
			echo '-->'.$url.'<BR>';
		echo $snota;
		echo '<HR>';
	//	echo '<BR><BR>';
	//	echo $sr;
		
		require("brapci_procite_1_artigo.php");
		require("brapci_procite_1_edition.php");
		require("brapci_procite_1_edition_article.php");
		require("brapci_procite_1_article_suporte.php");
		require("brapci_procite_1_artigo_keyword.php");
		require("brapci_procite_1_autoridade.php");
		require("journal_update.php");
		echo '<font color="orange" size=5>FIM</font>';
		$idpro++;
		if ($idpro > 40) { echo ' fim dos quarenta '; exit; }
	}

	}
//////////////////////////////////////// Processar Titulo
function brapci_recupera($sn,$nb)
	{
	$sx = $sn;
	$txt = '¢';
	for ($nr=0;$nr <= $nb;$nr++)
		{
		$sx = substr($sx,strpos($sx,$txt)+1,strlen($sx));
		}
	$sx = substr($sx,0,strpos($sx,$txt));
	$sx = troca($sx,'"','');
	return($sx);
	}	

//////////////////////////////////////// Processar autor
function brapci_autor_adress($autor,$sn)
	{
	$nra = array();
	$sxx =  $sn;
	$sxx = troca($sxx,'"','');
	$sxx .= ';';
	
	/////////////////////////////////////////////// Vários autores com identificacao
	$nx = 0;
	while (strpos($sxx,';') > 0)
		{
		$email =  substr($sxx,0,strpos($sxx,';'));
		$autor[$nx][6] = $email;
		$sxx = substr($sxx,strpos($sxx,';')+1,strlen($sxx));
		$nx++;
		}

	return($autor);
	}
	
//////////////////////////////////////// Processar autor
function brapci_autor_quali($autor,$sn)
	{
	$nra = array();
	$sx = $sn;
	$txt = '¢';
	$sx = substr($sx,strpos($sx,$txt)+1,strlen($sx));	
	$sx = substr($sx,strpos($sx,$txt)+1,strlen($sx));	
	$sx = substr($sx,strpos($sx,$txt)+1,strlen($sx));	
	$sx = substr($sx,strpos($sx,$txt)+1,strlen($sx));	

	$sx = substr($sx,0,strpos($sx,$txt));
	$sx = troca($sx,'"','');
	$sx .= ';';
	
	/////////////////////////////////////////////// Vários autores com identificacao
	if (strpos($sx,'2.') > 0)
		{
			echo '--------AQUI--------';
			$sx = ' '.$sx;
			for ($nrn = 0;$nrn < count($autor);$nrn++)
				{
				$nrb = ' '.($nrn+1).'.';
				$nrf = ' '.($nrn+2).'.';
				$nrq = trim(substr($sx,strpos($sx,$nrb)+strlen($nrb),strlen($sx)));
				if (strpos($nrq,$nrf) > 0) { $nrq = substr($nrq,0,strpos($nrq,$nrf)); }
				$autor[$nrn][5] = $nrq;
				echo '<BR>>>>'.$nrn.'. '.$nrq; 
				}
		} else {
	////////////////////////////////////////////// Unica qualificação
			$nx = 0;
			$autor[0][5] = substr($sx,0,strpos($sx,';'));
			}
	return($autor);
	}
	
//////////////////////////////////////// Processar autor
function brapci_autor($sn)
	{
	$nra = array();
	$sx = $sn;
	$txt = '¢';
	$sx = substr($sx,strpos($sx,$txt)+1,strlen($sx));	
	$sx = substr($sx,strpos($sx,$txt)+1,strlen($sx));	
	$sx = substr($sx,0,strpos($sx,$txt));
	$sx = troca($sx,'"','');
	$sx .= ';';
	while (strpos($sx,';') > 0)
		{
		$txt = ';';
		////////// Recupera nome do autor
		$sa = substr($sx,0,strpos($sx,';'));
		////////// Armazena se o nome for maior que 0
		if (strlen($sa) > 0)
			{
			array_push($nra,array('',$sa,nbr_autor($sa,5),nbr_autor($sa,1),'','',''));
			$sx = substr($sx,strpos($sx,$txt)+1,strlen($sx));	
			}
		}
	//// Processa na base
	for ($nr = 0;$nr < count($nra);$nr++)
		{
		$xsql = "select * from brapci_autor ";
		$xsql .= " where autor_nome_abrev = '".UpperCaseSql($nra[$nr][2])."'";
		$xrlt = db_query($xsql);
		if (!($xline = db_read($xrlt)))
			{
			$xsql = "insert into brapci_autor ";
			$xsql .= "(autor_codigo,autor_nome,autor_nome_asc,";
			$xsql .= "autor_nome_abrev,autor_nome_citacao,autor_nasc,";
			$xsql .= "autor_lattes,autor_alias";
			$xsql .= ") values (";
			$xsql .= "'','".$nra[$nr][1]."','".UpperCaseSql($nra[$nr][1])."',";
			$xsql .= "'".UpperCaseSql($nra[$nr][2])."','".$nra[$nr][1]."','',";
			$xsql .= "'',''";
			$xsql .= ")";
			echo $xsql.'<HR>';
			$xrlt = db_query($xsql);	
			
			$xsql = "update brapci_autor set autor_codigo=lpad(id_autor,7,'0') where autor_codigo=''";			
			$xrlt = db_query($xsql);

			$xsql = "select * from brapci_autor ";
			$xsql .= " where autor_nome_abrev = '".UpperCaseSql($nra[$nr][2])."'";
			$xrlt = db_query($xsql);
			$xline = db_read($xrlt);
			}
		$xcod = $xline['autor_codigo'];
		$nra[$nr][0] = $xcod;
		}
	return($nra);
	}
?>