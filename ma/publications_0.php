<?
require("cab.php");
require('../_class/_class_publications.php');
$pb = new publications;
$tabela = $pb->tabela;

require('../_class/_class_journals.php');
$jl = new journals;

require($include.'_class_form.php');
$form = new form;

$jnl = trim($dd[0]);

echo '<div class="nav">';
echo '<h1>'.$pb->journal_name($jnl).'</h1>';
echo '
	<A class="link" HREF="publications_row.php">'.msg('return').'</A>
';

echo '<table width="100%"><TR valign="top">';
echo '<TD width="20%">';
echo '<A HREF="publications_ed.php?dd0='.$dd[0].'"><font class="lt1 link">Resumo da publicação</font></A><BR>';
echo '<A HREF="publicacoes_1_ed.php?dd0='.$dd[0].'"><font class="lt1 link">Dados da publicação</font></A><BR>';
echo '<A HREF="publicacoes_2_ed.php?dd0='.$dd[0].'"><font class="lt1 link">Sobre a publicação</font></A><BR>';
echo '<A HREF="publicacoes_3_ed.php?dd0='.$dd[0].'"><font class="lt1 link">Links de acesso</font></A><BR>';
echo '<A HREF="publicacoes_4_ed.php?dd0='.$dd[0].'"><font class="lt1 link">Protocolo OAI</font></A><BR>';

echo '<TD>';

?>
