<br>
<br>
<div class="container">
	<div class="row">
		<div class="col-md-12 text-center">
			<span class="roboto superbig"> <?php echo msg('authority_search'); ?></span>
		</div>
	</div>
	<div class="row">
		<div class="col-md=12 nopr">
			<br><br>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 text-center">
			<?php echo form_open(); ?>
			<div class="input-group">
				<input type="text" class="form-control" name="search" placeholder="<?php echo msg('Search for...');?>" value="<?php echo get("search");?>">
				<span class="input-group-btn">
					<input type="submit" name="acao" class="btn btn-primary" value="<?php echo msg('search');?>">
				</span>
			</div>
			<?php echo '</form>'; ?>
		</div>
	</div>
</div>
