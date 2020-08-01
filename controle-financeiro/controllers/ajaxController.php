<?php
class ajaxController extends controller{
    
    public function __construct() {

        $u = new Usuarios();
        
        if(!isset($_SESSION['twlg']) && empty($_SESSION['twlg'])) {
            exit;
        }
    }
    
    public function pagar_cartao_credito() {

        $u = new Usuarios($_SESSION['twlg']);
        $f = new Fundos();

        $dados = array('aviso' => $f->pagarCartaoCredito($_POST['tipo_pagamento'], $_POST['id_fundo'], $_POST['vencimento'], $_POST['id_fundo_pagamento'], $_POST['valor_pagamento']));

        echo json_encode($dados);

    }

    public function filtrar_lancamentos() {
        $c = new Contas();
        $f = new Fundos();
        $l = new lancamentos();

        $lancamentos = $l->getLancamentosFiltrados($_POST['data_inicio'], $_POST['data_fim'], $_POST['id_fundo'], $_POST['id_conta'], $_POST['tipo'], $_POST['descricao']);
        //echo $lancamentos;
        //goto pular;
        ?>
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
                  <img src="<?php echo BASE_URL; ?>assets/images/icone_excluir_lancamento.png" onclick="abrirExcluir(this)" data-nome-lancamento="<?php echo $lancamento['descricao']; ?>" data-lancamento="<?php echo $lancamento['id']; ?>" data-toggle="modal" data-target="#modal_excluir" data-lancamento="<?php echo $lancamento['id'] ?>" onclick="alterarLancamento(this)" width="20px" height="20px" style="margin-right:15px;cursor:pointer"/>
                  <?php if(empty($lancamento['id_fundo'])): ?>
                  <img src="<?php echo BASE_URL; ?>assets/images/icone_confirmar_lancamento.png" data-toggle="modal" data-target="#modal_confirmar" data-lancamento="<?php echo $lancamento['id'] ?>" onclick="abrirConfirmarLancamento(this, '<?php echo BASE_URL; ?>')" width="20px" height="20px" style="margin-right:15px;cursor:pointer"/>
                  <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php
        //pular:

    }

    public function filtrar_lancamentos_cenario() {
        $c = new Contas();
        $f = new Fundos();
        $l = new lancamentos();
        $cn = new Cenarios();

        $lancamentos = $cn->getLancamentosFiltrados($_POST['data_inicio'], $_POST['data_fim'], $_POST['id_conta'], $_POST['descricao'], $_POST['id_cenario']);
        //echo $lancamentos;
        //goto pular;
        ?>
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
                  <img src="<?php echo BASE_URL; ?>assets/images/icone_excluir_lancamento.png" onclick="abrirExcluir(this)" data-nome-lancamento="<?php echo $lancamento['descricao']; ?>" data-lancamento="<?php echo $lancamento['id']; ?>" data-toggle="modal" data-target="#modal_excluir" data-lancamento="<?php echo $lancamento['id'] ?>" onclick="alterarLancamento(this)" width="20px" height="20px" style="margin-right:15px;cursor:pointer"/>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php
        //pular:

    }

    public function excluir_lancamento() {

        $l = new lancamentos();

        echo $l->excluirLancamento($_POST['id_lancamento']);

    }

    public function excluir_lancamento_cenario() {

        $cn = new Cenarios();

        echo $cn->excluirLancamento($_POST['id_lancamento']);

    }

    public function modal_confirmar_lancamento() {

        $id_lancamento = $_POST['id_lancamento'];
        
        $u = new Usuarios($_SESSION['twlg']);
        $l = new lancamentos();
        $f = new Fundos();

        $user_tipo = $u->getTipo();
        $fundos = $f->getFundos($user_tipo);
        $tipo_conta = $l->getTipoConta($id_lancamento);
        ?>

        <form>
          <div class="form-group">
            <label for="confirmar_vencimento" style="float:left">Vencimento:</label>
            <input type="date" class="form-control" name="confirmar_vencimento" value="<?php echo $l->getDados($id_lancamento)['data']; ?>">
          </div>
          <div class="form-group">
            <label for="confirmar_descricao" style="float:left">Descrição:</label>
            <input type="text" class="form-control" name="confirmar_descricao" value="<?php echo $l->getDados($id_lancamento)['descricao']; ?>">
          </div>
          <div class="form-group">
            <label for="confirmar_valor" style="float:left">Valor:</label>
            <input type="text" class="form-control" name="confirmar_valor" value="<?php echo $l->getDados($id_lancamento)['valor']; ?>">
          </div>          
            <div class="form-group">
              <label for="confirmar_fundo" style="float:left">Com qual fundo você deseja pagar sua fatura?</label>
              <select name="confirmar_fundo" class="form-control">
                <?php if($tipo_conta == '2'): ?>
                  <?php foreach ($fundos as $fundo): ?>
                    <option value="<?php echo $fundo['id']; ?>"><?php echo $fundo['nome']; ?> - <?php if($fundo['tipo'] == '1'){echo 'Dinheiro';}else{echo 'Conta corrente';} ?></option>
                  <?php endforeach; ?>
                <?php else: ?>
                  <?php foreach ($fundos as $fundo): ?>
                  <?php if($fundo['tipo'] != '3'): ?>
                    <option value="<?php echo $fundo['id']; ?>"><?php echo $fundo['nome']; ?> - <?php if($fundo['tipo'] == '1'){echo 'Dinheiro';}else{echo 'Conta corrente';} ?></option>
                  <?php endif; ?> 
                  <?php endforeach; ?>                 
                <?php endif; ?>
              </select>
            </div>
        </form>

        <?php     

    }

    public function confirmar_lancamento() {

        $l = new lancamentos();

        $id_lancamento = $_POST['id_lancamento'];

        if(!empty($_POST['id_lancamento'])) {

            $tipo_conta = $l->getTipoConta($_POST['id_lancamento']);

            if($tipo_conta == '2') {
                    
                if(!empty($_POST['vencimento'])){
                    $vencimento = $_POST['vencimento'];
                } else {
                    $vencimento = $l->getDados($id_lancamento)['data'];
                }

                $conta = $l->getDados($id_lancamento)['id_conta'];

                if(!empty($_POST['valor'])) {
                    $valor = $_POST['valor'];
                } else {
                    $valor = $l->getDados($id_lancamento)['valor'];
                }

                if(!empty($_POST['fundo'])) {

                    $resultado = $l->insertLancamentoPagamento($_POST['descricao'], $conta, $_POST['fundo'], $valor, '1', $vencimento, '0');
                    if($resultado == "<div class='text-success'>Lançamento inserido com sucesso!</div>") {
                        echo "<div class='text-success'>Sua confirmação foi realizada com sucesso!</div>";
                        $l->excluirLancamento($id_lancamento);
                    } else {
                        echo $resultado;
                    }

                } else {
                    echo "<div class='text-danger'>Você não informou nenhum fundo, a confirmação não foi realizada!</div>";
                }
            
            } else {

                if(!empty($_POST['vencimento'])){
                    $vencimento = $_POST['vencimento'];
                } else {
                    $vencimento = $l->getDados($id_lancamento)['data'];
                }

                $conta = $l->getDados($id_lancamento)['id_conta'];

                if(!empty($_POST['valor'])) {
                    $valor = $_POST['valor'];
                } else {
                    $valor = $l->getDados($id_lancamento)['valor'];
                }

                if(!empty($_POST['fundo'])) {

                    $resultado = $l->insertLancamentoRecebimento($_POST['descricao'], '0', $conta, $_POST['fundo'], $valor, '1', $vencimento);
                    if($resultado == "<div class='text-success'>Lançamento inserido com sucesso!</div>") {
                        echo "<div class='text-success'>Sua confirmação foi realizada com sucesso!</div>";
                        $l->excluirLancamento($id_lancamento);
                    } else {
                        echo $resultado;
                    }
                    
                } else {
                    echo "<div class='text-danger'>Você não informou nenhum fundo, a confirmação não foi realizada!</div>";
                }

            }

        } else {
            echo "<div class='text-danger'>Você não informou nenhum lançamento, a confirmação não foi realizada!</div>";
        }

    }

    public function form_editar_usuario() {
        $u = new Usuarios($_POST['id_usuario'])

        ?>
        <div class="form-group">
          <label for="user">Nome de usuário:</label>
          <input type="text" class="form-control" name="user" aria-describedby="emailHelp" placeholder="Insira o novo username aqui" value="<?php echo $u->getNome(); ?>">
        </div>
        <div class="form-group">
          <label for="senha">Senha:</label>
          <input type="password" class="form-control" name="senha" placeholder="Nova senha">
        </div> 
        <div class="form-group">
          <label for="confirmar_senha">Confirmar Senha:</label>
          <input type="password" class="form-control" name="confirmar_senha" placeholder="Confirmar nova senha">
        </div> 
        <?php       
    }

    public function form_editar_fundo() {
        $f = new Fundos();

        echo json_encode($f->getFundo($_POST['id_fundo']));
    }

    public function excluir_fundo() {
        $f = new Fundos();

        echo $f->excluirFundo($_POST['id_fundo']);
    }

    public function excluir_conta() {
        $c = new Contas();

        echo $c->excluirConta($_POST['id_conta']);
    }

    public function excluir_usuario() {
        $u = new Usuarios($_POST['id_usuario']);

        echo $u->excluirUsuario();
    }

    public function excluir_valor_conta() {
        $c = new Contas();

        echo $c->excluirValorConta($_POST['id_conta'], $_POST['competencia']);
    }    

    public function excluir_cenario() {
        $cn = new Cenarios();

        echo json_encode(array('aviso' => $cn->excluirCenario($_POST['id_cenario'])));
    }

    public function form_editar_conta() {
        $c = new Contas();

        echo json_encode($c->getConta($_POST['id_conta']));
    }

    public function form_editar_valor_conta() {
        $c = new Contas();

        echo json_encode($c->getRegistroValorConta($_POST['id_conta'], $_POST['competencia']));
    }

    public function get_cenario() {
        $cn = new cenarios();

        echo json_encode($cn->getCenario($_POST['id_cenario']));
    }

}
?>


















