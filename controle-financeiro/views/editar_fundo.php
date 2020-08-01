<form method="POST" style="margin-top:50px" class="corpo_login">  
  <div class="form-group">
        <label for="id_fundo">Fundos:</label>
        <select class="form-control" name="id_fundo" id="id_fundo" data-url="<?php echo BASE_URL; ?>">
            <?php foreach($fundos as $fundo): ?>
            <option value="<?php echo $fundo['id']; ?>"><?php echo $fundo['nome']; ?></option>
            <?php endforeach; ?>
        </select>
  </div>  
  <hr/>
  <div class="form-group">
    <label for="nome">Nome do fundo:</label>
    <input type="text" class="form-control" name="nome" placeholder="Insira o nome do fundo">
  </div>
  <div class="form-group">
        <label for="tipo">Tipo:</label>
        <select class="form-control" name="tipo" id="tipo" disabled="disabled">
            <?php foreach($tipos_fundos as $tipo): ?>
            <option value="<?php echo $tipo['id']; ?>"><?php echo utf8_encode($tipo['nome']); ?></option>
            <?php endforeach; ?>
        </select>
  </div>    
  <div class="form-group" id="vencimento">
    <label for="vencimento">Vencimento:</label>
    <input type="text" class="form-control" name="vencimento" placeholder="Insira o vencimento do cartão de crédito">
  </div>
  <div class="form-group" id="fechamento">
    <label for="fechamento">Fechamento:</label>
    <input type="text" class="form-control" name="fechamento" placeholder="Insira o fechamento do cartão de crédito">
  </div>
  <div class="form-group" id="saldo">
    <label for="saldo">Saldo atual:</label>
    <input type="text" class="form-control" name="saldo" placeholder="Insira o saldo atual do fundo">
  </div>        
  <div id="aviso">
  <?php
    if(!empty($aviso)) {
        echo $aviso;
    }
  ?>
  </div>
  <div class="display:flex">
    <button type="submit" class="btn btn-default" style="margin-top:20px;width:150px">Editar</button>
    <button id="btn_excluir"class="btn btn-danger" style="float:right;margin-top:20px;width:150px" data-url="<?php echo BASE_URL; ?>">Excluir</button>
  </div>
</form>

<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/select2.min.js"></script>
<!-- jQuery code -->
<script type="text/javascript">

  $('select[name=id_fundo]').change(function(){

    alterar_fundo();
    
  });

  alterar_fundo();

  $('#btn_excluir').click(function(e){
    e.preventDefault();
    var url = $(this).attr('data-url');
    var id_fundo = $('select[name=id_fundo]').val();

    $.ajax({
      type:'POST',
      url:url+'ajax/excluir_fundo',
      data:{id_fundo:id_fundo},
      dataType:'html',
      success:function(data){
        $('#aviso').html(data);
      },
      error:function(){
        $('#aviso').html('<div class="text-danger">Houve um erro, não é possível deletar esse fundo!</div>');
      }
    });

  });

  function alterar_fundo() {

    var url = $('select[name=id_fundo').attr('data-url');
    var id_fundo = $('select[name=id_fundo]').val();

    $.ajax({

      type:'POST',
      url:url+'ajax/form_editar_fundo',
      data:{id_fundo:id_fundo},
      dataType:'json',
      success:function(json){
        $('input[name=nome]').val(json.nome);
        $('select[name=tipo]').val(json.tipo);
        if(json.tipo == '3') {
          $('input[name=vencimento]').val(json.vencimento);
        }
        if(json.tipo == '3') {
          $('input[name=fechamento]').val(json.fechamento);
        }
        if(json.tipo != '3') {
          $('input[name=saldo]').val(json.saldo_atual);
        } else {
          $('input[name=saldo]').val(json.contas_a_pagar_total);
        }

        if(json.tipo == '3') {
          $('#saldo').find('label').html('Contas a pagar:');
        } else {
          $('#saldo').find('label').html('Saldo atual:');
        }

        if(json.tipo == '3') {
          $('#vencimento').slideDown('fast');
          $('#fechamento').slideDown('fast');
        } else {
          $('#vencimento').slideUp('fast');
          $('#fechamento').slideUp('fast');
        }
      },
      error:function(){
        $('#aviso').html('<div class="text-danger">Houve um erro, não é possível alterar esse fundo!</div>');
      }

    });

  }

</script>
<script type="text/javascript">
      $(document).ready(function() {
        $("#id_fundo").select2({
        });
      });
</script>
<!-- *********** -->



