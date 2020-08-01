<?php
class Lancamentos extends model {

	public function getTipoConta($id_lancamento) {
		$c = new Contas();

		$sql = "SELECT * FROM lancamentos WHERE id = '$id_lancamento'";
		$sql = $this->db->query($sql);

		if($sql->rowCount() > 0) {

			return $c->getTipo($sql->fetch()['id_conta']);

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

			$sql = "SELECT * FROM lancamentos WHERE id_usuario IN ( ".implode($ids_usuarios,',')." ) AND orcamento = '0' ORDER BY data LIMIT 50";
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

			$sql = "SELECT * FROM lancamentos WHERE id_usuario IN ( ".implode($ids_usuarios,',')." ) AND orcamento = '0' ORDER BY data LIMIT 50";
			$sql = $this->db->query($sql);

			if($sql->rowCount() > 0) {
				$lancamentos = $sql->fetchAll();
			}               
		}

		return $lancamentos;

	}

	public function getLancamentosFiltrados($data_inicio, $data_fim, $id_fundo, $id_conta, $tipo, $descricao) {

		$lancamentos = array();

		$u = new Usuarios($_SESSION['twlg']);
		$f = new Fundos();

		$user_tipo = $u->getTipo();

		if(empty($data_inicio) && empty($data_fim) && empty($id_fundo) && empty($id_conta) && empty($tipo) && empty($descricao)) {
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

			$array_usuarios = "(".implode(',',$ids_usuarios).")";

			if(count($ids_usuarios) > 0) {
				$sql = array();
				
				$sql[] = "SELECT * FROM lancamentos WHERE id_usuario IN ".$array_usuarios." AND orcamento = '0' ";

				if(!empty($data_inicio)) {
					$sql[] = "data >= '$data_inicio'";
				}
				if(!empty($data_fim)) {
					$sql[] = "data <= '$data_fim'";
				}		
				if(!empty($id_fundo)) {
					$sql[] = "id_fundo = '$id_fundo'";
				} 			
				if(!empty($id_conta)) {
					$sql[] = "id_conta = '$id_conta'";
				}
				if(!empty($tipo)) {
					if($tipo == 1){
						$sql[] = "cartao_credito = '0' AND previsao = '0'";
					} elseif($tipo == 2) {
						$sql[] = "cartao_credito = '1' AND previsao = '0'";
					} elseif($tipo == 3) {
						$sql[] = "previsao = '1' AND (select contas.tipo from contas where contas.id = lancamentos.id_conta) = '2'";
					} elseif($tipo == 4) {
						$sql[] = "previsao = '1' AND (select contas.tipo from contas where contas.id = lancamentos.id_conta) = '1'";
					}
				}
				if(!empty($descricao)) {
					$sql[] = "descricao LIKE '%".$descricao."%'";
				}

				$sql = implode(" AND ", $sql);

				$sql .= " ORDER BY data";
				//echo $sql;
				//exit;
				$sql = $this->db->query($sql);				

			}
		}

		if($sql->rowCount() > 0) {
			$lancamentos = $sql->fetchAll();
		}

		return $lancamentos;
	}

    public function insertLancamentoPagamento($descricao, $conta, $fundo, $valor, $parcelas, $data, $lancamento_futuro){

    	$f = new Fundos();

    	$sql = "SELECT * FROM fundos WHERE id = '$fundo'";
    	$sql = $this->db->query($sql);

    	if($sql->rowCount() > 0) {
    		$tipo_fundo = $sql->fetch()['tipo'];

    		if($lancamento_futuro == 0) {

	    		if($tipo_fundo == 3) {

	    			if((strtotime($data) <= strtotime(date('d-m-Y'))) && (strtotime($data) >= strtotime(date('d-m-Y', strtotime('-13 month')))) ) {

		    			$sql = "SELECT * FROM fundos WHERE id = '$fundo'";
		    			$sql = $this->db->query($sql);

		    			$fundo_query = $sql->fetch();

		    			$fechamento = $fundo_query['fechamento'];
		    			$vencimento = $fundo_query['vencimento'];

		    			if($vencimento >= $fechamento) {
							if(date('d', strtotime($data)) > $fechamento) {
			    				$primeira_parcela = $vencimento.'-'.date('m-Y', strtotime($data));
			    				$primeira_parcela = new DateTime($primeira_parcela);
			 	 				$primeira_parcela->modify('+1 month');
			    			} else {
			    				$primeira_parcela = $vencimento.'-'.date('m-Y', strtotime($data));	
			      				$primeira_parcela = new DateTime($primeira_parcela);	
			    			}
						
			    			if($parcelas > 1) {
			    				for($i=1;$i<=$parcelas;$i++){
				 	    			$sql = "INSERT INTO lancamentos SET id_usuario = '".$_SESSION['twlg']."', descricao = '".$descricao." (".$i."/".$parcelas.")"."', id_conta = '$conta', id_fundo = '$fundo', valor = '$valor', data = '".$primeira_parcela->format('Y-m-d')."', cartao_credito = '1', previsao = '0', data_cadastro = NOW(), pagamento = '0', valor_pago = '0.00', competencia = '".$primeira_parcela->format('m-Y')."', orcamento = '0'";
					    			$this->db->query($sql);

									$f->alterarSaldoFundo($fundo, $valor, 2);   

					    			$primeira_parcela->modify('+1 month');
			    				}

			    				return "<div class='text-success'>Lançamento inserido com sucesso!</div>";
			    			} else {
			 	    			$sql = "INSERT INTO lancamentos SET id_usuario = '".$_SESSION['twlg']."', descricao = '$descricao', id_conta = '$conta', id_fundo = '$fundo', valor = '$valor', data = '".$primeira_parcela->format('Y-m-d')."', cartao_credito = '1', previsao = '0', data_cadastro = NOW(), pagamento = '0', valor_pago = '0.00', competencia = '".$primeira_parcela->format('m-Y')."', orcamento = '0'";
				    			$this->db->query($sql); 

								return $f->alterarSaldoFundo($fundo, $valor, 2);		
			    			}
		    			} else {
							if(date('d', strtotime($data)) > $fechamento) {
			    				$primeira_parcela = $vencimento.'-'.date('m-Y', strtotime($data));
			    				$primeira_parcela = new DateTime($primeira_parcela);
			 	 				$primeira_parcela->modify('+2 month');
			    			} else {
			    				$primeira_parcela = $vencimento.'-'.date('m-Y', strtotime($data));	
			      				$primeira_parcela = new DateTime($primeira_parcela);
			      				$primeira_parcela->modify('+1 month');	
			    			}
						
			    			if($parcelas > 1) {
			    				for($i=1;$i<=$parcelas;$i++){
				 	    			$sql = "INSERT INTO lancamentos SET id_usuario = '".$_SESSION['twlg']."', descricao = '".$descricao." (".$i."/".$parcelas.")"."', id_conta = '$conta', id_fundo = '$fundo', valor = '$valor', data = '".$primeira_parcela->format('Y-m-d')."', cartao_credito = '1', previsao = '0', data_cadastro = NOW(), pagamento = '0', valor_pago = '0.00', competencia = '".$primeira_parcela->format('m-Y')."', orcamento = '0'";
					    			$this->db->query($sql);

									$f->alterarSaldoFundo($fundo, $valor, 2);   

					    			$primeira_parcela->modify('+1 month');
			    				}

			    				return "<div class='text-success'>Lançamento inserido com sucesso!</div>";
			    			} else {
			 	    			$sql = "INSERT INTO lancamentos SET id_usuario = '".$_SESSION['twlg']."', descricao = '$descricao', id_conta = '$conta', id_fundo = '$fundo', valor = '$valor', data = '".$primeira_parcela->format('Y-m-d')."', cartao_credito = '1', previsao = '0', data_cadastro = NOW(), pagamento = '0', valor_pago = '0.00', competencia = '".$primeira_parcela->format('m-Y')."', orcamento = '0'";
				    			$this->db->query($sql); 

								return $f->alterarSaldoFundo($fundo, $valor, 2);		
			    			}
		    			}

	    			} else {
		    			return "<div class='text-danger'>Você não pode inserir um lançamento de pagamento com data maior que a de hoje ou muito antigo!</div>";
		    		}

	    		} else {
		    			
		    		if((strtotime($data) <= strtotime(date('d-m-Y'))) && (strtotime($data) >= strtotime(date('d-m-Y', strtotime('-13 month')))) ) {
		    			$sql = "INSERT INTO lancamentos SET id_usuario = '".$_SESSION['twlg']."', descricao = '$descricao', id_conta = '$conta', id_fundo = '$fundo', valor = '$valor', data = '".date('Y-m-d',strtotime($data))."', cartao_credito = '0', previsao = '0', data_cadastro = NOW(), orcamento = '0'";
		    			$this->db->query($sql);
							
		    			return $f->alterarSaldoFundo($fundo, $valor, 2);

		    		} else {
		    			return "<div class='text-danger'>Você não pode inserir um lançamento de pagamento com data maior que a de hoje ou muito antigo!</div>";
		    		}

	    		}

	    	} else {

	    		if(strtotime($data) > strtotime(date('d-m-Y'))) {

	    			$data = date('d-m-Y', strtotime($data));	
	      			$data = new DateTime($data);	
				
		    		if($parcelas > 1) {
		    			for($i=1;$i<=$parcelas;$i++){
			 	    		$sql = "INSERT INTO lancamentos SET id_usuario = '".$_SESSION['twlg']."', descricao = '".$descricao." (".$i."/".$parcelas.")"."', id_conta = '$conta', valor = '$valor', data = '".$data->format('Y-m-d')."', cartao_credito = '0', previsao = '1', data_cadastro = NOW(), orcamento = '0'";
				    		$this->db->query($sql);

				    		$data->modify('+1 month');
		    			}

		    			return "<div class='text-success'>Lançamento inserido com sucesso!</div>";
		    		} else {
		 	    		$sql = "INSERT INTO lancamentos SET id_usuario = '".$_SESSION['twlg']."', descricao = '$descricao', id_conta = '$conta', valor = '$valor', data = '".$data->format('Y-m-d')."', cartao_credito = '0', previsao = '1', data_cadastro = NOW(), orcamento = '0'";
			    		$this->db->query($sql); 

			   			return "<div class='text-success'>Lançamento inserido com sucesso!</div>";	
		    		}

		    	} else {
		    		return "<div class='text-danger'>Você não pode inserir um lançamento de pagamento com data menor que a de hoje como a pagar! </div>";
		    	}    		

	    	}

    	} else {
    		return "<div class='text-danger'>Essa conta não existe</div>";
    	}

    }

    public function insertLancamentoRecebimento($descricao, $lancamento_futuro, $conta, $fundo, $valor, $parcelas, $data){

    	$f = new Fundos();

    	$sql = "SELECT * FROM fundos WHERE id = '$fundo'";
    	$sql = $this->db->query($sql);

    	if($sql->rowCount() > 0) {
    		$tipo_fundo = $sql->fetch()['tipo'];

    		if($lancamento_futuro == 0) {

	    		if($tipo_fundo == 3) {

	    			return "<div class='text-danger'>Você não pode inserir recebimento em cartão de crédito!</div>";

	    		} else {
		    			
		    		if((strtotime($data) <= strtotime(date('d-m-Y'))) && (strtotime($data) >= strtotime(date('d-m-Y', strtotime('-13 month')))) ) {
		    			$sql = "INSERT INTO lancamentos SET id_usuario = '".$_SESSION['twlg']."', descricao = '$descricao', id_conta = '$conta', id_fundo = '$fundo', valor = '$valor', data = '".date('Y-m-d',strtotime($data))."', cartao_credito = '0', previsao = '0', data_cadastro = NOW(), orcamento = '0'";
		    			$this->db->query($sql);
							
		    			return $f->alterarSaldoFundo($fundo, $valor, 1);

		    		} else {
		    			return "<div class='text-danger'>Você não pode inserir um lançamento de recebimento com data maior que a de hoje como não sendo a receber! </div>";
		    		}

	    		}

	    	} else {

	    		if($tipo_fundo == 3) {

	    			return "<div class='text-danger'>Você não pode inserir recebimento em cartão de crédito!</div>";

	    		} else {

	    			if(strtotime($data) > strtotime(date('d-m-Y'))) {

	    				$data = date('d-m-Y', strtotime($data));	
	      				$data = new DateTime($data);	
				
		    			if($parcelas > 1) {
		    				for($i=1;$i<=$parcelas;$i++){
			 	    			$sql = "INSERT INTO lancamentos SET id_usuario = '".$_SESSION['twlg']."', descricao = '".$descricao." (".$i."/".$parcelas.")"."', id_conta = '$conta', valor = '$valor', data = '".$data->format('Y-m-d')."', cartao_credito = '0', previsao = '1', data_cadastro = NOW(), orcamento = '0'";
				    			$this->db->query($sql);

				    			$data->modify('+1 month');
		    				}

		    				return "<div class='text-success'>Lançamento inserido com sucesso!</div>";
		    			} else {
		 	    			$sql = "INSERT INTO lancamentos SET id_usuario = '".$_SESSION['twlg']."', descricao = '$descricao', id_conta = '$conta', valor = '$valor', data = '".$data->format('Y-m-d')."', cartao_credito = '0', previsao = '1', data_cadastro = NOW(), orcamento = '0'";
			    			$this->db->query($sql); 	
		    			}

		    		} else {
		    			return "<div class='text-danger'>Você não pode inserir um lançamento de pagamento com data menor que a de hoje como a receber! </div>";
		    		}

	    		}	    				

	    	}

    	} else {
    		return "<div class='text-danger'>Essa fundo não existe</div>";
    	}   	

    }

    public function excluirLancamento($id_lancamento) {

    	$sql = "SELECT * FROM lancamentos WHERE id = '$id_lancamento'";
    	$sql = $this->db->query($sql);

    	if($sql->rowCount() > 0) {

	    	$lancamento = $sql->fetch();

	    	if($lancamento['previsao'] == '1') {
		    	$sql = "DELETE FROM lancamentos WHERE id = '$id_lancamento'";
		    	$this->db->query($sql);

		    	return "<div class='text-success'>Lançamento excluído com sucesso!</div>";
	    	} else {
	    		$sql = "SELECT * FROM contas WHERE id='".$lancamento['id_conta']."'";
	    		$sql = $this->db->query($sql);

	    		if($sql->rowCount() > 0) {
	    			$conta = $sql->fetch();

	    			$tipo_alteracao_saldo = ($conta['tipo'] == '1')?'2':'1';

	    			$sql = "SELECT * FROM fundos WHERE id = '".$lancamento['id_fundo']."'";
	    			$sql = $this->db->query($sql);

	    			if($sql->rowCount() > 0) {
	    				$f = new Fundos();
	    				$fundo = $sql->fetch();

	    				if($fundo['tipo'] == '1' || $fundo['tipo'] == '2') {

	    					if($lancamento['id_conta'] == '7') {
		    					
		    					$pagamentos = array();

		    					$sql = "SELECT * FROM pagamento_cartao_credito WHERE id_lancamento_pagamento='".$lancamento['id']."'";
		    					$sql = $this->db->query($sql);

		    					if($sql->rowCount() > 0) {

		    						$pagamentos = $sql->fetchAll();

		    					}

		    					foreach($pagamentos as $pagamento) {

		    						$sql = "SELECT * FROM lancamentos WHERE id = '".$pagamento['id_lancamento_pago']."'";
		    						$sql = $this->db->query($sql);

		    						if($sql->rowCount() > 0) {

		    							$lancamento_pago = $sql->fetch();

		    							if($lancamento_pago['pagamento'] == '2') {

		    								if($lancamento_pago['valor'] == $pagamento['valor']) {
		    									$sql = "UPDATE lancamentos SET pagamento = '0', valor_pago = '0.00' WHERE id = '".$lancamento_pago['id']."'";
		    									$this->db->query($sql);
		    								} else {
		    									$novo_saldo = $lancamento_pago['valor'] - $pagamento['valor'];

		    									$sql = "UPDATE lancamentos SET pagamento = '1', valor_pago = '".$novo_saldo."' WHERE id = '".$lancamento_pago['id']."'";
		    									$this->db->query($sql);		    									
		    								}

		    								$f->alterarSaldoFundo($lancamento_pago['id_fundo'], $pagamento['valor'], 2);
		    								$sql = "DELETE FROM pagamento_cartao_credito WHERE id='".$pagamento['id']."'";
		    								$this->db->query($sql);

		    							} elseif($lancamento_pago['pagamento'] == '1') {

		    								if($lancamento_pago['valor_pago'] == $pagamento['valor']) {
		    									$sql = "UPDATE lancamentos SET pagamento = '0', valor_pago = '0.00' WHERE id = '".$lancamento_pago['id']."'";
		    									$this->db->query($sql);
		    								} else {
		    									$novo_saldo = $lancamento_pago['valor_pago'] - $pagamento['valor'];

		    									$sql = "UPDATE lancamentos SET pagamento = '1', valor_pago = '".$novo_saldo."' WHERE id = '".$lancamento_pago['id']."'";
		    									$this->db->query($sql);		    									
		    								}

		    								$f->alterarSaldoFundo($lancamento_pago['id_fundo'], $pagamento['valor'], 2);
		    								$sql = "DELETE FROM pagamento_cartao_credito WHERE id='".$pagamento['id']."'";
		    								$this->db->query($sql);		

		    							}

		    						}

		    					}

		    					$f->alterarSaldoFundo($lancamento['id_fundo'], $lancamento['valor'], $tipo_alteracao_saldo);
							    $sql = "DELETE FROM lancamentos WHERE id = '$id_lancamento'";
							    $this->db->query($sql);	

	    					} else {
		    					$f->alterarSaldoFundo($lancamento['id_fundo'], $lancamento['valor'], $tipo_alteracao_saldo);
							    $sql = "DELETE FROM lancamentos WHERE id = '$id_lancamento'";
							    $this->db->query($sql);	
	    					}	    						

	    				} elseif($fundo['tipo'] == '3') {
	    					
	    					$pagamentos = array();

	    					$sql = "SELECT * FROM pagamento_cartao_credito WHERE id_lancamento_pago='".$lancamento['id']."'";
	    					$sql = $this->db->query($sql);

	    					if($sql->rowCount() > 0) {

	    						$pagamentos = $sql->fetchAll();

	    					}

	    					if($lancamento['pagamento'] == '2') {

	    						foreach($pagamentos as $pagamento) {

	    							$sql = "SELECT * FROM lancamentos WHERE id = '".$pagamento['id_lancamento_pagamento']."'";
	    							$sql = $this->db->query($sql);

	    							if($sql->rowCount() > 0) {
	    								
										$lancamento_pagamento = $sql->fetch();
	    							
										$f->alterarSaldoFundo($lancamento_pagamento['id_fundo'], $pagamento['valor'], $tipo_alteracao_saldo);

										$novo_saldo = $lancamento_pagamento['valor'] - $pagamento['valor'];

										if($novo_saldo > 0) {
											$sql = "UPDATE lancamentos SET valor = '".$novo_saldo."' WHERE id = '".$lancamento_pagamento['id']."'";
											$this->db->query($sql);	
										} else {
											$sql = "DELETE FROM lancamentos WHERE id = '".$lancamento_pagamento['id']."'";
											$this->db->query($sql);
										}									

										$sql = "DELETE FROM pagamento_cartao_credito WHERE id = '".$pagamento['id']."'";
										$this->db->query($sql);

	    							}    								

	    						}						


	    					} elseif($lancamento['pagamento'] == '1') {

	    						$saldo_devedor = $lancamento['valor'] - $lancamento['valor_pago'];

	    						foreach($pagamentos as $pagamento) {

	    							$sql = "SELECT * FROM lancamentos WHERE id = '".$pagamento['id_lancamento_pagamento']."'";
	    							$sql = $this->db->query($sql);

	    							if($sql->rowCount() > 0) {
	    								
										$lancamento_pagamento = $sql->fetch();
	    							
										$f->alterarSaldoFundo($lancamento_pagamento['id_fundo'], $pagamento['valor'], $tipo_alteracao_saldo);

										$novo_saldo = $lancamento_pagamento['valor'] - $pagamento['valor'];

										if($novo_saldo > 0) {
											$sql = "UPDATE lancamentos SET valor = '".$novo_saldo."' WHERE id = '".$lancamento_pagamento['id']."'";
											$this->db->query($sql);	
										} else {
											$sql = "DELETE FROM lancamentos WHERE id = '".$lancamento_pagamento['id']."'";
											$this->db->query($sql);
										}									

										$sql = "DELETE FROM pagamento_cartao_credito WHERE id = '".$pagamento['id']."'";
										$this->db->query($sql);

	    							}    								

	    						}

	    						$f->alterarSaldoFundo($lancamento['id_fundo'], $saldo_devedor, $tipo_alteracao_saldo);

	    					} elseif($lancamento['pagamento'] == '0') {

	    						$f->alterarSaldoFundo($lancamento['id_fundo'], $lancamento['valor'], $tipo_alteracao_saldo);

	    					}

						    $sql = "DELETE FROM lancamentos WHERE id = '$id_lancamento'";
						    $this->db->query($sql);

	    				}

	    				return "<div class='text-success'>Lançamento excluído com sucesso!</div>";

	    			} else {
	    				return "<div class='text-danger'>O fundo do lançamento não foi encontrado!</div>";	    				
	    			}

	    		} else {
	    			return "<div class='text-danger'>A conta do lançamento não foi encontrada!</div>";	    		
	    		}

	    	}

    	} else {
    		return "<div class='text-danger'>Lançamento já excluído!</div>";
    	}

    }

    public function gerarModalConfirmarLancamento($id_lancamento) {

    	return $id_lancamento;

    }
    
}
?>