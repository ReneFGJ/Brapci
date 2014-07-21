<?
function frase($ffr)
	{
	global $jid;
	$fsql = "select * from frases where fr_word = '".$ffr."' and journal_id = ".$jid;
	$frlt = db_query($fsql);
	$frase='';
	while ($fline = db_read($frlt))
		{
		$frase = $frase . $fline['fr_texto'];
		$frase = $frase . '<HR>'.chr(13).chr(10);
		$frase = $frase . chr(13).chr(10);
		}
	
	return($frase);
	}
?>