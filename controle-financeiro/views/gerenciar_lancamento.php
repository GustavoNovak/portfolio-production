<?php 
// DECLARAÇÃO DE OBJETOS
  $c = new Contas();
  $f = new Fundos();
?>
<!-- Modal pagar cartão de crédito -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header" style="float-left">
        <button type="button" class="close" data-dismiss="modal" style="float-right" onClick="refresh()">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      <div id="fundo_selecionado" style="margin-bottom: 20px;font-size: 18px;text-align: center"></div>
      <form method="POST">
        <div class="form-group">
          <label for="fatura">Tipo de pagamento:</label>
          <select name="tipo_pagamento" class="form-control">
            <option value="1">Pagar uma fatura</option>
            <option value="0">Pagar um valor qualquer</option>
          </select>
        </div>
        <div class="form-group">
          <label for="id_fundo_pagamento">Com qual fundo você deseja pagar sua fatura?</label>
          <select name="id_fundo_pagamento" class="form-control">
            <?php foreach ($fundos as $fundo): ?>
              <?php if($fundo['tipo'] != '3'): ?>
              <option value="<?php echo $fundo['id']; ?>"><?php echo $fundo['nome']; ?> - <?php if($fundo['tipo'] == '1'){echo 'Dinheiro';}else{echo 'Conta corrente';} ?></option>
              <?php endif; ?>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group" id="valor_pagamento">
          <label for="valor_pagamento">Valor:</label>
          <input type="text" name="valor_pagamento" class="form-control" placeholder="Informe aqui o valor do pagamento">
        </div>
        <div id="aviso"></div>
        <button id="pagar_modal" class="btn btn-default" style="width:100%;margin-top:20px" type="submit" data-url="<?php echo BASE_URL; ?>">Pagar</button>
      </form>
      </div>

    </div>
  </div>
</div>

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

<!-- Modal confirmar lançamento -->
<div class="modal" id="modal_confirmar">
  <div class="modal-dialog">
    <div class="modal-content" style="padding:10px">

      <!-- Modal Header -->
      <div class="modal-header" style="float-left">
        <button type="button" class="close" data-dismiss="modal" style="float-right">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      <div id="modal_confirmar_lancamento" style="margin-bottom: 20px;font-size: 18px;text-align: center"></div>
        
      </div>
      <div id="aviso_confirmar"></div>
        <button id="btn_confirmar" onClick="confirmar_lancamento(this, '<?php echo BASE_URL; ?>')" data-lancamento="0" class="btn btn-success" style="width:100%;margin-top:20px" data-url="<?php echo BASE_URL; ?>">Confirmar</button>
    </div>
  </div>
</div>


<?php if(count($cartoes_credito) > 0): ?>
<h1>Gerenciar cartões de crédito:</h1>
  <form>
        <div class="form-group">
            <label for="fundo">Fundo:</label>
            <select name="fundo" class="form-control">
              <?php foreach($cartoes_credito as $cartao_credito): ?>
                <option value="<?php echo $cartao_credito['id']; ?>"><?php echo $cartao_credito['nome']; ?></option>
              <?php endforeach; ?>        
            </select>
        </div>
        <div class="form-group">
          <label for="fatura">Fatura:</label>
          <?php foreach ($vencimentos_valores as $key => $vencimento_valor): ?>
          <select name="fatura_<?php echo $key; ?>" class="form-control fatura">
            <?php foreach ($vencimento_valor as $key => $value): ?>
            <option value="<?php echo $key; ?>"><?php echo $key.' | '.$value; ?></option>
            <?php endforeach; ?>
          </select>
          <?php endforeach; ?>
        </div>
      <?php foreach ($vencimentos_valores as $key => $vencimento_valor): ?>
      <div class="text-center total_fatura" id="total_fatura_<?php echo $key; ?>" data-conta="<?php echo $key; ?>" style="margin-top: 20px;font-size:20px"><strong>Total da fatura: <?php if(array_sum($vencimento_valor) > 0){ echo "R$ ".array_sum($vencimento_valor); } else{ echo "Você não tem faturas nesse cartão!"; } ?></strong></div>
      <?php endforeach; ?>
      <button id="pagar" class="btn btn-success" style="width:100%;margin-top:20px" data-toggle="modal" data-target="#myModal">pagar</button>
  </form>
<?php endif; ?>
<div class="filtro" style="margin-top:20px">
	<h1>Gerenciar lançamentos:</h1>
  <form>
  		<div class="form-row">
    		<div class="form-group col-md-3">
      		<label for="data_inicio">Data início:</label>
      		<input type="date" class="form-control" name="data_inicio">
    	</div>
    	<div class="form-group col-md-3">
      		<label for="data_fim">Data fim:</label>
      		<input type="date" class="form-control" name="data_fim">
    	</div>
      <div class="form-group col-md-6">
            <label for="id_fundo">Fundo:</label>
            <select name="id_fundo" class="form-control">
              <option></option>
              <?php foreach($fundos as $fundo): ?>
                <option value="<?php echo $fundo['id']; ?>"><?php echo $fundo['nome']; ?></option>
              <?php endforeach; ?>        
            </select>
      </div>
	  	</div>
	  	<div class="form-row">
	    	<div class="form-group col-md-6">
	      		<label for="tipo">Tipo:</label>
	      		<select name="tipo" class="form-control">
	        		<option value=""></option>
			        <option value="1">Normal</option>
              <option value="2">Cartão de crédito</option>
			        <option value="3">A pagar</option>
			        <option value="4">A receber</option>
	      		</select>
	    	</div>
        <div class="form-group col-md-6">
            <label for="id_conta">Conta:</label>
            <select name="id_conta" class="form-control">
              <option></option>
              <?php foreach($contas as $conta): ?>
                <option value="<?php echo $conta['id']; ?>"><?php echo $conta['nome']; ?></option>
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
      <th scope="col">Fundo</th>
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
        <td><?php echo $f->getNome($lancamento['id_conta'], $lancamento['id_fundo']); ?></td>
        <td>R$ <?php echo number_format($lancamento['valor'], 2, ',', '.'); ?></td>
        <td>
          <!-- <img src="<?php echo BASE_URL; ?>assets/images/icone_editar_lancamento.png" data-toggle="modal" data-target="#modal_confirmar" data-lancamento="<?php echo $lancamento['id'] ?>" onclick="excluirLancamento(this)" width="20px" height="20px" style="margin-right:15px;cursor:pointer"/> -->
          <img src="<?php echo BASE_URL; ?>assets/images/icone_excluir_lancamento.png" onclick="abrirExcluir(this)" data-nome-lancamento="<?php echo $lancamento['descricao']; ?>" data-lancamento="<?php echo $lancamento['id']; ?>" data-toggle="modal" data-target="#modal_excluir" data-lancamento="<?php echo $lancamento['id'] ?>" onclick="alterarLancamento(this)" width="20px" height="20px" style="margin-right:15px;cursor:pointer"/>
          <?php if(empty($lancamento['id_fundo'])): ?>
          <img src="<?php echo BASE_URL; ?>assets/images/icone_confirmar_lancamento.png" data-toggle="modal" data-target="#modal_confirmar" data-lancamento="<?php echo $lancamento['id'] ?>" onclick="abrirConfirmarLancamento(this, '<?php echo BASE_URL; ?>')" width="20px" height="20px" style="margin-right:15px;cursor:pointer"/>
          <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    
       <!-- <button class="btn btn-warning btn-gerenciar">Verificar</button> -->

  </tbody>
</table>
</div>
<script type="text/javascript" src="<?php echo BASE_URL; ?>models/jQuery/gerenciar_lancamento.js"></script>