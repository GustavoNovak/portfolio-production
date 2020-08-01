$(function(){ 

	var fundo = $('select[name=fundo]').val();

	atualizar_conta(fundo);

	var tipo_pagamento = $('select[name=tipo_pagamento]').val();

	alterar_valor_pagamento(tipo_pagamento);

	$('select[name=fundo]').change(function(){

		atualizar_conta($(this).val());
		
	});

	$('select[name=tipo_pagamento]').change(function(){

		alterar_valor_pagamento($(this).val());

	});

	/*

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

*/

	$('#pagar').click(function(event){
		$('#fundo_selecionado').html('Pagar ' + $('select[name=fundo]').find('option:selected').html());
		event.preventDefault();

	});

	$('#pagar_modal').click(function(event){
		event.preventDefault();

		var url = $(this).attr('data-url');

		pagarCartaoCredito(url);

	});

	$('#filtrar').click(function(event){
		event.preventDefault();

		var url = $(this).attr('data-url');

		filtrarLancamentos(url);

	});

});

function atualizar_conta(fundo) {

	$('.fatura').slideUp('fast');
	$('select[name=fatura_'+fundo+']').slideDown('fast');

	$('.total_fatura').slideUp('fast');
	$('#total_fatura_'+fundo).slideDown('fast');

}

function alterar_valor_pagamento(tipo_pagamento) {

	if(tipo_pagamento == 1) {
		$('#valor_pagamento').slideUp('fast');
	} else {
		$('#valor_pagamento').slideDown('fast');
	}

}

function filtrarLancamentos(url) {

	var data_inicio = $('input[name=data_inicio]').val();
	var data_fim = $('input[name=data_fim]').val();
	var id_fundo = $('select[name=id_fundo]').val();
	var id_conta = $('select[name=id_conta]').val();
	var tipo = $('select[name=tipo]').val();
	var descricao = $('input[name=descricao]').val();

	$.ajax({
		type:'POST',
		url:url+'ajax/filtrar_lancamentos',
		data:{  data_inicio:data_inicio,
				data_fim:data_fim,
				id_fundo:id_fundo,
				id_conta:id_conta,
				tipo:tipo,
				descricao:descricao  },
		dataType:'html',
		success:function(data){
			$('tbody').html(data);
		},
		error:function(){
			$('#aviso').html('<div class="text-danger">Houve um erro, seu pagamento não foi realizado!</div>');
		}		
	});

}

function pagarCartaoCredito(url) {

	var id_fundo = $('select[name=fundo]').val();
	var vencimento = $('select[name=fatura_'+id_fundo+']').find('option:eq(0)').val();
	var tipo_pagamento = $('select[name=tipo_pagamento]').val();
	var id_fundo_pagamento = $('select[name=id_fundo_pagamento]').val();
	var valor_pagamento =  $('input[name=valor_pagamento]').val();

	$.ajax({
		type:'POST',
		url:url+'ajax/pagar_cartao_credito',
		data:{tipo_pagamento:tipo_pagamento, id_fundo:id_fundo, vencimento:vencimento, id_fundo_pagamento:id_fundo_pagamento, valor_pagamento:valor_pagamento},
		dataType:'json',
		success:function(json){
			$('#aviso').html('<div class="text-success">'+json.aviso+'</div>');

			//$('option[value="'+vencimento+'"]').remove();
		},
		error:function(){
			$('#aviso').html('<div class="text-danger">Houve um erro, seu pagamento não foi realizado!</div>');
		}		
	});

}

function alterar_lancamento(url) {

	$.ajax({
		type:'POST',
		url:url+'ajax/alterar_lancamento',
		data:{},
		dataType:'html',
		success:function(data){
			$('table').html(data);
		},
		error:function(){
			$('#aviso').html('<div class="text-danger">Houve um erro, seu pagamento não foi realizado!</div>');
		}		
	});

}

function abrirExcluir(x) {

	var nome_lancamento = $(x).attr('data-nome-lancamento');

	$('#aviso_excluir_lancamento').html('Você realmente deseja excluir o lançamento '+nome_lancamento+'?');
	$('#btn_excluir').attr('data-lancamento',$(x).attr('data-lancamento'))
	$('#aviso_excluir').html('');

}

function excluir_lancamento(x, url) {

	var id_lancamento = $(x).attr('data-lancamento');

	$.ajax({
		type:'POST',
		url:url+'ajax/excluir_lancamento',
		data:{id_lancamento:id_lancamento},
		dataType:'html',
		success:function(data){
			$('#aviso_excluir').html(data);
		},
		error:function(){
			$('#aviso_excluir').html('<div class="text-danger">Houve um erro, seu lançamento não foi excluído!</div>');
		}		
	});	
	
}

function confirmar_lancamento(x, url) {

	var id_lancamento = $(x).attr('data-lancamento');
	var vencimento = $('input[name=confirmar_vencimento]').val();
	var descricao = $('input[name=confirmar_descricao]').val();
	var valor = $('input[name=confirmar_valor]').val();
	var fundo = $('select[name=confirmar_fundo]').val();

	$.ajax({
		type:'POST',
		url:url+'ajax/confirmar_lancamento',
		data:{id_lancamento:id_lancamento, vencimento:vencimento, descricao:descricao, valor:valor, fundo:fundo },
		dataType:'html',
		success:function(data){
			$('#aviso_confirmar').html(data);
		},
		error:function(){
			$('#aviso_confirmar').html('<div class="text-danger">Houve um erro, sua confirmação não foi concluída!</div>');
		}		
	});	

}

function abrirConfirmarLancamento(x, url) {
	
	var id_lancamento = $(x).attr('data-lancamento');
	$('#btn_confirmar').attr('data-lancamento',id_lancamento);
	$('#aviso_confirmar').html('');

	$.ajax({
		type:'POST',
		url:url+'ajax/modal_confirmar_lancamento',
		data:{id_lancamento:id_lancamento},
		dataType:'html',
		success:function(data){
			$('#modal_confirmar_lancamento').html(data);
		},
		error:function(){
			$('#aviso_excluir').html('<div class="text-danger">Houve um erro, seu lançamento não foi excluído!</div>');
		}		
	});		

}

$('#myModal').on('hidden.bs.modal', function (e) {
  window.location.href=window.location.href;
})
