<?
require("cab.php");
require('../_class/_class_publications.php');
$pb = new publications;

$jnl = trim($dd[0]);

require('../_class/_class_journals.php');
$jl = new journals;
$jl->le($dd[0]);
echo $jl->journals_mostra();

echo '<div class="nav">';
echo '<h1>'.$pb->journal_name($jnl).'</h1>';
echo '
	<A class="link" HREF="publications.php?dd0='.$pb->type.'">'.msg('return').'</A>
	|
	<A class="link" HREF="publications_ed.php?dd0='.$dd[0].'">'.msg('edit').'</A>	
';

echo $pb->resume($jnl);
echo $pb->list_issue($jnl);

echo '
		<input type="button" value="'.msg("new_issue").'" 
		class="botao-geral" 
		onclick="window.location.href = \'publications_issue_ed.php?dd2='.$jnl.'\';">';


require("../foot.php");
?>
