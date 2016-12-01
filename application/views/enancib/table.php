<?php echo $table; ?>

<!-- Javascript -->

<script type="text/javascript">
	var $table = $('#fresh-table'),
	    $alertBtn = $('#alertBtn'),
	    full_screen = false;

	$().ready(function() {
		$table.bootstrapTable({
			toolbar : ".toolbar",

			showRefresh : false,
			search : true,
			showToggle : true,
			showColumns : true,
			pagination : true,
			striped : true,
			sortable : true,
			pageSize : 10,
			pageList : [10, 25, 50, 100],

			formatShowingRows : function(pageFrom, pageTo, totalRows) {
				//do nothing here, we don't want to show the text "showing x of y from..."
			},
			formatRecordsPerPage : function(pageNumber) {
				return pageNumber + " rows visible";
			},
			icons : {
				refresh : 'glyphicon glyphicon-refresh',
				toggle : 'glyphicon glyphicon-th-list',
				columns : 'glyphicon glyphicon-cog',
				detailOpen : 'glyphicon glyphicon-plus-sign',
				detailClose : 'glyphicon glyphicon-minus-sign'
			}
		});
	}); 
</script>
