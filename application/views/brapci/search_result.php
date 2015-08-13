<?php
echo $tela;
?>

<script>
	function abstractshow(ms) {
		$(ms).toggle("slow");
	}

	function mark(ms, ta) {
		var ok = ta.checked;
		$.ajax({
			type : "POST",
			url : "<?php echo base_url('index.php/publico/mark/');?>",
			data : {
				dd1 : ms,
				dd2 : ok
			}
		}).done(function(data) {
			$("#basket").html(data);
		});
	}
</script>
