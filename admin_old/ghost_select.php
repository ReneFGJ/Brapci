<?
require("cab.php");

require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');
require("../_class/_class_docentes.php");

	$clx = new docentes;
	$tabela = $clx->tabela;
	$clx->le($dd[0]);

	$email = trim($clx->pp_email);
	$email = substr($email,0,strpos($email,'@'));
	
	$cracha = $clx->line['pp_cracha'];
	
	$sql = "select * from usuario where us_cracha = '".$cracha."' ";
	$rlt = db_query($sql);
	$line = db_read($rlt);
	
	$nw->user_login = UpperCase(trim($email));
	$nw->user_nome = trim($clx->pp_nome);
	$nw->user_nivel = 1;
	$nw->user_cracha = trim($clx->pp_cracha);
	$nw->user_perfil = trim($line['us_perfil']);;
	$nw->user_erro = 10;
	$nw->user_ss = trim($clx->pp_ss);
	$nw->LiberarUsuario();
	echo 'Modo Ghost Ativado';
	redirecina(http.'main.php');
?>
<script>
	$("#content").corner();
</script>

<? require("foot.php"); ?>