<?php
require("cab.php");
require($include."sisdoc_debug.php");
require("../include/sisdoc_data.php");

require("../_class/_class_keyword.php");
$key = new keyword;

require("../_class/_class_ticket.php");
$tk = new ticket;

require("../_class/_class_article.php");
$ar = new article;

$tk->le($dd[0]);

$act = $tk->line['tk_type'];

/* Resumo 1 */
if ($act=='A2')
	{
		$art = trim($tk->line['tk_article']);
		$fld1 = trim($tk->line['tk_field_1']);
		
		$ar->le($art);
		echo $ar->mostra();
		
		$article = $ar->codigo;
		
		/* palavas-chave */
		if ((strlen($fld1) > 0) and ($dd[1]=='3') and (strlen($acao) > 0))
			{
				$abst = $dd[2];
				$abst = troca($abst,chr(13),'');
				$abst = troca($abst,chr(10),'');
				$sql = "update  ".$ar->tabela." set ar_resumo_1 = '".$abst."'
						where id_ar = ".$ar->line['id_ar'];
				$rlt = db_query($sql);
				
				
				$tk->altera_status($dd[0],'B');
				echo 'SAVED';
				exit;
			}
		if (strlen($fld1) > 0)
			{
				echo '<h3>Resumo</h3>';
				echo $fld1;
				echo '<form action="'.page().'">';
				echo '<input type="hidden" name="dd0" value="'.$dd[0].'">';
				echo '<input type="hidden" name="dd1" value="3">';
				echo '<textarea name="dd2">'.$ar->abstract.'</textarea>';
				echo '<BR>';
				echo '<input name="acao" type="submit" value="aceitar">';
				echo '</form>';
			}
	}


/* palavras-chave */
if ($act=='A3')
	{
		$art = trim($tk->line['tk_article']);
		$fld1 = trim($tk->line['tk_field_1']);
		
		$ar->le($art);
		echo $ar->mostra();
		
		$article = $ar->codigo;
		
		/* palavas-chave */
		if ((strlen($fld1) > 0) and ($dd[1]=='3') and (strlen($acao) > 0))
			{
				$key_text = $dd[2];
				$idioma = $ar->idioma_1;
				$key->insert_keyword_in_article($article,$key_text,$idioma);
				
				$tk->altera_status($dd[0],'B');
				echo 'SAVED';
				exit;
			}
		if (strlen($fld1) > 0)
			{
				echo '<h3>Palavras-chave</h3>';
				echo $fld1;
				echo '<form action="'.page().'">';
				echo '<input type="hidden" name="dd0" value="'.$dd[0].'">';
				echo '<input type="hidden" name="dd1" value="3">';
				echo '<textarea name="dd2">'.$ar->keyword.'</textarea>';
				echo '<BR>';
				echo '<input name="acao" type="submit" value="aceitar">';
				echo '</form>';
			}
	}

require("../foot.php");
?>
