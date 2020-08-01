$(function(){ 

	$('#filtrar').click(function(event){
		event.preventDefault();

		var url = $(this).attr('data-url');

		filtrarLancamentos(url);

	});

});

function filtrarLancamentos(url) {

	var data_inicio = $('input[name=data_inicio]').val();
	var data_fim = $('input[name=data_fim]').val();
	var id_conta = $('select[name=id_conta]').val();
	var descricao = $('input[name=descricao]').val();
	var id_cenario = $('select[name=id_cenario]').val();

	$.ajax({
		type:'POST',
		url:url+'ajax/filtrar_lancamentos_cenario',
		data:{  data_inicio:data_inicio,
				data_fim:data_fim,
				id_conta:id_conta,
				descricao:descricao,
				id_cenario:id_cenario  },
		dataType:'html',
		success:function(data){
			$('tbody').html(data);
		},
		error:function(){
			alert("Houve um erro, seu filtro não foi realizado");
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
		url:url+'ajax/excluir_lancamento_cenario',
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
