<?php
class Empresas {

	private $id;
	private $codigo;
	private $nome;
	private $email_socio;
	private $cnpj;
	private $regime;
	private $estado_brasil;

	public function __construct($id) {

		require 'processos_php/config_sistemanovak.php';
		$this->id = $id;
		$sql = $pdo->prepare("SELECT * FROM empresas WHERE id = :id");
		$sql->bindValue(":id",$this->id);
		$sql->execute();

		if($sql->rowCount() > 0){
			$empresa = $sql->fetch();
			$this->codigo = $empresa['codigo'];
			$this->nome = $empresa['nome'];
			$this->email = $empresa['email_socio'];
			$this->regime = $empresa['regime'];
			$this->cnpj = $empresa['cnpj'];
			$this->estado_brasil = $empresa['estado_brasil'];
		}
	}

	public function getId(){
		return $this->id;
	}

	public function getCodigo(){
		return $this->codigo;
	}

	public function getNome(){
		return $this->nome;
	}

	public function getEmailSocio(){
		return $this->email_socio;
	}

	public function getCnpj(){
		return $this->cnpj;
	}

	public function getRegime(){
		return $this->regime;
	}

	public function getEstadoBrasil(){
		return $this->estado_brasil;
	}
}

class EmpresasSimples extends Empresas{

	private $categorias;
	private $fator_r;

	public function getCategorias(){

		if(empty($this->categorias)) {
			require 'processos_php/config_sistemanovak_programas_simulacaosimples.php';
			$sql = $pdo_programas_simulacaosimples->prepare("SELECT * FROM empresas_categorias WHERE id_empresa = :id_empresa");
			$sql->bindValue(":id_empresa",$this->getId());
			$sql->execute();
			$categorias = array();
			if($sql->rowCount() > 0){
				$categorias = $sql->fetchAll();
				foreach ($categorias as $categoria) {
					$this->categorias[] = $categoria['id_categoria'];
					$this->fator_r[$categoria['id_categoria']] = $categoria['fator_r'];
				}
			}			
		} else {
			return $this->categorias;
			exit;
		}

		return $this->categorias;

	}

	public function getFatorR(){

		if(empty($this->fator_r)) {
			require 'processos_php/config_sistemanovak_programas_simulacaosimples.php';
			$sql = $pdo_programas_simulacaosimples->prepare("SELECT * FROM empresas_categorias WHERE id_empresa = :id_empresa");
			$sql->bindValue(":id_empresa",$this->getId());
			$sql->execute();
			$categorias = array();
			if($sql->rowCount() > 0){
				$categorias = $sql->fetchAll();
				foreach ($categorias as $categoria) {
					$this->categorias[] = $categoria['id_categoria'];
					$this->fator_r[$categoria['id_categoria']] = $categoria['fator_r'];
				}
			}			
		} else {
			return $this->fator_r;
			exit;
		}

		return $this->fator_r;

	}

}

?>