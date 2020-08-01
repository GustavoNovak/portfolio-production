<?php
class homeController extends controller{
    
    public function __construct() {
        
        if(!isset($_SESSION['twlg']) && empty($_SESSION['twlg'])) {
            echo "<script>location.href='".BASE_URL."login"."';</script>"; 
            //header("Location: ".BASE_URL."login");
            exit;
        }
    }
    
    public function index() {

        $dados = array();

        $c = new Contas();
        $l = new lancamentos();
        $f = new Fundos();
        $u = new Usuarios($_SESSION['twlg']);
        $dados['nome'] = $u->getNome();
        $dados['tipo'] = $u->getTipo();
        

        $mobile = false;

        $user_agents = array("iPhone","iPad","Android","webOS","BlackBerry","iPod","Symbian","IsGeneric");

        foreach($user_agents as $user_agent){

            if (strpos($_SERVER['HTTP_USER_AGENT'], $user_agent) !== false) {
                $mobile = true;

                $modelo = $user_agent;

                break;
            }
        }

        if(!$mobile){
            $dados['previsao'] = $c->getPrevisaoComLancamentosPrevisao($dados['tipo']);
        }else{
            $dados['previsao'] = [];
        }


        $dados['fundos'] = $f->getFundos($dados['tipo']);
        $dados['ultimas_faturas'] = $f->getCartoesCreditoFaturas($dados['tipo']);
        $contas_a_pagar = $l->getLancamentosFiltrados('', '', '', '', '3', '');
        $contas_a_receber = $l->getLancamentosFiltrados('', '', '', '', '4', '');

        $dados['contas_a_pagar'] = 0.00;
        $dados['contas_a_receber'] = 0.00;
        foreach($contas_a_pagar as $conta_a_pagar) {
            $dados['contas_a_pagar'] += $conta_a_pagar['valor'];
        }
        foreach($contas_a_receber as $conta_a_receber) {
            $dados['contas_a_receber'] += $conta_a_receber['valor'];
        }

        $this->loadTemplate('home', $dados);
    }
    
}
?>


















