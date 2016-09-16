<?php
if (isset($content)) {
	echo $content;
	echo '<hr>';
}
if (isset($excluir)) { echo '<a href="' . base_url($link . '/' . $id) . '" class="btn btn-danger">Excluir</a>';
}
?>

