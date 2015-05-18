<table width="100%">
	<tr>
	<?php
	echo '<B>OAI-PMH 2.0</B> | ';
	echo '<A HREF="'.base_url('oai/Identify/'.$id).'">Identify</A>';
	echo ' | ';
	echo '<A HREF="'.base_url('oai/ListRecords/'.$id).'">ListRecords</A>';
	echo ' | ';
	echo '<A HREF="'.base_url('oai/ListSets/'.$id).'">ListSets</A>';
	echo ' | ';
	echo '<A HREF="'.base_url('oai/ListMetadataFormats/'.$id).'">ListMetadataFormats</A>';
	echo ' | ';
	echo '<A HREF="'.base_url('oai/ListIdentifiers/'.$id).'">ListIdentifiers</A>';
	echo ' | ';
	echo '<A HREF="'.base_url('oai/Harvesting/'.$id).'">HarvestingRecords</A>';	
	?>
</table>
