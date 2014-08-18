<div id="menuleft">
<img src="img/bt_down_mini.png" alt="" align="right"><center><?=$tit[0];?></center>
<UL id="mul">
<LI id="mli" style="height:5px;"></LI>
<?
for ($ra=0;$ra < count($menu);$ra++)
	{
	?>
    <LI id="mli">
	<? if (strlen($menu[$ra][1]) > 0) { ?><A HREF="<?=$menu[$ra][1];?>"> <? } ?><?=$menu[$ra][0];?></A>
    </LI>
    <?
	}
?>
</UL>
</div>