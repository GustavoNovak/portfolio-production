$(function(){ 

	$('input[name=parcelas]').val('1');
	$('input[name=valor]').val('0.00');

	$('select[name=tipo]').change(function(){

		var tipo = $(this).val();

		if(tipo == '3') {
			$('#vencimento').slideDown('fast');
			$('#fechamento').slideDown('fast');
			$('#saldo').slideUp('fast');

			$('input[name=saldo]').val('');
		} else {
			$('#vencimento').slideUp('fast');
			$('#fechamento').slideUp('fast');
			$('#saldo').slideDown('fast');

			$('input[name=vencimento]').val('');
			$('input[name=fechamento]').val('');
		}
		
	});

	$('input[name=valor]').change(function(){
		var valor_parcela = $(this).val() * $('input[name=parcelas]').val();

		$('.valor_parcela').html("Valor total: "+valor_parcela.toLocaleString('pt-BR',{ style: 'currency', currency: 'BRL' }));
	});
	$('input[name=parcelas]').change(function(){
		var valor_parcela = $('input[name=valor]').val() * $(this).val();;

		$('.valor_parcela').html("Valor total: "+valor_parcela.toLocaleString('pt-BR',{ style: 'currency', currency: 'BRL' }));

	});	

	$('select[name=fundo]').change(function(){

		alterar_fundo();
		
	});	

	alterar_fundo();

	$('select[name=lancamento_futuro]').change(function(){
		if($(this).val() == 0) {
			var valor = $('select[name=fundo]').val();
			var tipo = $('select[name=fundo]').find('option[value='+valor+']').attr('data-tipo');

			if(tipo == 3) {
				$('#parcelas').slideDown('fast');
				//$('#fechamento').slideDown('fast');
				//$('#saldo').slideUp('fast');

				//$('input[name=saldo]').val('');
			} else {
				$('#parcelas').slideUp('fast');
				//$('#fechamento').slideUp('fast');
				//$('#saldo').slideDown('fast');

				//$('input[name=vencimento]').val('');
				$('input[name=parcelas]').val('1');
			}
			
			$('#fundo').slideDown();
			$('#data').html('Data do lançamento:');

			$('#parcelas').val('1');
		} else {
			$('#parcelas').slideDown();
			$('#fundo').slideUp();
			$('#data').html('Data da primeira parcela:');

			$('#parcelas').val('1');			
		}	
	});

});

function alterar_fundo(){
	var valor = $('select[name=fundo]').val();
	var tipo = $('select[name=fundo]').find('option[value='+valor+']').attr('data-tipo');

	if(tipo == 3) {
		$('#parcelas').slideDown('fast');
		//$('#fechamento').slideDown('fast');
		//$('#saldo').slideUp('fast');

		//$('input[name=saldo]').val('');
	} else {
		$('#parcelas').slideUp('fast');
		//$('#fechamento').slideUp('fast');
		//$('#saldo').slideDown('fast');

		//$('input[name=vencimento]').val('');
		$('input[name=parcelas]').val('1');
	}

}