<form method="POST" style="margin-top:50px" class="corpo_login">  
  <div class="form-group">
    <label for="nome">Nome da conta:</label>
    <input type="text" class="form-control" name="nome" placeholder="Insira o nome da conta">
  </div>
  <div class="form-group">
        <label for="tipo">Tipo:</label>
        <select class="form-control" name="tipo" id="tipo">
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
  <?php
    if(!empty($aviso)) {
        echo $aviso;
    }
  ?>
  <button type="submit" class="btn btn-default" style="margin-top:20px;width:150px">Cadastrar</button>
</form>
<script type="text/javascript">
  $('select[name=periodicidade]').change(function(){
    if($(this).val() > 1) {
      $('#mes').slideDown('fast');
    } else {
      $('#mes').slideUp('fast');
    }
  });
</script>



