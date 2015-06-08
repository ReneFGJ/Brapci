<?php
class tools extends CI_Model {
	function actions_buttons($status,$id_ar) {
		$btns = array();
		$btns[0] = '<td><input id="bt_B" type="button" value="PARA 1º REVISÃO" class="botao3d back_green back_green_shadown" onclick="alterar_status(\'B\',\'Primeira Revisão\');"></td>';
		$btns[1] = '<td><input id="bt_C" type="button" value="PARA 2º REVISÃO" class="botao3d back_green back_green_shadown" onclick="alterar_status(\'C\',\'Segunda Revisão\');"></td>';
		$btns[2] = '<td><input id="bt_D" type="button" value="FINALIZAR" class="botao3d back_green back_green_shadown" onclick="alterar_status(\'D\',\'Finalizar Revisão\');"></td>';
		$btns[3] = '<td><input id="bt_X" type="button" value="CANCELAR TRABALHO" class="botao3d back_red back_red_shadown" onclick="alterar_status(\'X\',\'Cancelado\');"></td>';
		$btns[4] = '<td><input id="bt_A" type="button" value="REENVIAR PARA INDEXAÇÃO" class="botao3d back_blue back_blue_shadown" onclick="alterar_status(\'A\',\'Revisão da Reindexação\');"></td>';

		switch ($status) {
			case 'A' :
				$btns[1] = '';
				$btns[4] = '';
				break;
			case 'B' :
				$btns[0] = '';
				$btns[2] = '';
				break;
			case 'C' :
				$btns[0] = '';
				$btns[1] = '';
				break;				
			case 'X' :
				$btns[0] = '';
				$btns[1] = '';
				break;
			case 'D' :
				$btns[0] = '';
				$btns[1] = '';
				$btns[2] = '';
				$btns[3] = '';
				break;
		}

		$sx = '
				<table cellspacing=10 class="border01">
					<tr>
					<td colspan=3>Enviar para:
					<tr>' . $btns[0] . $btns[1] . $btns[2] . $btns[3] . $btns[4] . '</tr>
				</table>';

		$sx .= '
				<script>
					function alterar_status(sta,stb)
						{
							rsp = confirm(\'Confirmar alteração do estatus para \' + stb);
							if (rsp)
								{
									window.location.assign("' . base_url('admin/article_view/' . $id_ar . '/' . checkpost_link($id_ar)) . '/"+sta);
								}
						}
					</script>
					';
		return ($sx);
	}

	function progress_bar($status) {
		$pb = array();
		$pb[0] = array('', 'Indexação', 'Indexação do trabalho');
		$pb[1] = array('', '1º Revisão', '1º Revisão');
		$pb[2] = array('', '2º Revisão', '2º Revisão');
		$pb[3] = array('', 'Concluído', 'Concluído');

		switch ($status) {
			case 'A' :
				$pb[0][0] = '_active';
				break;
			case 'B' :
				$pb[0][0] = '_executed';
				$pb[1][0] = '_active';
				break;
			case 'C' :
				$pb[0][0] = '_executed';
				$pb[1][0] = '_executed';
				$pb[2][0] = '_active';
				break;
			case 'D' :
				$pb[0][0] = '_executed';
				$pb[1][0] = '_executed';
				$pb[2][0] = '_executed';
				$pb[3][0] = '_active';
				break;
		}

		$pb[4] = array('pbar_nr', 'Cancelado', 'Indexação do trabalho');
		$sx = '<div id="progress">';
		for ($r = 0; $r < count($pb); $r++) {
			$sx .= '
							<div class="pbar pbar' . $pb[$r][0] . '">
								<div class="pbar_nr' . $pb[$r][0] . '"></div>
								<B>' . $pb[$r][1] . '</B>
								<br><span class="lt0">' . $pb[$r][2] . '</span>
							</div>
						
						';
		}
		$sx .= '</div>';
		return ($sx);
	}

}
