<form method="post" action="<?php echo base_url('index.php/skos/concecpt_create');?>">
	<input type="hidden" name="dd1" value="<?php echo $id;?>">
	<input type="submit" value="<?php echo msg('create_concept');?>">
</form>