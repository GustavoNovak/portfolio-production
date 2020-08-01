$(function(){ 

	$('input[name=parcelas]').val('1');
	$('input[name=valor]').val('0.00');

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
});