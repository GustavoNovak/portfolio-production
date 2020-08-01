<?php
class Cenarios extends model {

	public function getNome($id_cenario) {

		$sql = "SELECT * FROM cenarios WHERE id = '$id_cenario'";
		$sql = $this->db->query($sql);

		if($sql->rowCount() > 0) {
			return $sql->fetch()['nome'];
		} else {
			return "Não achou";
		}

	}

	public function getTipoConta($id_lancamento) {
		$c = new Contas();

		$sql = "SELECT * FROM lancamentos WHERE id = '$id_lancamento'";
		$sql = $this->db->query($sql);

		if($sql->rowCount() > 0) {

			return $c->getTipo($sql->fetch()['id_conta']);

		}

	}

	public function getCenario($id_cenario) {

		$sql = "SELECT * FROM cenarios WHERE id = '$id_cenario'";
		$sql = $this->db->query($sql);

		if($sql->rowCount() > 0) {
			return $sql->fetch();
		} else {
			return array('id' => '', 'id_usuario' => '' ,'nome' => '');
		}
	}

	public function getDados($id_lancamento) {

		$sql = "SELECT * FROM lancamentos WHERE id = '$id_lancamento'";
		$sql = $this->db->query($sql);

		if($sql->rowCount() > 0) {
			return $sql->fetch();
		} else {
			return array();
		}

	}

	public function getCenarios($user_tipo) {

		$cenarios = array();

		if($user_tipo == 1){
			$usuarios = array();

			$sql = "SELECT * FROM usuario_dependente WHERE id_independente = '".$_SESSION['twlg']."'";
			$sql = $this->db->query($sql);

			if($sql->rowCount() > 0) {
				$usuarios = $sql->fetchAll();
			}

			foreach ($usuarios as $usuario) {
				$ids_usuarios[] = $usuario['id_dependente'];
			}

			$ids_usuarios[] = $_SESSION['twlg'];

			$sql = "SELECT * FROM cenarios WHERE id_usuario IN ( ".implode($ids_usuarios,',')." ) LIMIT 50";
			$sql = $this->db->query($sql);

			if($sql->rowCount() > 0) {
				$cenarios = $sql->fetchAll();
			}

		} else {
			$u = new Usuarios($_SESSION['twlg']);

			$id_usuario = $u->getAdm();

			$sql = "SELECT * FROM usuario_dependente WHERE id_independente = '".$id_usuario."'";
			$sql = $this->db->query($sql);

			if($sql->rowCount() > 0) {
				$usuarios = $sql->fetchAll();
			}

			foreach ($usuarios as $usuario) {
				$ids_usuarios[] = $usuario['id_dependente'];
			}

			$ids_usuarios[] = $id_usuario;

			$sql = "SELECT * FROM cenarios WHERE id_usuario IN ( ".implode($ids_usuarios,',')." ) LIMIT 50";
			$sql = $this->db->query($sql);

			if($sql->rowCount() > 0) {
				$cenarios = $sql->fetchAll();
			}               
		}

		return $cenarios;

	}

	public function alterarCenario($cenario, $nome) {

		$sql = "SELECT * FROM cenarios WHERE id = '$cenario'";
		$sql = $this->db->query($sql);

		if($sql->rowCount() > 0) {
			if(!empty($nome)) {
				$sql = "UPDATE cenarios SET nome = '$nome' WHERE id = '$cenario'";
				$this->db->query($sql);

				return "<div class='text-success'>Cenário alterado com sucesso!</div>";
			} else {
				return "<div class='text-danger'>Você deve informar um nome!</div>";
			}
		} else {
			return "<div class='text-danger'>Esse cenário não existe!</div>";
		}
	}

	public function excluirCenario($cenario) {

		$cenario = addslashes($cenario);

		$sql = "SELECT * FROM cenarios WHERE id = '$cenario'";
		$sql = $this->db->query($sql);

		if($sql->rowCount() > 0) {
			$sql = "DELETE FROM cenarios WHERE id = '$cenario'";
			$this->db->query($sql);

			$sql = "DELETE FROM exclusao_conta_cenario WHERE id_cenario = '$cenario'";
			$this->db->query($sql);

			$sql = "DELETE FROM lancamentos WHERE id_cenario = '$cenario' AND orcamento = '1'";
			$this->db->query($sql);

			return "<div class='text-success'>O cenário foi excluído!</div>"; 			
		} else {
			return "<div class='text-danger'>Esse cenário não existe!</div>";
		}

	}

	public function getContasDesconsideradas($id_cenario) {

		$sql = "SELECT id_conta FROM exclusao_conta_cenario WHERE id_cenario = '$id_cenario'";
		$sql = $this->db->query($sql);

		if($sql->rowCount() > 0) {
			return $sql->fetchAll();
		} else {
			return array();
		}

	}

	public function getLancamentos($user_tipo) {

		$f = new Fundos();

		$lancamentos = array();

		if($user_tipo == 1){
			$usuarios = array();

			$sql = "SELECT * FROM usuario_dependente WHERE id_independente = '".$_SESSION['twlg']."'";
			$sql = $this->db->query($sql);

			if($sql->rowCount() > 0) {
				$usuarios = $sql->fetchAll();
			}

			foreach ($usuarios as $usuario) {
				$ids_usuarios[] = $usuario['id_dependente'];
			}

			$ids_usuarios[] = $_SESSION['twlg'];

			$sql = "SELECT * FROM lancamentos WHERE id_usuario IN ( ".implode($ids_usuarios,',')." ) AND orcamento = '1' ORDER BY data LIMIT 50";
			$sql = $this->db->query($sql);

			if($sql->rowCount() > 0) {
				$lancamentos = $sql->fetchAll();
			}

		} else {
			$u = new Usuarios($_SESSION['twlg']);

			$id_usuario = $u->getAdm();

			$sql = "SELECT * FROM usuario_dependente WHERE id_independente = '".$id_usuario."'";
			$sql = $this->db->query($sql);

			if($sql->rowCount() > 0) {
				$usuarios = $sql->fetchAll();
			}

			foreach ($usuarios as $usuario) {
				$ids_usuarios[] = $usuario['id_dependente'];
			}

			$ids_usuarios[] = $id_usuario;

			$sql = "SELECT * FROM lancamentos WHERE id_usuario IN ( ".implode($ids_usuarios,',')." ) AND orcamento = '1' ORDER BY data LIMIT 50";
			$sql = $this->db->query($sql);

			if($sql->rowCount() > 0) {
				$lancamentos = $sql->fetchAll();
			}               
		}

		return $lancamentos;

	}

	public function getLancamentosFiltrados($data_inicio, $data_fim, $id_conta, $descricao, $id_cenario) {

		$lancamentos = array();

		$u = new Usuarios($_SESSION['twlg']);
		$f = new Fundos();

		$user_tipo = $u->getTipo();

		if(empty($data_inicio) && empty($data_fim) && empty($id_conta) && empty($descricao) && empty($id_cenario)) {
			return $this->getLancamentos($user_tipo);
			exit;
		} else {

			if($user_tipo == 1){
				$usuarios = array();

				$sql = "SELECT * FROM usuario_dependente WHERE id_independente = '".$_SESSION['twlg']."'";
				$sql = $this->db->query($sql);

				if($sql->rowCount() > 0) {
					$usuarios = $sql->fetchAll();
				}

				foreach ($usuarios as $usuario) {
					$ids_usuarios[] = $usuario['id_dependente'];
				}

				$ids_usuarios[] = $_SESSION['twlg'];
			} else {
				$u = new Usuarios($_SESSION['twlg']);

				$id_usuario = $u->getAdm();

				$sql = "SELECT * FROM usuario_dependente WHERE id_independente = '".$id_usuario."'";
				$sql = $this->db->query($sql);

				if($sql->rowCount() > 0) {
					$usuarios = $sql->fetchAll();
				}

				foreach ($usuarios as $usuario) {
					$ids_usuarios[] = $usuario['id_dependente'];
				}

				$ids_usuarios[] = $id_usuario;
			}

			$array_usuarios = "(".implode(' AND ',$ids_usuarios).")";

			if(count($ids_usuarios) > 0) {
				$sql = array();
				
				$sql[] = "SELECT * FROM lancamentos WHERE id_usuario IN ".$array_usuarios." AND orcamento = '1'";

				if(!empty($data_inicio)) {
					$sql[] = "data >= '$data_inicio'";
				}
				if(!empty($data_fim)) {
					$sql[] = "data <= '$data_fim'";
				}				
				if(!empty($id_conta)) {
					$sql[] = "id_conta = '$id_conta'";
				}
				if(!empty($id_cenario)) {
					$sql[] = "id_cenario = '$id_cenario'";
				}
				if(!empty($descricao)) {
					$sql[] = "descricao LIKE '%".$descricao."%'";
				}

				$sql = implode(" AND ", $sql);

				$sql .= " ORDER BY data LIMIT 50";

				$sql = $this->db->query($sql);				

			}
		}

		if($sql->rowCount() > 0) {
			$lancamentos = $sql->fetchAll();
		}

		return $lancamentos;
	}

	public function insertCenario($nome) {

		$sql = "INSERT INTO cenarios SET id_usuario = '".$_SESSION['twlg']."', nome = '$nome'";
		$this->db->query($sql);

		return "<div class='text-success'>Cenário inserido com sucesso</div>";

	}

    public function insertLancamentoPagamento($descricao, $conta, $valor, $parcelas, $data, $id_cenario){

	    if(strtotime($data) >= strtotime(date('d-m-Y'))) {

	    	$data = date('d-m-Y', strtotime($data));	
	      	$data = new DateTime($data);	
				
		    if($parcelas > 1) {
		    	for($i=1;$i<=$parcelas;$i++){
			 	    $sql = "INSERT INTO lancamentos SET id_usuario = '".$_SESSION['twlg']."', descricao = '".$descricao." (".$i."/".$parcelas.")"."', id_conta = '$conta', valor = '$valor', data = '".$data->format('Y-m-d')."', cartao_credito = '0', previsao = '0', data_cadastro = NOW(), orcamento = '1', id_cenario = '$id_cenario'";
				    $this->db->query($sql);

				    $data->modify('+1 month');
		    	}

		    	return "<div class='text-success'>Orçamento inserido com sucesso!</div>";
		    } else {
		 	    $sql = "INSERT INTO lancamentos SET id_usuario = '".$_SESSION['twlg']."', descricao = '$descricao', id_conta = '$conta', valor = '$valor', data = '".$data->format('Y-m-d')."', cartao_credito = '0', previsao = '0', data_cadastro = NOW(), orcamento = '1', id_cenario = '$id_cenario'";
			    $this->db->query($sql); 

			   	return "<div class='text-success'>Orçamento inserido com sucesso!</div>";	
		    }

		} else {
		    return "<div class='text-danger'>Você não pode inserir um orçamento de pagamento com data menor que a de hoje! </div>";
		}    		

    }

    public function insertLancamentoRecebimento($descricao, $conta, $valor, $parcelas, $data, $id_cenario){

	    if(strtotime($data) >= strtotime(date('d-m-Y'))) {

	    	$data = date('d-m-Y', strtotime($data));	
	      		$data = new DateTime($data);	
				
		    	if($parcelas > 1) {
		    		for($i=1;$i<=$parcelas;$i++){
			 	    	$sql = "INSERT INTO lancamentos SET id_usuario = '".$_SESSION['twlg']."', descricao = '".$descricao." (".$i."/".$parcelas.")"."', id_conta = '$conta', valor = '$valor', data = '".$data->format('Y-m-d')."', cartao_credito = '0', previsao = '0', data_cadastro = NOW(), orcamento = '1', id_cenario = '$id_cenario'";
				    	$this->db->query($sql);

				    	$data->modify('+1 month');
		    		}

		    		return "<div class='text-success'>Orçamento inserido com sucesso!</div>";
		    	} else {
		 	    	$sql = "INSERT INTO lancamentos SET id_usuario = '".$_SESSION['twlg']."', descricao = '$descricao', id_conta = '$conta', valor = '$valor', data = '".$data->format('Y-m-d')."', cartao_credito = '0', previsao = '0', data_cadastro = NOW(), orcamento = '1', id_cenario = '$id_cenario'";
			    	$this->db->query($sql); 

			    	return "<div class='text-success'>Orçamento inserido com sucesso!</div>";	
		    	}

		} else {
		   	return "<div class='text-danger'>Você não pode inserir um orçamento de pagamento com data menor que a de hoje! </div>";
		}    				

    }

    public function excluirLancamento($id_lancamento) {

    	$sql = "SELECT * FROM lancamentos WHERE id = '$id_lancamento' AND orcamento = '1'";
    	$sql = $this->db->query($sql);

    	if($sql->rowCount() > 0) {

	    	$lancamento = $sql->fetch();

		    $sql = "DELETE FROM lancamentos WHERE id = '$id_lancamento' AND orcamento = '1'";
		    $this->db->query($sql);

		    return "<div class='text-success'>Orçamento excluído com sucesso!</div>";

	    } else {

	    	return "<div class='text-danger'>Esse lançamento não é um orçamento ou já foi excluído!</div>";

	    }

    }

    public function excluirContaCenario($id_conta, $id_cenario) {

    	$sql = "SELECT * FROM exclusao_conta_cenario WHERE id_conta = '$id_conta' AND id_cenario = '$id_cenario'";
    	$sql = $this->db->query($sql);

    	if($sql->rowCount() == 0) {

	    	$sql = "INSERT INTO exclusao_conta_cenario SET id_conta = '$id_conta', id_cenario = '$id_cenario'";
	    	$this->db->query($sql);

	    	return "<div class='text-success'>Exclusão feita com sucesso!</div>";

    	} else {
    		return "<div class='text-danger'>Essa conta já foi excluída desse cenário!</div>";
    	}

    }

    public function excluirExclusaoCenario($id_conta, $id_cenario) {

    	$sql = "SELECT * FROM exclusao_conta_cenario WHERE id_conta = '$id_conta' AND id_cenario = '$id_cenario'";
    	$sql = $this->db->query($sql);

    	if($sql->rowCount() > 0) {
    		$sql = "DELETE FROM exclusao_conta_cenario WHERE id_conta = '$id_conta' AND id_cenario = '$id_cenario'";
    		$this->db->query($sql);

    		return "<div class='text-success'>Exclusão deseita com sucesso!</div>";
    	} else {
    		return "<div class='text-danger'>Essa conta nunca foi excluída desse cenário!</div>";
    	}

    }

    public function getPrevisaoCenario($user_tipo, $id_cenario) {

        $juros = 0.12;

        $f = new Fundos();
        $l = new lancamentos();
        $c = new Contas();

        $contas_desconsideradas = $this->getContasDesconsideradas($id_cenario);
        $contas_desc = array();
        foreach ($contas_desconsideradas as $value) {
        	$contas_desc[] = $value['id_conta'];
        }

        $contas = $c->getContas($user_tipo);

        $competencia = new Datetime('01-'.date('m-Y'));   
        
        $previsao = array( $competencia->format('m-Y') => 0.00 );

        $fundos = $f->getFundos($user_tipo);

        foreach($fundos as $fundo) {
            if($fundo['tipo'] != 3) {
                $previsao[$competencia->format('m-Y')] += $fundo['saldo_atual'];
            } else {
                $previsao[$competencia->format('m-Y')] -= $f->getDivida($fundo['id']);
            }
        }

        $saldo = $previsao[$competencia->format('m-Y')];

        for($i=1;$i<20;$i++) {
            $valor_competencia = 0.00;
            if($i == 1) {
                foreach ($contas as $conta) {
                	if(!in_array($conta['id'], $contas_desc)) {
	                    $valor_conta = $c->getValorConta($conta['id'], $competencia->format('m-Y'));
	                    $valor_conta_a_pagar = 0.00;
	                    $valor_cenario = 0.00;

	                    $competencia_ultimo_dia = $competencia->format('d-m-Y');
	                    $competencia_ultimo_dia = new Datetime($competencia_ultimo_dia);
	                    $competencia_ultimo_dia = $competencia_ultimo_dia->modify('+1 month')->modify('-1 day');

	                    $lancamentos_conta = $l->getLancamentosFiltrados($competencia->format('Y-m-d'), $competencia_ultimo_dia->format('Y-m-d'), '', $conta['id'], '', '');
	                    $lancamentos_cenario = $this->getLancamentosFiltrados($competencia->format('Y-m-d'), $competencia_ultimo_dia->format('Y-m-d'), $conta['id'], '', $id_cenario);

	                    foreach ($lancamentos_conta as $lancamento_conta) {
	                        
	                        if($conta['id'] != '7') {
	                            if($conta['id'] != '6') {
	                                if($conta['tipo'] == '1') {
	                                    $valor_conta -= $lancamento_conta['valor'];
	                                } else {
	                                    if ($lancamento_conta['cartao_credito'] == '1') {
	                                        if($lancamento_conta['pagamento'] == '2') {
	                                            $valor_conta -= $lancamento_conta['valor'];
	                                        } else {
	                                            $valor_conta_a_pagar = $valor_conta_a_pagar + $lancamento_conta['valor'] - $lancamento_conta['valor_pago'];
	                                            $valor_conta -= $lancamento_conta['valor_pago'];
	                                        }
	                                    } else {
	                                        $valor_conta -= $lancamento_conta['valor'];
	                                    }
	                                }
	                            } else {
	                                $valor_conta += $valor_conta;
	                            }
	                        }
	                        
	                    }

	                    foreach($lancamentos_cenario as $lancamento_cenario) {

	                        if($conta['id'] != '7') {
	                        	if($conta['tipo'] == '1') {
	                        		$valor_cenario += $lancamento_cenario['valor'];
	                        	} else {
	                        		$valor_cenario += $lancamento_cenario['valor'];
	                        	}
	                        }	                    	

	                    }

	                    if($conta['tipo'] == '2') {
	                        if(($valor_conta - $valor_conta_a_pagar) >= 0) {
	                            $valor_competencia -= ($valor_conta + $valor_cenario);
	                        } else {
	                            $valor_competencia -= ($valor_conta_a_pagar + $valor_cenario);
	                        }
	                    } else {
	                        if($valor_conta > 0) {
	                            $valor_competencia += ($valor_conta + $valor_cenario);
	                        } else {
	                        	$valor_competencia += $valor_cenario;
	                        }
	                    }

	               	}

                }

                $previsao[$competencia->format('m-Y')] = number_format(($saldo + $valor_competencia),2,'.','');
                $saldo = $previsao[$competencia->format('m-Y')];

            } else {
                foreach ($contas as $conta) {
					if(!in_array($conta['id'], $contas_desc)) {
	                    $valor_conta = $c->getValorConta($conta['id'], $competencia->format('m-Y'));
	                    $valor_conta_a_pagar = 0.00;
	                    $valor_cenario = 0.00;

	                    $competencia_ultimo_dia = $competencia->format('d-m-Y');
	                    $competencia_ultimo_dia = new Datetime($competencia_ultimo_dia);
	                    $competencia_ultimo_dia = $competencia_ultimo_dia->modify('+1 month')->modify('-1 day');

	                    $lancamentos_conta = $l->getLancamentosFiltrados($competencia->format('Y-m-d'), $competencia_ultimo_dia->format('Y-m-d'), '', $conta['id'], '', '');
	                    $lancamentos_cenario = $this->getLancamentosFiltrados($competencia->format('Y-m-d'), $competencia_ultimo_dia->format('Y-m-d'), $conta['id'], '', $id_cenario);

	                    foreach ($lancamentos_conta as $lancamento_conta) {
	                        
	                        if($conta['id'] != '7') {
	                            if($conta['id'] != '6') {
	                                if($conta['tipo'] == '1') {
	                                    $valor_conta -= $lancamento_conta['valor'];
	                                } else {
	                                    if ($lancamento_conta['cartao_credito'] == '1') {
	                                        if($lancamento_conta['pagamento'] == '2') {
	                                            $valor_conta -= $lancamento_conta['valor'];
	                                        } else {
	                                            $valor_conta_a_pagar = $valor_conta_a_pagar + $lancamento_conta['valor'] - $lancamento_conta['valor_pago'];
	                                            $valor_conta -= $lancamento_conta['valor_pago'];
	                                        }
	                                    } else {
	                                        $valor_conta -= $lancamento_conta['valor'];
	                                    }
	                                }
	                            } else {
	                                $valor_conta += $valor_conta;
	                            }
	                        }
	                        
	                    }

	                    foreach($lancamentos_cenario as $lancamento_cenario) {

		                    if($conta['id'] != '7') {
		                       	if($conta['tipo'] == '1') {
		                        	$valor_cenario += $lancamento_cenario['valor'];
		                        } else {
		                        	$valor_cenario += $lancamento_cenario['valor'];
		                        }
		                   	}	                    	

		               	}

	                    if($conta['tipo'] == '2') {
	                        if(($valor_conta - $valor_conta_a_pagar) >= 0) {
	                            $valor_competencia -= ($valor_conta + $valor_cenario);
	                        } else {
	                            $valor_competencia -= ($valor_conta_a_pagar + $valor_cenario);
	                        }
	                    } else {
	                        if($valor_conta > 0) {
	                            $valor_competencia += ($valor_conta + $valor_cenario);
	                        } else {
	                        	$valor_competencia += $valor_cenario;
	                        }
	                    }

	              	}

                }

                if($saldo >= 0) {
                    $previsao[$competencia->format('m-Y')] = number_format(($saldo + $valor_competencia),2,'.','');
                    $saldo = $previsao[$competencia->format('m-Y')];
                } else {
                    $previsao[$competencia->format('m-Y')] = number_format(((1 + $juros)*$saldo + $valor_competencia),2,'.','');
                    $saldo = $previsao[$competencia->format('m-Y')];                    
                }

            }

            $competencia->modify('+1 month');
        }    

        return $previsao;

    }
}
?>