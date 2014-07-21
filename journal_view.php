<?
require("cab.php");
require('_class/_class_journals.php');
$pb = new journals;

require('_class/_class_issue.php');
$issue = new issue;

$jname = troca($dd[0],'/','');

if (strlen($jname) > 0)
	{
		$ja = $pb->recupera_journal_pelo_path($jname);
		
		$jid = $ja[0];
		$tipo = $ja[1];
		
	}

echo '<div class="nav">';

if ($jid > 0)
		{
			switch ($tipo)
				{
				case 'J':
						$pb->le($jid);
						echo $pb->mostra_issue_menu();	
						echo $pb->mostra();
						break;
				default:
					echo 'Not found '.$jid;
				}
		} else {
			echo 'not found '.$jid;
		}

echo '</div>';
require("foot.php");
?>