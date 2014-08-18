<?
require("db.php");
$ip = $_SERVER['REMOTE_ADDR'];

$sql = "select * from brapci_article_suporte ";
$sql .= " inner join brapci_article on ar_codigo = bs_article ";
$sql .= " left join brapci_edition on ed_codigo = ar_edition ";
$sql .= " left join brapci_journal on ar_journal_id = jnl_codigo ";
$sql .= " where id_bs = ".sonumero($dd[0]);
$rlt = db_query($sql);
$line = db_read($rlt);

/// status do download
$sta = 'A';

$file = $uploaddir.trim($line['bs_adress']);
$artigo = trim($line['ar_codigo']);
$arq = $file;

$nome = trim($line['jnl_nome_abrev']).'-'.trim($line['ed_vol']).'('.trim($line['ed_nr']).')'.trim($line['ed_ano']).'-';
//$nome .= $secu.'-';
$nome .= trim(lowercase($line['ar_titulo_1_asc']));
$nome = troca($nome,'?','');
$nome = troca($nome,' ','_');
$nome = troca($nome,'.pdf','xpdf');
$nome = troca($nome,'.','_');
$nome = troca($nome,'xpdf','.xpdf');

if (!(file_exists($arq)))
	{
		$arq .= '.pd';
		if (!(file_exists($arq)))
		{
			$sta = 'F';
			echo $arq;
			echo '<BR> Arquivo não localizado ';
			echo '<BR> Repostando erro ao administrador';
			exit;
		}
	}
//////////////// Salva marcação de download realizado
//require("db_pesquisador.php");
//$sqld = "insert into download (down_data, down_hora, down_arquivo, down_ip, down_status) ";
//$sqld .= " values ";
//$sqld .= "(".date("Ymd").",'".date("H:m:s")."','".$artigo."','".$ip."','".$sta."') ";
//$xrlt = db_query($sqld);
	
if ($sta == 'A')
{
header("Expires: 0");
//header('Content-Length: $len');
header('Content-Transfer-Encoding: none');
$file_extension = strtolower(substr($file,strlen($file)-3,3));
switch( $file_extension ) {
	      case "pdf": $ctype="application/pdf"; break;
    	  case "exe": $ctype="application/octet-stream"; break;
	      case "zip": $ctype="application/zip"; break;
	      case "doc": $ctype="application/msword"; break;
	      case "xls": $ctype="application/vnd.ms-excel"; break;
	      case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
	      case "gif": $ctype="image/gif"; break;
	      case "png": $ctype="image/png"; break;
	      case "jpeg":
	      case "jpg": $ctype="image/jpg"; break;
	      case "mp3": $ctype="audio/mpeg"; break;
	      case "wav": $ctype="audio/x-wav"; break;
	      case "mpeg":
	      case "mpg":
	      case "mpe": $ctype="video/mpeg"; break;
	      case "mov": $ctype="video/quicktime"; break;
	      case "avi": $ctype="video/x-msvideo"; break;
	}
header("Content-Type: $ctype");
header('Content-Disposition: attachment; filename="'.$nome.'"');
readfile($arq);
	$pj_codigp = $dd[1];
	$acao="D";
}
?>