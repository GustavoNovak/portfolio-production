<?php
class loginController extends controller{
    
    public function index() {
        $dados = array();
        
        if(isset($_POST['user']) && !empty($_POST['user'])) {
            
            $user = addslashes($_POST['user']);
            $senha = md5($_POST['senha']);
            
            $u = new Usuarios();
            
            if($u->fazerLogin($user, $senha)) {
                header('Location: '.BASE_URL);
            }
        }
        
        $this->loadView('login', $dados);

    }
    
    public function cadastro() {
        $dados = array();
        
        if(isset($_POST['user']) && !empty($_POST['user'])) {

            $user = addslashes($_POST['user']);
            if(!empty($_POST['senha']) && ( $_POST['senha'] == $_POST['confirmar_senha']) ){
                
                $senha = md5($_POST['senha']);

                if(!empty($user) && !empty($senha)) {
                    
                    $u = new Usuarios();
                    if(!$u->usuarioExiste($user)) {
                        $_SESSION['twlg'] = $u->inserirUsuario($user,$senha,1);
                        header('Location: '.BASE_URL."cadastro/usuario");
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
        
        $this->loadView('cadastro', $dados);
    }
    
    public function sair() {
        unset($_SESSION['twlg']);
        header('Location: '.BASE_URL."login");
    }

}
?>
