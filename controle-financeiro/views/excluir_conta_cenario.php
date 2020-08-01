<form method="POST" style="margin-top:50px" class="corpo_login">  
  <div class="form-group">
    <label for="conta">Conta:</label>
    <select class="form-control" name="conta" id="conta">
      <?php foreach($contas as $conta): ?>
      <option value="<?php echo $conta['id']; ?>"><?php echo $conta['nome']; ?> - <?php if($conta['tipo'] == '1'){echo "Entrada";}else{echo "Saída";} ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="form-group">
    <label for="cenario">Cenário:</label>
    <select class="form-control" name="cenario">
      <?php foreach($cenarios as $cenario): ?>
      <option value="<?php echo $cenario['id']; ?>"><?php echo $cenario['nome']; ?></option>
      <?php endforeach; ?>
    </select>
  </div>    
  <?php
    if(!empty($aviso)) {
        echo $aviso;
    }
  ?>
  <button type="submit" class="btn btn-default" style="margin-top:20px;width:150px">Excluir conta</button>
</form>

<hr/>

<form method="POST" style="margin-top:50px">  
  <div class="form-group">
      <label for="id_cenario">Cenários:</label>
      <select class="form-control" name="id_cenario" id="id_cenario" data-url="<?php echo BASE_URL; ?>">
          <?php foreach($cenarios as $cenario): ?>
          <option value="<?php echo $cenario['id']; ?>"><?php echo $cenario['nome']; ?></option>
          <?php endforeach; ?>
      </select>
  </div>
  <?php $c = new Contas(); ?>
  <div class="form-group">
      <label for="id_exclusao">Contas excluídas:</label>
      <select class="form-control" name="id_exclusao" id="id_exclusao" data-url="<?php echo BASE_URL; ?>">
          <?php foreach($contas_excluidas as $conta_excluida): ?>
          <option value="<?php echo $conta_excluida['id_conta']; ?>"><?php echo $c->getNome($conta_excluida['id_conta']); ?></option>
          <?php endforeach; ?>
      </select>
  </div>
  <div id="aviso2">  
  <?php
    if(!empty($aviso2)) {
        echo $aviso2;
    }
  ?>
  </div>
    <button type="submit" class="btn btn-danger" style="margin-top:20px;width:150px">Excluir exclusão</button>
</form>

<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/select2.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $("#id_exclusao").select2({});
    $("#conta").select2({});

    $('select[name=id_cenario]').change(function(){
      var id_cenario = $(this).val();
      var url = $(this).attr('data-url');

      window.location.href = url+'cenarios/excluir_conta/'+id_cenario;
    });

    <?php if(!empty($cenario_selecionado)){echo "$('select[name=id_cenario]').val(".$cenario_selecionado.")";} ?>

    $('#btn_excluir').click(function(e){
      e.preventDefault();
      var url = $('select[name=id_cenario]').attr('data-url');
      var id_cenario = $('select[name=id_cenario]').val();

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

</script>