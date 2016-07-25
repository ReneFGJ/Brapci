<div class="container" id="formFiled">
	<form id="formWrapper" method="get" style="min-width: 600px; width: 100%;">
		<div class="row">
			<div class="col-md-9">
				<h4><?php echo msg('search_info');?></h4>
				<div class="input-group">					
					<input type="text" class="form-control" placeholder="Search for...">
					<span class="input-group-btn">
						<button class="btn btn-primary" type="button">
							<?php echo msg('search'); ?>
						</button> </span>
				</div><!-- /input-group -->
			</div>
			<div class="col-md-3">
				<?php
				echo '<h4>'.msg('publication_type').'</h4>';
				/***************************************************************** Journal Type ****************************/
				$sql = "select * from brapci_journal_tipo where jtp_ativo = 1 order by jtp_ordem";
				$rlt = $this->db->query($sql);
				$rlt = $rlt->result_array();
				for ($r=0;$r < count($rlt);$r++)
					{
						$line = $rlt[$r];
						$id = $line['id_jtp'];
						if (isset($_SESSION['tp_'.$line['id_jtp']]))
							{
								if ($_SESSION['tp_'.$line['id_jtp']] == 0)
									{
										$check = '';
									} else {
										$check = 'checked';		
									}
							} else {
								$check = 'checked';
							}
						echo '<input type="checkbox" '.$check.'> ';
						echo $line['jtp_descricao'].'<br>'.cr();
					}
				?>
				
			<!---------------------------------------------------------------------------- YEARS ----------->
			<?php
			/******* years *************/
			$data1 = '';
			$data2 = '';
			$anof = 1972;
			if (isset($_SESSION['anoi']))
				{
					$ano1 = $_SESSION['anoi'];		
				} else {
					$ano1 = $anof;
				}
			if (isset($_SESSION['anof']))
				{
					$ano2 = $_SESSION['anof'];		
				} else {
					$ano2 = date("Y");
				}
				
			/******************************************************** Mount Form Select ****************/			
			for ($r=$anof; $r <= (date("Y")+1);$r++)
				{
					$chk = '';
					if ($r == round($ano1)) { $chk = 'selected'; }					
					$data1 .= '<option value="'.$r.'" '.$chk.'>'.$r.'</option>'.cr();
				}
			for ($r=(date("Y")+1);$r >= $anof;$r--)
				{
					$chk = '';
					if ($r == round($ano2)) { $chk = 'selected'; }
					$data2 .= '<option value="'.$r.'" '.$chk.'>'.$r.'</option>'.cr();
				}
			?>
			<br>
			<h4><?php echo msg('form_year_cut');?></h4>
			<select>
				<?php echo $data1;?>
			</select> 
			-
			<select>
				<?php echo $data2;?>
			</select> 
			</div>			
		</div> <!--- ROW -->
		<div class="row">

			<div class="col-md-2">
				<input type="checkbox" name="dd3" >
			</div>
			<div class="col-md-2">
				<input type="checkbox" name="dd3" >
			</div>
			<div class="col-md-2">
				<input type="checkbox" name="dd3" >
			</div>
			<div class="col-md-2">
				<input type="checkbox" name="dd3" >
			</div>
			<div class="col-md-2">
				<input type="checkbox" name="dd3" >
			</div>				
		</div>
	</form>
</div>
