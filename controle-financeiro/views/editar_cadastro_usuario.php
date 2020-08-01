<form method="POST" style="margin-top:50px">  
  <div class="form-group" id="fundo">
    <label for="id_usuario">Usuário:</label>
    <select class="form-control" name="id_usuario" id="id_usuario" data-url="<?php echo BASE_URL; ?>">
      <?php foreach($usuarios as $usuario): ?>
      <option value="<?php echo $usuario['id']; ?>" ><?php echo $usuario['nome']; ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <hr/>
  <div id="form-editar-usuario">

  </div> 

  <div id="aviso">           
  <?php
    if(!empty($aviso)) {
        echo "<div class='text-danger' id='aviso'>".$aviso."</div>";
    }
  ?>
  </div>

  <div style="display:flex;justify-content: space-between;">
    <button type="submit" class="btn btn-default" style="margin-top:20px;width:150px">Editar</button>
    <button id="btn_excluir"class="btn btn-danger" style="float:right;margin-top:20px;width:150px" data-url="<?php echo BASE_URL; ?>">Excluir</button>
  </div>
</form>

<!-- jQuery code -->
<script type="text/javascript">

  $('select[name=id_usuario]').change(function(){

    alterar_usuario();

  });

    alterar_usuario();

  function alterar_usuario() {

    var id_usuario = $('select[name=id_usuario]').val();
    var url = $('select[name=id_usuario]').attr('data-url');

    $.ajax({

      type:'POST',
      url:url+'ajax/form_editar_usuario',
      data:{id_usuario:id_usuario},
      dataType:'html',
      success:function(data){
        $('#form-editar-usuario').html(data);
      },
      error:function(){
        $('#form-editar-usuario').html('');
        $('#aviso').html('<div class="text-danger">Houve um erro, não é possível alterar esse usuário!</div>');
      }

    });

    $('#btn_excluir').click(function(e){
      e.preventDefault();
      var url = $(this).attr('data-url');
      var id_usuario = $('select[name=id_usuario]').val();

      $.ajax({
        type:'POST',
        url:url+'ajax/excluir_usuario',
        data:{id_usuario:id_usuario},
        dataType:'html',
        success:function(data){
          $('#aviso').html(data);
        },
        error:function(){
          $('#aviso').html('<div class="text-danger">Houve um erro, não é possível deletar esse fundo!</div>');
        }
      });

    });

  }

</script>
<!-- *********** -->