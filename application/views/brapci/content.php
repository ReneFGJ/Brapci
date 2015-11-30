<div class="menu">
	<div class="menu_01">
		<A HREF="<?php echo base_url('index.php/home');?>" class="link_menu"> <img src="<?php echo base_url('img/icone_home.png');?>" border=0 height="20" >&nbsp;
		HOME</A>
	</div>
	<div class="menu_01">
		<A HREF="#">&nbsp;
		-
	</div>
	<div class="menu_01">
		<A HREF="<?php echo base_url('index.php/home/cited');?>" class="link_menu" id="cited"> <img src="<?php echo base_url('img/icone_my_library.png');?>" border=0 height="20" >&nbsp;
		Busca por referências</A>
	</div>
</div><DIV ID="content_TOP"></div>
<BR>
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
<DIV id="conteudo">