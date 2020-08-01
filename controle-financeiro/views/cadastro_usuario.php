<form method="POST" style="margin-top:50px">  
  <div class="form-group">
    <label for="exampleInputEmail1">Nome de usuário:</label>
    <input type="text" class="form-control" name="user" aria-describedby="emailHelp" placeholder="Insira seu usuário">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Senha:</label>
    <input type="password" class="form-control" name="senha" placeholder="Senha">
  </div> 
  <div class="form-group">
    <label for="exampleInputPassword1">Confirmar Senha:</label>
    <input type="password" class="form-control" name="confirmar_senha" placeholder="Senha">
  </div>            
  <?php
    if(!empty($aviso)) {
        echo "<div class='text-danger'>".$aviso."</div>";
    }
  ?>
  <button type="submit" class="btn btn-default" style="margin-top:20px;width:150px">Cadastrar</button>
</form>



