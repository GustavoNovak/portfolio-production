<?php 
// DECLARAÇÃO DE OBJETOS
  $c = new Contas();
  $f = new Fundos();
  $cn = new Cenarios();
?>
<!-- Modal excluir lançamento -->
<div class="modal" id="modal_excluir">
  <div class="modal-dialog">
    <div class="modal-content" style="padding:10px">

      <!-- Modal Header -->
      <div class="modal-header" style="float-left">
        <button type="button" class="close" data-dismiss="modal" style="float-right">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      <div id="fundo_selecionado" style="margin-bottom: 20px;font-size: 18px;text-align: center"></div>
      <h4 id="aviso_excluir_lancamento">Você realmente deseja excluir esse lançamento?</h4>
      <div id="aviso_excluir"></div>
      </div>
        <button id="btn_excluir" onClick="excluir_lancamento(this,'<?php echo BASE_URL; ?>')" data-lancamento="0" class="btn btn-danger" style="width:100%;margin-top:20px" type="submit" data-url="<?php echo BASE_URL; ?>">Excluir</button>
    </div>
  </div>
</div>

<div class="filtro" style="margin-top:20px">
	<h1>Gerenciar lançamentos:</h1>
  <form>
  		<div class="form-row">
    		<div class="form-group col-md-6">
      		<label for="data_inicio">Data início:</label>
      		<input type="date" class="form-control" name="data_inicio">
    	  </div>
    	  <div class="form-group col-md-6">
      		<label for="data_fim">Data fim:</label>
      		<input type="date" class="form-control" name="data_fim">
    	  </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
            <label for="id_conta">Conta:</label>
            <select name="id_conta" class="form-control">
              <option></option>
              <?php foreach($contas as $conta): ?>
                <option value="<?php echo $conta['id']; ?>"><?php echo $conta['nome']; ?></option>
              <?php endforeach; ?>        
            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="id_cenario">Cenario:</label>
            <select name="id_cenario" class="form-control">
              <option></option>
              <?php foreach($cenarios as $cenario): ?>
                <option value="<?php echo $cenario['id']; ?>"><?php echo $cenario['nome']; ?></option>
              <?php endforeach; ?>        
            </select>
        </div>
	  	</div>
	  	<div class="form-group">
	    	<label for="descricao">Descrição:</label>
	    	<input type="text" class="form-control" name="descricao" placeholder="Descrição qualquer">
	  	</div>
      <div id="aviso_filtro"></div>
	  	<button id="filtrar" data-url="<?php echo BASE_URL; ?>" class="btn btn-default" style="width:100%">Filtrar</button>
	</form>
</div>
<div class="lancamentos">
<table class="table">
  <thead>
    <tr>
      <th scope="col">Vencimento</th>
      <th scope="col">Usuário</th>
      <th scope="col">Descrição</th>
      <th scope="col">Conta</th>
      <th scope="col">Cenário</th>
      <th scope="col">Valor</th>
      <th scope="col">Ações</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($lancamentos as $lancamento): ?>
    <tr style="background-color:
    <?php
    if($lancamento['cartao_credito'] == 1) {
      if($lancamento['pagamento'] == 0) {
        echo "#ffb3b3";
      } elseif($lancamento['pagamento'] == 1) {
        echo "#fefdbb";
      } elseif($lancamento['pagamento'] == 2) {
        echo "#b1ffac";
      }
    } else {
      if($lancamento['previsao'] == 1) {
        echo "#c2ccff";
      }
    } 
    ?>">
        <td><?php $vencimento = new DateTime($lancamento['data']); echo $vencimento->format('d/m/Y'); ?></td>
        <td><?php $user = new Usuarios($lancamento['id_usuario']); echo $user->getNome(); ?></td>
        <td><?php echo $lancamento['descricao']; ?></td>
        <td><?php echo $c->getNome($lancamento['id_conta']); ?></td>
        <td><?php echo $cn->getNome($lancamento['id_cenario']); ?></td>
        <td>R$ <?php echo number_format($lancamento['valor'], 2, ',', '.'); ?></td>
        <td>
          <!-- <img src="<?php echo BASE_URL; ?>assets/images/icone_editar_lancamento.png" data-toggle="modal" data-target="#modal_confirmar" data-lancamento="<?php echo $lancamento['id'] ?>" onclick="excluirLancamento(this)" width="20px" height="20px" style="margin-right:15px;cursor:pointer"/> -->
          <img src="<?php echo BASE_URL; ?>assets/images/icone_excluir_lancamento.png" onclick="abrirExcluir(this)" data-nome-lancamento="<?php echo $lancamento['descricao']; ?>" data-lancamento="<?php echo $lancamento['id']; ?>" data-toggle="modal" data-target="#modal_excluir" data-lancamento="<?php echo $lancamento['id'] ?>" onclick="alterarLancamento(this)" width="20px" height="20px" style="margin-right:15px;cursor:pointer"/>
        </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</div>
<script type="text/javascript" src="<?php echo BASE_URL; ?>models/jQuery/gerenciar_lancamento_cenario.js"></script>