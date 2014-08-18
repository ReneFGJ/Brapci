<?php
require("cab.php");

require("../_class/_class_bris.php");
$bris = new bris;


echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>'.chr(13).chr(10);
echo '<script type="text/javascript" src="../js/jquery.awesomeCloud-0.2.min.js"></script>'.chr(13).chr(10);

$ano = $dd[0];
echo '<H1>Contração de Assunto</h1>';
echo '<h3>Ano Base: '.$ano.'</h3>';
if (strlen($ano)==0) { $ano = date("Y"); }
echo $bris->indicador_keys($ano-1,$ano);
echo $bris->show_cloud('1');

echo $bris->show_keyswords();
?>
		<style type="text/css">
			.wordcloud {
				border: 1px solid #036;
				height: 1024px;
				margin: 0.5in auto;
				padding: 0;
				page-break-after: always;
				page-break-inside: avoid;
				width: 800px;
			}
		</style>
		
		<script>
			$(document).ready(function(){
				$("#wordcloud1").awesomeCloud({
					"size" : {
						"grid" : 2,
						"normalize" : false
					},
					"options" : {
						"color" : "random-dark",
						"rotationRatio" : 0.15,
						"printMultiplier" : 6,
						"sort" : "random"
					},
					"font" : "'Arial','Times New Roman', Times, serif",
					"shape" : "square"
				});
			});
		</script>