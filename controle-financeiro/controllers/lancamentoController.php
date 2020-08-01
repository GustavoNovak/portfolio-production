<?php
class lancamentoController extends controller{
    
    public function __construct() {

        $u = new Usuarios();
        
        if(!isset($_SESSION['twlg']) && empty($_SESSION['twlg'])) {
            echo "<script>location.href='".BASE_URL."login"."';</script>"; 
            //header("Location: ".BASE_URL."login");
            exit;
        }
    }

    public function cadastrar_pagamento() {
        $dados = array();

        $l = new lancamentos();
        $u = new Usuarios($_SESSION['twlg']);

        if(isset($_POST['conta']) && !empty($_POST['conta'])) {

            $descricao = $_POST['descricao'];
            $conta = $_POST['conta'];
            $lancamento_futuro = $_POST['lancamento_futuro'];
            $fundo = $_POST['fundo'];
            $valor = $_POST['valor'];
            $parcelas = $_POST['parcelas'];
            $data = $_POST['data'];

            $dados['aviso'] = $l->insertLancamentoPagamento($descricao, $conta, $fundo, $valor, $parcelas, $data, $lancamento_futuro);

        }

        $dados['nome'] = $u->getNome();
        $dados['tipo'] = $u->getTipo();

        $f = new Fundos();
        $c = new Contas();

        $dados['contas'] = $c->getContas($dados['tipo']);
        $dados['fundos'] = $f->getFundos($dados['tipo']);

        $this->loadTemplate('cadastro_lancamento_pagamento', $dados);

    }

    public function cadastrar_recebimento() {
        $dados = array();

        $l = new lancamentos();
        $u = new Usuarios($_SESSION['twlg']);

        if(isset($_POST['conta']) && !empty($_POST['conta'])) {

            $descricao = $_POST['descricao'];
            $conta = $_POST['conta'];
            $lancamento_futuro = $_POST['lancamento_futuro'];
            $fundo = $_POST['fundo'];
            $valor = $_POST['valor'];
            $parcelas = $_POST['parcelas'];
            $data = $_POST['data'];

            $dados['aviso'] = $l->insertLancamentoRecebimento($descricao, $lancamento_futuro, $conta, $fundo, $valor, $parcelas, $data);

        }

        $dados['nome'] = $u->getNome();
        $dados['tipo'] = $u->getTipo();

        $f = new Fundos();
        $c = new Contas();

        $dados['contas'] = $c->getContas($dados['tipo']);
        $dados['fundos'] = $f->getFundos($dados['tipo']);

        $this->loadTemplate('cadastro_lancamento_recebimento', $dados);

    }

    public function gerenciar() {
        $dados = array();

        $u = new Usuarios($_SESSION['twlg']);

        $dados['nome'] = $u->getNome();
        $dados['tipo'] = $u->getTipo();

        $c = new Contas();
        $f = new Fundos();
        $l = new lancamentos();

        $dados['contas'] = $c->getContas($dados['tipo']);
        $dados['cartoes_credito'] = $f->getCartoesCredito($dados['tipo']);
        $dados['fundos'] = $f->getFundos($dados['tipo']);

        if(count($dados['cartoes_credito']) > 0) {
            foreach ($dados['cartoes_credito'] as $value) {
                $dados['vencimentos_valores'][$value['id']] = $f->getVencimentosProximos($value['id']);   
            }
        }

        $dados['lancamentos'] = $l->getLancamentos($dados['tipo']);
        
        $this->loadTemplate('gerenciar_lancamento', $dados);
    }
    
}
?>


















