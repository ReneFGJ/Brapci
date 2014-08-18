<?
require("../db.php");
$sql = "update brapci_article set at_mt = ".$dd[1];
$sql .= " where id_ar = ".$dd[0];
$rlt = db_query($sql);
echo 'ok';
?>
<script>
	window.close();
</script>
