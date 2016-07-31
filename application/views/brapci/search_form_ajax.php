		<div class="container">


			<h2>Dynamic Width</h2>
			<p>
				Type country name in english:
			</p>
			<div>
				<input type="text" name="country" id="autocomplete-dynamic" style="width: 100%; border-width: 5px;"/>
			</div>
		</div>
		<script src="<?php echo base_url('js/jquery.autocomplete.js'); ?>"></script>
		<script src="<?php echo base_url('js/jquery.mockjax.js'); ?>"></script>
		<script src="<?php echo base_url('js/autor_complete.js'); ?>"></script>
		<script src="<?php echo base_url('js/demo/demo.js'); ?>"></script>

<style>
	body {
		font-family: sans-serif;
		font-size: 14px;
		line-height: 1.6em;
		margin: 0;
		padding: 0;
	}
	.container {
		width: 800px;
		margin: 0 auto;
	}

	.autocomplete-suggestions {
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
		border: 1px solid #999;
		background: #FFF;
		cursor: default;
		overflow: auto;
		-webkit-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64);
		-moz-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64);
		box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64);
	}
	.autocomplete-suggestion {
		padding: 2px 5px;
		white-space: nowrap;
		overflow: hidden;
	}
	.autocomplete-no-suggestion {
		padding: 2px 5px;
	}
	.autocomplete-selected {
		background: #F0F0F0;
	}
	.autocomplete-suggestions strong {
		font-weight: bold;
		color: #000;
	}
	.autocomplete-group {
		padding: 2px 5px;
	}
	.autocomplete-group strong {
		font-weight: bold;
		font-size: 16px;
		color: #000;
		display: block;
		border-bottom: 1px solid #000;
	}

	input {
		font-size: 28px;
		padding: 10px;
		border: 1px solid #CCC;
		display: block;
		margin: 20px 0;
	}

</style>