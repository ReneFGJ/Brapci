<?
///////////////// Versao 0.0.1f de 02/08/2008
require("cab.php");

require($include.'sisdoc_colunas.php');
require($include.'sisdoc_data.php');

$sql = "select * from _messages 
		order by msg_field, msg_language
";
$rlt = db_query($sql);

$xfld = 'xxxx';
echo '<table width="100%" bgcolor="white" class="lt0" border=1 cellpadding=2 cellspacing=0>';
echo '<TR><TH>Field';
echo '<TH>English';
echo '<TH>Spanish';
echo '<TH>French';
echo '<TH>Portugues';
while ($line = db_read($rlt))
	{
		$fld = trim($line['msg_field']);
		if (strlen($fld) > 0)
		{
		if ($xfld != $fld)
			{
				$s = $line['msg_field'];
				$link = '<A href="javascript:newxy2(';
				$link .= "'message_ed_pop.php?dd2=" . page() . "&dd1=" . $s;
				$link .= "',600,300);";
				$link .= '">';
				$cor = '';
				echo '<TR>';
				echo '<TD>'.$link.$fld;
				echo '&nbsp;';
				$xfld = $fld;
			}
		$mmm = trim($line['msg_content']);
		$cor = '<font color="grey">';
		if ($mmm == $fld) { $cor = '<font color="red">'; }
		echo '<TD>'.$link.$cor.trim($line['msg_content']).'</font></A>';
		}
	}
echo '</table>';
echo '</div>';
require("foot.php");	
?>