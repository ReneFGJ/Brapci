<?
if ((strlen($jidsel) > 0) and ($user_nivel == 1))
	{
	$tit[0] = 'OAI Harvesting';
	$menu = array();
	array_push($menu,array('Harvesting','brapci_brapci_journal_harvesting.php?dd0='.$jidsel));
	array_push($menu,array('Get Record','brapci_brapci_journal_harvesting_get.php?dd0='.$jidsel));
	array_push($menu,array('Processa Record','brapci_brapci_journal_harvesting_proc.php?dd0='.$jidsel));
	require("menu_mostrar.php");	
	}
?>