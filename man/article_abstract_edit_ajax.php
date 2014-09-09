<?php
$include = '../';
header('Content-type: text/html; charset=ISO-8859-1');
require("../db.php");
require($include.'sisdoc_debug.php');
require("../_class/_class_message.php");

$file = '../messages/msg_'.$LANG.'.php';
$LANG = $lg->language_read();
if (file_exists($file)) { require($file); } else { echo 'message not found '.$file; }

require("../_class/_class_publications.php");
$pb = new publications;
$pb->article_id = $dd[0];
$dd0= $dd[0];

$action = $dd[89];
$sx = '';
/* Editar titulo */

/* SALVAR DADOS */
if ($action == 'abstract_save')
	{
		$sql = "select * from brapci_article where id_ar = ".$dd0;
		$rlt = db_query($sql);
		$line = db_read($rlt);

		$art = trim($line['ar_codigo']);
		$idi1 = trim($line['ar_idioma_1']);
		$idi2 = trim($line['ar_idioma_2']);
				
		$tit1 = utf8_decode($dd[10]);
		$tit1 = troca($tit1,chr(13),' ');
		$tit1 = troca($tit1,chr(10),' ');

		$tit2 = utf8_decode($dd[11]);
		$tit2 = troca($tit2,chr(13),' ');
		$tit2 = troca($tit2,chr(10),' ');
		
		
		$key1 = utf8_decode($dd[12]);
		$key1 = troca($key1,chr(13),' ');
		$key1 = troca($key1,chr(10),' ');
		$key1 = troca($key1,'. ','; ');
		
		$key2 = utf8_decode($dd[13]);
		$key2 = troca($key2,chr(13),' ');
		$key2 = troca($key2,chr(10),' ');
		$key2 = troca($key2,'. ','; ');
				
		$sql = "update brapci_article set
				ar_resumo_1 = '".$tit1."',
				ar_resumo_2 = '".$tit2."'
				where id_ar = ".round($dd0);
		$rlt = db_query($sql);
		$pb->keyword_delete($art);
		$pb->keyword_save($art,$key1,$idi1);
		$pb->keyword_save($art,$key2,$idi2);
		$action = 'abstract_view';
	}


/* MOSTRAR DADOS */
$sql = "select * from brapci_article where id_ar = ".$dd0;
$rlt = db_query($sql);
$line = db_read($rlt);

if ($action == 'abstract_view')
	{
		$editar = 1;
		$sx = $pb->show_abstract($line);
	}

if ($action == 'abstract_edit')
	{
		$art = trim($line['ar_codigo']);
		$idi1 = trim($line['ar_idioma_1']);
		$idi2 = trim($line['ar_idioma_2']);
		
		$keys1 = $pb->show_keywords_words($art,$idi1);
		$keys2 = $pb->show_keywords_words($art,$idi2);
		$sx .= msg('abstract').'<BR>';
		$sx .= '<textarea id="abs1" rows=9 style="width:100%">';
		$sx .= trim($line['ar_resumo_1']);
		$sx .= '</textarea>';
		$sx .= '<BR>';
		$sx .= msg('keywords').'<BR>';
		$sx .= '<textarea id="key1" rows=2 style="width:100%">';
		$sx .= trim($keys1);
		$sx .= '</textarea>';
		$sx .= '<BR>';
		
		$sx .= msg('abstract').' '.msg('alternative').'<BR>';
		$sx .= '<textarea id="abs2" rows=9 style="width:100%">';
		$sx .= trim($line['ar_resumo_2']);
		$sx .= '</textarea>';
		$sx .= '<BR>';
		$sx .= msg('keywords').' '.msg('alternative').'<BR>';	
		$sx .= '<textarea id="key2" rows=2 style="width:100%">';
		$sx .= trim($keys2);
		$sx .= '</textarea>';
		$sx .= '<BR>';
		
		$sx .= '<input id="abs_save" type="button" value="'.msg('save').'" class="botao-geral">';
		$sx .= ' | ';

		$sx .= '<input id="abs_cancel" type="button" value="'.msg('cancel').'" class="botao-geral">';
		$sx .= '
		<script>
					$("#abs_cancel").click(function(){
						$.ajax("article_abstract_edit_ajax.php?dd0='.round($dd0).'&dd89=abstract_view")
						 	.done(function(data) { $("#abstract").html(data); })
							.fail(function() { alert("error"); });
					});	
					
    				$("#abs_save").click(function(){
						var title1 = $("#abs1").val();
						var title2 = $("#abs2").val();

						var title3 = $("#key1").val();
						var title4 = $("#key2").val();
						
						$.post("article_abstract_edit_ajax.php?dd0='.round($dd0).'", 
						{
							dd0: "'.$dd0.'",
							dd10: title1,
							dd11: title2,
							dd12: title3,
							dd13: title4,
							dd89: "abstract_save",
						})
						.done(function(data) {
								$("#abstract").html(data); 
						});
					});								
		</script>
		';	
	}
echo $sx;
?>
