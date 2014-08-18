<?php
class ajax extends form
	{
	var $botao_class = 'botao-geral';
	
	function div($size=700,$height=500)
		{
			global $idf;
			if (!(isset($idf))) { $idf = 1; } else { $idf++; }
			$sx = '
			<div id="box'.$idf.'" 
								style="padding: 5px; position: absolute; 
										display: none;
										top: 140px; 
										left: 50%;
										margin-left: -'.round($size/2).'px;
										border:1px solid #808080; 
										background-color: #FFFFFF;" 
					>';
			$sx .= '</div>';
			return($sx);			
		}
	
	function botao_novo($page_ajax,$tabela,$id)
		{
			global $idf,$dd;
			$sx .= '<input type="button" 
						value="novo registro" 
						name="bt_pop" 
						id="box'.$idf.'a" 
						onclick="show_div(\'box'.$idf.'\',\''.$page_ajax.'\',700,\''.$tabela.'\',\''.$id.'\');"
						class="'.$this->botao_class.'">';
			$sx .= $this->js($page_ajax);
			return($sx);
		}

	function js()
		{
			$sx = '
			<script>
				function hidden_div(divname)
					{
						var obj = "#"+divname;
						$( obj ).animate({ height: "10px" } ,
								{ duration: 250, 
									complete: function() { $( obj ).hide() }
								} 
						);						
					}
				function show_div(divname, url, wd, tabela, id)
					{
						$("html, body").animate({ scrollTop: 0 }, "fast");
						var obj = "#"+divname;
						
						$( obj ).focus();
						$( obj ).show();
						$( obj ).animate({ width: wd, height: 400 } , 500 );
						ajaxshow(obj , url, tabela, id)
					}

				function ajaxshow(obj, link, tabela, id)
					{
						$.ajax({
							url: link,
							data: { dd0: id, dd1: tabela, dd89: obj }
							})
						.done(function (html) { $( obj ).html(html); });
					}
				</script>
				';
			return($sx);
		}	
	}
?>
