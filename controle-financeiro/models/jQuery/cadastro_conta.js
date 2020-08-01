$(function(){ 

	$('select[name=conta]').change(function(){

		var conta_var = $(this).val();
		var url = $(this).attr('data-url');

		window.location.href = url+'cadastro/valores_conta/'+conta_var;
		
	});

});