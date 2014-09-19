<?php
require("cab.php");

echo '<h1>Indicadores<h1>';
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

/* Livro */
echo '<TR valign="top">
		<TD><img src="img/icone_rk_livro.png" width="80"></TD>
		<TD>
			<UL>
			</UL>
		</TD></TR>';	
		
/* Capitulos Livro */
echo '<TR valign="top">
		<TD><img src="img/icone_rk_capitulo.png"  width="80"></TD>
		<TD>
			<UL>
			</UL>
		</TD></TR>';	

/* Evento */
echo '<TR valign="top">
		<TD><img src="img/icone_rk_evento.png"  width="80"></TD>
		<TD>
			<UL>
			</UL>
		</TD></TR>';	

/* Tese */
echo '<TR valign="top">
		<TD><img src="img/icone_rk_tese.png"  width="80"></TD>
		<TD>
			<UL>
			</UL>
		</TD></TR>';	
		

echo '</table>';
?>
