<?php
class graphics {
	
		function grafico_barras($ar,$title='')
			{
				for ($r=0;$r < count($ar);$r++)
					{
						$sf .= '<TD class="tabela01">';
						$sf .= '<center>';
						$sf .= $ar[$r][0].chr(13);
						
						$size = 15 * round($ar[$r][1]);						
						$sg .= '<TD>';
						$sg .= '<img src="'.http.'img/gr_green.png" width="30" height="'.$size.'" title="'.round($ar[$r][1]).' trabalhos">';
					}
				$sx = '<table>';
				$sx .= '<TR><TD colspan=20>';
				$sx .= '<font class="lt2">'.$title;
				$sx .= '<TR valign="bottom">'.$sg;
				$sx .= '<TR>'.$sf;
				$sx .= '</table>';
				return($sx);
			}
	
		function grafico_folha($it1,$it2,$title,$cap1='',$cap2='')
			{
				
				
				$it2 = 'R$ '.number_format($it2,2,',','.');
				$sx = '<table width="280" border=0 cellpadding=0 cellspacing=0 class="fundo_branco">'.chr(13);
				
				$sx .= '<TR><TD colspan=5>'.chr(13);
				$sx .= '<h3 class="cinza">'.msg($title).'</h3>';
				
				$sx .= '<TR valign="middle">';

				$sx .= '<TD width="25" height="48" valign="top" class="fundo_verde_claro">';
				$sx .= '<img src="'.http.'/img/img_folha_verde_left.png" >';
				$sx .= '<TD valign="middle" class="fundo_verde_claro">';
				$sx .= '<center><font style="font-size: 26px; color: #FFFFFF;">'.$it1.chr(13);
				$sx .= '<TD width="25" valign="bottom" class="fundo_verde_claro">';
				$sx .= '<img src="'.http.'/img/img_folha_verde_right.png" >';
				
				$sx .= '<TD>&nbsp;';	

				$sx .= '<TD width="25" valign="top" class="fundo_verde_claro">';
				$sx .= '<img src="'.http.'/img/img_folha_verde_left.png" >';
				$sx .= '<TD valign="middle" class="fundo_verde_claro">';
				$sx .= '<center><font style="font-size: 26px; color: #FFFFFF;">'.$it2.chr(13);
				$sx .= '<TD width="25" valign="bottom" class="fundo_verde_claro">';
				$sx .= '<img src="'.http.'/img/img_folha_verde_right.png" >';
				
				$sx .= '<TR>';
				$sx .= '<TD colspan=3><center><font class="lt0 cinza">';
				$sx .= '<center>'.$cap1;
				$sx .= '<TD colspan=3><center><font class="lt0 cinza">';
				$sx .= '<center>'.$cap2;
				
				$sx .= '</table>'.chr(13);
			return($sx);
			}
	
		function grafico_bonecos($ai,$title='',$legend='',$tp='')
			{
			global $messa,$LANG;
			
			$sx .= '<table width="300" border=0 class="fundo_branco">'.chr(13);
			$sx .= '<TR><TD colspan=2>'.chr(13);
			$sx .= '<h3 class="cinza">'.msg($title).'</h3>';
			$hg = 36;
			
			for ($r=0;$r < count($ai);$r++)
				{
				if (($ai[$r][1]+$ai[$r][2]+$ai[$r][3]+$ai[$r][4]) > 0)
					{
					$ano = $ai[$r][0];
					$sx .= '<TR>'.chr(13);
					$sx .= '<TD class="lt2 cinza" height="'.$hg.'" align="center" width="10">';
					$sx .= '<center>'.$ano.''.chr(13);
					$sx .= '<TD width="290">';
					
					for ($y=0;$y < $ai[$r][1];$y++) 
						{ $sx .= '<img src="'.http.'img/img_icone_boneco_01'.$tp.'.png" height="'.$hg.'" title="'.$ai[$r][0].'">'.chr(13); }
					for ($y=0;$y < $ai[$r][2];$y++) 
						{ $sx .= '<img src="'.http.'img/img_icone_boneco_02'.$tp.'.png" height="'.$hg.'" title="'.$ai[$r][0].'">'.chr(13); }
					for ($y=0;$y < $ai[$r][3];$y++) 
						{ $sx .= '<img src="'.http.'img/img_icone_boneco_03'.$tp.'.png" height="'.$hg.'" title="'.$ai[$r][0].'">'.chr(13); }
						$sx .= '&nbsp;';
					}
				}

				/* legend */
				$sx .= '<TR><TD><TD colspan=2 class="lt0">'.chr(13);
				$sx .= $legend;				
				$sx .= '</table>'.chr(13);
			return($sx);
		}
		
		
	function gr01($v1, $v2, $cor) {

	}

	function imageCreateTransparent($x, $y) {
		$imageOut = imagecreate($x, $y);
		$colourBlack = imagecolorallocate($imageOut, 0, 0, 0);
		imagecolortransparent($imageOut, $colourBlack);
		return $imageOut;
	}

	function folha_texto($image, $x = 0, $y = 0, $texto = '', $size = 1, $cor = 'black') {
		$xcor = imagecolorallocate($image, 0x00, 0x00, 0x00);
		if ($cor == 'white') { $xcor = imagecolorallocate($image, 0xFF, 0xFF, 0xFF); }
		if ($cor == 'red') { $xcor = imagecolorallocate($image, 0xFF, 0x00, 0x00); }
		if ($cor == 'black') { $xcor = imagecolorallocate($image, 0x00, 0x00, 0x00); }
		if ($cor == 'gray') { $xcor = imagecolorallocate($image, 0x50, 0x50, 0x50); }
		

		// Allocate colors
		if ($size==1) { $font = imageloadfont('css/fontes/roboto12.gdf'); }
		if ($size==2) { $font = imageloadfont('css/fontes/roboto18.gdf'); }
		if ($size==3) { $font = imageloadfont('css/fontes/roboto24.gdf'); }
		if ($size==4) { $font = imageloadfont('css/fontes/roboto32.gdf'); }
		if ($size==5) { $font = imageloadfont('css/fontes/roboto48.gdf'); }
		imagestring($image, $font, $x, $y, $texto, $xcor);
		return ($image);
	}

	function boneco($image, $x = 10, $y = 10, $s = 300, $cor ='') 
	{
		//$s = 20;	
		$s = round($s/2)*2;
		$r = $s;
		$r2 = round($r/2);
		$r4 = round($r/4);
		$r8 = round($r/8);
				
		
		/* Cabeca */
		imagefilledarc($image, $x, $y, $r, $r, 0, 360, $cor, IMG_ARC_PIE);
		
		/* Corpo */
		imagefilledrectangle($image, $x - $r2 , $y+$r-$r2, $x + $r2, $y+$r*5, $cor);
		imagefilledrectangle($image, $x - $r + $r4  , $y+$r-$r2, $x + $r - $r4, $y+$r*1, $cor);
		imagefilledrectangle($image, $x - $r2 - $r2 , $y+$r-$r4, $x + $r, $y+$r*1, $cor);		
		imagefilledrectangle($image, $x - $r8 , $y+$r*3, $x + $r8, $y+$r*5, $white);
		
		/* Ombro */
		imagefilledarc($image, $x - $r + $r4  , $y	+$r2 + $r4, $r2, $r2, 180, 270, $cor, IMG_ARC_PIE);
		imagefilledarc($image, $x + $r - $r4  , $y	+$r2 + $r4, $r2, $r2, 270, 360, $cor, IMG_ARC_PIE);
		
		/* Bracos */
		imagefilledrectangle($image, $x - $r, $y+$r, $x - $r + $r4 , $y+$r2*5, $cor);
		imagefilledrectangle($image, $x + $r, $y+$r, $x + $r - $r4 , $y+$r2*5, $cor);
		
		/* Mao */
	
		imagefilledarc($image, $x - $r + $r8 , $y+$r2*5, $r4, $r4, 0, 360, $cor, IMG_ARC_PIE);
		imagefilledarc($image, $x + $r - $r8 , $y+$r2*5, $r4, $r4, 0, 360, $cor, IMG_ARC_PIE);
//		imagefilledarc($image, $x + $s75 + $s75m - $s10, $y+$md+$md*4, $r, $r, 0, 360, $navy, IMG_ARC_PIE);

		/* PERNAS */
		

		return ($image);
	}

	function convert_svg_jpg($file) {

	}

	function folha($image, $x = 10, $y = 10, $s = 100, $tam = 10) {
		$darkgray = imagecolorallocate($image, 0x90, 0x90, 0x90);
		$lime = imagecolorallocate($image, 0xA7, 0xEa, 0x05);
		$navy = imagecolorallocate($image, 0x00, 0x00, 0x80);
		
		
		$s2 = round($s / 2);

		$x = $x + $s2;
		$y = $y + $s2;

		imagefilledarc($image, $x, $y, $s, $s, 180, 270, $lime, IMG_ARC_PIE);
		imagefilledarc($image, $x + $tam, $y, $s, $s, 0, 90, $lime, IMG_ARC_PIE);

		imagefilledrectangle($image, $x, $y - $s2, $x + $s2 + $tam, $y, $lime);
		imagefilledrectangle($image, $x - $s2, $y, $x, $y + $s2, $lime);

		if ($tam > 0) {
			imagefilledrectangle($image, $x, $y - $s2, $x + $tam, $y + $s2, $lime);
		}
		//imagefilledrectangle($image, $x-$s2 , $y, $x, $y+$s2, $lime );
		//imagefilledrectangle($image, $x + $s , $y-$s2, $x +$s + $s2, $y, $lime );

		return ($image);
	}

	function pie($image) {
		// allocate some colors
		$white = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
		$gray = imagecolorallocate($image, 0xC0, 0xC0, 0xC0);
		$darkgray = imagecolorallocate($image, 0x90, 0x90, 0x90);
		$navy = imagecolorallocate($image, 0x00, 0x00, 0x80);
		$darknavy = imagecolorallocate($image, 0x00, 0x00, 0x50);
		$red = imagecolorallocate($image, 0xFF, 0x00, 0x00);
		$darkred = imagecolorallocate($image, 0x90, 0x00, 0x00);
		$lime = imagecolorallocate($image, 0xA7, 0xEa, 0x05);
		// make the 3D effect
		for ($i = 60; $i > 50; ($i = $i - 1)) {
			imagefilledarc($image, 50, $i, 400, 200, 0, 45, $darknavy, IMG_ARC_PIE);
			imagefilledarc($image, 50, $i, 400, 200, 45, 75, $darkgray, IMG_ARC_PIE);
			imagefilledarc($image, 50, $i, 400, 200, 75, 360, $darkred, IMG_ARC_PIE);
		}

		imagefilledarc($image, 50, 50, 400, 200, 0, 45, $navy, IMG_ARC_PIE);
		imagefilledarc($image, 50, 50, 400, 200, 45, 75, $gray, IMG_ARC_PIE);
		imagefilledarc($image, 50, 50, 400, 200, 75, 360, $red, IMG_ARC_PIE);
		return ($image);
	}

	function create_image($x, $y) {
		global $img;
		$x = round($x);
		$y = round($y);
		if ($x == 0) { $x = 640; }
		if ($y == 0) { $y = 480; }
		$img = $this -> imageCreateTransparent($x, $y);
		return ($img);
	}

	function circulo($img, $x, $y) {

		$navy = imagecolorallocate($image, 0x00, 0x00, 0x80);
		imagefilledarc($img, 50, 50, 100, 50, 0, 45, $navy, IMG_ARC_PIE);
		return ($img);
	}

}

// allocate some colors
//$white = imagecolorallocate($img, 255, 255, 255);
//$red   = imagecolorallocate($img, 255,   0,   0);
//$green = imagecolorallocate($img,   0, 255,   0);
//$blue  = imagecolorallocate($img,   0,   0, 255);
//
//// draw the head
//imagearc($img, 100, 100, 200, 200,  0, 360, $white);
//// mouth
//imagearc($img, 100, 100, 150, 150, 25, 155, $red);
//// left and then the right eye
//imagearc($img,  60,  75,  50,  50,  0, 360, $green);
//imagearc($img, 140,  75,  50,  50,  0, 360, $blue);
//
//// output image in the browser
//header("Content-type: image/png");
//imagepng($img);

// free memory
//imagedestroy($img);
?>