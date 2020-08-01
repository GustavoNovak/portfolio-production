<?php
class cenariosController extends controller{
    
    public function __construct() {

        $u = new Usuarios();
        
        if(!isset($_SESSION['twlg']) && empty($_SESSION['twlg'])) {
            echo "<script>location.href='".BASE_URL."login"."';</script>"; 
            //header("Location: ".BASE_URL."login");
            exit;
        }
    }
    
    public function criar() {
        $dados = array();

        $u = new Usuarios($_SESSION['twlg']);
        $c = new Contas();
        $cn = new Cenarios();

        $dados['nome'] = $u->getNome();
        $dados['tipo'] = $u->getTipo();

        if(isset($_POST['nome']) && !empty($_POST['nome'])) {
            $dados['aviso'] = $cn->insertCenario($_POST['nome']);
        }

        if(isset($_POST['nome_novo']) && !empty($_POST['nome_novo'])) {
            $dados['aviso2'] = $cn->alterarCenario($_POST['cenario'], $_POST['nome_novo']);
        }

        $dados['cenarios'] = $cn->getCenarios($dados['tipo']);
        
        $this->loadTemplate('criar_cenario', $dados);
    }

    public function excluir_conta($id_cenario = '') {        
        $dados = array();

        $u = new Usuarios($_SESSION['twlg']);
        $c = new Contas();
        $cn = new Cenarios();

        $dados['nome'] = $u->getNome();
        $dados['tipo'] = $u->getTipo();
        $dados['contas'] = $c->getContas($dados['tipo']);
        $dados['cenarios'] = $cn->getCenarios($dados['tipo']);

        if(isset($_POST['conta']) && isset($_POST['cenario'])) {
            $dados['aviso'] = $cn->excluirContaCenario($_POST['conta'], $_POST['cenario']);
        }

        if(isset($_POST['id_cenario']) && isset($_POST['id_exclusao'])) {
            $dados['aviso2'] = $cn->excluirExclusaoCenario(addslashes($_POST['id_exclusao']), addslashes($_POST['id_cenario']));
        }
        
        if(count($dados['cenarios']) > 0) {
            if(!empty($id_cenario)) {
                $dados['contas_excluidas'] = $cn->getContasDesconsideradas($id_cenario);
            } else {
                $dados['contas_excluidas'] = $cn->getContasDesconsideradas($dados['cenarios'][0]['id']);
            }
        } else {
            $dados['contas_excluidas'] = array();
        }

        if(!empty($id_cenario)) {
            $dados['cenario_selecionado'] = $id_cenario;
        } else {
            $dados['cenario_selecionado'] = '';
        }
        
        $this->loadTemplate('excluir_conta_cenario', $dados);
    }

    public function cadastrar_pagamento() {
        $dados = array();

        $l = new lancamentos();
        $u = new Usuarios($_SESSION['twlg']);
        $cn = new Cenarios();

        if(isset($_POST['conta']) && !empty($_POST['conta'])) {

            $descricao = $_POST['descricao'];
            $conta = $_POST['conta'];
            $valor = $_POST['valor'];
            $parcelas = $_POST['parcelas'];
            $data = $_POST['data'];
            $id_cenario = $_POST['id_cenario'];

            $dados['aviso'] = $cn->insertLancamentoPagamento($descricao, $conta, $valor, $parcelas, $data, $id_cenario);

        }

        $dados['nome'] = $u->getNome();
        $dados['tipo'] = $u->getTipo();
        $dados['cenarios'] = $cn->getCenarios($dados['tipo']);

        $c = new Contas();

        $dados['contas'] = $c->getContas($dados['tipo']);

        $this->loadTemplate('cenario_lancamento_pagamento', $dados);
    }

    public function cadastrar_recebimento() {
        $dados = array();

        $l = new lancamentos();
        $u = new Usuarios($_SESSION['twlg']);
        $cn = new Cenarios();

        if(isset($_POST['conta']) && !empty($_POST['conta'])) {

            $descricao = $_POST['descricao'];
            $conta = $_POST['conta'];
            $valor = $_POST['valor'];
            $parcelas = $_POST['parcelas'];
            $data = $_POST['data'];
            $id_cenario = $_POST['id_cenario'];

            $dados['aviso'] = $cn->insertLancamentoRecebimento($descricao, $conta, $valor, $parcelas, $data, $id_cenario);

        }

        $dados['nome'] = $u->getNome();
        $dados['tipo'] = $u->getTipo();
        $dados['cenarios'] = $cn->getCenarios($dados['tipo']);

        $c = new Contas();

        $dados['contas'] = $c->getContas($dados['tipo']);

        $this->loadTemplate('cenario_lancamento_recebimento', $dados);
    }

    public function gerenciar() {
        $dados = array();

        $u = new Usuarios($_SESSION['twlg']);

        $dados['nome'] = $u->getNome();
        $dados['tipo'] = $u->getTipo();

        $c = new Contas();
        $l = new lancamentos();
        $cn = new Cenarios();

        $dados['contas'] = $c->getContas($dados['tipo']);

        $dados['lancamentos'] = $cn->getLancamentos($dados['tipo']);
        $dados['cenarios'] = $cn->getCenarios($dados['tipo']);
        
        $this->loadTemplate('gerenciar_lancamento_cenario', $dados);        
    }
    
}
?>


















