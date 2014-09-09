<?php
class svg {
	var $img = '';
	var $cr = '';
	var $x = 100;
	var $y = 100;
	var $def = array();
	var $g = array();
	var $color = '#000000';
	var $fill = '#80800';
	var $ids = array();

	function size($x = 0, $y = 0) {
		$sx = 0;
		if ($x > 0) { $this -> x = $x;
			$sx = 1;
		}
		if ($y > 0) { $this -> y = $y;
			$sx = 1;
		}
		return ($sx);
	}

	function __construct() {
		$this -> cr = chr(13) . chr(10);
	}

	function mostra_defs() {
		$sx = '';
		$defs = $this -> def;
		for ($r = 0; $r < count($defs); $r++) {

		}
		$sx = '  
		<radialGradient gradientTransform="matrix(1 0 0 1 0 0)" gradientUnits="userSpaceOnUse" id="blue" r="797.677537" cy="126.875" cx="296.25">
   			<stop stop-color="rgb(0, 0, 255)" offset="0"/>
   			<stop stop-color="rgb(0, 0, 0)" offset="1"/>
  		</radialGradient>';
		return ($sx);
	}

	function mostra_g() {
		$img = '';
		$g = $this -> g;
		for ($r = 0; $r < count($g); $r++) {
			$type = $g[$r][0];
			$id = $g[$r];
			switch ($type) {
				case 'rect' :
					$img .= $this -> svg_rect($id);
					break;
				case 'arc' :
					$img .= $this -> svg_arc($id);
					break;
				case 'line' :
					$img .= $this -> svg_line($id);
					break;
				case 'text' :
					$img .= $this -> svg_text($id);
					break;
			}
		}
		return ($img);
	}

	function save($file = 'sample.svg') {
		$rlt = fopen($file, "w+");
		fwrite($rlt, $this -> img());
		fclose($rlt);
		return (1);
	}

	function svg_line($id) {
		$idn = $id[0];
		$x1 = $id[2];
		$x2 = $id[4];
		$y1 = $id[3];
		$y2 = $id[5];
		$sx = '<line id="' . $id . '" ';
		$sx .= 'y2="' . $y2 . '" ';
		$sx .= 'x2="' . $x2 . '" ';
		$sx .= 'y1="' . $y1 . '" ';
		$sx .= 'x1="' . $x1 . '" ';
		$sx .= 'stroke-linecap="null" ';
		$sx .= 'stroke-linejoin="null" ';
		$sx .= 'stroke-dasharray="null" ';
		$sx .= 'stroke-width="1" ';
		$sx .= 'stroke="#000000" ';
		$sx .= 'fill="none"';
		$sx .= '/>';
		$sx .= $this -> cr;
		return ($sx);
	}

	function svg_arc($id) {
		$idn = $id[0];
		$x = $id[2];
		$y = $id[3];
		$r = $id[4];
		$sx .= '<circle id="' . $idn . '" ';
		$sx .= 'r="'.$r.'" ';
		$sx .= 'cy="'.$x.'" ';
		$sx .= 'cx="'.$y.'" ';
		$sx .= 'stroke-linecap="null" ';
		$sx .= 'stroke-linejoin="null" ';
		$sx .= 'stroke-dasharray="null" ';
		$sx .= 'stroke-width="1" ';
		$sx .= 'stroke="#000000" ';
		$sx .= 'fill="url(#blue)"';
		$sx .= '/>';
		$sx .= $this -> cr;
		return ($sx);
	}

	function svg_rect($id) {
		$idn = $id[0];
		$x = $id[2];
		$y = $id[3];
		$w = $id[4];
		$h = $id[5];
		$sx = '<rect id="' . $idn . '" ';
		$sx .= 'height="' . $h . '" ';
		$sx .= 'width="' . $w . '" ';
		$sx .= 'y="' . $y . '" ';
		$sx .= 'x="' . $x . '" ';
		$sx .= 'stroke-linecap="null" ';
		$sx .= 'stroke-linejoin="null" ';
		$sx .= 'stroke-dasharray="null" ';
		$sx .= 'stroke-width="1" ';
		$sx .= 'stroke="#000000" ';
		$sx .= 'fill="url(#blue)" ';
		$sx .= '/>';
		$sx .= $this -> cr;
		return ($sx);
	}

	function svg_text($id) {
		$idn = $id[0];
		$x = $id[2];
		$y = $id[3];
		$sx .= '<text xml:space="preserve" ';
		$sx .= 'text-anchor="left" ';
		$sx .= 'font-family="Roboto, Tahoma, Arial, Sans-serif" ';
		$sx .= 'font-size="12" ';
		$sx .= 'id="'.$id.'" ';
		$sx .= 'y="'.$y.'" ';
		$sx .= 'x="'.$x.'" ';
		$sx .= 'stroke-linecap="null" ';
		$sx .= 'stroke-linejoin="null" ';
		$sx .= 'stroke-dasharray="null" ';
		$sx .= 'stroke-width="0" ';
		$sx .= 'stroke="#000000" ';
		$sx .= 'fill="#000000">';
		$sx .= $id[4];
		$sx .= '</text>';
		$sx .= $this -> cr;
		return ($sx);
	}

	function line_from_to($o1, $o2) {
		$ids = $this -> ids;
		$ox1 = -1;
		$ox2 = -1;
		for ($r = 0; $r < count($ids); $r++) {
			if ($o1 == $ids[$r][0]) { $ox1 = $r;
			}
			if ($o2 == $ids[$r][0]) { $ox2 = $r;
			}
		}
		if (($ox1 < 0) or ($ox2 < 0)) {
			echo 'Object ' . $o1 . ' or ' . $o2 . ' not found';
			exit ;
		}
		$x1 = $ids[$ox1][1];
		$y1 = $ids[$ox1][2];
		$x2 = $ids[$ox2][1];
		$y2 = $ids[$ox2][2];
		$this -> line_add($x1, $y1, $x2, $y2);
		return (1);
	}

	function line_add($x = 0, $y = 0, $w = 0, $h = 0, $id = '') {
		$g = $this -> g;
		if (strlen($id) == 0) { $id = 'svg' . count($g);
		}
		array_push($g, array('line', $id, $x, $y, $w, $h, $color, $fill));
		$this -> g = $g;
		/* Ids */
		$xx = -1;
		$yy = -1;
		$this -> add_ids($id, $xx, $yy);
	}

	function text_add($texto, $x = 0, $y = 0) {
		$g = $this -> g;
		if (strlen($id) == 0) { $id = 'svg' . count($g);
		}
		array_push($g, array('text', $id, $texto, $x, $y));
		$this -> g = $g;
		/* Ids */
		$xx = -1;
		$yy = -1;
		$this -> add_ids($id, $xx, $yy);
	}

	function rect_add($x = 0, $y = 0, $w = 0, $h = 0, $id = '') {
		$g = $this -> g;
		if (strlen($id) == 0) { $id = 'svg' . count($g);
		}
		array_push($g, array('rect', $id, $x, $y, $w, $h, $color, $fill));
		$this -> g = $g;
		/* Ids */
		$xx = ($x + (int)($w / 2));
		$yy = ($y + (int)($h / 2));
		$this -> add_ids($id, $xx, $yy);
	}

	function arc_add($x = 0, $y = 0, $r = 1, $id = '') {
		$g = $this -> g;
		if (strlen($id) == 0) { $id = 'svg' . count($g);
		}
		array_push($g, array('arc', $id, $y, $x, $r, $color, $fill));
		$this -> g = $g;

		/* Ids */
		$xx = ($x + (int)($r / 2));
		$yy = ($y + (int)($r / 2));
		$this -> add_ids($id, $xx, $yy);
	}

	function add_ids($id, $xx, $yy) {
		$ids = $this -> ids;
		array_push($ids, array($id, $xx, $yy));
		$this -> ids = $ids;
		return (1);
	}

	function img() {
		$cr = $this -> cr;
		$img = '<svg width="' . $this -> x . '" height="' . $this -> y . '" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg">' . $cr;
		$img .= $this -> mostra_defs();
		$img .= $this -> mostra_g();
		$img .= '</svg>' . $cr;
		return ($img);
	}

	function create($x = 100, $y = 100) {
		$this -> x = $x;
		$this -> y = $y;
	}

}
?>