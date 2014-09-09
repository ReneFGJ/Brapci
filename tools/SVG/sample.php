<?php
$include = '../../';
require("../../db.php");

require("_class/_class_svg_net.php");
$svg = new svg;
$svg->size(1024,1024);
$id = 0;

require("_autores.php");
require("_anos.php");
$fm = 1;

for ($r=0;$r < count($am);$r++)
	{
		$f = $am[$r][1];
		$fm = $fm + $f*2;
		$ano = $am[$r][0];
		$s = $am[$r][1]*2;
		$x = 10;
		$y = $r*16+16;
		
		$svg->arc_add($x+50,$y,$s,'N'.$r);
		$svg->text_add($x,$y,$ano);		
	}

for ($r=0;$r < count($au);$r++)
	{
		$f = $au[$r][1];
		$fm = $fm + $f*2;
		$autor = $au[$r][0];
		$s = $au[$r][1]*2;
		$x = 900;
		$y = $r*16+16;
		
		$svg->arc_add($x,$y,$s,'A'.$r);
		$svg->text_add($x+15+$s,$y,$autor);		
	}
$svg->line_from_to('A1','N12');
$svg->save('sample.svg');

echo '<body bgcolor="#FEFEFE">';
echo '<img src="sample.svg" border=5>';

echo '<HR>';
?>
