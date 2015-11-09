<?php
$link = '<img src="' . base_url('img/icone_url.png') . '" title="Site da publicação" height="40">';
$link = '<A HREF="' . $jnl_url . '" target=_new_' . $jnl_codigo . '">' . $link . '</A>';
?>
<div style="float: right"><?php echo $link; ?></div>

<h1><?php echo $jnl_nome . ', ' . $cidade_nome; ?></h1>
ISSN: <?php echo $jnl_issn_impresso . ', eISSN: ' . $jnl_issn_eletronico; ?>

<!-- Sobre os fasciculos -->
<table width="100%" border=1>
<tr align="center" class="bgblue">
<td align="center" colspan=2 ><?php echo "$nr EDIÇÕES | $na TRABALHOS | desde $desde | $anos anos"; ?></td>
</tr>
</table>

<table width="100%" border=0>
<tr valign="top"><td width="300">
<div style="float: right"><?php echo $logo; ?></div>
<?php echo $edicoes; ?>
</td><td><?php echo $issue_view; ?>
</td></tr>
</table>