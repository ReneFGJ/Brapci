<B><I>Descritores</I></B>
<?
$dd[2] = trim($dd[2]);
if (strlen($dd[2]) > 0)
	{
	$idioma = $dd[4];
	$termo = trim($dd[2]);
	$termo_asc = trim(uppercasesql($dd[2]));
	require("descriptor_append.php");
	}
$dd[1] = trim($dd[1]);

$sql = "select * from tci_keyword ";
if (strlen($dd[1]) > 0)
	{ $sql .= " where kw_word_asc like '%".trim(uppercasesql($dd[1]))."%' "; }
$sql .= " order by kw_word_asc ";
$sql .= " limit 100 ";
$rlt = db_query($sql);
$s = '';
$ini = 0;
while ($line = db_read($rlt))
	{
	$ini++;
	$lnk = '<A HREF="descriptor_key.php?dd0='.$line['kw_codigo'].'">';
	$sw = $lnk.$line['kw_word'].'</A>';
	$s = '<BR>'.$sw;
	$s .= '<font class="lt0"> ('.trim($line['kw_idioma']).')</font> ';
	if ($ini < 50)
		{
		$c1 .= $s;
		} else {
		$c2 .= $s;
		}
	}
?>
<table>
<tr valign="top">
<TD><form method="get"><input type="text" name="dd1" value="<?=$dd[1];?>" size="40" maxlength="100">
<TD><input type="submit" name="acao" value="pesquisar descritor >>>"></TD>
<TD></form></TD></tr>
</table>

<table>
<tr valign="top">
<TD><form method="get"><input type="text" name="dd2" value="" size="40" maxlength="100">
<TD><input type="submit" name="acao" value="cadastrar novo descritor >>>"></TD>
<TD>
<select name="dd4" size="1">
<option value="pt_BR">Portugues (Brasil)</option>
<option value="en">Inglês</option>
<option value="es">Espanhol</option>
<option value="fr">Francês</option>
</select></TD>
<TD></form></TD></tr>
</table>


<table width="100%">
<TR valign="top"><TD width="50%"><?=$c1;?></TD><td width="50%"><?=$c2;?></td></TR>
</table>