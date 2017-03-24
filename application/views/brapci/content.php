<script>
	$.ajax({
	type : "POST",
	url : "<?php echo base_url('index.php/publico/selected');?>
		",
		data : {}
		}).done(function(data) {
		$("#basket").html(data);
		});
</script>
