<div class="container" style="background-color: #ffffff; border:1px solid; border-radius: 20px; padding: 30px 0px;">
	<div class="col-md-10">
		<?php echo $tela; ?>
	</div>
	<div class="col-md-2">
		<?php if (isset($tela2)) { echo $tela2; } ?>
	</div>
	<script>
				function abstractshow(ms) {
		$(ms).toggle("slow");
		}

		function mark(ms, ta) {
		var ok = ta.checked;
		$.ajax({
		type : "POST",
		url : "<?php echo base_url('index.php/publico/mark/'); ?>
			",
			data : {
			dd1 : ms,
			dd2 : ok
			}
			}).done(function(data) {
			$("#basket").html(data);
			});
			}
	</script>
</div>
<br>
<br>
<br>
<br>
