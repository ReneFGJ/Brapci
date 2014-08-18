<?
ob_start();
session_start();
//$include = "mm/";
require("db.php");
require($include."sisdoc_cookie.php");

$codigo = '0000001';

/////////////////////q
	$sql = "select * from enquete_resposposta where erq_codigo = '".$codigo."' and erq_data = '".date("Ymd")."' and erq_ip = '".$_SERVER['REMOTE_ADDR']."'";
	$rlt = db_query($sql);

	if ($line = db_read($rlt))
		{
			echo '<BR><BR>';
			echo "<CENTER><font color=red >Este equipamento já respondeu a enquete hoje.</font></CENTER>";

			setcookie("__brapci", 'respondido',time()+3600*24*24);
			$_SESSION['__brapci'] = 'respondido';
			exit;
			?>
			<script>
				close();
			</script>
			<?
			exit;
		}
/////////////////////////////////////		
if ((strlen($acao) > 0) and (strlen($dd[1]) > 0))
	{
			echo '<BR><BR>';
			echo "<CENTER>Obrigado por sua participação</CENTER>";
		
			$sql = "INSERT INTO enquete_resposposta ( erq_codigo , erq_vlr , erq_texto , erq_data , erq_hora , erq_ip ) ";
			$sql .= " VALUES ";
			$sql .= "( '".$codigo."', '".$dd[1]."', '', '".date("Ymd")."', '".date("H:i")."', '".$_SERVER['REMOTE_ADDR']."' );";
			$rlt = db_query($sql);
			
			setcookie("__brapci", 'respondido', time()+3600*24*24);	
			$_SESSION['__brapci'] = 'respondido';	
			exit;
	}
/////////////////////q
	$sql = "select * from enquete_resposposta where erq_codigo = '".$codigo."' and erq_data = '".date("Ymd")."' and erq_ip = '".$_SERVER['REMOTE_ADDR']."'";
	$rlt = db_query($sql);

	if ($line = db_read($rlt))
		{
			echo '<BR><BR>';
			echo "<CENTER><font color=red >Este equipamento já respondeu a enquete hoje.</font></CENTER>";
			exit;
		}
/////////////////////

$sql = "select * from enquete where eq_codigo = '$codigo'";
$rlt = db_query($sql);

$pg = array();
if ($line = db_read($rlt))
	{
	$peg = $line['eq_pg'];
	array_push($pg,$line['eq_r1']);
	array_push($pg,$line['eq_r2']);
	array_push($pg,$line['eq_r3']);
	array_push($pg,$line['eq_r4']);
	array_push($pg,$line['eq_r5']);
	array_push($pg,$line['eq_r6']);
	}
?>
<TITLE>Perfil do pesquisador da Base</TITLE>
<TABLE width="400">
<tr><td>
<font face="Verdana" style="font-size: 12px;">
<?=$peg;?></td></tr>
<TR><TD><form method="post"></TD></TR>
<TR><TD>
<font face="Verdana" style="font-size: 12px;">
<? 
for ($k=0;$k < count($pg);$k++)
	{
	if (strlen($pg[$k]) > 0)
		{
		echo '<input type="radio" name="dd1" value="'.$k.'">'.$pg[$k].'<BR>';
		}
	}
?>
</TD></TR>
<TR><TD><input type="submit" name="acao" value="gravar >>"></TD></TR>
</TABLE></form>

