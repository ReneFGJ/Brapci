<?
require("cab.php");
require($include.'sisdoc_data.php');
require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');
require("../_class/_class_usuario.php");
$uss = new users;
$cp = $uss->cp();

$ss->id = $dd[0];
$ss->le($dd[0]);
$ss->user_codigo = $ss->codigo;
$tabela = 'usuario';

echo '<div id="content">';
echo '<table width="'.$tab_max.'" class="lt0">';
echo '<TR><TD colspan=5>';
echo '<h1>Dados do usuário</h1>';
echo '<TR valign="top"><TD width="100">';
echo '<img src="../img/icone_user.png" height="100">';
echo '<TD>';
echo $perfil->mostrar($ss->user_cracha);
echo '</table>';
echo '<BR><BR>';
echo '<table width="'.$tab_max.'" class="lt0">';
echo '<TR><TD colspan=5>';
echo '<h2>Perfis atribuidos</h2>';
echo '<TR><TD colspan=5>';
echo $perfil->display($dd[0]);

/* Ghost User */
echo $perfil->ghost($dd[0]);

echo '<TR><TD colspan=5>';
echo '<form action="admin_user.php"><input type="submit" value="'.msg("back").'" class="bt_back"></form>';
echo '</table>';
echo '</div>';

?>
<script>
	$("#content").corner();
</script>
