<form method="POST" style="margin-top:50px" class="corpo_login">  
  <div class="form-group">
    <label for="descricao">Descrição do Lançamento:</label>
    <input type="text" class="form-control" name="descricao" placeholder="Insira a descrição do lançamento">
  </div>
  <div class="form-group">
    <label for="conta">Conta:</label>
    <select class="form-control" name="conta" id="conta">
      <?php foreach($contas as $conta): ?>
        <?php if($conta['tipo'] == 2): ?>
        <option value="<?php echo $conta['id']; ?>"><?php echo $conta['nome']; ?></option>
        <?php endif; ?>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="form-group" id="lancamento_futuro">
    <label for="lancamento_futuro">Previsão:</label>
    <select class="form-control" name="lancamento_futuro">
      <option value="0" >Não</option>
      <option value="1" >Sim</option>
    </select>
  </div>

  <div class="form-group" id="fundo">
    <label for="fundo">Fundo:</label>
    <select class="form-control" name="fundo">
      <?php foreach($fundos as $fundo): ?>
      <option value="<?php echo $fundo['id']; ?>" data-tipo="<?php echo $fundo['tipo']; ?>" ><?php echo $fundo['nome']; ?> - <?php if($fundo['tipo'] == '1'){echo "Dinheiro";}else{if($fundo['tipo'] == '2'){echo "Conta corrente";}else{echo "Cartão de crédito";}} ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="form-group">
    <label for="valor">Valor do Lançamento:</label>
    <input type="text" class="form-control" name="valor" placeholder="Insira o valor do lançamento">
  </div>
  <div class="form-group" id="parcelas">
    <label for="parcelas">Número de parcelas:</label>
    <input type="text" class="form-control" name="parcelas" placeholder="Insira o número de parcelas">
  </div>
  <div class="valor_parcela" style="margin-bottom:20px">Valor total: R$ 0,00</div>         
  <div class="form-group" id="vencimento">
    <label for="data">Data do lançamento:</label>
    <input type="date" class="form-control" name="data">
  </div>      
  <?php
    if(!empty($aviso)) {
        echo $aviso;
    }
  ?>
  <button type="submit" class="btn btn-default" style="margin-top:20px;width:150px">Cadastrar</button>
</form>
<script type="text/javascript" src="<?php echo BASE_URL; ?>models/jQuery/cadastro_lancamento.js"></script>



