<?php
require("../db.php");

require("../_class/_class_issue.php");
require("../_class/_class_article.php");
require('../_include/_class_form.php');
require('../_include/_class_msg.php');

$class = $dd[91];
$id = $dd[1];
$verb = $dd[2];

echo $verb.'-'.$id.'-'.$class;

$fclass = "_class_".$class.'.php';
if (file_exists($fclass))
	{
		require($fclass);
	}
$aj = new ajax_class;

switch($verb)
	{
	case 'REFRESH':
		echo $aj->refresh($id);
	}
?>
