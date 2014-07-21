<?
require("cab.php");
require("../_class/_class_lattes.php");
$lt = new lattes;

?>
<center>
		<form id="upload" action="<?=page();?>" method="post" enctype="multipart/form-data">
		<BR><input type="checkbox" name="dd14" value="1"> Excluir dados de 2017
		<BR><input type="checkbox" name="dd14" value="1"> Excluir dados de 2016
		<BR><input type="checkbox" name="dd14" value="1"> Excluir dados de 2015
		<BR><input type="checkbox" name="dd14" value="1"> Excluir dados de 2014
		<BR><input type="checkbox" name="dd13" value="1"> Excluir dados de 2013
		<BR><input type="checkbox" name="dd12" value="1"> Excluir dados de 2012
		<BR><input type="checkbox" name="dd11" value="1"> Excluir dados de 2011
		<BR><input type="checkbox" name="dd10" value="1"> Excluir dados de 2010
		<BR><input type="checkbox" name="dd14" value="1"> Excluir dados de 2009
		<BR>
		<input type="submit" name="dd1" value="enviar >>>">
	</form>
</center>
<?

//$sql = "delete from lattes_artigos"; $rlt = db_query($sql);
if ($dd[17]=='1') { echo '<HR>Excluíndo 2017<HR>'; $sql = "delete from lattes_artigos where la_tipo = 'A' and la_ano = '2017' "; $rlt = db_query($sql); }
if ($dd[16]=='1') { echo '<HR>Excluíndo 2016<HR>'; $sql = "delete from lattes_artigos where la_tipo = 'A' and la_ano = '2016' "; $rlt = db_query($sql); }
if ($dd[15]=='1') { echo '<HR>Excluíndo 2015<HR>'; $sql = "delete from lattes_artigos where la_tipo = 'A' and la_ano = '2015' "; $rlt = db_query($sql); }
if ($dd[14]=='1') { echo '<HR>Excluíndo 2014<HR>'; $sql = "delete from lattes_artigos where la_tipo = 'A' and la_ano = '2014' "; $rlt = db_query($sql); }
if ($dd[13]=='1') { echo '<HR>Excluíndo 2013<HR>'; $sql = "delete from lattes_artigos where la_tipo = 'A' and la_ano = '2013' "; $rlt = db_query($sql); }
if ($dd[12]=='1') { echo '<HR>Excluíndo 2012<HR>'; $sql = "delete from lattes_artigos where la_tipo = 'A' and la_ano = '2012' "; $rlt = db_query($sql); }
if ($dd[11]=='1') { echo '<HR>Excluíndo 2011<HR>'; $sql = "delete from lattes_artigos where la_tipo = 'A' and la_ano = '2011' "; $rlt = db_query($sql); }
if ($dd[10]=='1') { echo '<HR>Excluíndo 2010<HR>'; $sql = "delete from lattes_artigos where la_tipo = 'A' and la_ano = '2010' "; $rlt = db_query($sql); }
if ($dd[14]=='1') { echo '<HR>Excluíndo 2009<HR>'; $sql = "delete from lattes_artigos where la_tipo = 'A' and la_ano = '2009' "; $rlt = db_query($sql); }
?>
