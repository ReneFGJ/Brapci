<?
require("cab.php");
require('../messages/msg_perfil_ver.php');

global $acao,$dd,$cp,$tabela;
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_debug.php');
require("../_class/_class_perfil.php");

	$clx = new perfil;
	$cp = $clx->cp();
	$tabela = $clx->tabela;
	
	if (strlen($dd[0])==0)
		{ exit; }
	$clx->le($dd[0]);
	
	$label = msg("perfil");
	
/* Nomear usuário */
if (strlen($dd[5] == '1'))
	{
		$ok = $clx->perfil_usuario_nomear($dd[0],$dd[1]);
		if ($ok == 1) { $msg = 'Nomeado com sucesso'; }
	}
/* Destituir usuário */
if (strlen($dd[5] == '0'))
	{
		/* Nomear usuário */
		$ok = $clx->perfil_usuario_destituir($dd[0],$dd[1]);
		if ($ok == 1) { $msg =  'Destituído com sucesso'; }
	}

/********************************** Dados */
$ativo = $clx->perfil_usuario_ativos();
$inativo = $clx->perfil_usuario_inativos();

$sx = ''; $sy = '';	
for ($r=0;$r < count($ativo);$r++)
	{
		$sx .= '<TR '.coluna().'>';
		$sx .= '<TD>';
		$sx .= $ativo[$r][0];
		$sx .= '<TD>';
		$sx .= $ativo[$r][1];
		$sx .= '<TD>';
		$sx .= $ativo[$r][2];
		$sx .= '<TD align="center">';
		$link = '<a href="perfil_ver.php?dd5=0&dd1='.$ativo[$r][3].'&dd0='.$clx->per_codigo.'&dd90='.$dd[90].'">';
		$link .= 'destituir</A>';
		$sx .= $link;
	}

if (strlen($sx) == 0) { $sx = '<TR><TD colspan="3" align="center">** VAZIO **'; }

for ($r=0;$r < count($inativo);$r++)
	{
		$sy .= '<TR '.coluna().'>';
		$sy .= '<TD>';
		$sy .= $inativo[$r][0];
		$sy .= '<TD>';
		$sy .= $inativo[$r][1];
		$sy .= '<TD>';
		$sy .= $inativo[$r][2];
		$sy .= '<TD align="center">';
		$link = '<a href="perfil_ver.php?dd5=1&dd1='.$inativo[$r][3].'&dd0='.$clx->per_codigo.'&dd90='.$dd[90].'">';
		$link .= 'nomear</A>';
		$sy .= $link;
	}

if (strlen($sy) == 0) { $sy = '<TR><TD colspan="3" align="center">** VAZIO **'; }

?>
<table width="<?=$tab_max;?>">
	<TR><TH colspan="3"><H1><?=msg('ativados');?></TR>
	<TR><TH>Usuário
		<TH>Código
		<TH>Cracha
	</TR>
	<?=$sx;?>

	<TR><TH colspan="3"><H1><?=msg('inativados');?></TR>
	<TR><TH>Usuário
		<TH>Código
		<TH>Cracha
	</TR>
	<?=$sy;?>
</table>
<?
require("../foot.php");		
?> 