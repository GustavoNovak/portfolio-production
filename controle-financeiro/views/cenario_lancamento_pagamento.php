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
  <div class="form-group">
    <label for="id_cenario">Cenario:</label>
    <select class="form-control" name="id_cenario">
      <?php foreach($cenarios as $cenario): ?>
      <option value="<?php echo $cenario['id']; ?>"><?php echo $cenario['nome']; ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="form-group">
    <label for="valor">Valor do orçamento:</label>
    <input type="text" class="form-control" name="valor" placeholder="Insira o valor do orçamento">
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
<script type="text/javascript" src="<?php echo BASE_URL; ?>models/jQuery/cenario_lancamento.js"></script>



