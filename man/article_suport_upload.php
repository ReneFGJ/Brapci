<?php
require("cab.php");

require("../_class/_class_issue.php");

require("../_class/_class_author.php");

require("../_class/_class_publications.php");
$res = new publications;

require('../include/sisdoc_windows.php');

$art = $dd[0];
$editar = 1;
$res->article_id = $art;
echo $res->show_article($art);

echo $res->show_files($art);

require("../_class/_class_article.php");
$art = new article;
$art->page_load_type = 'F';

echo date("d/m/Y H:i:s").'<BR>';



if (strlen($acao) > 0)
			{
				$arti = $res->line['ar_codigo'];
				$journal = $res->line['ar_journal_id'];
				$idx = $res->line['id_ar'];

			    $nome = lowercasesql($_FILES['arquivo']['name']);
				$temp = $_FILES['arquivo']['tmp_name'];
				$size = $_FILES['arquivo']['size'];
					
						
				/* caso n�o apresente erro */
					if (strlen($erro)==0) 
						{
							print_r($res);
							echo '<BR>File:'.$temp;
							echo '<BR>arti = '.$arti;
							echo '<BR>idx = '.$idx;
							echo '<BR>journal = '.$journal;
							echo 'SALVA';
							$ok = $art->coleta_e_salva_pdf($temp,$arti,$idx,$journal);
						} else {
							echo '<center>'.msg($erro).'</center>';
						}
						
				}


			

echo '
<fieldset class="fieldset01"><legend class="legend01">uPLOAD</legend>
					<form id="upload" action="'.page().'" method="post" enctype="multipart/form-data"> 
    				<span id="post"><input type="file" name="arquivo" id="arquivo" /></span>
    				<input type="hidden" name="dd0" value="'.$dd[0].'"> 
    				<input type="hidden" name="dd1" value="'.$dd[1].'">
    				<input type="hidden" name="dd50" value="'.$dd[50].'">
    				<input type="hidden" name="dd51" value="'.$dd[51].'"> 
    				<input type="hidden" name="dd90" value="'.$dd[90].'"> 
    				<input type="submit" value="enviar arquivo" name="acao" id="idbotao" />
    				</fieldset>  
';
?>
