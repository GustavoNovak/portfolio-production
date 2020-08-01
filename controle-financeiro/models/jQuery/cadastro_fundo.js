$(function(){ 
	$('#vencimento').hide();
	$('#fechamento').hide();

	$('select[name=tipo]').change(function(){

		var tipo = $(this).val();

		if(tipo == '3') {
			$('#vencimento').slideDown('fast');
			$('#fechamento').slideDown('fast');
			$('#saldo').slideUp('fast');

		} else {
			$('#vencimento').slideUp('fast');
			$('#fechamento').slideUp('fast');
			$('#saldo').slideDown('fast');

			$('input[name=vencimento]').val('');
			$('input[name=fechamento]').val('');
		}
		
	});

});