<?php
class google
	{
		function mais()
			{
				$sx = '
				<!-- Place this tag where you want the +1 button to render. -->
				<div class="g-plusone" data-annotation="inline" data-width="300"></div>
				<!-- Place this tag after the last +1 button tag. -->
				<script type="text/javascript">
  					(function() {
    				var po = document.createElement(\'script\'); po.type = \'text/javascript\'; po.async = true;
    				po.src = \'https://apis.google.com/js/plusone.js\';
    				var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(po, s);
  				})();
				</script>';
				return($sx);
			}
		function google_scholar()
			{
				$cr = chr(13).chr(10);
				$hd = '<meta name="citation_title" content="The testis isoform of the phosphorylase kinase catalytic subunit (PhK-T) plays a critical role in regulation of glycogen mobilization in developing lung">';
				for ($r=0;$r < count($authors);$r++)
					{
					$hd .= $cr. '<meta name="citation_author" content="Liu, Li">';
					}
					 
				$hd .= $cr. '<meta name="citation_publication_date" content="1996/05/17">';
				$hd .= $cr. '<meta name="citation_journal_title" content="Journal of Biological Chemistry">';
				$hd .= $cr. '<meta name="citation_volume" content="271">';
				$hd .= $cr. '<meta name="citation_issue" content="20">';
				$hd .= $cr. '<meta name="citation_firstpage" content="11761">';
				$hd .= $cr. '<meta name="citation_lastpage" content="11766">';
				$hd .= $cr. '<meta name="citation_pdf_url" content="http://www.example.com/content/271/20/11761.full.pdf">';
				return($hd);
			}
		
	}
?>
