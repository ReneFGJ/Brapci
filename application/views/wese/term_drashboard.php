<table width="1024" border=0 align="center">
	<tr valign="top">
		<td width="40%"><?php echo $terms; ?></td>
		<td width="60%"><?php echo $terms_content; ?></td>
	</tr>
</table>

<script>
					function allowDrop(ev) {
		ev.preventDefault();
	}

	function drag(ev) {
		/*
		 ev.dataTransfer.setData("text", ev.target.id);
		 */
	}

	function drop(ev) {
		ev.preventDefault();
		var data = ev.dataTransfer.getData("text");
		ajax_narrow(data);
		//ev.target.appendChild(document.getElementById(data));
	}

	function ajax_narrow($link) {
		$("#narrower").html($link);
	    $.post("<?php echo base_url('index.php/skos/narrows_add/'); ?>",
			{
				verb: "NARROWED",
				link: $link
			},
			function(data, status){
			$("#narrower").html(data);
			});
		}
</script>