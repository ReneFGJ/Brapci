<?php
if ((isset($filename)) or (strlen($filename) == 0)) {
	if (isset($content)) {

		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-type: application/x-msexcel; charset=utf-8");
		header("Content-Disposition: attachment; filename=".$filename);
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private", false);

		echo ($content);
	}
} else {
	echo 'filename is not seted';
}
?>
