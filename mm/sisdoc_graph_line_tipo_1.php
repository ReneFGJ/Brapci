<?
$max = 1;

$crnf = chr(13).chr(10);
$sn .= '{'.$crnf;
$sn .= '  "y_legend":{'.$crnf;
$sn .= '    "text":   "'.$eixo_y_legend.'",'.$crnf;
$sn .= '    "style": "{color: #736AFF;}"'.$crnf;
$sn .= '  },'.$crnf;
$sn .= ''.$crnf;

$sn .= '	"elements":['.$crnf;
$sn .= '		{'.$crnf;
$sn .= '			"type":      "line",'.$crnf;
$sn .= '			"colour":    "#0000cc",'.$crnf;
$sn .= '			"text":      "'.$titu[0].'",'.$crnf;
$sn .= '			"font-size": 10,'.$crnf;
$sn .= '			"width":     4,'.$crnf;
$sn .= '			"values" :   ['.$crnf;

for ($kk=0;$kk < count($vlr);$kk++)
	{
	if (strlen($vlr[$kk]) > 0)
		{
		if (round($kk/6) == ($kk/6)) { $sn .= $crnf; }
		if ($kk > 0) { $sn .= ','; }
		$sn .= ''.$vlr[$kk].'';
		if ($vlr[$kk] > $max) { $max = round($vlr[$kk] + $vlr[$kk]*0.05); }
		}
	}
//$sn .= '				{"value":2.44,"colour":"#FF0000","tip":"monkies"},'.$crnf;
$sn .= ']'.$crnf;
$sn .= '		}'.$crnf;
$sn .= '	],'.$crnf;
$sn .= ''.$crnf;
$sn .= '  "x_axis":{'.$crnf;
$sn .= '    "labels": {'.$crnf;
$sn .= '      "rotate": "vertical",'.$crnf;
$sn .= '      "labels":[';
for ($kk=0;$kk < count($fid);$kk++)
	{
	if (round($kk/6) == ($kk/6)) { $sn .= $crnf; }
	if ($kk > 0) { $sn .= ','; }
	$sn .= '"'.$fid[$kk].'" ';
	}
////////////////////////////////////////////
//$max = 600;
///////////////////////////////////////////	
$sn .= ' ]'.$crnf;
$sn .= '    }'.$crnf;
$sn .= '  },'.$crnf;
$sn .= ''.$crnf;
$sn .= '    "y_axis":{ "max":  '.$max.' }'.$crnf;
$sn .= '}'.$crnf;

$myFile = "../data-files/y-axis-auto-steps.txt";
$fh = fopen($myFile, 'w') or die("can't open file");
fwrite($fh, $sn);
fclose($fh);
?>