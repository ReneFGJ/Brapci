<table width="90%" align="center">
<TR><TD><BR></TD></TR>
<TR valign="top">
	<TD width="106"><img src="oai/oai_logo.gif" height="76" alt="" border="0"></TD>
	<TD><B>Coletor autormático de arquivos PDF disponíveis em Links</B>
	<BR>Desenvolvido por Rene Faustino Gabriel Junior &copy 2011
	<BR>Versão 0.11.47
	
	</TD>
	</TR>
<TR><TD colspan="2"><TT>

<?
function http_file_exists($url)
{
echo '<BR>Avaliando disponibilidade da página';
echo '<BR>Soquete:'.$url;
$fp = fopen($url,'r');
if (!$fp) 
	{
	    echo "Unable to open\n";
		return false;
	} else {
		fclose($fp);
		return true;
	}
} 
function stream_copy($src, $dest) 
    { 
        $fsrc = fopen($src,'r'); 
        $fdest = fopen($dest,'w+'); 
        $len = stream_copy_to_stream($fsrc,$fdest); 
        fclose($fsrc); 
        fclose($fdest); 
        return $len; 
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