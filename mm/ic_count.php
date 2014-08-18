<?
//'sql = "delete from log_pagina"
//'set pra = cn.execute(SQL)
//'sql = "delete from log"
//'set pra = cn.execute(SQL)

$ht = array();
array_push($ht,"HTTP_ACCEPT_LANGUAGE");
array_push($ht,"HTTP_CONNECTION");
array_push($ht,"HTTP_HOST");
array_push($ht,"HTTP_REFERER");
array_push($ht,"HTTP_USER_AGENT");
array_push($ht,"LOCAL_ADDR");
array_push($ht,"LOGON_USER");
array_push($ht,"PATH_INFO");
array_push($ht,"REMOTE_ADDR");
array_push($ht,"REMOTE_HOST");
array_push($ht,"REMOTE_USER");
array_push($ht,"SCRIPT_NAME");

$hp = array();
//3 - local de onde veio o download

for ($r=0;$r < count($ht);$r++)
	{
		$var = $ht[$r];
		array_push($hp,$_SERVER[$var]);
//		echo '<BR>'.$r.'='.$hp[$r];
	}
//////////// de onde veio a busca
//
$origem = $hp[3];
$or = "";
$ok = 0;
$pg2='1';
for ($k=0;$k < strlen($origem); $k++)
	{
	$ca = substr($origem,$k,1);
	if ($ca=="/") {$ok++;}
	if ($ok < 3) { $or = $or . $ca; }
	}

	$sql = "select * from log_pagina where logpg_http='".trim($or)."'";
	$rel = db_query($sql);
	
	if (!$line = db_read($rel))
		{
		$sqlb = "insert into log_pagina (logpg_http,logpg_nome) values ('".trim($or)."','')";
		$rel2 = db_query($sqlb);
		$rel = db_query($sql);
		$line = db_read($rel);
		$pg2 = $line["id_logpg"];
		}
	else
		{
		$pg2 = $line["id_logpg"];
		}
//
//
	if (strlen($dd[99]) > 0)
		{
		$hp[11] = $hp[11] . "?dd99=".$dd[99];	
		}

$sql = "select * from log_pagina where logpg_http='".trim($hp[11])."'";
$rel = db_query($sql);

	if (!$line = db_read($rel))
		{
		$sqlb = "insert into log_pagina (logpg_http,logpg_nome) values ('".trim($hp[11])."','')";
		$rel2 = db_query($sqlb);
		$rel = db_query($sql);
		$line = db_read($rel);
		}

$pg1 = $line["id_logpg"];
$vpg = $dd[2];
if (strlen(trim($vpg))==0) { $vpg = $dd[99]; }

if ((substr($hp[8],0,3) != '66.') and ($hp[8] != '10.100.1.134'))
	{
	$sql = 'insert into log (journal_id,log_dd1,log_dd2,log_ip,log_data,log_hora,log_pagina,log_origem,log_user) values ';
	$sql = $sql . "('0".$jid."','".substr($dd[1],0,10)."','".substr($vpg,0,10)."','".$hp[8]."','".date('Ymd');
	$sql = $sql . "','".date('H:i')."','";
	$sql = $sql . $pg1."','".$pg2."','".$user_id."')";
	$rel = db_query($sql);
	}
?>
