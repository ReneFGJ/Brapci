<? 
require("db.php");
diretorio_checa($uploaddir);
diretorio_checa($uploaddir.'/'.date("Y"));
diretorio_checa($uploaddir.'/'.date("Y")."/".date("m"));

$uploaddir .= '/'.date("Y").'/'.date("m").'/';
$dbdir = '/'.date("Y").'/'.date("m").'/';

require($include."sisdoc_data.php");
require($include."sisdoc_windows.php");
$filename = trim($_FILES['userfile']['name']);
$chksun = md5($dd[0].$dd[1].'448545');
$chave = "448545ct";

if (($chksun != trim($dd[2])) or (strlen($dd[2]) < 10))
	{
	echo '<CENTER><B><font color=red face="verdana" size="2">Checksun dos parametros incorreto</font>';
	exit;
	}
	
$sql = "select * from brapci_article where id_ar = '".$dd[0]."'; ";
$rlt = db_query($sql);

if ($line = db_read($rlt))
	{
	$doc_nr = $line['ar_codigo'];
	$doc_titulo = $line['ar_titulo_1'];
	}
?>
<TITLE>Anexar arquivo</TITLE>
<BODY topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" <?=$bgcolor;?>
<link rel="stylesheet" href="letras.css" type="text/css" />
<TABLE width="100%" align="center" border="0" class="lt1" >
<TR><TD colspan="10" bgcolor="#c0c0c0" align="center"><B>Anexar Arquivo ao Protocolo de pesquisa</B></TD></TR>
<TR><TD colspan="10">Número Doc. : <B><?=$doc_nr;?></TD></TR>
<TR><TD colspan="10">Título : <B><?=$doc_titulo;?>
<HR size="1"></TD></TR>
<? if (strlen($filename) == 0 ) { ?>
<TR><TD align="right">
<form enctype="multipart/form-data" action="brapci_suporte_up.php" method="POST">
</TD><TD>
<input type="hidden" name="XXMAX_FILE_SIZE" value="3000000000">
<TR valign="top"><TD align="right">
Arquivo para anexar</TD><TD colspan="3"><input name="userfile" type="file" class="lt2">
&nbsp;<input type="submit" value="e n v i a r" class="lt2" <?=$estilo?>>
<input type="hidden" name="dd0" value="<?=$dd[0]?>">
<input type="hidden" name="dd1" value="<?=$dd[1]?>">
<input type="hidden" name="dd2" value="<?=$dd[2]?>">
<input type="hidden" name="dd3" value="<?=$dd[3]?>">
</form>
</TD>
<TD><?=$xnome?></TD></TR>
<TR><TD colspan="5"><font color="#ff8040">Tamanho máximo por arquivo (2 Mega bytes)</font></TD></TR>
<? } ?>
</TABLE>
<?
///////////
if (strlen($filename) == 0) { exit; }

echo '<HR size="1"><font class=lt1>Filename : '.$filename;
echo '<BR>';


if ((strlen($filename) > 0 ) and (strlen($dd[0]) > 0))
	{
	$ver = count($versoes)+1;
	$filename = trim(UpperCaseSQL($filename));
	$filename = troca($filename,' ','_');
	$chave = UpperCaseSQL(substr(md5($chave.$dd[0]),0,8));
	$xfilename = $dd[0].'-'.strzero($ver,2).'-'.$chave.'-'.substr($filename,0,50);
	$file_ext = substr($filename,strlen($filename)-3,3);
	$ok == false;
	if ($file_ext == 'JPG') { $ok = true; }
	if ($file_ext == 'RTF') { $ok = true; }
	if ($file_ext == 'DOC') { $ok = true; }
	if ($file_ext == 'PDF') { $ok = true; }
	if ($ok == false)
		{ 
		echo '<CENTER><BR><BR><FONT COLOR=RED >';
		echo 'A extansão do arquivo <B>.'.$file_ext.'</B> não é válida, somente são aceitos arquivos com a extensão ';
		echo '<B>JPG, DOC, RTF, PDF</B>.';
		exit;
		}
			
	while (file_exists($uploaddir.$xfilename)) 
		{
		$ver++;
		$xfilename = $dd[0].'-'.strzero($ver,2).'-'.$chave.'-'.$filename;
		}
	////////////////////////////////////////////////////////////////
	echo '<TABLE class="lt1" width="100%">';
	$arq = $uploaddir.$xfilename;

	$uploadfile = $uploaddir.''.$xfilename;
	$dbloadfile = $dbdir.$xfilename;
	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) 
		{
			$name = $_FILES['userfile']['name'];
			$size = round($_FILES['userfile']['size']/10);
			$type = $_FILES['userfile']['name'];
			$type = substr($type,strlen($type)-3,3);
			$doc_acesso = '1';
			$sql = "insert into brapci_article_suporte (bs_adress,bs_type,bs_status,";
			$sql = $sql . "bs_update,bs_article";
			$sql = $sql . ") values (";
			$sql = $sql . "'".$dbloadfile."','".$file_ext."','A',";
			$sql = $sql . date("Ymd").",'".$dd[0]."'";
			$sql .= ")";

		    $message = "<CENTER><FONT class=lt3 ><font color=green><B>Arquivo válido e foi salvo.</FONT></CENTER>";
			$rlt = db_query($sql);
			//echo $sql;
			////////////// ATUALIZA
			require("close.php");
			?>
			<script>
				window.opener.location.reload();
			</script>
			<?
			echo '<TR><TD colspan="10">versão do arquivo : <B>'.$ver.'</B></TD></TR>';
			echo '<TR><TD colspan="10">'.$message.'</TD></TR>';
			echo '<TR><TD colspan="10"><A HREF="#" onclick="winclose();">[Fechar]</A></TD></TR>';
		} else {
		    print "<CENTER><FONT COLOR=RED>ERRO EM SALVAR O ARQUIVO";
			print "<BR>->".$uploadfile;
	    //print_r($_FILES);
		}
	echo '</TABLE>';
	}
	
function diretorio_checa($vdir)
	{
	if(is_dir($vdir))
		{ $rst =  '<FONT COLOR=GREEN>OK';
		} else { 
			$rst =  '<FONT COLOR=RED>NÃO OK';	
			mkdir($vdir, 0777);
			if(is_dir($vdir))
				{
				$rst =  '<FONT COLOR=BLUE>CRIADO';	
				}
		}
		$filename = $vdir."/index.htm";	
		if (!(file_exists($filename)))
		{
			$ourFileHandle = fopen($filename, 'w') or die("can't open file");
			$ss = "<!DOCTYPE HTML PUBLIC -//W3C//DTD HTML 4.01 Transitional//EN><html><head><title>404 : Page not found</title></head>";
			$ss = $ss . "<body bgcolor=#0080c0 marginheight=0 marginwidth=0><table align=center border=0 cellpadding=0 cellspacing=0>	";
			$ss = $ss . "<tbody><tr>	<td height=31 width=33><img src=/reol/noacess/quadro_lt.gif alt= border=0 height=31 width=33></td>	";
			$ss = $ss . "<td><img src=/reol/noacess/quadro_top.gif alt= border=0 height=31 width=600></td>	<td height=31 width=33>";
			$ss = $ss . "<img src=/reol/noacess/quadro_rt.gif alt= border=0 height=31 width=33></td>	</tr>	<tr>	<td>	";
			$ss = $ss . "<img src=/reol/noacess/quadro_left.gif alt= border=0 height=300 width=33></td>	<td align=center bgcolor=#ffffff>";
			$ss = $ss . "<img src=/reol/noacess/sisdoc_logo.jpg width=590 height=198 alt= border=0><BR>	<font color=#808080 face=Verdana size=1>";
			$ss = $ss . "&nbsp;&nbsp;&nbsp;&nbsp;	programação / program : <a href=mailto:rene@sisdoc.com.br>Rene F. Gabriel Junior</a>	<p>";
			$ss = $ss . "<font color=#808080 face=Verdana size=4>	<font color=#808080 face=Verdana size=1>&nbsp;	<font color=#ff0000 face=Verdana size=3><B>";
			$ss = $ss . "Acesso Restrito / Access restrit	</font></font></td>	<td><img src=/reol/noacess/quadro_right.gif alt= border=0 height=300 width=33></td></tr><tr>";
			$ss = $ss . "<td height=31 width=33><img src=/reol/noacess/quadro_lb.gif alt= border=0 height=31 width=33></td>	<td><img src=/reol/noacess/quadro_botton.gif alt= border=0 height=31 width=600></td>";
			$ss = $ss . "<td height=31 width=33><img src=/reol/noacess/quadro_rb.gif alt= border=0 height=31 width=33></td>	</tr></tbody></table></body></html>";
			$rst = $rst . '*';
			fwrite($ourFileHandle, $ss);
			fclose($ourFileHandle);		
		}
		return($rst);
	}	
?>
