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
					<li class="title-box2">					
						<h2>38 Revistas Indedadas, 27 ativas e 11 descontinuadas, 12.000 trabalhos indexados</h2>
					</li>
					<li><a href="#"><img src="img/dummy.png" alt="dummy"><h3>Indicadores de Eventos</h3></a></li>
					<li><a href="#"><img src="img/dummy.png" alt="dummy"><h3>Indicadores de Livros & Capítulos de Livros</h3></a></li>
					<li><a href="#"><img src="img/icone_rk_journal.png" alt="dummy"><h3>Indicadores de Revistas</h3></a></li>
					<li><a href="#"><img src="img/dummy.png" alt="dummy"><h3>Indicadores de Teses e Dissertações</h3></a></li>
					
					<li class="title-box2">
						<h2><a href="http://www.brapci.inf.br/">Brapci</a></h2>
					</li>
					<li><a href="#"><img src="img/dummy.png" alt="dummy"><h3>Indicadores de TCC</h3></a></li>
					<li><a href="#"><img src="img/dummy.png" alt="dummy"><h3>Indicadores de Relatórios</h3></a></li>
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

echo '<table width="98%" align="center" class="tabela00" border=0 cellspacing=10>';
echo '<TR><TD width="5%"></TD><td rowspan=10 width="20">&nbsp;&nbsp;</td>';
/* Journal */
echo '<TR valign="top">
		<TD><img src="img/icone_rk_journal.png"  width="80"></TD>
		<TD width="45%">
			<UL>
				<LI><A HREF="indicador_jnl_fi.php" class="lt1">Fator de Impacto</A></LI>
			</UL>
		<TD><img src="img/icone_rk_autor.png"  width="80"></TD>	
		<TD width="5%"></TD>
		<TD width="45%">
			<UL>
				<LI><A HREF="indicador_autor_prod.php" class="lt1">Produção</A></LI>
				<LI><A HREF="indicador_autor_h.php" class="lt1">Índice h</A></LI>
			</UL>
		</TD></TR>';


echo '</table>';
?>
