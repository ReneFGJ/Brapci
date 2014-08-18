<?
require("func_status.php");
global $ed_editar;
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
array_push($mnpos,array('sumário','brapci_brapci_article_edition.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_tips.php");
require($include."sisdoc_message.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Trabalho dentro da Base';

?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><?
require("diris_xml_import_classe.php");
///////////////////////// recupera nome do arquivo enviado
$filename = $_FILES['userfile']['name'];
global $tab_max;
/////////////////////// verifica se o arquivo foi enviado
///////////Se não foi enviado solicitar arquivo para envio
echo '<FONT class="lt5">Importação de biografia (.xlm, .xlmx)</FONT>';

$tp = 'L';

$tp = 'V';
$filename = 'http://www.directorioexit.info/consulta.php?directorio=exit&formato=XML&campo=ID&texto='.(28+$dd[0]).'';

if ($dd[0] > 2300) { echo 'FIM'; exit; }
?>
<META HTTP-EQUIV="Refresh" CONTENT="0; URL=diris_xml_import.php?dd0=<?=($dd[0]+1);?>">
<?
if (strlen($filename) == 0){
	echo '<TABLE width="'.$tab_max.'" border="0">';	
	echo '<TR><TD align="center">';
	?>
	<br>
	<form enctype="multipart/form-data" action="diris_xml_import.php" method="post">	
		<input type="hidden" name="MAX_FILE_SIZE" value="3000000">
		<font class="lt2">Arquivo para anexar</font>
		<input type="file" name="userfile">
		&nbsp;
		<br><br>
		<input type="submit" name="btEnviar1" id="btEnviar1" value=" G r a v a r ">
	</form>
	<?
	echo '</TABLE>';
	require($vinclude."foot.php");
	exit;
	}
	
if ($tp == 'L')
	{
		$filename = $_FILES['userfile']['tmp_name'];
		if ($_FILES['userfile']['size'] > 0){
			parser($filename);
			}
	} else {
		echo msg_info("O arquivo está vazio!");
	}

if ($tp == 'V')
	{ parser($filename);}
//echo "<br>Arquivo: $filename<br>";

require($vinclude."foot.php");
?>
