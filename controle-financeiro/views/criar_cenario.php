<form method="POST" style="margin-top:50px" class="corpo_login">  
  <div class="form-group">
    <label for="nome">Nome do cenário:</label>
    <input type="text" class="form-control" name="nome" placeholder="Insira o nome do cenário">
  </div>
  <div id="aviso">
  <?php
    if(!empty($aviso)) {
        echo $aviso;
    }
  ?>
  </div>
  <button type="submit" class="btn btn-default" style="margin-top:20px;width:150px">Cadastrar</button>
</form>

<hr/>

<form method="POST" style="margin-top:50px">  
  <div class="form-group">
      <label for="cenario">Cenários:</label>
      <select class="form-control" name="cenario" id="cenario" data-url="<?php echo BASE_URL; ?>">
          <?php foreach($cenarios as $cenario): ?>
          <option value="<?php echo $cenario['id']; ?>"><?php echo $cenario['nome']; ?></option>
          <?php endforeach; ?>
      </select>
  </div>
  <div class="form-group">
    <label for="nome_novo">Nome do cenário:</label>
    <input type="text" class="form-control" name="nome_novo" placeholder="Insira o nome do cenário">
  </div>
  <div id="aviso2">  
  <?php
    if(!empty($aviso2)) {
        echo $aviso2;
    }
  ?>
  </div>
  <div class="display:flex;justify-content:space-between">
    <button type="submit" class="btn btn-default" style="margin-top:20px;width:150px">Editar</button>
    <button id="btn_excluir" class="btn btn-danger" style="float:right;margin-top:20px;width:150px">Excluir</button>
  </div>
</form>

<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/select2.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $("#cenario").select2({});

    $('select[name=cenario]').change(function(){
      alterar_cenario();
    });

    alterar_cenario();

    $('#btn_excluir').click(function(e){
      e.preventDefault();
      var url = $('select[name=cenario]').attr('data-url');
      var id_cenario = $('select[name=cenario]').val();

      $.ajax({
        type:'POST',
        url:url+'ajax/excluir_cenario',
        data:{id_cenario:id_cenario},
        dataType:'json',
        success:function(json){
          $('option[value='+id_cenario+']').remove();
          $('#aviso2').html(json.aviso);
        },
        error:function(){
          $('#aviso2').html('<div class="text-danger">Houve um erro, não é possível encontrar este cenário!</div>');
        }
      });

    });

  });

  function alterar_cenario() {
    var url = $('select[name=cenario]').attr('data-url');
    var id_cenario = $('select[name=cenario]').val();

    $.ajax({
      type:'POST',
      url:url+'ajax/get_cenario',
      data:{id_cenario:id_cenario},
      dataType:'json',
      success:function(json){
        $('input[name=nome_novo]').val(json.nome);
      },
      error:function(){
        $('#aviso2').html('<div class="text-danger">Houve um erro, não é possível encontrar este cenário!</div>');
      }
    });
  }
</script>