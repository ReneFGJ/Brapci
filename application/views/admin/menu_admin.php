<?php
$bt = array();
$bt['publications'] = 'admin/journal';

$bt['tools'] = 'admin/tools';

$bt['oai'] = 'oai';

$bt['authority'] = 'author/row';
$bt['vocabulary'] = 'vocabulario/row';

$bt['cited'] = 'cited';

echo '<h1>Admin Menu</h1>';

foreach ($bt as $key => $value) {
	$icone = '<img src="'.base_url('img/icon/icone_'.$key.'.png').'" align="left;" style="margin: 0px 5px 0px 0px; vertical-align: top;" height="40px">';
	echo '<a href="'.base_url('index.php/'.$value).'" class="link" style="cursor: pointer;">';
	echo '<div class="bt_admin">'.$icone.$key.'</div>';
	echo '</a>';	
}
?>
<style>
	.bt_admin
		{
			float: left;
			width: 300px;
			height: 70px;
			background-color: #F0F0F0;
			border: 1px solid #c0c0c0;
			border-radius: 10px;
			padding: 5px 10px 5px 10px;
			margin: 10px 10px 0px 0px;	
			vertical-align: top;
		}
</style>
