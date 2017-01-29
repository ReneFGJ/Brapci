<div class="row" style="padding: 20px;">
	<div class="md-col-12">
		<?php
		$bt = array();
		$bt['publications'] = 'admin/journal';

		$bt['tools'] = 'admin/tools';

		$bt['oai'] = 'oai';

		$bt['authority'] = 'author/row';
		$bt['vocabulary'] = 'vocabulario/row';
		$bt['games'] = 'admin/games';

		$bt['cited'] = 'cited';

		$bt['skos'] = 'skos';

		$bt['autoindex'] = 'autoindex';

		echo '<h1>Admin Menu</h1>';

		foreach ($bt as $key => $value) {
			$icone = '<img src="' . base_url('img/icon/icone_' . $key . '.png') . '" align="left;" style="margin: 0px 5px 0px 0px; vertical-align: top;" height="40px">';
			echo '<div class="col-md-3 col-sm-2 col-xs-6">
			<a href="' . base_url('index.php/' . $value) . '" class="link" style="cursor: pointer;">
			<div class="bt_admin">' . $icone . $key . '</div>
			</a>
		  </div>' . cr();
		}
	?>
	</div>
</div>
<style>
	.bt_admin {
		float: left;
		width: 180px;
		height: 70px;
		background-color: #F0F0F0;
		border: 1px solid #c0c0c0;
		border-radius: 10px;
		padding: 5px 10px 5px 10px;
		margin: 10px 10px 0px 0px;
		vertical-align: top;
	}
</style>
