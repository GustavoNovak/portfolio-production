<?php
class Usuarios {

	private $id;
	private $nome;
	private $email;
	private $genero;
	private $cpf;
	private $tipo;
	private $empresas;
	private $permissoes;

	public function __construct($id) {

		require 'processos_php/config_sistemanovak.php';
		$this->id = $id;
		$sql = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
		$sql->bindValue(":id",$this->id);
		$sql->execute();

		if($sql->rowCount() > 0){
			$usuario = $sql->fetch();
			$this->nome = $usuario['nome'];
			$this->email = $usuario['email'];
			$this->genero = $usuario['genero'];
			$this->cpf = $usuario['cpf'];
			$this->tipo = $usuario['tipo'];
			
			$sql = $pdo->prepare("SELECT * FROM associacao_empresa_usuario WHERE id_usuario = :id_usuario");
			$sql->bindValue(":id_usuario",$this->id);
			$sql->execute();
			if($sql->rowCount() > 0){
				$empresas = $sql->fetchAll();
				$this->empresas = array();
				$this->permissoes = array();
				foreach($empresas as $empresa){
					if($this->id == $empresa['id_usuario']) {
						$this->empresas[] = $empresa['id_empresa'];
						$this->permissoes[$empresa['id_empresa']] = $empresa['permissao']; 
					}
				}
			}
		}
	}

	public function getId(){
		return $this->id;
	}

	public function getNome(){
		return $this->nome;
	}

	public function getEmail(){
		return $this->email;
	}

	public function getGenero(){
		return $this->genero;
	}

	public function getCpf(){
		return $this->cpf;
	}

	public function getTipo(){
		return $this->tipo;
	}

	public function getEmpresas(){
		return $this->empresas;
	}

	public function getPermissoes(){
		return $this->permissoes;
	}
}

?>