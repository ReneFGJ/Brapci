<?php
class body
	{
		
	}
?>
<div id="wait"><div id="wait_img"><center><span id="wait_text">Loading...</center></span></div></div>
<script>
function wait()
	{
		$("body").css("cursor", "progress");
		$("#wait").show(); 
	}
function nowait()
	{
		$("#wait").hide();
		$("body").css("cursor", "auto");
	}
</script>

<style>
#wait {
    display:    none;
    position:   fixed;
    z-index:    1000;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
}
#wait_text
	{
		font-family:Verdana, Geneva, Arial, Helvetica, sans-serif;
		font-size: 12px;
		color: #303030;
	}
#wait_img
	{
    position:   fixed;
    z-index:    1000;
    top:        40%;
    left:       40%;
    height:     20%;
    width:      20%;
	border-style:solid;
	border-width:5px;
	border-color:rgba(200, 200, 200, .8);
    background: rgba( 255, 255, 255, .8 ) 
                url('<?php echo $include.'img/icone_wait_01.gif';?>') 
                50% 60% 
                no-repeat;
	}
</style>

