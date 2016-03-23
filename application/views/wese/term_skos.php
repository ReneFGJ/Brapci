<br>
<br>
<br>
<center>
	<table width="1024" cellspacing="0" cellpadding="0" border=0>
		<tr valign="middle">
			<td width="29%"><?php
			for ($r = 0; $r < count($border); $r++) {
				$line = $border[$r];
				$link = base_url('index.php/skos/thema/' . $line['sh_initials'] . '/' . $line['c_id']);
				$link = '<a href="' . $link . '" class="link">';
				$sx = $link . '<div class="term_skos_narrow">';
				$sx .= $line['t_name'];
				$sx .= '</div></a>' . cr();
				echo $sx;
			}
			?></td>
			<td width="5%">
			<hr>
			</td>
			<td width="33%" colspan=3>
			<div class="term_skos_concept">
				<?php echo $t_name; ?>
			</div></td>
			<td width="5%">
			<hr>
			</td>
			<td width="29%"><?php
			for ($r = 0; $r < count($narrow); $r++) {
				$line = $narrow[$r];
				$link = base_url('index.php/skos/thema/' . $line['sh_initials'] . '/' . $line['c_id']);
				$link = '<a href="' . $link . '" class="link">';
				$sx = $link . '<div class="term_skos_narrow">';
				$sx .= $line['t_name'];
				$sx .= '</div></a>' . cr();
				echo $sx;
			}
			?></td>
		</tr>
	<tr valign="top">
		<td colspan=2>			
			<!-- alternative labels -->
			<?php
			echo '<font class="lt3"><b>'.msg('used_by').'</b></font>';
			echo '<uL>';
			for ($r = 0; $r < count($used); $r++) {
				$line = $used[$r];
				$sx = '<li>'.$line['t_name'].'</li>';
				$sx .= cr();
				echo $sx;
			}
			?></td>
			
		<td colspan=2>			
			<!-- alternative labels -->
			<?php
			echo '<font class="lt3"><b>'.msg('term_hidden').'</b></font>';
			echo '<uL>';
			for ($r = 0; $r < count($hidden); $r++) {
				$line = $hidden[$r];
				$sx = '<li>'.$line['t_name'].'</li>';
				$sx .= cr();
				echo $sx;
			}
			?></td>
			<td>
				<a href="<?php echo base_url('index.php/skos/thema_edit/'.$scheme.'/'.$concept);?>" class="lt1 link">edit</a>
			</td>			
	</tr>		
	</table>
	
</center>