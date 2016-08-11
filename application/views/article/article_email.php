<style>
	.middle {
		font-size: 15px;
	}
	.big {
		font-size: 20px;
	}
	.superbig {
		font-size: 25px;
	}
</style>
<center>
<div class="middle" style="width: 600px; padding: 20px; border-radius: 20px; border: 1px solid #000000;">
		<font style="font-size: 20px;"><b><?php echo $jnl_nome; ?></b></font><br>
		<div class="middle">v. <?php echo $ed_vol . ', n.' . $ed_nr . ', ' . $ed_ano . $pages; ?></div>
		<br>
		<div style="text-align: left;">	
		DOI: <?php echo $ar_doi; ?>
		</div>
		</br>
		</br>
		<div style="text-align: left;">		
		<?php echo $reference; ?>		
		</div>
		</br>&nbsp;
		</br>&nbsp;
		</br>
		<center><font style="font-size: 25px;"><?php echo $ar_titulo_1; ?></font></center>
		</br>&nbsp;
		</br>&nbsp;
		<div class="middle" style="width: 100%; text-align: right">
			<?php echo $authores_row; ?>
		</div>
		</br>&nbsp;
		</br>&nbsp;
		<div class="middle" style="width: 100%; text-align: justify;">
		<?php echo $ar_resumo_1; ?>
		<br>
		</div>
		<div style="text-align: left;">	
		<b>Palavras-chave: </b>
		<?php
		for ($r = 0; $r < count($keywords); $r++) {
			$l = $keywords[$r];
			$l = trim($l['kw_word']);
			if (strlen($l) > 0) {
				echo $l . '. ';
			}
		}
		?>
		</div>
		
		<?php
		if (isset($tab_descript)) { echo $tab_descript;
		}
		 ?>
		    	<br><br>
		<?php
		if (isset($tab_refer)) { echo $tab_refer;
		}
		?>
		<?php
		if (strlen($link_pdf) > 10) { echo '<a href="' . $link_pdf . '" target="_new">PDF Download</a>';
		}
		?>    
</div>
