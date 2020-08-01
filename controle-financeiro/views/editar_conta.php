<form method="POST" style="margin-top:50px" class="corpo_login">  
  <div class="form-group">
      <label for="id_fundo">Contas:</label>
      <select class="form-control" name="id_conta" id="id_conta" data-url="<?php echo BASE_URL; ?>">
          <?php foreach($contas as $conta): ?>
          <?php if($conta['id_usuario'] != '0'): ?>
          <option value="<?php echo $conta['id']; ?>"><?php echo $conta['nome']; ?></option>
          <?php endif; ?>
          <?php endforeach; ?>
      </select>
  </div>
  <hr/>
  <div class="form-group">
    <label for="nome">Nome da conta:</label>
    <input type="text" class="form-control" name="nome" placeholder="Insira o nome da conta">
  </div>
  <div class="form-group">
        <label for="tipo">Tipo:</label>
        <select class="form-control" name="tipo" id="tipo" disabled="disabled">
            <option value="1">Entrada</option>
            <option value="2">Saída</option>
        </select>
  </div>
  <div class="form-group">
        <label for="periodicidade">Periodicidade:</label>
        <select class="form-control" name="periodicidade">
            <option value="1">Mensal</option>
            <option value="2">Bimestral</option>
            <option value="3">Trimestral</option>
            <option value="4">Quadrimestral</option>
            <option value="6">Semestral</option>
            <option value="12">Anual</option>
        </select>
  </div>
  <div class="form-group" id="mes" style="display:none">
        <label for="mes">Mês que inicia:</label>
        <select class="form-control" name="mes">
            <option value="1">Janeiro</option>
            <option value="2">Fevereiro</option>
            <option value="3">Março</option>
            <option value="4">Abril</option>
            <option value="5">Maio</option>
            <option value="6">Junho</option>
            <option value="7">Julho</option>
            <option value="8">Agosto</option>
            <option value="9">Setembro</option>
            <option value="10">Outubro</option>
            <option value="11">Novembro</option>
            <option value="12">Dezembro</option>
        </select>
  </div>
  <div class="form-group">
    <label for="orcamento_fixo">Orçamento fixo:</label>
    <input type="text" class="form-control" name="orcamento_fixo" placeholder="Insira o orçamento fixo da conta">
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
<!-- jQuery -->
<script type="text/javascript">
  function alterar_periodicidade() {
     if($('select[name=periodicidade]').val() > 1) {
      $('#mes').slideDown('fast');
    } else {
      $('#mes').slideUp('fast');
    }

  } 

  function alterar_conta() {

    var url = $('select[name=id_conta').attr('data-url');
    var id_conta = $('select[name=id_conta]').val();

    $.ajax({

      type:'POST',
      url:url+'ajax/form_editar_conta',
      data:{id_conta:id_conta},
      dataType:'json',
      success:function(json){
        $('input[name=nome]').val(json.nome);
        $('select[name=tipo]').val(json.tipo);
        $('select[name=periodicidade]').val(json.periodicidade);
        if(json.mes != '') {
          $('select[name=mes]').val(json.mes);
        }
        $('input[name=orcamento_fixo]').val(json.orcamento_fixo);

        alterar_periodicidade();

      },
      error:function(){
        $('#aviso').html('<div class="text-danger">Houve um erro, não é possível alterar essa conta!</div>');
      }

    });

    $('#btn_excluir').click(function(e){

      e.preventDefault();
      var url = $(this).attr('data-url');
      var id_conta = $('select[name=id_conta]').val();

      $.ajax({
        type:'POST',
        url:url+'ajax/excluir_conta',
        data:{id_conta:id_conta},
        dataType:'html',
        success:function(data){
          $('#aviso').html(data);
        },
        error:function(){
          $('#aviso').html('<div class="text-danger">Houve um erro, não é possível deletar essa conta!</div>');
        }
      });

    });

  }

  $('select[name=id_conta]').change(function(){
    alterar_conta();
    $('#aviso').html('');   
  });

  alterar_conta();

  $('select[name=periodicidade]').change(function(){
    alterar_periodicidade();
    $('select[name=mes]').val(1);
    $('#aviso').html(''); 
  });

  alterar_periodicidade();

</script>
<script type="text/javascript">
      $(document).ready(function() {
        $("#id_conta").select2({
        });
      });
</script>
<!-- ***** -->