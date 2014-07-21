<?
$keys = troca($dd[11],',',';').';';

$keys = troca($keys,chr(10),'').';';
$keys = troca($keys,chr(13),'').';';
$keys = troca($keys,'.',';');

$ky = array();
$kx = array();

while (strpos($keys,';') > 0)
	{
	$dd5 = trim(substr($keys,0,strpos($keys,';')));
	$keys = substr($keys,strpos($keys,';')+1,strlen($keys));
	if (strlen($dd5) > 0)
		{
		array_push($ky,$dd5);
		array_push($kx,'');
		}
	}

for ($r=0;$r < count($ky);$r++)
	{
	$dd5 = $ky[$r];
	$dd5a = UpperCaseSql($dd5);
	require("article_keyword_ed_gr.php");
	$kx[$r] = $xcod;
	}

//$sql = "delete from brapci_article_keyword where kw_article = '".$dd[1]."' ";
//$rlt = db_query($sql);

for ($r=0;$r < count($ky);$r++)
	{
	$dd5 = $ky[$r];
	$dd5a = UpperCaseSql($dd5);
	$sql = "insert into brapci_article_keyword ";
	$sql .= "(kw_article,kw_keyword,kw_ord)";
	$sql .= " values ";
	$sql .= "('".$dd[1]."','".$kx[$r]."','".($r+1)."') ";
	$rlt = db_query($sql);
	}
require("../close.php");
?>