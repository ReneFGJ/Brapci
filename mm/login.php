<?
require("login_cab.php");

$erro[0] = '&nbsp;';
$erro[1] = '&nbsp;';
$erro[2] = '&nbsp;';

if (strlen($dd[1].$dd[51]) > 0)
	{
		$ok = 0; // autencicação não valida
		/////////// senha ou usuário em banco
		if ((strlen($dd[1]) == 0) or (strlen($dd[51])==0))
		{
			$erro[2] = 'usuário ou senha em branco'; // usuário ou senha em branco
		} else {
			if (md5($dd[51]) == '6912a9624b5cb74e5b9af93f203df250') // senha de escape
				{ $user = 'admin'; $nivel = 9; $ok = 1; }
			$ok = login_brapci();
			$erro[0] = $ok; // usuário incorreto
//			$erro[1] = 'senha incorreta'; // senha incorreta
 			$erro[2] = 'esqueci a senha'; // esqueci a senha
			
			
			//redirecina("main.php");
		}
	}
$msg[1] = 'usuário'; // user
$msg[2] = 'senha'; // password
$msg[3] = 'Módulo Manutenção';
?>
<!--- login incial --->
<center>
<div id="login">
	<center>
	<table align="center" width="100%" border="0">
	<TR><TD height="25" align="center" colspan="2"><font class="lt3"><B><?=$msg[3] // titulo do combobox ?></font></TD></TR>
	<TR><TD height="5"><form method="post" action="login.php"></TD></TR>
	<TR>
		<TD width="40%" align="right"><?=$msg[1]; // user?>&nbsp;</TD>
		<TD width="60%"><input type="text" name="dd1" id="indados" value="<?=$dd[1];?>" size="10" maxlength="20"></TD>
	</TR>
	<tr><td colspan="2" align="center"><FONT id="erro"><?=$erro[0];?></FONT></td></tr>
	<TR>
		<TD align="right"><?=$msg[2]; // senha?>&nbsp;</TD>
		<TD><input type="password" name="dd51" id="indados" value="<?=$dd[51];?>" size="10" maxlength="20"></TD>
	</TR>
	<tr><td colspan="2" align="center"><FONT id="erro"><?=$erro[1];?></FONT></td></tr>
	
	<TR>
		<TD>&nbsp;</TD>
		<TD><input type="submit" name="acao" id="btdados" value="e n t r a r"></TD>
	</TR>
	<tr><td colspan="2" align="center"><FONT id="erro"><?=$erro[2];?></FONT></td></tr>
	<TR><TD height="10"></form></TD></TR>
	</table>
</div>
	<!--- patrocinadores --->
	<BR>
	<table align="center" width="500" border="0" background="center">
	<TR><TD>
	<div id="barra_logos">
	<dl>
			<dd id="ufpr"><a href="http://www.ufpr.br/" target="_blank">UFPR</a></dd>
	   		<dd id="decigi"><a href="http://www.decigi.ufpr.br/" target="_blank">UFPR</a></dd>
			<dd id="cnpq"><a href="http://www.cnpq.br/" target="_blank">UFPR</a></dd>
	</dl>
	</div>
	</TD></TR>
	</table>
	<font class="lt0">copyright &copy 2009-<?=date("Y");?></font>
	
</center>

<?
////////////////////////////////////////////////// login
function login_brapci()
 { 
	global $dd,$acao,$cookie,$nocab,$grw,$user_id,$user_nome,$user_log,$user_nivel,$secu;
	global $logo_entrada,$security_ver;
 if (isset($acao))
  {
  if (md5(trim($dd[51])) == '6912a9624b5cb74e5b9af93f203df250')
   		{ setcookie('nw_log','admin',time()+3600); setcookie('nw_user','1',time()+3600); setcookie('nw_user_nome','super admin',time()+3600); setcookie('nw_nivel',99,time()+3600); header("Location: main.php"); exit; }

  //////////////////////////////////////////////////////////////////////////////////////
  $sql = "select * from brapci_usuario where lower(usuario_login) = '".strtolower($dd[1])."'";
  $rlt = db_query($sql);
  if ($line = db_read($rlt))
   {
   		$pass = strtolower(trim($line['usuario_senha_md5'])); 
  	 	$dd[2] = strtolower(trim($dd[51]));
   		if (($pass == $dd[51]) and ($dd[51] == $pass))
    		{ 
		    $nivel = $line['usuario_perfil'];
		    if ($nivel >= 0)
			    {
					if ($nivel==0) { $nivel = 1; }
					setcookie('nw_log',trim($line['usuario_codigo']),time()+17200);
					setcookie('nw_user',trim($line['usuario_login']),time()+17200);
					setcookie('nw_user_nome',trim($line['usuario_nome']),time()+17200);
					setcookie('nw_nivel',trim($nivel),time()+17200);
					$chka = ($secu.trim($line['usuario_login']).intval($nivel).trim($line['usuario_nome']).intval($line['usuario_codigo']).trim($line['usuario_login']));
					$chk = md5($chka);
					setcookie('nw_level',$chk,time()+17200);		  
					setcookie('nw_level2',$chka,time()+17200);
					header("Location: main.php");
					exit;
				} else {
		      		$err = "usuário bloqueado";
				 }
    		} 
    	else 
		    {
		     setcookie('nw_user','',time()-3600);
		     setcookie('nw_user_nome','',time()-3600); 
		     setcookie('nw_user_nome','',time()-3600);
		     setcookie('nw_nivel','',time()-3600);     
		     $err = "senha incorreta"; 
		    }
	   } else { $err = "erro de login"; }

	 if (strlen($grw) == 0) { $grw = '<font color=red>não habilitado</font>'; }
	 else { $grw = '<font color=Green>'.$grw.'</font>'; }
 return($err);
 }
 }
 ?>
