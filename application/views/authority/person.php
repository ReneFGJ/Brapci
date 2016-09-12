<?php
global $hd_ns;
if (!isset($hd_ns)) {
	echo '<div clas="row">' . cr();
	echo '<div class="col-md-1 text-center"><span style="color: #000000">' . msg('type') . '</span></div>' . cr();
	echo '<div class="col-md-11"><span style="color: #000000">' . msg('name') . '</span></div>' . cr();
	echo '</div>' . cr();
	$hd_ns = true;
}
$flag = '';
if (strlen($autor_nacionalidade) > 0)
	{
		$flag = '<img src="'.base_url('img/flags/flag_'.$autor_nacionalidade.'.png').'" height="20" border=0>';
	}
$link = '<a href="'.base_url('index.php/author/view/'.$autor_alias).'" class="link" style="color: #333333;">';
if ($autor_codigo == $autor_alias) {
	echo '<div class="row" style="line-height: 200%; border-top: 1px solid #cccccc;">' . cr();
	echo '<div class="col-md-1"><span style="color: #8080ff">' . msg('authorized') . '</span></div>' . cr();
	echo '<div class="col-md-1">' . msg('person') . '</div>' . cr();
	echo '<div class="col-md-10"><b>' . $link.$autor_nome.'</a>' . '</b> '.$flag.'</div>' . cr();	
	echo '</div>' . cr();
} else {
	echo '<div class="row"  style="line-height: 200%; border-top: 1px solid #cccccc;">' . cr();
	echo '<div class="col-md-1"><span style="color: #ff8080">' . msg('reference') . '</span></div>' . cr();
	echo '<div class="col-md-1">&nbsp;</div>' . cr();
	echo '<div class="col-md-10"><i>' . $link. $autor_nome . '</a></i></div>' . cr();
	
	echo '</div>' . cr();
}
?>
