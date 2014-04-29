<?php
/**
 * @author Rene Faustino Gabriel Junior <renefgj@gmail.com>
 * @version v.0.13.30
 * @package SVG - Graphics
 */
class svg
	{
	var $img;
	var $x=0;
	var $y=0;
	var $cor = "rgb(10,10,10)";
	var $cor_borda = 'rgb(0,0,0)';
	var $borda = 1;
	var $font_size = 12;
	var $font_family = 'Arial, Tahoma, Verdana';
	var $der_size = 150;
	
	function der_relation($x,$y,$w,$z,$tp='1:8')
		{
			$sx = $this->line($x,$y,$w,$z);
			
			if (substr($tp,0,1) == '8')
				{ $sx .= $this->der_arrow($y,$x,'l'); }
			if (substr($tp,2,1) == '8')
				{ $sx .= $this->der_arrow($z,$w,'r'); }

			$this->img .= $sx;
		}
	function der_arrow($x,$y,$p='l')
		{
			if ($p=='l')
				{
				$sx = '
					<polygon points="'.$x.','.$y.' '.($x+6).','.($y+6).' '.($x+6).','.($y-6).' '.$x.','.$y.'" 
  						style="fill:black;stroke:black;stroke-width:1"/>';
				}
			if ($p=='r')
				{
				$sx = '
					<polygon points="'.$x.','.$y.' '.($x-6).','.($y-6).' '.($x-6).','.($y+6).' '.$x.','.$y.'" 
  						style="fill:black;stroke:black;stroke-width:1"/>';
				}
			$this->img .= $sx;			
		}
	function der($x,$y,$c,$keys=array())
		{
			$z = 30+count($keys)*15;
			$w = $this->der_size;
			$sx = $this->rectangle($y,$x,$w,$z,'rgb(240,240,240)',1,'black');
			$sx = $this->rectangle($y,$x,$w,22,'rgb(128,128,128)',1,'black');
			$this->font_size=15;
			$sx .= $this->texto($c,$x+18,$y+5);
			//$sx .= $this->line($x+22,$y,$x+22,$y+$w,2);
			$this->font_size=12;
			for ($r=0;$r < count($keys);$r++)
				{
					$sx .= $this->texto($keys[$r],$x+38+$r*15,$y+10);
				}
			$this->img = $this->img . $sx;
		}
		
	function line($x=-1,$y=-1,$w=-1,$z=-1,$borda=1,$cor_borda='')
		{
			if (strlen($borda)==0) { $borda = $this->borda; }
			if (strlen($cor_borda)==0) { $cor_borda = $this->cor_borda; }

			$sx = '<line x1="'.$y.'" y1="'.$x.'" x2="'.$z.'" y2="'.$w.'" stroke="'.$cor_borda.'" stroke-width="'.$borda.'" />
			';
			$this->img .=  $sx;
			return('');			
		}
	function texto($texto='',$y=-1,$x=-1,$cor='black',$size=0)
		{
			$fsize = $this->font_size; 
			if ($x==-1) { $x = $this->x; }
			if ($y==-1) { $y = $this->y; }
			if (strlen($cor)==0) { $cor = $this->cor; }
			if ($size > 0) { $fsize = $size; }
			$sx = '<text x="'.$y.'" y="'.$x.'" fill="'.$cor.'" font-family="'.$this->font_family.'" font-size="'.$fsize.'">'.$texto.'</text>
			';
			$this->img .=  $sx;
			return('');
		}
	function show($file,$x=300,$y=300)
		{
			$sx = '<object data="'.$file.'" type="image/svg+xml" height="'.$x.'" width="'.$y.'">
					<embed src="'.$file.'" type="image/svg+xml">
					</object>';
			return($sx);
		}
	

	function save($name)
		{
			$fld = fopen($name,'w');
			fwrite($fld,$this->img);
			fclose($fld);
		}
	function create($y=500,$x=500)
		{
			$sx = '<?xml version="1.0" standalone="no"?>
					<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
					<svg xmlns="http://www.w3.org/2000/svg" version="1.1" height="'.$x.'px" width="'.$y.'px"> 
					';
			$this->img .=  $sx;	
			return('');
		}
		
	function circle($x=100,$y=100,$r=50,$cor="white",$borda=2,$cor_borda='black')
		{
			$sx = '<circle cx="'.$x.'" cy="'.$y.'" r="'.$r.'" stroke="'.$cor_borda.'" stroke-width="'.$borda.'" fill="'.$cor.'" />
			';
			$this->img .=  $sx;
			return('');
		}

	function rectangle($x=10,$y=20,$w=20,$z=20,$cor="black",$borda=2,$cor_borda='black')
		{
			$sx = '<rect x="'.$x.'" y="'.$y.'" width="'.$w.'" height="'.$z.'"  stroke="'.$cor_borda.'" stroke-width="'.$borda.'" fill="'.$cor.'" />
			';
			$this->img .=  $sx;
			return('');			
		}		
		
	function close()
		{
			$sx .= '
					</svg>';
			$this->img .=  $sx;
			return(''); 
		}	
	}
?>
