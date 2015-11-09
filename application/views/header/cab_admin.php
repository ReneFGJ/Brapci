<?php
if (!isset($title_page)) { $title_page = 'Home';
}
$this -> load -> view("header/header");
$this -> load -> view('header/analytics.google.php');

$user = $this -> session -> userdata('user');
$email = $this -> session -> userdata('email');
$nivel = $this -> session -> userdata('nivel');

/* Segurança */
if ($nivel < 5) { redirect(base_url('index.php/home')); }

?>
<div id="cab_admin" class="cab_admin">
	<table width="98%" border=0 align="center">
		<tr valign="top">
			<td class="lt1" >
			<ul id="nav_cab">
				<li class="nav_menu">
					<a href="<?php echo base_url("index.php/home"); ?>">home</a>
				</li>				
				
				<?php
				echo '<li class="nav_menu">';
				if (strlen($user) > 0) {
					echo '<a href="' . base_url("index.php/social/logout") . '"><font color="#9090ff">logout<font></a>';
				} else {
					echo '<a href="' . base_url("index.php/social/login") . '">login</a>';
				}
				echo '</li>';

				/* Admin */
				if ($nivel == 9) {
					echo '<li class="nav_menu">';
					echo '<a href="' . base_url("index.php/admin/journal") . '">'.msg('publications').'</a>';
					echo '</li>' . cr();
					
					echo '<li class="nav_menu">';
					echo '<a href="' . base_url("index.php/author/row") . '">'.msg('autoridades').'</a>';
					echo '</li>' . cr();
					
					echo '<li class="nav_menu">';
					echo '<a href="' . base_url("index.php/vocabulario/row") . '">'.msg('vocabulario').'</a>';
					echo '</li>' . cr();					

					echo '<li class="nav_menu">';
					echo '<a href="' . base_url("index.php/admin/oai") . '">'.msg('OAI').'</a>';
					echo '</li>' . cr();		

					echo '<li class="nav_menu">';
					echo '<a href="' . base_url("index.php/admin/tools") . '">'.msg('tools').'</a>';
					echo '</li>' . cr();							
				}
				?>					
			</ul></td>
			<td>
					<div id="cab_logo_1" class="cab_logo cab_admin_logo_01"></div>
					<div id="cab_logo_2" class="cab_logo cab_admin_logo_02"></div> 
			</td>
		</tr>
		<tr>
			<td class="lt1"><br><font color="#C0C0C0" style="margin-left: 10px;"><?php echo $user . ' (' . $email . ')'; ?></font></td>
		</tr>
	</table>	
</div>

<script>
	/* $("body").addClass("margin120"); -*/
	var $offset = 0;
	$(document).on("scroll", function() {

		var $logo1 = $("#cab_logo_1");
		var $logo2 = $("#cab_logo_2");
		var $cab = $("#cab_admin");

		var offset = $(document).scrollTop();

		if (offset > 1) {
			if ($offset == 0) {
				$($cab).animate({
					top : "0px",
					height : "30px"
				}, 500);
				$($logo1).animate({
					top : "-100px",
					height : "10px"
				}, 500);
				$($logo2).animate({
					top : "0px",
					height : "41px"
				}, 500);
				$offset = 1;
			}

		} else {
			$offset = 0;
			$($cab).animate({
				top : "0px",
				height : "90px"
			}, 500);
			$($logo1).animate({
				top : "0px",
				height : "90px"
			}, 500);
			$($logo2).animate({
				top : "-100px",
				height : "90px"
			}, 500);
		}
	});
</script>

<div id="content">
