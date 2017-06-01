<div class="container" style="padding-top: 20px;">
	<?php
	echo '<B>OAI-PMH 2.0</B> | ';
	echo '<A HREF="'.base_url('index.php/oai/Identify/'.$id).'">Identify</A>';
	echo ' | ';
	echo '<A HREF="'.base_url('index.php/oai/ListRecords/'.$id).'">ListRecords</A>';
	echo ' | ';
	echo '<A HREF="'.base_url('index.php/oai/ListSets/'.$id).'">ListSets</A>';
	echo ' | ';
	echo '<A HREF="'.base_url('index.php/oai/ListMetadataFormats/'.$id).'">ListMetadataFormats</A>';
	echo ' | ';
	echo '<A HREF="'.base_url('index.php/oai/ListIdentifiers/'.$id).'">ListIdentifiers</A>';
	echo ' | ';
	echo '<A HREF="'.base_url('index.php/oai/Harvesting/'.$id).'">HarvestingRecords</A>';	
	echo ' | ';
	echo '<A HREF="'.base_url('index.php/oai/ProcessRecords/'.$id).'">ProcessRecords</A>';	
	echo ' | ';
	echo '<A HREF="'.base_url('index.php/oai/ReScan/'.$id).'">ReScan</A>';
    echo ' | ';
    echo '<A HREF="'.base_url('index.php/admin/journal_view/'.round($id).'/'.checkpost_link(round($id))).'">Journal</A>';	
	?>
</div>
