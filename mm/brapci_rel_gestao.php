<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('pendências','brapci_rel_pendencia.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Relatório de pendências';
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><?
$tabela = "";
$cp = array();
array_push($cp,array('$H8','','',False,False,''));
array_push($cp,array('$A8','','Gestão de base - atividade dos membors',False,True,''));
array_push($cp,array('$D8','','de:',True,True,''));
array_push($cp,array('$D8','','até:',True,True,''));
array_push($cp,array('$Q usuario_nome:usuario_codigo:select * from brapci_usuario where usuario_ativo = 1 order by usuario_nome ','','Nome individual',True,True,''));
array_push($cp,array('$C','','Selecionar todos os membros:',False,True,''));

require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');
$tab_max = 450;
$http_edit = 'brapci_rel_gestao.php';
$http_redirect = '';
?><TABLE width="<?=$tab_max?>" align="center"><TR><TD><?
editar();
?></TD></TR></TABLE>

<?
if ($saved > 0)
	{	
	$sql = "select ar_titulo_1, ed_periodo, ed_vol, ed_nr , ed_ano , jnl_nome, lg_data, lg_hora, usuario_nome, lg_artigo from log_gestao_mm ";
	$sql .= "inner join brapci_usuario on id_usuario = lg_user ";
	$sql .= "inner join brapci_article on id_ar = lg_artigo ";
	$sql .= "inner join brapci_journal on jnl_codigo = ar_journal_id ";
	$sql .= "inner join brapci_edition on ed_codigo = ar_edition ";
	$sql .= " where usuario_ativo = 1 ";
	$sql .= " and ( lg_data >= ".brtos($dd[2])." and lg_data <= ".brtos($dd[3])." ) ";
	$sql .= " order by usuario_nome, lg_data, lg_hora ";
	$rlt = db_query($sql);
	$tot = 0;
	$top = 0;
	$xnome = 'X';
	while ($line = db_read($rlt))
		{
		$nome = $line['usuario_nome'];
		if ($nome != $xnome)
			{
			$top = 0;
			if ($top > 0)
				{ $s .= '<TR><TD class="lt1" colspan="6" align="right"><B><I>sub-total '.$top.'</I></B></TD></TR>';  $top = 0; }
			$s .= '<TR><TD class="lt2" colspan="6"><B>'.$nome.'</TD></TR>';
			$xnome = $nome;
			}
		$tot++;
		$top++;
		$vol = trim($line['ed_vol']);
		$nru = trim($line['ed_nr']);
		$ano = trim($line['ed_ano']);
		if (strlen($vol) > 0) { $vol = ' v. '.$vol; }
		if (strlen($nru) > 0) { $nru = ', no '.$nru; }
		if (strlen($ano) > 0) { $ano = ', '.$ano; }
		$v = $vol. $nru. $ano;
		
		$s .= '<TR '.coluna().' valign="top">';
		$s .= '<TD><nobr>';
		$s .= stodbr($line['lg_data']);
		$s .= ' ';
		$s .= trim($line['lg_hora']);
		$s .= '<TD><nobr>';
		$s .= trim($line['jnl_nome']);
		$s .= '<TD><nobr>';
		$s .= $v;
		$s .= '<TD>';
		$s .= trim($line['ar_titulo_1']);
		}
	if ($top > 0)
		{ $s .= '<TR><TD class="lt1" colspan="6" align="right"><B><I>sub-total '.$top.'</I></B></TD></TR>';  $top = 0; }
	if ($tot > 0)
		{ $s .= '<TR><TD class="lt2" colspan="6" align="right"><B><I>Total '.$tot.'</I></B></TD></TR>'; }
	?>
	<CENTER><H1>Relatório de atividades</H1>
	<font class="lt0"><?=$dd[2].' até '.$dd[3];?></font>
	</CENTER>
	<TABLE width="98%" border=0 align="center" class="lt0">
	<?=$s;?>
	</TABLE>
	<BR><BR>
	<?
	}
?></DIV>