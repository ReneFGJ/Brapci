<?php
//print_r($this);
?>
<table>
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
		for ($r = 0; $r < count($authors); $r++) {
			echo $authors[$r]['name'] . '<BR>';
		}
		?>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>

	<tr valign="top">
		<td class="lt0">Abstract</td>
		<td><?php
		for ($r = 0; $r < count($abstract); $r++) {
			echo $abstract[$r]['content'] . '<HR>';
		}
		?>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>

	<tr valign="top">
		<td class="lt0">Keywords</td>
		<td><?php
		for ($r = 0; $r < count($keywords); $r++) {
			echo $keywords[$r]['term'] . ' ';
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