<?php
if (!isset($title_page)) { $title_page = 'Home'; }
$this -> load -> view("header/header");
$this -> load -> view('header/analytics.google.php');
?>
<div id="cab_admin" class="cab_admin">
	<div id="cab_title" class="cab_title"><?php echo $title_page;?></div>
	<div id="cab_logo_1" class="cab_logo cab_admin_logo_01"></div>
	<div id="cab_logo_2" class="cab_logo cab_admin_logo_02"></div>  
</div>

<A HREF="<?php echo base_url('/admin');?>" class="link_white">ADMIN</A> | <A HREF="<?php echo base_url('/admin/journal');?>" class="link_white">JOURNALS</A>
<BR><BR>
<script>
	/* $("body").addClass("margin120"); -*/
	var $offset = 0;
	$(document).on("scroll", function() {

		var $logo1 = $("#cab_logo_1");
		var $logo2 = $("#cab_logo_2");
		var $cab = $("#cab_admin");
		
		var offset = $(document).scrollTop();
		
		if (offset > 1) {
			if ($offset == 0)
			{
				$($cab).animate({ top : "0px", height: "30px" }, 500);
				$($logo1).animate({ top : "-100px", height: "10px" }, 500);
				$($logo2).animate({ top : "0px", height: "41px" }, 500);
				$offset = 1;
			}
			
		} else {			
			$offset = 0;
			$($cab).animate({ top : "0px", height: "90px" }, 500);
			$($logo1).animate({ top : "0px", height: "90px" }, 500);
			$($logo2).animate({ top : "-100px", height: "90px" }, 500);
		}
	}); 
</script>

<div id="content">
