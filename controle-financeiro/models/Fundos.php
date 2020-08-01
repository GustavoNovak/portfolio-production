<?php
class Fundos extends model {

    public function getNome($id_conta, $id_fundo) {

        if(!empty($id_fundo)) {
        
            $sql = "SELECT * FROM fundos WHERE id = '$id_fundo'";
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0) {
                return $sql->fetch()['nome'];
            }

        } else {

            $sql = "SELECT * FROM contas WHERE id = '$id_conta'";
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0) {
                if($sql->fetch()['tipo'] == 1) {
                    return "A receber";
                } else {
                    return "A pagar";
                }
            }

        }

    }

    public function getTipo($id_fundo) {

        $sql = "SELECT * FROM fundos WHERE id = '$id_fundo'";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0) {
            return $sql->fetch()['tipo'];
        } else {
            return '';
        }

    }

    public function getFundo($id_fundo) {
        $resultado = array();

        $sql = "SELECT * FROM fundos WHERE id = '$id_fundo'";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0) {
            $resultado = $sql->fetch();
        }

        return $resultado;

    }

    public function editarFundo($id_fundo, $nome, $vencimento, $fechamento, $saldo) {
        $tipo_antes = $this->getTipo($id_fundo);

        if(!empty($tipo_antes)) {
            if($tipo_antes == '3') {
                $sql = "UPDATE fundos SET nome = '$nome', vencimento = '$vencimento', fechamento = '$fechamento', saldo_atual = NULL, contas_a_pagar_total = '$saldo' WHERE id = '$id_fundo'";
                $this->db->query($sql);
            } else {
                $sql = "UPDATE fundos SET nome = '$nome', vencimento = NULL, fechamento = NULL, saldo_atual='$saldo', contas_a_pagar_total = NULL WHERE id = '$id_fundo'";
                $this->db->query($sql);
            }
            return "<div class='text-success'>Fundo alterado com sucesso</div>";
        } else {
            return "<div class='text-danger'>Esse fundo não pode ser alterado</div>";
        }

    }

    public function excluirFundo($id_fundo) {

        $sql = "SELECT * FROM lancamentos WHERE id_fundo = '$id_fundo'";
        $sql = $this->db->query($sql);

        if($sql->rowCount() == 0) {
            $sql = "DELETE FROM fundos WHERE id = '$id_fundo'";
            $this->db->query($sql);

            return "<div class='text-success'>Fundo excluído com sucesso!</div>";
        } else {
            return "<div class='text-danger'>Não é possível excluir esse fundo pois ele já tem lançamentos em seu nome!</div>";
        }


    }

    public function getTipos() {

        $sql = "SELECT * FROM tipos_fundos";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0) {
            return $sql->fetchAll();
        }

    }

    public function getFundos($user_tipo) {
	
	$resultado = array();
	
        if($user_tipo == '1') {
            $sql = "SELECT * FROM fundos WHERE id_usuario = '".$_SESSION['twlg']."'";
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0) {
                $resultado = $sql->fetchAll();
            }
        } else {
            $sql = "SELECT id_independente FROM usuario_dependente WHERE id_dependente = '".$_SESSION['twlg']."'";
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0) {
                $id_usuario = $sql->fetch()['id_independente'];

                $sql = "SELECT * FROM fundos WHERE id_usuario = '".$id_usuario."'";
                $sql = $this->db->query($sql);

                if($sql->rowCount() > 0) {
                    $resultado = $sql->fetchAll();
                }               
            }           
        }
        
        return $resultado;

    }

    public function getCartoesCredito($user_tipo) {

        if($user_tipo == '1') {
            $sql = "SELECT * FROM fundos WHERE id_usuario = '".$_SESSION['twlg']."' AND tipo = '3'";
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0) {
                return $sql->fetchAll();
            } else {
                return array();
            }
        } else {
            $sql = "SELECT id_independente FROM usuario_dependente WHERE id_dependente = '".$_SESSION['twlg']."'";
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0) {
                $id_usuario = $sql->fetch()['id_independente'];

                $sql = "SELECT * FROM fundos WHERE id_usuario = '".$id_usuario."' AND tipo = '3'";
                $sql = $this->db->query($sql);

                if($sql->rowCount() > 0) {
                    return $sql->fetchAll();
                } else {
                    return array();
                }               
            }           
        }

    }

    public function getCartoesCreditoFaturas($user_tipo) {
	
        $cartoes_credito = $this->getCartoesCredito($user_tipo);

        foreach($cartoes_credito as $cartao_credito) {
            $faturas[$cartao_credito['id']] = array();
            $vencimento = $cartao_credito['vencimento'];

            $hoje = date('d-m-Y');
            $hoje = new DateTime($hoje);
                    
            if($vencimento < $hoje->format('d')) {
                $hoje->modify('+1 month');
            }  

            for($i=1;$i<12;$i++) {
                if($i == 1) {
                   
                    $sql = "SELECT pagamento, SUM(valor) as soma_valor, SUM(valor_pago) as soma_valor_pago FROM lancamentos WHERE id_fundo = '".$cartao_credito['id']."' AND competencia = '".$hoje->format('m-Y')."' AND pagamento != '2'"; 
                    $sql = $this->db->query($sql);

                    if($sql->rowCount() > 0) {
                        $result = $sql->fetch();

                        $fatura = $result['soma_valor'] - $result['soma_valor_pago'];

                        if($fatura == 0) {
                            $faturas[$cartao_credito['id']][$vencimento.'/'.$hoje->format('m/Y')] = 0.00 + $this->getDivida($cartao_credito['id']);
                        } else {
                            $faturas[$cartao_credito['id']][$vencimento.'/'.$hoje->format('m/Y')] = $fatura + $this->getDivida($cartao_credito['id']);
                        }

                    }

                } else {

                    $sql = "SELECT pagamento, SUM(valor) as soma_valor, SUM(valor_pago) as soma_valor_pago FROM lancamentos WHERE id_fundo = '".$cartao_credito['id']."' AND competencia = '".$hoje->format('m-Y')."' AND pagamento != '2'";
                    $sql = $this->db->query($sql);

                    if($sql->rowCount() > 0) {
                        $result = $sql->fetch();

                        $fatura = $result['soma_valor'] - $result['soma_valor_pago'];

                        if($fatura == 0) {
                            $faturas[$cartao_credito['id']][$vencimento.'/'.$hoje->format('m/Y')] = 0.00;
                        } else {
                            $faturas[$cartao_credito['id']][$vencimento.'/'.$hoje->format('m/Y')] = $fatura;
                        }

                    }

                }

                $hoje->modify('+1 month');

            }

        }
        
        if(isset($faturas)){
        	return $faturas;
        } else {
        	return array();
        }  

    }

    public function getVencimentosProximos($id_fundo) {

        $sql = "SELECT * FROM fundos WHERE id = '$id_fundo'";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0) {

            $result = $sql->fetch();

            if($result['tipo'] == '3') {

                $vencimento = $result['vencimento'];

                $hoje = date('d-m-Y');
                $hoje = new DateTime($hoje);
                $hoje->modify('-1 month');
                    
                if($vencimento < $hoje->format('d')) {
                    $hoje->modify('+1 month');
                }

            }
            $vencimentos = array();

            for($i=1;$i<10;$i++) {

                $sql = "SELECT pagamento, SUM(valor) as soma_valor, SUM(valor_pago) as soma_valor_pago FROM lancamentos WHERE id_fundo = '$id_fundo' AND competencia = '".$hoje->format('m-Y')."' AND pagamento != '2'";
                $sql = $this->db->query($sql);

                if($sql->rowCount() > 0) {
                    $result = $sql->fetch();

                    $fatura = $result['soma_valor'] - $result['soma_valor_pago'];

                    if($fatura == 0) {
                        //goto pular;
                    } else {
                        $vencimentos[$vencimento.'/'.$hoje->format('m/Y')] = $fatura;
                    }
                    $hoje->modify('+1 month');

                } else {
                    //goto pular;
                }

            } 
            //pular:
            return $vencimentos;

        }           

    }

    public function getDivida($id_fundo) {

        $divida = 0.00;

        $sql = "SELECT * FROM fundos WHERE id = '$id_fundo'";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0) {

            $fundo = $sql->fetch();

            if($fundo['tipo'] == '3') {
                $vencimentos = array();

                $dia = new DateTime(date($fundo['vencimento'].'-m-Y'));

                for($dia->modify('-12 month');($dia->format('d') < date('d') && $dia->format('m') == date('m') && $dia->format('Y') == date('Y')) || ($dia->format('m') < date('m') && $dia->format('Y') == date('Y')) || $dia->format('Y') < date('Y');$dia->modify('+1 month')) {

                    $sql = "SELECT SUM(valor) as soma_valor, SUM(valor_pago) as soma_valor_pago FROM lancamentos WHERE id_fundo = '$id_fundo' AND competencia = '".$dia->format('m-Y')."' AND pagamento != '2'";
                    $sql = $this->db->query($sql);

                    if($sql->rowCount() > 0) {
                        $result = $sql->fetch();

                        $fatura = $result['soma_valor'] - $result['soma_valor_pago'];

                        if($fatura != 0) {
                            $divida += $fatura;
                        }

                    }

                }

            }

        }    

        return $divida;     

    }

    public function inserirFundo($nome,$tipo, $vencimento, $fechamento, $saldo, $user_tipo){

        if($user_tipo == '1') {
            if($tipo != '3') {
                if (!empty($nome) && !empty($tipo)) {
                    if(!empty($saldo)) {
                        $sql = "INSERT INTO fundos SET id_usuario = '".$_SESSION['twlg']."', nome = '$nome', tipo = '$tipo', saldo_atual = '$saldo'";
                        $this->db->query($sql);
                        return "<div class='text-success'>Cadastro realizado com sucesso!</div>";
                    } else {
                        $sql = "INSERT INTO fundos SET id_usuario = '".$_SESSION['twlg']."', nome = '$nome', tipo = '$tipo', saldo_atual = '0.00'";
                        $this->db->query($sql);
                        return "<div class='text-success'>Cadastro realizado com sucesso!</div>";                    
                    }
                } else {
                    return "<div class='text-danger'>Preencha nome e tipo!</div>";
                }
            } else {           
                if (!empty($nome) && !empty($tipo) && !empty($vencimento) && !empty($fechamento)) {
                    $sql = "INSERT INTO fundos SET id_usuario = '".$_SESSION['twlg']."', nome = '$nome', tipo = '$tipo', vencimento = '$vencimento', fechamento = '$fechamento', contas_a_pagar_total = '0.00'";
                    $this->db->query($sql);
                    return "<div class='text-success'>Cadastro realizado com sucesso!</div>";
                } else {
                    return "<div class='text-danger'>Preencha nome, tipo, vencimento e fechamento!</div>";
                }
            }
        } else {
            return "<div class='text-danger'>Você não pode cadastrar contas pois você não é um administrador!</div>";
        }

    }

    public function alterarSaldoFundo($id_fundo, $valor, $tipo_lancamento) {

        $sql = "SELECT * FROM fundos WHERE id = '$id_fundo'";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0){
            $result = $sql->fetch();
            
            if($result['tipo'] == 1) {
                $saldo_atual = $result['saldo_atual'];

                if($tipo_lancamento == 2){
                    $novo_saldo = $saldo_atual - $valor;

                    if ($novo_saldo >= 0) {
                        $sql = "UPDATE fundos SET saldo_atual = '$novo_saldo' WHERE id = '$id_fundo'";
                        $sql = $this->db->query($sql);

                        return "<div class='text-success'>Lançamento inserido com sucesso!</div>";
                    } else {
                        $sql = "UPDATE fundos SET saldo_atual = '0.00' WHERE id = '$id_fundo'";
                        $sql = $this->db->query($sql);

                        return "<div class='text-warning'>Lançamento inserido, mas saldo do dinheiro ficou negativo e foi corrigido para zero!</div>";
                    }
                } else {
                    $novo_saldo = $saldo_atual + $valor;

                    $sql = "UPDATE fundos SET saldo_atual = '$novo_saldo' WHERE id = '$id_fundo'";
                    $sql = $this->db->query($sql);

                    return "<div class='text-success'>Lançamento inserido com sucesso!</div>";   
                }
             
            } elseif($result['tipo'] == 2) {
                $saldo_atual = $result['saldo_atual'];

                if($tipo_lancamento == 2){
                    $novo_saldo = $saldo_atual - $valor;

                    $sql = "UPDATE fundos SET saldo_atual = '$novo_saldo' WHERE id = '$id_fundo'";
                    $sql = $this->db->query($sql);

                    return "<div class='text-success'>Lançamento inserido com sucesso!</div>";
                } else {
                    $novo_saldo = $saldo_atual + $valor;

                    $sql = "UPDATE fundos SET saldo_atual = '$novo_saldo' WHERE id = '$id_fundo'";
                    $sql = $this->db->query($sql);

                    return "<div class='text-success'>Lançamento inserido com sucesso!</div>";  
                }                

            } elseif($result['tipo'] == 3) {
                $contas_a_pagar_total = $result['contas_a_pagar_total'];

                if($tipo_lancamento == 2){

                    $novo_contas_a_pagar_total = $contas_a_pagar_total + $valor;

                    $sql = "UPDATE fundos SET contas_a_pagar_total = '$novo_contas_a_pagar_total' WHERE id = '$id_fundo'";
                    $sql = $this->db->query($sql);

                    return "<div class='text-success'>Lançamento inserido com sucesso!</div>";
                } else {

                    $novo_contas_a_pagar_total = $contas_a_pagar_total - $valor;

                    if($novo_contas_a_pagar_total > 0) {
                        $sql = "UPDATE fundos SET contas_a_pagar_total = '$novo_contas_a_pagar_total' WHERE id = '$id_fundo'";
                        $sql = $this->db->query($sql);

                        return "<div class='text-success'>Lançamento inserido com sucesso!</div>"; 
                    } else {
                        $sql = "UPDATE fundos SET contas_a_pagar_total = '0.00' WHERE id = '$id_fundo'";
                        $sql = $this->db->query($sql);

                        return "<div class='text-warning'>A conta de crédito ficou negativa e nós levamos para zero novamente!</div>"; 
                    }

                }                  

            } else {

                return "<div class='text-danger'>Esse fundo não existe em nosso banco de dados!</div>";  
            }

        }

    }

    public function pagarCartaoCredito($tipo_pagamento, $id_fundo, $vencimento, $id_fundo_pagamento, $valor_pagamento) {

        if($tipo_pagamento == '1') {
            $vencimento = new DateTime(str_replace('/', '-', $vencimento));
            $competencia = $vencimento->format('m').'-'.$vencimento->format('Y');

            $sql = "SELECT SUM(valor) as soma_valor, SUM(valor_pago) as soma_valor_pago FROM lancamentos WHERE id_fundo='$id_fundo' AND competencia='$competencia' AND (pagamento='0' OR pagamento='1')";
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0) {
                
                $result = $sql->fetch();

                $soma_valor = $result['soma_valor'];
                $soma_valor_pago = $result['soma_valor_pago'];

                $soma_valor_pago_agora = $soma_valor - $soma_valor_pago;

                if($soma_valor_pago_agora > 0) {

                    $nome_cartao_pago = $this->getNome('0',$id_fundo);

                    $sql = "INSERT INTO lancamentos SET id_usuario = '".$_SESSION['twlg']."', id_fundo= '$id_fundo_pagamento', descricao = 'Pagamento do ".$nome_cartao_pago."', id_conta = '7', valor = '".number_format($soma_valor_pago_agora,'2','.','')."', data = '".date('Y-m-d')."', cartao_credito = '0', previsao = '0', data_cadastro = NOW()";
                    $this->db->query($sql);

                    $id_lancamento_pagamento = $this->db->lastInsertId();

                    $this->alterarSaldoFundo($id_fundo_pagamento, $soma_valor_pago_agora, 2);

                    $sql = "SELECT * FROM lancamentos WHERE id_fundo='$id_fundo' AND competencia='$competencia' AND (pagamento='0' OR pagamento='1')";
                    $sql = $this->db->query($sql);

                    foreach ($sql->fetchAll() as $lancamento) {
                        if($lancamento['pagamento'] == 0) {
                            $sql = "INSERT INTO pagamento_cartao_credito SET id_lancamento_pago = '".$lancamento['id']."', id_lancamento_pagamento = '$id_lancamento_pagamento', valor = '".$lancamento['valor']."', data_realizacao = NOW()";
                            $this->db->query($sql);
                        } else {

                            $valor = $lancamento['valor'] - $lancamento['valor_pago'];

                            $sql = "INSERT INTO pagamento_cartao_credito SET id_lancamento_pago = '".$lancamento['id']."', id_lancamento_pagamento = '$id_lancamento_pagamento', valor = '$valor', data_realizacao = NOW()";
                            $this->db->query($sql);                            
                        }
                    }

                    $sql = "UPDATE lancamentos SET pagamento = '2', valor_pago = '0.00' WHERE id_fundo = '$id_fundo' AND competencia = '$competencia' AND pagamento != '2'";
                    $this->db->query($sql);

                    $this->alterarSaldoFundo($id_fundo, $soma_valor_pago_agora, 1);

                } else {
                    return "Você já pagou tudo dessa fatura";
                }

            }
    
            return 'Você pagou R$ '.number_format($soma_valor_pago_agora, 2, ',', '.').' com esse pagamento!'; 

        } elseif($tipo_pagamento == '0') {
            $sql = "SELECT * FROM lancamentos WHERE id_fundo='$id_fundo' AND pagamento != '2'";
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0) {

                $lancamentos = $sql->fetchAll();
                $valor_pagamento_inicial = $valor_pagamento;

                $nome_cartao_pago = $this->getNome('0',$id_fundo);

                $sql = "INSERT INTO lancamentos SET id_usuario = '".$_SESSION['twlg']."', id_fundo= '$id_fundo_pagamento', descricao = 'Pagamento do ".$nome_cartao_pago."', id_conta = '7', valor = '0.00', cartao_credito = '0', previsao = '0', data = '".date('Y-m-d')."', data_cadastro = NOW()";

                $this->db->query($sql);

                $id_lancamento_pagamento = $this->db->lastInsertId(); 

                foreach($lancamentos as $lancamento) {

                    if($lancamento['pagamento'] == 0) {
                        if($lancamento['valor'] <= $valor_pagamento) {
                            $valor_pagamento = $valor_pagamento - $lancamento['valor'];

                            $sql = "INSERT INTO pagamento_cartao_credito SET id_lancamento_pago = '".$lancamento['id']."', id_lancamento_pagamento = '$id_lancamento_pagamento', valor = '".$lancamento['valor']."', data_realizacao = NOW()";
                            $this->db->query($sql);

                            $sql = "UPDATE lancamentos SET pagamento = '2', valor_pago = '0.00' WHERE id = '".$lancamento['id']."'";
                            $this->db->query($sql);

                            $this->alterarSaldoFundo($id_fundo, $lancamento['valor'], 1);
                        } else {

                            $sql = "INSERT INTO pagamento_cartao_credito SET id_lancamento_pago = '".$lancamento['id']."', id_lancamento_pagamento = '$id_lancamento_pagamento', valor = '$valor_pagamento', data_realizacao = NOW()";
                            $this->db->query($sql);   

                            $sql = "UPDATE lancamentos SET pagamento = '1', valor_pago = '".$valor_pagamento."' WHERE id = '".$lancamento['id']."'";
                            $this->db->query($sql); 

                            $this->alterarSaldoFundo($id_fundo, $valor_pagamento, 1);

                            $valor_pagamento = 0.00;                           
                        }   
                    } elseif($lancamento['pagamento'] == 1) {
                        $valor_devedor = $lancamento['valor'] - $lancamento['valor_pago'];

                        if($valor_devedor <= $valor_pagamento) {

                            $sql = "INSERT INTO pagamento_cartao_credito SET id_lancamento_pago = '".$lancamento['id']."', id_lancamento_pagamento = '$id_lancamento_pagamento', valor = '$valor_devedor', data_realizacao = NOW()";
                            $this->db->query($sql);

                            $sql = "UPDATE lancamentos SET pagamento = '2', valor_pago = '0.00' WHERE id = '".$lancamento['id']."'";
                            $this->db->query($sql);

                            $this->alterarSaldoFundo($id_fundo, $valor_devedor, 1);
                            
                            $valor_pagamento = $valor_pagamento - $valor_devedor;                           
                        } else {
                            $novo_valor_pago = $lancamento['valor_pago'] + $valor_pagamento;

                            $sql = "INSERT INTO pagamento_cartao_credito SET id_lancamento_pago = '".$lancamento['id']."', id_lancamento_pagamento = '$id_lancamento_pagamento', valor = '$valor_pagamento', data_realizacao = NOW()";
                            $this->db->query($sql);
                          
                            $sql = "UPDATE lancamentos SET pagamento = '1', valor_pago = '".$novo_valor_pago."' WHERE id = '".$lancamento['id']."'";
                            $this->db->query($sql);

                            $this->alterarSaldoFundo($id_fundo, $valor_pagamento, 1); 

                            $valor_pagamento = 0.00;  
                        }
                    }

                    if($valor_pagamento == 0) {
                        $valor_real_pago = $valor_pagamento_inicial - $valor_pagamento;

                        $sql = "UPDATE lancamentos SET valor = '".number_format($valor_real_pago,'2','.','')."' WHERE id = '$id_lancamento_pagamento'";
                        $this->db->query($sql);  

                        $this->alterarSaldoFundo($id_fundo_pagamento, $valor_real_pago, 2); 

                        return "Você pagou R$ ".number_format($valor_real_pago,2,',','.')." até a fatura ".$lancamento['data'];
                        goto pagou_tudo;
                    }
                }

                $valor_real_pago = $valor_pagamento_inicial - $valor_pagamento;

                $sql = "UPDATE lancamentos SET valor = '".number_format($valor_real_pago,'2','.','')."' WHERE id = '$id_lancamento_pagamento'";
                $this->db->query($sql); 

                $this->alterarSaldoFundo($id_fundo_pagamento, $valor_real_pago, 2);   

                return "Você pagou R$ ".number_format($valor_real_pago,2,',','.')." e quitou todas as dívidas do ".$nome_cartao_pago;                

                pagou_tudo:

            } else {
                return "Você não tem faturas nesse cartão, todas já foram pagas!";
            }

        } else {
            return "Seu lançamento não foi realizado, tipo de pagamento inválido!";
        }

    }
    
}
?>