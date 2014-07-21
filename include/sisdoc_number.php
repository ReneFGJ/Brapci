<?php
function numberformat_br($n,$r)
	{
		$rs = number_format($n,$r);
		//$rs = troca($rs,',','@');
		//$rs = troca($rs,'.',',');
		//$rs = troca($rs,'@','.');
		return($rs);
	}
?>
