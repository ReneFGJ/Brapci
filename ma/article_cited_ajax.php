<?php
$include = '../';
require("../db.php");
header('Content-type: text/html; charset=ISO-8859-1');

//require('../include/sisdoc_debug.php');
require('../include/sisdoc_autor.php');
function msg($x) { return($x); }
require("../_class/_class_publications.php");
$pb = new publications;
$pb->article_id = $dd[0];
$dd0= $dd[0];

require("../_class/_class_cited.php");
$cit = new cited;

$action = $dd[89];
$sx = '';

if ($action == 'cited_save')
	{
		$dd[10] = utf8_decode($dd[10]);
		$action = 'cited_edit';
		$save = round($dd[11]);
		if ($save=='1')
			{
				$art = strzero($dd[0],10);
				$cit->save_cited($dd[10],$art);
				$action = 'cited_view';
			}
		$res = new publications;			
		echo $res->show_references($art);
	}

if ($action == 'cited_edit')
	{
		$dd[10] = troca($dd[10],'  ',' ');
		$dd[10] = MAR_preparar($dd[10]);
		echo 'Insira as referências';
		echo $cit->cited_form();
		$sx = '
		<script>
				var erro = 0;
				$("#botao_cited").click(function(){
						var title1 = $("#title1").val();
						var title2 = $("#title2").val();					
						if (erro == 0)
						{
							$.post("article_cited_ajax.php?dd0='.round($dd0).'&dd89=cited_view", 
							{
								dd0: "'.$dd0.'",
								dd10: title1,
								dd11: title2,
								dd89: "cited_save"
							})
							.done(function(data) {
								$("#cited").html(data); 
							});
						}
					});
			</script>					
			';
		echo $sx;	
	}
?>
