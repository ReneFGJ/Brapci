<?php
require("cab.php");

require("../_class/_class_bris.php");
$bris = new bris;


echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>'.chr(13).chr(10);
echo '<script type="text/javascript" src="../js/jquery.awesomeCloud-0.2.min.js"></script>'.chr(13).chr(10);

$ano = $dd[0];
echo '<H1>Contração de Assunto</h1>';
echo '<h3>Ano Base: '.$ano.'</h3>';

echo $bris->indicador_keys($ano,$ano);
echo $bris->show_cloud('1');
?>
		<style type="text/css">
			.wordcloud {
				border: 1px solid #036;
				height: 7in;
				margin: 0.5in auto;
				padding: 0;
				page-break-after: always;
				page-break-inside: avoid;
				width: 7in;
			}
		</style>
		
		<script>
			$(document).ready(function(){
				$("#wordcloud1").awesomeCloud({
					"size" : {
						"grid" : 16,
						"normalize" : false
					},
					"options" : {
						"color" : "random-dark",
						"rotationRatio" : 0.35,
						"printMultiplier" : 6,
						"sort" : "random"
					},
					"font" : "'Times New Roman', Times, serif",
					"shape" : "square"
				});
			});
		</script>