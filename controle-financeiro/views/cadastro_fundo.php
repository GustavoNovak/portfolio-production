<form method="POST" style="margin-top:50px" class="corpo_login">  
  <div class="form-group">
    <label for="nome">Nome do fundo:</label>
    <input type="text" class="form-control" name="nome" placeholder="Insira o nome do fundo">
  </div>
  <div class="form-group">
        <label for="tipo">Tipo:</label>
        <select class="form-control" name="tipo" id="tipo">
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
  <?php
    if(!empty($aviso)) {
        echo $aviso;
    }
  ?>
  <button type="submit" class="btn btn-default" style="margin-top:20px;width:150px">Cadastrar</button>
</form>
<script type="text/javascript" src="<?php echo BASE_URL; ?>models/jQuery/cadastro_fundo.js"></script>



