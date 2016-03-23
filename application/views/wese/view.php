<?php
$admin = 1;
?>
<table width="100%">
	<tr><td class="lt6" colspan=5 ><b><?php echo $t_name; ?></b>
		<span class="bg_grey radius5 lt0 pad5"><?php echo $t_lang; ?></span>		
	</td></tr>
	<tr><td class="lt0" colspan=2 ><a href="<?php echo $uri; ?>" class="link lt0"><?php echo $uri; ?></a>
	</td></tr>
	<tr><td><br><br></td></tr>
	<tr valign="top">
		<td width="30%">
			<?php
			if ((count($boader) > 0) or ($admin == 1)) {
				echo '<div class="border1 radius5 bg_lblue pad5">';
				if ($admin == 1) {
					echo '<a href=""><img src="' . base_url('img/icon/icone_mais.png') . '" width="24" align="right" border=0></A>';
				}
				echo '<B>' . msg('broader_concept') . '</B>
				<br><br>';

				for ($r = 0; $r < count($boader); $r++) {
					$id2 = $boader[$r]['tg_conceito_1'];
					$link = '<a href="' . base_url('index.php/skos/t/' . $id2) . '" class="link lt2 blue">';
					echo '<span style="margin-left: 20px;">';
					echo 'TG' . '</font> ' . $link . $boader[$r]['t_name'] . '</a>';
					echo '<br>';
					//print_r($narrow[$r]);
				}
				echo '<br></div><br>';
			}
			?>
			<!------------------------------ NARROW ----------------------->			
			<?php
			if ((count($narrow) > 0) or ($admin == 1)) {
				echo '
				<div class="border1 radius5 bg_lblue pad5">';
				if ($admin == 1) {
					echo '<a href=""><img src="' . base_url('img/icon/icone_mais.png') . '" width="24" align="right" border=0></A>';
				}
				echo '<B>' . msg('narrower_concept') . '</B>
				<BR><BR>';
				for ($r = 0; $r < count($narrow); $r++) {
					$id2 = $narrow[$r]['tg_conceito_2'];
					$link = '<a href="' . base_url('index.php/skos/t/' . $id2) . '" class="link lt2 blue">';
					echo '<span style="margin-left: 20px;">';
					echo 'TE1' . '</font> ' . $link . $narrow[$r]['t_name'] . '</a>';
					echo '<br>';
				}
				echo '<br></div><br>';
			}
			?>
			<div class="border1 radius5 bg_lblue pad5"><B><?php echo msg('related_concept'); ?></B></div>
		</td>
		<td width="1%">&nbsp;</td>
		<td width="30%">
		<!------------------------------- PREF ------------------>		
			<?php
			if ((count($pref) > 0) or ($admin == 1)) {
				echo '
				<div class="border1 radius5 bg_lgreen pad5">';
				if ($admin == 1) {
					echo '<a href=""><img src="' . base_url('img/icon/icone_mais.png') . '" width="24" align="right" border=0></A>';
				}
				echo '<B>' . msg('pref_label') . '</B>
				<BR>';
				for ($r = 0; $r < count($pref); $r++) {
					$id2 = $pref[$r]['l_concept_id'];
					$link = '<a href="' . base_url('index.php/skos/t/' . $id2) . '" class="link lt2 blue">';
					echo '<br>';
					echo '<span style="margin-left: 20px;">';
					echo '</font> ' . $link . $pref[$r]['t_name'] . '</a>';
					echo '&nbsp;<span class="bg_grey radius5 lt0 pad5">' . $pref[$r]['t_lang'] . '</span>';
					echo '<br>';

				}
			}
			?>
			<br>
			</div>
			<br>
		<!------------------------------- UP ------------------>
			<?php
			if ((count($used) > 0) or ($admin == 1)) {
				echo '
				<div class="border1 radius5 bg_lgreen pad5">';
				if ($admin == 1) {
					$idc = $l_concept_id;
					echo '<a href="#" onclick="newwin(\'' . base_url('index.php/skos/ed/' . $idc . '/ALT') . '\',400,400);">';
					echo '<img src="' . base_url('img/icon/icone_mais.png') . '" width="24" align="right" border=0></A>';
				}
				echo '<B>' . msg('alt_label') . '</B>
				<BR><BR>';

				for ($r = 0; $r < count($used); $r++) {
					$id2 = $used[$r]['l_concept_id'];
					$link = '<a href="' . base_url('index.php/skos/t/' . $id2) . '" class="link lt2 blue">';
					echo '<span style="margin-left: 20px;">';
					echo 'UP' . '</font> ' . $link . $used[$r]['t_name'] . '</a>';
					echo '<br>';
				}

			}
			?>				
			<br>	
			</div>
			<br>

		<!------------------------------- PREF ------------------>		
			<?php
			if ((count($hidden) > 0) or ($admin == 1)) {
				echo '
				<div class="border1 radius5 bg_lgreen pad5">';
				if ($admin == 1) {
					$idc = $l_concept_id;
					echo '<a href="#" onclick="newwin(\'' . base_url('index.php/skos/ed/' . $idc . '/HIDDEN') . '\',400,400);">';
					echo '<img src="' . base_url('img/icon/icone_mais.png') . '" width="24" align="right" border=0></A>';
				}

				echo '<B>' . msg('hidden_label') . '</B>
				<BR><BR>';
				for ($r = 0; $r < count($hidden); $r++) {
					$id2 = $hidden[$r]['l_concept_id'];
					$link = '<a href="' . base_url('index.php/skos/t/' . $id2) . '" class="link lt2 blue">';
					echo '<span style="margin-left: 20px;">';
					echo 'UPH' . '</font> ' . $link . $hidden[$r]['t_name'] . '</a>';
					//echo '&nbsp;<span class="bg_grey radius5 lt0 pad5">'.$used[$r]['t_lang'].'</span>';
					echo '<br>';
				}
			}
			?>			
			<br>
		</td>
		<td width="1%">&nbsp;</td>
		<td width="30%">
			<?php
			echo '<div class="border1 radius5 bg_lred pad5"><B>';
			if ($admin == 1) {
				if (strlen($id_wn) > 0)
					{
						echo '<a href="#" onclick="newwin(\'' . base_url('index.php/skos/edn/' . $id_wn . '/') . '\',400,400);">';
						echo '<img src="' . base_url('img/icon/icone_edit.png') . '" width="24" align="right" border=0></A>';						
					} else {
						echo '<a href="#" onclick="newwin(\'' . base_url('index.php/skos/ed/' . $idc . '/NOTE') . '\',400,400);">';
						echo '<img src="' . base_url('img/icon/icone_mais.png') . '" width="24" align="right" border=0></A>';						
					}
			}

			echo msg('comment');
			echo '</b><br><br>';
			echo '<div style="padding: 5px; text-align: left;">';
			echo mst($wn_note);
			echo '</div>';
			?>
			<br>
			<br>
			</div>
		</td>
	</tr>
</table>
