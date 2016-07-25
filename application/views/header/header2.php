<?php
if (!isset($title)) { $title = 'Brapci';
}
?><head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="google-site-verification" content="VZpzNVBfl5kOEtr9Upjmed96smfsO9p4N79DZT38toA" />
<meta name="description" content="">
<link rel="shortcut icon" href="<?echo base_url('favicon.png'); ?>" />

<link rel="STYLESHEET" type="text/css" href="<?echo base_url('css/bootstrap.css'); ?>">
<link rel="STYLESHEET" type="text/css" href="<?echo base_url('css/brapci.css'); ?>">

<!--- Java Script -->
<script type="text/javascript" src="<?php echo base_url('js/jquery.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/bootstrap.min.js'); ?>"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->

<title><?php echo $title; ?></title>
</head>

<!-- Facebook -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v2.5&appId=547858661992170";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>