<div class="Wrapper">
	<?php echo msg('actions');?><br>
	<table width="600" cellpadding="0" cellspacing="0">
		<tr>
			<td width="80" align="center">
			<a href="<?php echo base_url('index.php/home/selection_save/'.($session));?>" target="_new">
				<img src="<?php echo base_url('img/icon/icone_save.png');?>" height="40" title="Save selection">
			</a>
			</td>
			
			<td width="80" align="center">
			<a href="<?php echo base_url('index.php/home/selection_send_email/'.($session));?>" target="_new">
				<img src="<?php echo base_url('img/icon/icone_send_email.png');?>" height="40" title="Send to e-mail">
			</a>
			</td>
			
			<td width="80" align="center">
			<a href="<?php echo base_url('index.php/home/selection_xls/'.($session));?>" target="_new">
				<img src="<?php echo base_url('img/icon/icone_excel_export.png');?>" height="40" title="Export to Excel (CSV)">
			</a>
			</td>
						
			<td width="*"></td>
		</tr>
	</table>
</div>