<?
function progress($item, $pos) {
	$sx .= '<div id="stages"><ul>';
	for ($r = 0; $r < count($item); $r++) {

		if ($r < $pos) { $class = "visited";
		}
		if ($r == $pos) { $class = "active";
		}
		if ($r > $pos) { $class = "";
		}
		if ($pos==9) { $class = "cancel";
		}
		$sx .= '
				<li class="' . $class . '">
					' . $item[$r] . '
				</li>
				';
	}
	$sx .= '</ul></div>';
	return ($sx);
}
?>
<style>
	#stages {
		width: 1024px;
		height: 30px;
		margin: auto;
		margin-top: 40px;
		background-color: #fff;
		font-family: verdana, "sans-serif";
		font-size: 9px;
	}
	#stages ul {
		list-style: none;
		padding: 0px;
		margin: 0px;
	}
	#stages li {
		float: left;
		width: 25%;
		text-align: center;
		text-transform: uppercase;
		color: #333f33;
	}
	#stages ul > li:before {
		content: "*";
		font-size: 85px;
		line-height: 1px;
		display: block;
		color: #ededed;
		border-top: 1px solid #B0C0B0;
		margin: 0 0 15px 0;
	}
	#stages ul > li.active:before {
		color: #000000;
		text-shadow: 0px 0px 10px #56ff5f;
	}
	#stages ul > li.visited:before {
		color: #808080;
		text-shadow: 0px 0px 10px #808080;
	}
	#stages ul > li.cancel:before {
		color: #FF8080;
		text-shadow: 0px 0px 10px #FF8080;
	}
</style>
