<?
if (strlen($dd[0]) > 0)
	{
		require("cp/cp_relation.php");
	?>
	<table width="100%" border="1">
	<? editar(); ?>
	</table>
	
	<?
	if ($saved > 0)
		{ require('class_relation_rules.php'); }
	}
	?>