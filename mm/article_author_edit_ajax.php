<?php
$include = '../';
header('Content-type: text/html; charset=ISO-8859-1');
require("../db.php");
require($include.'sisdoc_debug.php');
require($include.'sisdoc_autor.php');
require("../_class/_class_message.php");

$file = '../messages/msg_'.$LANG.'.php';
$LANG = $lg->language_read();
if (file_exists($file)) { require($file); } else { echo 'message not found '.$file; }

require("../_class/_class_publications.php");
$pb = new publications;

require("../_class/_class_author.php");
$au = new author;

$pb->article_id = $dd[0];
$dd0= $dd[0];

$action = $dd[89];
$sx = '';
/* Editar titulo */

/* SALVAR DADOS */
if ($action == 'author_save')
	{
		$author = $au->author_find($dd[10]);
		
		$sql = "select * from brapci_article where id_ar = ".$dd0;
		$rrr = db_query($sql);
		if ($line = db_read($rrr))
			{
				$jid = $line['ar_journal_id'];
				$art = $line['ar_codigo'];
				$pos = $au->author_next_pos($art);
				$au->author_article_save($art,$author,$pos,$jid);
			}
		$action = 'author_view';
	}


/* MOSTRAR DADOS */
$sql = "select * from brapci_article where id_ar = ".$dd0;
$rlt = db_query($sql);
$line = db_read($rlt);

if ($action == 'author_view')
	{
		$editar = 1;
		$sx = $au->show_author($dd0,1);
	}

if ($action == 'author_edit')
	{
		$art = trim($line['ar_codigo']);
		$idi1 = trim($line['ar_idioma_1']);
		
		$keys1 = $pb->show_keywords_words($art,$idi1);
		$sx .= msg('author').'<BR>';
		$sx .= '<BR>';
		
		$sx .= '<input type="text" size="80" maxsize="100" id="author1">';
		
		$sx .= '<BR>';
		$sx .= '<input id="abs_save" type="button" value="'.msg('save').'" class="botao-geral">';
		$sx .= ' | ';

		$sx .= '<input id="abs_cancel" type="button" value="'.msg('cancel').'" class="botao-geral">';
		$sx .= '
		<script>
					$("#abs_cancel").click(function(){
						$.ajax("article_author_edit_ajax.php?dd0='.round($dd0).'")
						 	.done(function(data) { $("#authors").html(data); })
							.fail(function() { alert("error"); });
					});	
					
    				$("#abs_save").click(function(){
						var title1 = $("#author1").val();
						$.post("article_author_edit_ajax.php?dd0='.round($dd0).'", 
						{
							dd0: "'.$dd0.'",
							dd10: title1,
							dd89: "author_save"
						})
						.done(function(data) {
								$("#authors").html(data); 
						});
					});								
		</script>
		';	
	}
echo $sx;
?>
