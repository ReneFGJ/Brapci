<?php
$aa = '';

for ($r=0;$r < count($autor_alias);$r++)
{
	$line = $autor_alias[$r];
	$aa .= '<li  style="margin-left: 30px;">'.$line['autor_nome'].'</li>';
}
if (strlen($aa) > 0)
	{
		$aa = '<b>'.msg('used_for').'</b><ul>'.$aa.'</ul>';
	}
if (strlen($autor_coautores) > 0)
	{
		$autor_coautores = '<b>'.msg('coautors').'</b><ul>'.$autor_coautores.'</ul>';
	}	
?>
<table width="100%" class="tabela00">
	<tr valign="top"><td rowspan="10" width="150">PICTURE</td>
		<td class="lt3" style="border-bottom: 2px solid #333333;" colspan=3><b><?php echo $autor_nome;?></b></td>
	</tr>
	<tr valign="top">
		<td width="60%"><?php echo $producao;?></td>
		<td width="30%"><?php echo $aa;?>
						<br>
						<?php echo $autor_coautores;?>			
		</td>
	</tr>
</table>
