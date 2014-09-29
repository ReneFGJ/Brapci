<?php
require ("cab.php");

echo '
		<link rel="stylesheet" type="text/css" href="css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="css/demo.css" />
		<link rel="stylesheet" type="text/css" href="css/component.css" />
		<script src="js/modernizr.custom.js"></script>

			<section class="grid-wrap">
				<ul class="grid swipe-right" id="grid">
					<li class="title-box">
						<h2>Indicadores de produção e citação</h2>
					</li>
					<li><a href="index_author.php"><img src="img/img_author_group.jpg" alt="dummy"><h3>Indicadores de Autores</h3></a></li>
					<li class="title-box">					
						<h2>38 Revistas Indedadas, 27 ativas e 11 descontinuadas</h2>
					</li>
					<li><a href="indicador_producao_ano.php?dd0='.date("Y").'"><img src="img/img_prod_journals.jpg" alt="produção"><h3>Indicador de Produção Anual</h3></a></li>
					<li class="title-box">					
						<h2>mais de 12.000 trabalhos indexados</h2>
					</li>
					<li><a href="#"><img src="img/dummy.png" alt="dummy"><h3>Indicadores de Livros & Capítulos de Livros</h3></a></li>
					<li class="title-box">					
						<h2>mais de 70.000 referências processadas</h2>
					</li>
					<li><a href="index_journal.php"><img src="img/icone_rk_journal.png" alt="dummy"><h3>Indicadores de Revistas</h3></a></li>
					<li><a href="indicador_colaboracao.php"><img src="img/img_colaboration.jpg" alt="dummy"><h3>Indicadores de Colaboração</h3></a></li>
					
					<li class="title-box">
						<h2><a href="http://www.brapci.inf.br/">Brapci</a></h2>
					</li>
					<li><a href="#"><img src="img/dummy.png" alt="dummy"><h3>Indicadores de TCC</h3></a></li>
					<li><a href="indicador_icpa.php?dd0='.(date("Y")-1).'"><img src="img/img_dispersion.jpg" alt="iCPA"><h3>Indice de Concentração de Produção por Autor (iCPA)</h3></a></li>
					<li><a href="#"><img src="img/dummy.png" alt="dummy"><h3>Indicadores de Links de Internet</h3></a></li>
				</ul>
			</section>
		</div><!-- /container -->
		<script src="js/masonry.pkgd.min.js"></script>
		<script src="js/imagesloaded.pkgd.min.js"></script>
		<script src="js/classie.js"></script>
		<script src="js/colorfinder-1.1.js"></script>
		<script src="js/gridScrollFx.js"></script>
		<script>
			new GridScrollFx( document.getElementById( \'grid\' ), {
				viewportFactor : 0.4
			} );
		</script>
';

?>
