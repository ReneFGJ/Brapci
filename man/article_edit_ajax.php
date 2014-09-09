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
if ($action == 'title_save')
	{
		$tit1 = utf8_decode($dd[10]);
		$tit1 = troca($tit1,chr(13),' ');
		$tit1 = troca($tit1,chr(10),' ');
				
		$tit2 = utf8_decode($dd[11]);
		$tit2 = troca($tit2,chr(13),' ');
		$tit2 = troca($tit2,chr(10),' ');
				
		$tit3 = $dd[12];
		$tit4 = $dd[13];
		$tit5 = $dd[14];
		$pag1 = $dd[15];
		$pag2 = $dd[16];
		$sql = "update brapci_article set
				ar_titulo_1 = '".$tit1."',
				ar_titulo_1_asc = '".UpperCaseSql($tit1)."',
				ar_titulo_2 = '".$tit2."',
				ar_idioma_1 = '".$tit3."',
				ar_idioma_2 = '".$tit4."',
				ar_pg_inicial = '".$pag1."',
				ar_pg_final = '".$pag2."',
				ar_tipo = '".$tit5."',
				ar_section = '".$tit5."'				
				where id_ar = ".round($dd0);
		$rlt = db_query($sql);
		$action = 'title_view';
	}


/* MOSTRAR DADOS */
$sql = "select * from brapci_article where id_ar = ".$dd0;
$rlt = db_query($sql);
$line = db_read($rlt);

if ($action == 'title_view')
	{
		$editar = 1;
		$sx = $pb->show_title($line);
	}

if ($action == 'title_edit')
	{
		$section = trim($line['ar_tipo']);
		$sx .= msg('section').'<BR>';
		$sx .= '<select id="section">';
		$sx .= '<option value=""></option>';
		$sx .= $pb->section_option_form($section);
		$sx .= '</select><BR><BR>';
		$sx .= msg('title_article').'<BR>';
		$sx .= '<textarea id="tit1" rows=5 style="width:100%">';
		$sx .= trim($line['ar_titulo_1']);
		$sx .= '</textarea>';
		$sx .= '<BR>';
		$chk1  = ''; $chk2 = ''; $chk3 = ''; $chk4 = '';
		if (trim($line['ar_idioma_1']) == 'pt_BR') { $chk1 = 'selected'; }
		if (trim($line['ar_idioma_1']) == 'us') { $chk2 = 'selected'; }
		if (trim($line['ar_idioma_1']) == 'es') { $chk3 = 'selected'; }
		if (trim($line['ar_idioma_1']) == 'fr') { $chk4 = 'selected'; }
		if (trim($line['ar_idioma_1']) == 'en') { $chk2 = 'selected'; }
		
		$sx .= '<select id="idioma1">.chr(13)';
		$sx .= '<option value=""></option>'.chr(13);
		$sx .= '<option value="pt_BR" '.$chk1.'>'.msg('Português').'</option>'.chr(13);
		$sx .= '<option value="us" '.$chk2.'>'.msg('Inglês').'</option>'.chr(13);
		$sx .= '<option value="es" '.$chk3.'>'.msg('Espanhol').'</option>'.chr(13);
		$sx .= '<option value="fr" '.$chk4.'>'.msg('Francês').'</option>'.chr(13);
		$sx .= '</select>'.chr(13);
		
		$sx .= '<BR><BR>';
		$sx .= msg('title_article').' '.msg('alternative').'<BR>';
		$sx .= '<textarea id="tit2" rows=5 style="width:100%">';
		$sx .= trim($line['ar_titulo_2']);
		$sx .= '</textarea>';
		$sx .= '<BR>';	

		$chk1  = ''; $chk2 = ''; $chk3 = ''; $chk4 = '';
		if (trim($line['ar_idioma_2']) == 'pt_BR') { $chk1 = 'selected'; }
		if (trim($line['ar_idioma_2']) == 'us') { $chk2 = 'selected'; }
		if (trim($line['ar_idioma_2']) == 'es') { $chk3 = 'selected'; }
		if (trim($line['ar_idioma_2']) == 'fr') { $chk4 = 'selected'; }
		
		$sx .= '<select id="idioma2">.chr(13)';
		$sx .= '<option value=""></option>'.chr(13);
		$sx .= '<option value="pt_BR" '.$chk1.'>'.msg('Português').'</option>'.chr(13);
		$sx .= '<option value="us" '.$chk2.'>'.msg('Inglês').'</option>'.chr(13);
		$sx .= '<option value="es" '.$chk3.'>'.msg('Espanhol').'</option>'.chr(13);
		$sx .= '<option value="fr" '.$chk4.'>'.msg('Francês').'</option>'.chr(13);
		$sx .= '</select>'.chr(13);
		$sx .= '<BR><BR>';
		$pag1 = $line['ar_pg_inicial'];
		$pag2 = $line['ar_pg_final'];
		
		$sx .= 'Páginação: ';
		$sx .= '<input type="text" id="pag1" size=5 maxsize=5 value="'.$pag1.'">';
		$sx .= ' até <input type="text" id="pag2" size=5 maxsize=5 value="'.$pag2.'">';
		$sx .= '<BR>';	
		$sx .= '<BR>';	
		$sx .= '<input id="title_save" type="button" value="'.msg('save').'" class="botao-geral">';
		$sx .= ' | ';
		 
		$sx .= '<input id="title_cancel" type="button" value="'.msg('cancel').'" class="botao-geral">';
		$sx .= '
		<script>
					$("#title_cancel").click(function(){
						$.ajax("article_edit_ajax.php?dd0='.round($dd0).'&dd89=title_view")
						 	.done(function(data) { $("#titles").html(data); })
							.fail(function() { alert("error"); });
					});	
					
    				$("#title_save").click(function(){
						var title1 = $("#tit1").val();
						var title2 = $("#tit2").val();
						var title3 = $("#idioma1").val();
						var title4 = $("#idioma2").val();
						var title5 = $("#section").val();
						var title6 = $("#pag1").val();
						var title7 = $("#pag2").val();
						var erro = 0;
						
						if (title5.length==0) { alert("'.msg('section_not_informed').'"); erro = 1; }
						if (title4.length==0) { alert("'.msg('second_langage_not_informed').'"); erro = 1; }
						if (title3.length==0) { alert("'.msg('first_langage_not_informed').'"); erro = 1; }
						
						if (erro == 0)
						{
							$.post("article_edit_ajax.php?dd0='.round($dd0).'&dd89=title_view", 
							{
								dd0: "'.$dd0.'",
								dd10: title1,
								dd11: title2,
								dd12: title3,
								dd13: title4,
								dd14: title5,
								dd15: title6,
								dd16: title7,
								dd89: "title_save"
							})
							.done(function(data) {
								$("#titles").html(data); 
							});
						}
					});								
		</script>
		';	
	}
echo $sx;
?>
