<?php
class Usuarios extends model {

    private $uid;
    
    public function __construct($id = '') {
        parent::__construct();
        
        if(!empty($id)) {
            $this->uid = $id;
        }
    }
    
    public function isLogged() {
        if(isset($_SESSION['twlg']) && !empty($_SESSION['twlg'])) {
            return true;
        } else {
            return false;
        }
    }

    public function getUsuariosDependentes() {
        $resultado = array();

        $sql = "SELECT id_dependente as id, (select usuarios.user FROM usuarios WHERE usuarios.id = usuario_dependente.id_dependente) as nome FROM usuario_dependente WHERE id_independente = '".$_SESSION['twlg']."'";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0) {
            $resultado = $sql->fetchAll();
        }

        return $resultado;
    }
    
    public function usuarioExiste($user) {
        
        $sql = "SELECT * FROM usuarios WHERE user = '$user'";
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
        
    }
    
    public function inserirUsuario($user, $senha, $tipo) {
        
        if($tipo == 1) {
            $sql = "INSERT INTO usuarios SET user = '$user', senha = '$senha', tipo = '$tipo' ";
            $this->db->query($sql);
            
            $id = $this->db->lastInsertId();
            
            return $id;
        } else {
            $sql = "INSERT INTO usuarios SET user = '$user', senha = '$senha', tipo = '$tipo' ";
            $this->db->query($sql);

            $sql = "INSERT INTO usuario_dependente SET id_dependente = '".$this->db->lastInsertId()."', id_independente = '".$_SESSION['twlg']."'";
            $this->db->query($sql);
        }
        
    }

    public function editarUsuario($id_usuario, $user, $senha) {

        if(!empty($user) || !empty($senha)) {
            if(!empty($senha)) {

                $senha = MD5($senha);
                $user = addslashes($user);

                $sql = "SELECT * FROM usuarios WHERE user = '$user' AND id != '".$id_usuario."'";
                $sql = $this->db->query($sql);

                if($sql->rowCount() == 0) {
                    $sql = "UPDATE usuarios SET user = '$user', senha = '$senha' WHERE id = '".$id_usuario."'";
                    $this->db->query($sql);

                    return "<div class='text-success'>Usuário alterado com sucesso!</div>";
                } else {
                    return "<div class='text-danger'>Esse username já está sendo utilizado!</div>";
                }

            } else {

                $user = addslashes($user);

                $sql = "SELECT * FROM usuarios WHERE user = '$user' AND id != '".$id_usuario."'";
                $sql = $this->db->query($sql);

                if($sql->rowCount() == 0) {
                    $sql = "UPDATE usuarios SET user = '$user' WHERE id = '".$id_usuario."'";
                    $this->db->query($sql);

                    return "<div class='text-success'>Usuário alterado com sucesso!</div>";
                } else {
                    return "<div class='text-danger'>Esse username já está sendo utilizado!</div>";
                }

            }


        } else {
            return "<div class='text-danger'>Insira pelo menos um usuario e uma senha!</div>";
        }

    }

    public function excluirUsuario() {

        $sql = "SELECT * FROM lancamentos WHERE id_usuario = '".$this->uid."'";
        $sql = $this->db->query($sql);

        if($sql->rowCount() == 0) {
            $sql = "DELETE FROM usuarios WHERE id = '".$this->uid."'";
            $this->db->query($sql);

            return "<div class='text-success'>Usuário excluído com sucesso!</div>";
        } else {
            return "<div class='text-danger'>Não é possível excluir esse usuário pois ele já tem lançamentos em seu nome!</div>";
        }        

    }
    
    public function fazerLogin($user, $senha) {
        
        $sql = "SELECT * FROM usuarios WHERE user = '$user' AND senha = '$senha'";
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0) {
            $sql = $sql->fetch();
            
            $_SESSION['twlg'] = $sql['id'];
            return true;
        } else {
            return false;
        }
        
    }
    
    public function getNome() {
        if(!empty($this->uid)) {
            $sql = "SELECT * FROM usuarios WHERE id = '".$this->uid."'";
            $sql = $this->db->query($sql);
            
            if($sql->rowCount() > 0) {
                $sql = $sql->fetch();
                
                return $sql['user'];
            }
        }
    }

    public function getTipo() {
        if(!empty($this->uid)) {
            $sql = "SELECT * FROM usuarios WHERE id = '".$this->uid."'";
            $sql = $this->db->query($sql);
            
            if($sql->rowCount() > 0) {
                $sql = $sql->fetch();
                
                return $sql['tipo'];
            }
        }
    }

    public function getAdm() {

        if(!empty($this->uid)) {
            $sql = "SELECT * FROM usuario_dependente WHERE id_dependente = '".$this->uid."'";
            $sql = $this->db->query($sql);
            
            if($sql->rowCount() > 0) {
                $sql = $sql->fetch();
                
                return $sql['id_independente'];
            }
        }       

    }
    
}
?>