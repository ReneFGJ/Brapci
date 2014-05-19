<?
require("cab.php");
require('../_class/_class_publications.php');
$pb = new publications;

require("main_menu.php");

echo '<div class="nav">';
echo '<h1>'.$pb->type($dd[0]).'</h1>';
$type = trim($dd[0]);
echo $pb->list_row($type);
echo '</div>';

require("../foot.php");
?>
