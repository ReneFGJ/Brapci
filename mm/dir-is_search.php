<?
echo 'Ordem alfabética<BR>';
for ($r=65;$r <= 90;$r++)
	{
	echo '<A HREF="#'.chr($r).'">';
	echo chr($r);
	echo '</A>';
	echo '&nbsp;';
	}
echo '<BR><BR>';

$sql = "select count(*) as total from diris where dis_ativo = 1 ";
$sql .= " order by dis_nome ";
$rlt = db_query($sql);
$line = db_read($rlt);
$total = $line['total'];

$metade = 50;

$sql = "select * from diris where dis_ativo = 1 ";
$sql .= " order by dis_nome ";
$rlt = db_query($sql);
$us = "X";
$cp1 = '';
$cp2 = '';
$tot = 0;
$pag = 0;
while ($line = db_read($rlt))
	{
	$tot++;
	$alias = trim($line['di_eq_bp']);
	$alias_br = trim($line['dir_nascionalidade']);
	$link = '<A HREF="dir-is_author.php?dd0='.$line['id_dis'].'" class="lt2">';
	$ns = trim($line['dir_nascionalidade']);
	$nome = trim($line['dis_nome']);
	$noas = UpperCaseSql(substr($nome,0,1));
	$cp = '';
	if ($noas != $us)
		{ 
		$cp .= '<a name="'.$noas.'"></a><BR><font class="lt3">=='.$noas.'==</font>'; 
		$us = $noas;
		}
	
	$linkx = '';
	if (($alias_br == 'bra') and (strlen($alias) == 0))
		{
		$linkx= '<font color="red">[*] ';
		}
	$cp .= '<BR>';
 	$cp .= '&nbsp;<img src="dir-is/flag_'.$ns.'.png" width="16" height="16" alt="" border="0">&nbsp;';
	$cp .= $link.$nome;
	$cp .= ' ';
	$cp .= $linkx;
	$cp .= '</A>';
	
	if ($tot > $metade)
		{ $cp2 .= $cp; } else { $cp1 .= $cp; }
	//echo '('.$ns.')';
	if ($tot == ($metade * 2))
		{
		?>
		<table width="100%">
		<TR>
		<TD width="50%"><?=$cp1;?></TD>
		<TD width="50%"><?=$cp2;?></TD>
		</TR>
		</table>
		<p style="page-break-before: always;"></p>
		<?
		$cp1 = '';
		$cp2 = '';
		$tot = 0;
		}
	}
?>
</div>