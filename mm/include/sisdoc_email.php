<?
///////////////////////////////////////////
// BIBLIOTECA DE FUNÇÕS PHP ///////////////
////////////////////////////// criado por /
////////////////// Rene F. Gabriel Junior /
/////////////////    rene@sisdoc.com.br   /
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0c                       16/07/2008 //
// 0.0b                       07/07/2008 //
// 0.0a                       20/05/2008 //
///////////////////////////////////////////
if ($mostar_versao == True) {array_push($sis_versao,array("sisDOC (e-mail)","0.0c",20080716)); }

function emailfoot($texto,$email_to,$subject)
	{
	if (strlen($email_to) > 0)
		{
		global $cnn,$email_adm,$admin_nome;	
		echo 'enviando ';
		echo $email_adm;
		echo ' e para '.$email_to;
		echo '<BR>';
		echo $admin_nome;
		if (enviaremail($email_to,$e2,$subject,$texto))
			{
			return(true);
			} 
		}
	return(false);
	}
function emailcab($http_local)
	{
	global $dd;
	$bb1 = "imprimir";
	$bb2 = "enviar por e-mail";
	$s = '<TABLE cellpadding="0" cellspacing="0" border="0" class="lt1" width="100%">';
	$s .= '<TR>';
	$s .= '<form method="post" action="'.$http_local.'" >';
	$s .= '<TD>&nbsp;e-mail:&nbsp;';
	$s .= '<input type="text" name="dd80" value="'.$dd[80].'" size="30" maxlength="100">';
	$s .= '&nbsp;';
	$s .= '<input type="submit" name="dd81" value="'.$bb2.'">';
	$s .= '<TD align="right">';
	$s .= '<input type="submit" name="dd81" value="'.$bb1.'">';
	$s .= '</TD>';
	$s .= '<TD width="1"></form></TD>';
	$s .= '</TR>';
	$s .= '<TR><TD colspan="10"><HR size="1"></TD></TR>';
	$s .= '</TABLE>';
	if (strlen($dd[81]) > 0)
		{
		if ($dd[81] == $bb1)
			{
			$s = '<SCRIPT LANGUAGE="JavaScript">'.chr(13);
			$s .= '		window.print();  '.chr(13);
			$s .= '</script>'.chr(13);
			}
		if (($dd[81] == $bb2) and (strlen($dd[80]) > 0))
			{
			$s = '';
			}
		}
	return($s);
	}

function enviaremail($e1,$e2,$e3,$e4)
	{
	global $email_adm, $admin_nome;
	if (strlen($admin_nome) == 0) { $admin_nome = $email_adm; }
	$to = $e1;
	$subject = $e3;
	$body = $e4;
	$headers = '';
	$headers .= "To: ".$e1." \n";
	$headers .= "From: ".$admin_nome." <" .$email_adm. "> \n";
	$headers .= "Mime-Version: 1.0 \n";
//	$headers .= "Priority: Normal \n";
//	$headers .= "Reply-To: " .$email_adm. " \n";
//	$headers .= "Return-Path: ".$email_adm." \n";
//	$headers .= "Subject: ".$subject." \n";
//	$headers .= "X-Assp-Envelope-From:".$email_adm." \n";
	$headers .= 'Content-Type: text/html; charset="iso-8859-1"'." \n";		
	
	$headers = '';
	$headers .= "MIME-Version: 1.0\n" ;
	$headers .= "To: ".$e1." \n";
	$headers .= "From: ".$admin_nome." <" .$email_adm. "> \n";
	$headers .= "Reply-To: " .$email_adm. " \n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\n";		

	$body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">'."\n".$body;
	if (mail($to, $subject, $body, $headers)) {
	   return('OK');
	 } else {
	  return('ERRO');
	  }
 }
 
function checaemail($chemail)
	{
	$result = count_chars($chemail, 0);
	
	if (($result[64] == 1) and ($result[32] == 0) and ($result[13] == 0) and ($result[10] == 0))
		{$xerr = True; }
	else
		{$xerr = False; }
		
	if ($chemail[strlen($chemail)-1] < 'a') { $xerr = false; }
	return($xerr);
	}
?>
