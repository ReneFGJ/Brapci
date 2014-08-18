<? ob_start(); ?>
<? require("db.php"); ?>
<?
printf("MySQL host info: %s\n", mysql_get_host_info());
echo '<HR>';
printf(mysql_info());
echo '<HR>';
$result = db_query('SHOW GLOBAL STATUS');
while ($row = mysql_fetch_assoc($result)) {
    echo '<BR><TT>'.$row['Variable_name'] . ' = ' . $row['Value'] . "\n";
}
?>