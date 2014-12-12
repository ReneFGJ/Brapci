<?
$include = '../';
require("../db.php");
require('../_class/_class_header.php');
$hd = new header;
$hd->path = '../';
echo $hd->head();


/* Messages */
require("../_class/_class_message.php");
$LANG = 'pt_BR';
//$lg->language_set($LANG);

$file = '../messages/msg_pt_BR.php';
require($file);

require($include.'sisdoc_debug.php');

/* Mensagens */
$tabela = 'ged_upload';
	
$tipo = $dd[3];
require("_ged_config.php");

echo $ged->file_attach_form();
?>
