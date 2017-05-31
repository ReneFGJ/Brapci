<div class="container">
	<div class="row">
		<?php 
		if (isset($tela2))
			{
				echo '
					<div class="col-md-9 col-sx-12">'.$tela.'</div>
					<div class="col-md-3 col-sx-12">'.$tela2.'</div>
				'; 			
			} else {
				echo '
					<div class="col-md-12">'.$tela.'</div>
				'; 				
			}
			
			if (isset($tela3))
				{
				echo '
					<div class="col-md-12"><h3>Obras citantes</h3></div>
					<div class="col-md-12">'.$tela3.'</div>
					';	
				}
		?>
	</div>
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

<br>
<br>
<br>
<br>
