<?php
require("db.php");
require("_class/_class_message.php");
$file = 'messages/msg_'.$LANG.'.php';
$LANG = $lg->language_read();
if (file_exists($file)) { require($file); } else { echo 'message not found '.$file; }

require("_class/_class_article.php");
$art = new article;

require("_class/_class_search.php");
$sc = new search;
echo '<BR><BR>';
echo '<h2>'.msg('search_result').'</h2>';
echo '<BR><P>';
if (strlen($dd[2]) ==0 )
	{
		echo msg("term_not_informed");
	}
else 
	{
		
		echo '<table border=1 class="lt1" width="100%">';
		echo '<TR valign="top">';
		echo '<TD>';
		echo msg('find').'<B> '.$dd[2].'</B>';
		echo $sc->result_article($dd[2]);
		
		echo '<TD width="120">';
		$sa = $sc->result_journals();
		$sa .= $sc->result_year();
		$sa .= $sc->result_author();
		echo $sa;
		echo '</table>';
		
	}
echo '</P>';
?>
