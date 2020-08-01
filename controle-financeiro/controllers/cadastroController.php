<?php
class cadastroController extends controller{
    
    public function __construct() {

        if(!isset($_SESSION['twlg']) && empty($_SESSION['twlg'])) {
            echo "<script>location.href='".BASE_URL."login"."';</script>"; 
            //header("Location: ".BASE_URL."login");
            exit;
        }
    }

    public function usuario() {
        $dados = array();

        $u = new Usuarios($_SESSION['twlg']);
        $dados['nome'] = $u->getNome();
        $dados['tipo'] = $u->getTipo();
        
        if(isset($_POST['user']) && !empty($_POST['user'])) {

            $user = addslashes($_POST['user']);
            if(!empty($_POST['senha']) && ( $_POST['senha'] == $_POST['confirmar_senha']) ){
                
                $senha = md5($_POST['senha']);

                if(!empty($user) && !empty($senha)) {
                    
                    $u = new Usuarios();
                    if(!$u->usuarioExiste($user)) {
                        $u->inserirUsuario($user,$senha,0);
                        header('Location: '.BASE_URL."home");
                    } else {
                        $dados['aviso'] = "Este usuário já existe!";
                    }
                    
                } else {
                    $dados['aviso'] = "Preencha todos os campos!";
                }

            } else {

                $dados['aviso'] = "Senha e confirmar senha diferentes!";
            }
            
        }
        
        $this->loadTemplate('cadastro_usuario', $dados);
    }

    public function editar_usuario() {
        $dados = array();

        $u = new Usuarios($_SESSION['twlg']);
        $dados['nome'] = $u->getNome();
        $dados['tipo'] = $u->getTipo();
        
        if(isset($_POST['user']) && !empty($_POST['user'])) {
            if( (!empty($_POST['senha']) && ( $_POST['senha'] == $_POST['confirmar_senha'])) || empty($_POST['senha']) ){
                $dados['aviso'] = $u->editarUsuario($_POST['id_usuario'], $_POST['user'], $_POST['senha']);
            } else {
                $dados['aviso'] = "<div class='text-danger'>Senha e confirmar senha estão diferentes!</div>";
            }
        }

        $dados['usuarios'] = $u->getUsuariosDependentes();
        
        $this->loadTemplate('editar_cadastro_usuario', $dados);
    }

    public function editar_fundo() {
        $dados = array();

        $u = new Usuarios($_SESSION['twlg']);
        $f = new Fundos();
        $dados['nome'] = $u->getNome();
        $dados['tipo'] = $u->getTipo();
        $dados['tipos_fundos'] = $f->getTipos();
        
        if(isset($_POST['nome']) && !empty($_POST['nome'])) {
            $id_fundo = addslashes($_POST['id_fundo']);
            $nome = addslashes($_POST['nome']);
            $vencimento = addslashes($_POST['vencimento']);
            $fechamento = addslashes($_POST['fechamento']);
            $saldo = addslashes($_POST['saldo']);

            $dados['aviso'] = $f->editarFundo($id_fundo, $nome, $vencimento, $fechamento, $saldo);

        }
        
        $dados['fundos'] = $f->getFundos($dados['tipo']);

        $this->loadTemplate('editar_fundo', $dados);        
    }

    public function fundo() {
        $dados = array();

        $u = new Usuarios($_SESSION['twlg']);
        $f = new Fundos();
        $dados['nome'] = $u->getNome();
        $dados['tipo'] = $u->getTipo();
        $dados['tipos_fundos'] = $f->getTipos();

        if( (isset($_POST['nome']) && !empty($_POST['nome'])) && (isset($_POST['tipo']) && !empty($_POST['tipo'])) ) {

            $saldo = str_replace(',', '.', $_POST['saldo']);

            $dados['aviso'] = $f->inserirFundo($_POST['nome'],$_POST['tipo'], $_POST['vencimento'], $_POST['fechamento'], $saldo, $dados['tipo']);

        }

        $this->loadTemplate('cadastro_fundo', $dados);
    }

    public function conta() {
        $dados = array();

        $u = new Usuarios($_SESSION['twlg']);
        $f = new Contas();
        $dados['nome'] = $u->getNome();
        $dados['tipo'] = $u->getTipo();

        if( (isset($_POST['nome']) && !empty($_POST['nome'])) && (isset($_POST['tipo']) && !empty($_POST['tipo'])) && (isset($_POST['orcamento_fixo']) && !empty($_POST['orcamento_fixo'])) && (isset($_POST['periodicidade']) && !empty($_POST['periodicidade'])) ) {

            $dados['aviso'] = $f->inserirConta($_POST['nome'],$_POST['tipo'], $_POST['orcamento_fixo'], $_POST['periodicidade'], $_POST['mes'], $dados['tipo']);

        }

        $this->loadTemplate('cadastro_conta', $dados);
    }

    public function editar_conta() {
        $dados = array();

        $c = new Contas();
        $u = new Usuarios($_SESSION['twlg']);
        $dados['nome'] = $u->getNome();
        $dados['tipo'] = $u->getTipo();
        
        if(isset($_POST['nome']) && !empty($_POST['nome'])) {
            
            $id_conta = addslashes($_POST['id_conta']);
            $nome = addslashes($_POST['nome']);
            $periodicidade = addslashes($_POST['periodicidade']);
            if(isset($_POST['mes'])){ $mes = addslashes($_POST['mes']); }else{$mes = 'NULL';};
            $orcamento_fixo = addslashes($_POST['orcamento_fixo']);

            $dados['aviso'] = $c->editarConta($id_conta, $nome, $periodicidade, $mes, $orcamento_fixo);
        }

        $dados['contas'] = $c->getContas($dados['tipo']);
        
        $this->loadTemplate('editar_conta', $dados);        
    }

    public function valores_conta($id_conta = '0')  {
        $dados = array();
        
        $u = new Usuarios($_SESSION['twlg']);
        $f = new Fundos();
        $c = new Contas();
        
        $dados['nome'] = $u->getNome();
        $dados['tipo'] = $u->getTipo();

        if(isset($_POST['conta']) && isset($_POST['competencia']) && isset($_POST['valor'])) {

            $dados['aviso'] = $c->inserirRegistroConta($_POST['conta'], $_POST['competencia'], $_POST['valor']);

        }

        $dados['contas'] = $c->getContas($dados['tipo']);

        if($id_conta == '0') {
            $id_conta = $dados['contas'][0]['id'];
        }

        $dados['competencias'] = $c->getCompetencias($id_conta,25);

        $dados['valores_conta'] = $c->getValores($id_conta, 12);
        $dados['id_conta'] = $id_conta;

        $this->loadTemplate('cadastro_valores_conta', $dados);

    }
    
}
?>


















