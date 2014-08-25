<?php
require("cab.php");
require($include.'sisdoc_form2.php');
require($include.'sisdoc_autor.php');
require($include.'sisdoc_debug.php');
require("../_class/_class_oai.php");
$oai = new oai;

require("../_class/_class_author.php");
$au = new author;

require("../_class/_class_keyword.php");
$kw = new keyword;

require("../_class/_class_article.php");
$ar = new article;

echo $oai->cab();

$jid = round($dd[1]);


echo '<BR>Loolking record...';
$line = $oai->proximo_processamento($jid);
if (strlen(trim($line['cache_journal'])) > 0)
	{
		echo '<A HREF="'.page().'?dd0='.$line['id_cache'].'&dd10=RELOAD">RECOLETAR</A>';
		echo ' | ';
		echo '<A HREF="oai_ver_xml.php?dd0='.$line['id_cache'].'">VER XML</A><BR>';
		echo '<BR>';
		if ($dd[10]=='RELOAD')
			{
					$sql = "update oai_cache set cache_status = '@' where id_cache = ".round($dd[0]);
					$rlt = db_query($sql);
					redirecina(page());
			}
		
		echo '<BR>Saving...';
		$id = trim($line['cache_oai_id']);
		
		echo $oai->form_cancel();
		
		$jid = $line['cache_journal'];
				$file = 'oai/'.strzero($line['id_cache'],7).'.xml';
				echo '<BR>'.$file;
				if (!file_exists($file))
					{
						echo '<meta HTTP-EQUIV = "Refresh" CONTENT = "0; URL = '.page().'?dd0='.$line['id_cache'].'&dd1='.$dd[1].'&dd10=RELOAD"> ';
						exit;
						redirecina('oai_processar.php?dd0='.$line['id_cache'].'&dd10=RELOAD');
						exit;
					}
				
				$s = $oai->read_link_fopen($file);
				if ($oai->utf8_detect($s)==1)
					{
						echo '<BR>Codificaзгo: UTF8';
						//$s = utf8_decode($s);
					}
				//echo '<PRE>'.$s.'</pre>';
				
				/* Troca de Caracteres */
				$s = troca($s,'&lt;','<');
				$s = troca($s,'&gt;','>');
				$s = troca($s,'&quot;','"');
				$s = troca($s,' xml:lang="pt_BR">','>[pt_BR]');
				$s = troca($s,' xml:lang="pt-BR">','>[pt_BR]');
				$s = troca($s,' xml:lang="en">','>[en]');
				$s = troca($s,' xml:lang="en-US">','>[en]');
				$s = troca($s,' xml:lang="es">','>[es]');
				$s = troca($s,' xml:lang="fr">','>[fr]');
				$s = troca($s,'<p>','');
				$s = troca($s,'</p>','');
				
		/* Erros de Ob_start() */
		if (strpos($s,'<OAI-PMH') != 0)
			{
				$s = substr($s,strpos($s,'<OAI-PMH'),strlen($s));
			}

		$flt = fopen('process.xml','w+');
		//if (utf8_detect($s))
		//	{ $s = utf8_decode($s); echo '<BR>Decodificando UFT8'; }
		//$s = troca($s,'&','[e]');
		$s = troca($s,'<div id=','[div id=');
		$s = troca($s,'</div','[/div');
		$s = troca($s,'<span id','[span id=');
		$s = troca($s,'</span','[/span');
		$s = troca($s,'<p dir="ltr">','');
		
		//$s = utf8_encode($s);
		fwrite($flt,$s);
		fclose($flt);
	} else {
		echo '<h4>Fim do processamento</h4>';
		exit;
	}

/* Leitura de Arquivos XML */
$doc = new DOMDocument();
$doc->load( 'process.xml' );

$dados = $doc->getElementsByTagName( "record" ); 
foreach( $dados as $fields ) 
{ 
//  $name = $names->item(0)->nodeValue; 
  $creator= $fields->getElementsByTagName( "creator" ); 
  $title = $fields->getElementsByTagName( "title" ); 
  $description = $fields->getElementsByTagName( "description" );
  $identifier = $fields->getElementsByTagName( "identifier" );
  $source = $fields->getElementsByTagName( "source" );
  $language = $fields->getElementsByTagName( "language" );
  $data = $fields->getElementsByTagName( "date" );
  $setSpec = $fields->getElementsByTagName( "setSpec" );
  $keyword = $fields->getElementsByTagName( "subject" );
  
  $authors = values($creator);
  $titles = values($title);
  $abstract = values($description);
  $files = values($identifier);
  $keys = values($keyword);
  
  $sessao = values($setSpec);
  $issues = values($source);
  }

echo '<HR>';
	$k = array();
	for ($r=0; $r < count($keys); $r++)
		{
			$kt = $keys[$r];
			$term = $kt[0];
			$term = troca($term,'.',';');
			$term = troca($term,',',';');
			//$term = utf8_decode($term);
			$idio = $kt[1];
			$kts = splitx(';',$term);
			for ($q=0;$q < count($kts);$q++)
				{
					array_push($k,array($kts[$q],$idio));
				}
		}
$keys = $k;

/* Dados */
$oai->id = $id;
$oai->journal_id = $jid;
$issue = $issues[0][0];

/* Salvar */
if (count($titles)==0)
	{
		$oai->record_cancel($line['id_cache']);
	}

/* Processa arquivos OAI */
echo '<table width="100%" class="tabela00">';
echo '<TR><TH colspan=2><h3>Dados do coletor</h3>';

echo '<TR valign="top">';
echo '<TD align="right" width="10%">'.msg('journal_id');
echo '<TD class="tabela01">'.$oai->journal_id;

echo '<TR valign="top">';
echo '<TD align="right" width="10%">'.msg('article_oai_id');
echo '<TD class="tabela01">'.$oai->id;

/* Ignorar */
echo '<TR valign="top">';
echo '<TD align="right" width="10%">'.msg('action');
echo '<TD class="tabela01">';
echo $oai->form_cancel();

echo '<TR valign="top">';
echo '<TD align="right" width="10%">'.msg('title');
echo '<TD class="tabela01">';
	for ($r=0;$r < count($titles);$r++)
		{
			echo utf8_decode($titles[$r][0]);
			echo '<BR>';
		}
$oai->title = utf8_decode($titles[0][0]);
$oai->title_alt = utf8_decode($titles[1][0]);

$session = $sessao[0][0];
$issue = utf8_decode($issues[0][0]);


echo '<TR valign="top">';
echo '<TD align="right" width="10%">'.msg('session').' (file)';
echo '<TD class="tabela01">'.$session;

echo '<TR valign="top">';
echo '<TD align="right" width="10%">'.msg('session'). '(base)';
echo '<TD class="tabela01">'.$oai->session($session);
$session = $oai->session($session);

echo '<TR valign="top">';
echo '<TD align="right" width="10%">'.msg('issue');
echo '<TD>'.$issue;

echo '<TR valign="top">';
echo '<TD align="right" width="10%">'.msg('issue');
echo '<TD class="tabela01">'.$oai->issue_select($issue);

echo '<TR valign="top">';
echo '<TD align="right" width="10%">'.msg('authors');
echo '<TD class="tabela01">';
for ($r=0;$r < count($authors);$r++)
{
	echo utf8_decode($authors[$r][0]).'<BR>';
	$authors[$r][0] = utf8_decode($authors[$r][0]);
}

echo '<TR valign="top">';
echo '<TD align="right" width="10%">'.msg('keyword');
echo '<TD class="tabela01">';
$keywords = array();

for ($r=0;$r < count($keys);$r++)
{
	$idioma = $keys[$r][1];
//	$keys[$r][0] = utf8_decode($keys[$r][0]);
	$words = $keys[$r][0];
	array_push($keywords,array($words,$idioma));
}

for ($r=0;$r < count($keywords);$r++)
	{
		$keywords[$r][0] = utf8_decode($keywords[$r][0]);
		echo '<NOBR>'.$keywords[$r][0].' ('.$keywords[$r][1].')</nobr><BR>';
	}

	
/* arquivos */
echo '<TR valign="top">';
echo '<TD align="right" width="10%">'.msg('files');
echo '<TD class="tabela01">';

for ($r=0;$r < count($files);$r++)
	{
		$xlink = trim($files[$r][0]);
		if (substr($xlink,0,4) == 'http')
			{ $xlink = '<A HREF="'.$xlink.'" target="_new">'.$xlink.'</A>'; }
		echo '<BR>'.$xlink;
	}	
	
echo '</table>';
$task = 0;
$idioma_1t = $titles[0][1];

echo '[a]';
$oai->session = $session;
$oai->issue = utf8_decode($dd[12]);

echo '[b]';
$oai->abstract = utf8_decode($abstract[0][0]);
$oai->abstract_alt = utf8_decode($abstract[1][0]);

echo '[c]';
$oai->idioma_1 = $abstract[0][1];
$oai->idioma_2 = $abstract[1][1];

echo '[d]';
if ($idioma_1 != $idioma_1t)
	{
		$aa = $oai->idioma_2;
		$oai->idioma_2 = $oai->idioma_1;
		$oai->idioma_1 = $aa;
		
		$aa = $oai->abstract;
		$oai->abstract = $oai->abstract_alt;
		$oai->abstract_alt = $aa;
	}
echo '[e]';
if ((strlen($oai->session) < 7) and (strlen($oai->session) >= 2) and (strlen($oai->issue) > 0))
{
	echo '<HR><h3>'.utf8_decode($titles[0][0]).'</h3><HR>';
	
	echo '[f]';
	$oai->oai_save_00(utf8_decode($titles[0][0]),$oai->journal_id,$oai->id);
	echo '[g]';
	$oai->oai_save_01($oai->id);
	echo '[h]';
	$ar->updatex();
	echo '[i]';
	$codigo = $oai->oai_recupera_cod($oai->id);
	echo '[j]';
	$oai->oai_save_02($codigo,$authors);
	echo '[l]';
	$oai->oai_save_03($codigo,$keywords);
	echo '[m]';
	$oai->oai_save_04($codigo,$files);
	echo '[n]';	
	//$this->oai_save_03($art,$keys);
	echo '<BR>ID OAI:'.$oai->id;
	$oai->oai_save_99($oai->id);
	echo '<meta HTTP-EQUIV = "Refresh" CONTENT = "5; URL = '.page().'?dd1='.$dd[1].'"> ';
} else {
	echo '<HR><h1>'.$titles[0][0].'</h1><HR>';
	echo '<BR><font color="red">';
	echo '<BR>ERROR:';
	echo '<BR>Session: '.$oai->session;
	echo '<BR>Issue: '.$oai->issue;
	echo '</font>';
}
require("../foot.php");
exit;

function values($vlr)
	{
		$nm = array();
		if (count($vlr)==0) { return($nm); }
		for ($r=0;$r < 20;$r++)
			{
			$idioma = 'pt_BR';
  			$name = trim($vlr->item($r)->nodeValue);
  			if (strlen($name) > 0)
				{						
					if (substr($name,0,1)=='[')
						{
							$xpos = strpos($name,']');
							$idioma = substr($name,1,$xpos-1);
							$name = substr($name,$xpos+1,strlen($name));
							
						}
					array_push($nm,array($name,$idioma));
  				}
  			}
		return($nm);
	}

function acento_para_html($umarray){
	$comacento = array('Б','б','В','в','А','а','Г','г','Й','й','К','к','И','и','У','у','Ф','ф','Т','т','Х','х','Н','н','О','о','М','м','Ъ','ъ','Ы','ы','Щ','щ','З','з',);
	$acentohtml   = array('&Aacute;','&aacute;','&Acirc;','&acirc;','&Agrave;','&agrave;','&Atilde;','&atilde;','&Eacute;','&eacute;','&Ecirc;','&ecirc;','&Egrave;','&egrave;','&Oacute;','&oacute;','&Ocirc;','&ocirc;','&Ograve;','&ograve;','&Otilde;','&otilde;','&Iacute;','&iacute;','&Icirc;','&icirc;','&Igrave;','&igrave;','&Uacute;','&uacute;','&Ucirc;','&ucirc;','&Ugrave;','&ugrave;','&Ccedil;','&ccedil;');
	$umarray  = str_replace($comacento, $acentohtml, $umarray);
	return $umarray;
}
function html_para_acentos($umarray){
	$comacento = array('Б','б','В','в','А','а','Г','г','Й','й','К','к','И','и','У','у','Ф','ф','Т','т','Х','х','Н','н','О','о','М','м','Ъ','ъ','Ы','ы','Щ','щ','З','з',);
	$acentohtml   = array('&Aacute;','&aacute;','&Acirc;','&acirc;','&Agrave;','&agrave;','&Atilde;','&atilde;','&Eacute;','&eacute;','&Ecirc;','&ecirc;','&Egrave;','&egrave;','&Oacute;','&oacute;','&Ocirc;','&ocirc;','&Ograve;','&ograve;','&Otilde;','&otilde;','&Iacute;','&iacute;','&Icirc;','&icirc;','&Igrave;','&igrave;','&Uacute;','&uacute;','&Ucirc;','&ucirc;','&Ugrave;','&ugrave;','&Ccedil;','&ccedil;');
	$umarray  = str_replace($acentohtml, $comacento, $umarray);
	return $umarray;
}
?>
