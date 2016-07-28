<div class="container">
	<table>
	<tr valign="top">
		<td class="lt0">File</td>
		<td><?php echo $file; ?>
	</tr>
	<tr valign="top">
		<td class="lt0">Issue ID</td>
		<td><?php
		switch ($issue_id) {
			case '0' :
				echo '<a href="' . base_url('index.php/admin/journal_view/' . $id_jnl . '/' . checkpost_link($id_jnl)) . '" target="_blank">';
				echo '<span class="btn btn-success">Ver todas as edições</span>';
				echo '</a>&nbsp;';

				echo '<a href="' . base_url('index.php/admin/issue_edit/0/' . $id_jnl) . '">';
				echo '<span class="btn btn-default">Registrar nova edição</span>';
				echo '</a>';
				echo '<br>';
				echo $issue_ver;
				break;
			case '9999999' :
				echo '<font color="red">** ISSUE Bloqueado **</font>';
				break;
			default :
				echo 'Open Edition ' . $issue_id;
				break;
		}
		?>
	</tr>
	<tr valign="top">
		<td class="lt0">setSpec</td>
		<td><?php echo $setSpec; ?> / <?php echo $idf; ?>
	</tr>
	<tr valign="top">
		<td class="lt0">Section</td>
		<td><?php echo $section; ?></td>
	</tr>	
	<tr valign="top">
		<td class="lt0">Titles</td>
		<td><?php
		for ($r = 0; $r < count($titles); $r++) {
			echo $titles[$r]['title'] . ' (' . $titles[$r]['idioma'] . ')' . '<BR>';
		}
		?>
	</tr>
	
	

	<tr valign="top">
		<td class="lt0">Authors</td>
		<td><?php
		if (isset($authors)) {
			for ($r = 0; $r < count($authors); $r++) {
				echo $authors[$r]['name'] . '<BR>';
			}
		}
		?>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>

	<tr valign="top">
		<td class="lt0">Abstract</td>
		<td><?php
		if (isset($abstract)) {
			for ($r = 0; $r < count($abstract); $r++) {
				echo $abstract[$r]['content'] . '<HR>';
			}
		}
		?>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>

	<tr valign="top">
		<td class="lt0">Keywords</td>
		<td><?php
		if (isset($keywords)) {
			for ($r = 0; $r < count($keywords); $r++) {
				echo $keywords[$r]['term'] . ' ';
			}
		}
		?>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>

	<tr valign="top">
		<td class="lt0">Source</td>
		<td><?php
		for ($r = 0; $r < count($sources); $r++) {
			echo $sources[$r]['source'] . '<BR>';
		}
		?>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>

	<tr valign="top">
		<td class="lt0">Identifier</td>
		<td><?php
		for ($r = 0; $r < count($links); $r++) {
			echo $links[$r]['link'] . '<BR>';
		}
		?>
	</tr>
</table>
<?php
if ($issue_id > 0) {
	echo 'REFRESH em 1 seg.';
	echo '<meta http-equiv="refresh" content="1">' . cr();
}
?>
</div>