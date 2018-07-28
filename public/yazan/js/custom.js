$('a[href="#"][data-top!=true]').click(function(e){
		e.preventDefault();
	});
$('.btn-search').click(function(e){
		e.preventDefault();
		$('#myModal').modal('show');
	});