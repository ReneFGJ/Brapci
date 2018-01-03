<?php
$command = "python hello.py 2>&1";
#$command = "python --version 2>&1";
$pid = popen($command, "r");
while (!feof($pid)) {
	echo fread($pid, 256);
	flush();
	ob_flush();
	usleep(100000);
}
pclose($pid);
?>