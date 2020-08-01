<?php
class relatoriosController extends controller{
    
    public function __construct() {

        $u = new Usuarios();
        
        if(!isset($_SESSION['twlg']) && empty($_SESSION['twlg'])) {
            echo "<script>location.href='".BASE_URL."login"."';</script>"; 
            //header("Location: ".BASE_URL."login");
            exit;
        }
    }
    
    public function previsao_real() {
        $dados = array();

        $u = new Usuarios($_SESSION['twlg']);
        $c = new Contas();

        $dados['nome'] = $u->getNome();
        $dados['tipo'] = $u->getTipo();
        $dados['previsao'] = $c->getPrevisao($dados['tipo']);
        $dados['previsao_com_lancamentos_previsao'] = $c->getPrevisaoComLancamentosPrevisao($dados['tipo']);

        $this->loadTemplate('previsao', $dados);
    }

    public function visualizar_cenarios() {
        $dados = array();

        $u = new Usuarios($_SESSION['twlg']);
        $c = new Contas();
        $cn = new Cenarios();

        $dados['nome'] = $u->getNome();
        $dados['tipo'] = $u->getTipo();
        $dados['cenarios'] = $cn->getCenarios($dados['tipo']);
        $dados['previsao_cenario'] = array();
        
        if(count($dados['cenarios']) > 0) {
            foreach($dados['cenarios'] as $cenario) {
                $dados['previsao_cenario'][$cenario['id']] = $cn->getPrevisaoCenario($dados['tipo'], $cenario['id']);
            }
        }
        
        $this->loadTemplate('visualizar_cenarios', $dados);        
    }

    public function cartoes_credito() {
        $dados = array();

        $u = new Usuarios($_SESSION['twlg']);
        $c = new Contas();
        $f = new Fundos();

        $dados['nome'] = $u->getNome();
        $dados['tipo'] = $u->getTipo();
        $dados['cartoes_credito'] = $f->getCartoesCreditoFaturas($dados['tipo']);

        $this->loadTemplate('visualizar_cartoes_credito', $dados);  
    }

    public function por_conta() {
        $dados = array();

        $u = new Usuarios($_SESSION['twlg']);
        $c = new Contas();
    
        $dados['nome'] = $u->getNome();
        $dados['tipo'] = $u->getTipo();
        $dados['contas'] = $c->getContas($dados['tipo']);
        $dados['valores_conta'] = [];
        $dados['valores_gastos_conta'] = [];

        if(count($dados['contas']) > 0) {
            foreach ($dados['contas'] as $conta) {
				if($conta['id'] != 7) {
		        	$dados['nome_contas'][$conta['id']] = $c->getNome($conta['id']);
                    $dados['valores_gastos_conta'][$conta['id']] = $c->getValoresReais($conta['id'], 7);
                    $dados['valores_previsao_conta'][$conta['id']] = $c->getValoresPassados($conta['id'], 7);
				}
			}
        }

        $this->loadTemplate('visualizar_por_conta', $dados);  
    }
    
}
?>


















