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
			url : "article_mark.php",
			data : {
				dd1 : ms,
				dd2 : ok
			}
		}).done(function(data) {
			$("#basket").html(data);
		});
	}
</script>
