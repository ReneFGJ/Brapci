<?php
require("_class/_class_publications.php");
$res = new publications;

require("_class/_class_article.php");
$art = new article;

require("_class/_class_oai.php");
$oai = new oai;

echo date("d/m/Y H:i:s").'<BR>';

$sql = "select * from brapci_article_suporte
			where id_bs = ".round($dd[0]);
$rlt = db_query($sql);

if ($line = db_read($rlt))
{
	$url = trim($line['bs_adress']);
	$journal = $line['bs_journal_id'];

	/* Phase VIEW */
	if (strpos($url,'/view/') > 0)
		{
			$idx = $line['id_bs'];
			$arti = trim($line['bs_article']);
			$url2 = troca($url,'/view/','/download/').'/pdf';
			
			echo '<HR>'.$url2.'<hr>';
			$ok = $art->coleta_e_salva_pdf($url2,$arti,$idx,$journal);
			
			if ($ok == 1) { echo '<script> location.reload(); </script>'; }
			else 
				{
				$sx = $art->page_load($url);
				$art->pdfa = $sx;
				
				$st = '<meta name="citation_pdf_url" content="';
				$pos = strpos($sx,$st);
				if ($pos > 0)
					{
						$pos = $pos + strlen($st);
						$url2 = trim(substr($sx,($pos),300));
						$url2 = substr($url2,0,strpos($url2,'>')-2);
						
						$ok = $art->coleta_e_salva_pdf($url2,$arti,$idx,$journal);
						if ($ok == 1) { echo '<script> location.reload(); </script>'; }
							else { echo 'ERRO'; }
							
					/* Não localizado */
					} else {
						echo 'ERRO';
						exit;
					}
				
			}
			
			/* Phase II - Convert */
		}
}

?>
