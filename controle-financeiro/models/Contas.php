<?php
class Contas extends model {

    public function getTipo($id_conta) {

        $sql = "SELECT * FROM contas WHERE id = '$id_conta'";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0) {

            return $sql->fetch()['tipo']; 

        } else {
            return '0';
        }

    }

    public function getConta($id_conta) {

        $sql = "SELECT * FROM contas WHERE id = '$id_conta'";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0) {
            return $sql->fetch();
        } else {
            return array();
        }

    }

    public function getNome($id_conta) {

        $sql = "SELECT * FROM contas WHERE id = '$id_conta'";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0) {
            return $sql->fetch()['nome'];
        }
    }

    public function getContas($user_tipo) {
	
	$resultado = array();
	
        if($user_tipo == '1') {
            $sql = "SELECT * FROM contas WHERE id_usuario = '".$_SESSION['twlg']."' OR id_usuario = '0'";
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0) {
                $resultado = $sql->fetchAll();
            }
        } else {
            $sql = "SELECT id_independente FROM usuario_dependente WHERE id_dependente = '".$_SESSION['twlg']."'";
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0) {
                $id_usuario = $sql->fetch()['id_independente'];

                $sql = "SELECT * FROM contas WHERE id_usuario = '".$id_usuario."' OR id_usuario = '0'";
                $sql = $this->db->query($sql);

                if($sql->rowCount() > 0) {
                    $resultado = $sql->fetchAll();
                }               
            }           
        }
        
        return $resultado;
        
    }

    public function excluirConta($id_conta) {

        $sql = "SELECT * FROM lancamentos WHERE id_conta = '$id_conta'";
        $sql = $this->db->query($sql);

        if($sql->rowCount() == 0) {
            $sql = "DELETE FROM contas WHERE id = '$id_conta'";
            $this->db->query($sql);

            return "<div class='text-success'>Conta excluída com sucesso!</div>";
        } else {
            return "<div class='text-danger'>Não é possível excluir essa conta pois ele já tem lançamentos em seu nome!</div>";
        }

    }

    public function editarConta($id_conta, $nome, $periodicidade, $mes, $orcamento_fixo) {

        $sql = "SELECT * FROM contas WHERE id = '$id_conta'";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0) {
            if($periodicidade == '1') {
                $sql = "UPDATE contas SET nome = '$nome', periodicidade = '$periodicidade', mes = NULL, orcamento_fixo = '$orcamento_fixo' WHERE id = '$id_conta'";
                $this->db->query($sql);

                return "<div class='text-success'>Conta alterada com sucesso!</div>";
            } else {
                $sql = "UPDATE contas SET nome = '$nome', periodicidade = '$periodicidade', mes = '$mes', orcamento_fixo = '$orcamento_fixo' WHERE id = '$id_conta'";
                $this->db->query($sql);

                return "<div class='text-success'>Conta alterada com sucesso!</div>";                
            }
        } else {
            return "<div class='text-success'>Essa conta não existe!</div>";
        }

    }

    public function getCompetencias($id_conta, $qtd) {
        $competencias = array();

        $sql = "SELECT * FROM contas WHERE id = '$id_conta'";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0) {

            $result = $sql->fetch();
            $periodicidade = $result['periodicidade'];

            if($periodicidade != '1') {
                $mes_inicio = $result['mes'];
            } else {
                $mes_inicio = 1;
            }

            $comp_hoje = new Datetime('01-'.date('m-Y'));

            for($comp_inicio = new Datetime('01-'.$mes_inicio.'-2017');strtotime($comp_inicio->format('d-m-Y'))<strtotime($comp_hoje->format('d-m-Y'));$comp_inicio->modify('+'.$periodicidade.' month')) {

            } 

            for($i=1;$i<$qtd;$comp_inicio->modify('+'.$periodicidade.' month')) {

                $competencias[] = $comp_inicio->format('m-Y');

                $i++;
            }

            return $competencias;

        }

    }

    public function getPassedCompetencias($id_conta, $qtd) {
        $competencias = array();

        $sql = "SELECT * FROM contas WHERE id = '$id_conta'";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0) {

            $result = $sql->fetch();
            $periodicidade = $result['periodicidade'];

            if($periodicidade != '1') {
                $mes_inicio = $result['mes'];
            } else {
                $mes_inicio = 1;
            }

            $comp_hoje = new Datetime('01-'.date('m-Y'));

            $comp_inicio = new Datetime('01-'.$mes_inicio.'-2017');
            while(strtotime($comp_inicio->format('d-m-Y'))<strtotime($comp_hoje->format('d-m-Y'))) {
                $comp_inicio->modify('+'.$periodicidade.' month');
            }
            if($periodicidade != 1 && ($comp_inicio->format('m') != $comp_hoje->format('m'))){
                $comp_inicio->modify('-'.$periodicidade.' month');
            }

            for($i=1;$i<$qtd;$comp_inicio->modify('-'.$periodicidade.' month')) {

                $competencias[] = $comp_inicio->format('m-Y');

                $i++;
            }

            return $competencias;

        }

    }

    public function getPrevisao($user_tipo) {

        $juros = 0.00;

        $f = new Fundos();
        $l = new lancamentos();
        $c = new Contas();

        $contas = $this->getContas($user_tipo);

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
                    
                    $valor_conta = $c->getValorConta($conta['id'], $competencia->format('m-Y'));
                    $valor_conta_a_pagar = 0.00;

                    $competencia_ultimo_dia = $competencia->format('d-m-Y');
                    $competencia_ultimo_dia = new Datetime($competencia_ultimo_dia);
                    $competencia_ultimo_dia = $competencia_ultimo_dia->modify('+1 month')->modify('-1 day');

                    $lancamentos_conta = $l->getLancamentosFiltrados($competencia->format('Y-m-d'), $competencia_ultimo_dia->format('Y-m-d'), '', $conta['id'], '1', '');
                    $lancamentos_conta_previsao_1 = $l->getLancamentosFiltrados($competencia->format('Y-m-d'), $competencia_ultimo_dia->format('Y-m-d'), '', $conta['id'], '2', '');

                    foreach($lancamentos_conta_previsao_1 as $value) {
                        $lancamentos_conta[] = $value;
                    }

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

                    if($conta['tipo'] == '2') {
                        if(($valor_conta - $valor_conta_a_pagar) >= 0) {
                            $valor_competencia -= $valor_conta;
                        } else {
                            $valor_competencia -= $valor_conta_a_pagar;
                        }
                    } else {
                        if($valor_conta > 0) {
                            $valor_competencia += $valor_conta;
                        } else {
                            $valor_competencia = 0;
                        }
                    }

                }

                $previsao[$competencia->format('m-Y')] = number_format($saldo + $valor_competencia,2,'.','');
                $saldo = $previsao[$competencia->format('m-Y')];

            } else {
                foreach ($contas as $conta) {
                    
                    $valor_conta = $c->getValorConta($conta['id'], $competencia->format('m-Y'));
                    $valor_conta_a_pagar = 0.00;

                    $competencia_ultimo_dia = $competencia->format('d-m-Y');
                    $competencia_ultimo_dia = new Datetime($competencia_ultimo_dia);
                    $competencia_ultimo_dia = $competencia_ultimo_dia->modify('+1 month')->modify('-1 day');

                    $lancamentos_conta = $l->getLancamentosFiltrados($competencia->format('Y-m-d'), $competencia_ultimo_dia->format('Y-m-d'), '', $conta['id'], '1', '');
                    $lancamentos_conta_previsao_1 = $l->getLancamentosFiltrados($competencia->format('Y-m-d'), $competencia_ultimo_dia->format('Y-m-d'), '', $conta['id'], '2', '');

                    foreach($lancamentos_conta_previsao_1 as $value) {
                        $lancamentos_conta[] = $value;
                    }

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

                    if($conta['tipo'] == 2) {
                        if(($valor_conta - $valor_conta_a_pagar) >= 0) {
                            $valor_competencia -= $valor_conta;
                        } else {
                            $valor_competencia -= $valor_conta_a_pagar;
                        }
                    } else {
                        if($valor_conta > 0) {
                            $valor_competencia += $valor_conta;
                        }
                    }

                }

                if($saldo >= 0) {
                    $previsao[$competencia->format('m-Y')] = number_format($saldo + $valor_competencia,2,'.','');
                    $saldo = $previsao[$competencia->format('m-Y')];
                } else {
                    $previsao[$competencia->format('m-Y')] = number_format((1 + $juros)*$saldo + $valor_competencia,2,'.','');
                    $saldo = $previsao[$competencia->format('m-Y')];                    
                }

            }

            $competencia->modify('+1 month');
        }    

        return $previsao;

    }

    public function getPrevisaoComLancamentosPrevisao($user_tipo) {

        $juros = 0.00;

        $f = new Fundos();
        $l = new lancamentos();
        $c = new Contas();

        $contas = $this->getContas($user_tipo);

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
                    
                    $valor_conta = $c->getValorConta($conta['id'], $competencia->format('m-Y'));
                    $valor_conta_a_pagar = 0.00;

                    $competencia_ultimo_dia = $competencia->format('d-m-Y');
                    $competencia_ultimo_dia = new Datetime($competencia_ultimo_dia);
                    $competencia_ultimo_dia = $competencia_ultimo_dia->modify('+1 month')->modify('-1 day');

                    $lancamentos_conta = $l->getLancamentosFiltrados($competencia->format('Y-m-d'), $competencia_ultimo_dia->format('Y-m-d'), '', $conta['id'], '', '');

                    foreach ($lancamentos_conta as $lancamento_conta) {
                        
                        if($conta['id'] != '7') {
                            if($conta['id'] != '6') {
                                if($conta['tipo'] == '1') {
                                    if($lancamento_conta['previsao'] == '1') {
                                        if( ($valor_conta - $lancamento_conta['valor']) <= 0 ) {
                                            $valor_conta = -($valor_conta - $lancamento_conta['valor']);
                                        } else {
                                            $valor_conta -= $lancamento_conta['valor'];
                                        }
                                    } else {
                                        $valor_conta -= $lancamento_conta['valor'];
                                    }
                                } else {
                                    if($lancamento_conta['previsao'] == '1') {
                                        $valor_conta_a_pagar = $valor_conta_a_pagar + $lancamento_conta['valor'];
                                    } else {
                                        if ($lancamento_conta['cartao_credito'] == '1') {
                                            if($lancamento_conta['pagamento'] == '2') {
                                                $valor_conta -= $lancamento_conta['valor'];
                                            } else {
                                                $valor_conta_a_pagar = $valor_conta_a_pagar + $lancamento_conta['valor'] - $lancamento_conta['valor_pago'];
                                                $valor_conta -= $lancamento_conta['valor_pago'];
                                            }
                                        }

                                    }
                                }
                            } else {
                                $valor_conta += $valor_conta;
                            }
                        }
                        
                    }

                    if($conta['tipo'] == '2') {
                        if(($valor_conta - $valor_conta_a_pagar) >= 0) {
                            $valor_competencia -= $valor_conta;
                        } else {
                            $valor_competencia -= $valor_conta_a_pagar;
                        }
                    } else {
                        if($valor_conta > 0) {
                            $valor_competencia += $valor_conta;
                        }
                    }

                }

                $previsao[$competencia->format('m-Y')] = number_format($saldo + $valor_competencia,2,'.','');
                $saldo = $previsao[$competencia->format('m-Y')];

            } else {
                foreach ($contas as $conta) {
                    
                    $valor_conta = $c->getValorConta($conta['id'], $competencia->format('m-Y'));
                    $valor_conta_a_pagar = 0.00;
                    $valor_conta_a_receber = 0.00;

                    $competencia_ultimo_dia = $competencia->format('d-m-Y');
                    $competencia_ultimo_dia = new Datetime($competencia_ultimo_dia);
                    $competencia_ultimo_dia = $competencia_ultimo_dia->modify('+1 month')->modify('-1 day');

                    $lancamentos_conta = $l->getLancamentosFiltrados($competencia->format('Y-m-d'), $competencia_ultimo_dia->format('Y-m-d'), '', $conta['id'], '', '');

                    foreach ($lancamentos_conta as $lancamento_conta) {
                        
                        if($conta['id'] != '7') {
                            if($conta['id'] != '6') {
                                if($conta['tipo'] == '1') {
                                    if($lancamento_conta['previsao'] == '1') {
                                        $valor_conta_a_receber = $valor_conta_a_receber + $lancamento_conta['valor'];
                                    } else {
                                        if( ($valor_conta - $lancamento_conta['valor']) > 0) {
                                            $valor_conta -= $lancamento_conta['valor'];
                                        } else {
                                            $valor_conta = 0.00;
                                        }
                                    } 
                                } else {
                                    if($lancamento_conta['previsao'] == '1') {
                                        $valor_conta_a_pagar = $valor_conta_a_pagar + $lancamento_conta['valor'];
                                    } else {
                                        if ($lancamento_conta['cartao_credito'] == '1') {
                                            if($lancamento_conta['pagamento'] == '2') {
                                                $valor_conta -= $lancamento_conta['valor'];
                                            } else {
                                                $valor_conta_a_pagar = $valor_conta_a_pagar + $lancamento_conta['valor'] - $lancamento_conta['valor_pago'];
                                                $valor_conta -= $lancamento_conta['valor_pago'];
                                            }
                                        }

                                    }
                                }
                            } else {
                                $valor_conta += $valor_conta;
                            }
                        }
                        
                    }

                    if($conta['tipo'] == 2) {
                        if(($valor_conta - $valor_conta_a_pagar) >= 0) {
                            $valor_competencia -= $valor_conta;
                        } else {
                            $valor_competencia -= $valor_conta_a_pagar;
                        }
                    } else {
                        if(($valor_conta - $valor_conta_a_receber) >= 0) {
                            $valor_competencia += $valor_conta;
                        } else {
                            $valor_competencia += $valor_conta_a_receber;
                        }
                    }

                }

                if($saldo >= 0) {
                    $previsao[$competencia->format('m-Y')] = number_format($saldo + $valor_competencia,2,'.','');
                    $saldo = $previsao[$competencia->format('m-Y')];
                } else {
                    $previsao[$competencia->format('m-Y')] = number_format((1 + $juros)*$saldo + $valor_competencia,2,'.','');
                    $saldo = $previsao[$competencia->format('m-Y')];                    
                }

            }

            $competencia->modify('+1 month');
        }    

        return $previsao;

    }

    public function inserirConta($nome,$tipo, $orcamento_fixo, $periodicidade, $mes, $user_tipo){

        if($user_tipo == '1') {
            if (!empty($nome) && !empty($tipo) && !empty($orcamento_fixo) && !empty($periodicidade)) {
                if($periodicidade > 1) {
                    $sql = "INSERT INTO contas SET id_usuario = '".$_SESSION['twlg']."', nome = '$nome', tipo = '$tipo', orcamento_fixo = '$orcamento_fixo', periodicidade = '$periodicidade', mes = '$mes'";
                    $this->db->query($sql);
                } else {
                    $sql = "INSERT INTO contas SET id_usuario = '".$_SESSION['twlg']."', nome = '$nome', tipo = '$tipo', orcamento_fixo = '$orcamento_fixo', periodicidade = '$periodicidade'";
                    $this->db->query($sql);
                }
                return "<div class='text-success'>Cadastro realizado com sucesso!</div>";
            } else {
                return "<div class='text-danger'>Você não inseriu todos os dados!</div>";
            }

        } else {
            return "<div class='text-danger'>Você não pode cadastrar contas pois você não é um administrador!</div>";
        }

    }

    public function inserirRegistroConta($conta, $competencia, $valor) {

        $sql = "SELECT * FROM registro_valores_contas WHERE id_conta = '$conta' AND competencia = '$competencia'";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0){
            if(!empty($valor)) {
                $sql = "UPDATE registro_valores_contas SET valor = '$valor' WHERE id_conta = '$conta' AND competencia = '$competencia'";
                $this->db->query($sql);

                return "<div class='text-success'>Registro de valor alterado com sucesso!</div>";
            } else {
                return "<div class='text-danger'>Você inserir um valor!</div>";
            }
        } else {
            $sql = "INSERT INTO registro_valores_contas SET id_conta = '$conta', valor = '$valor', competencia = '$competencia'";
            $this->db->query($sql);

            return "<div class='text-success'>Registro de valor inserido com sucesso!</div>";
        }

    }

    public function excluirValorConta($id_conta, $competencia) {

        $sql = "SELECT * FROM registro_valores_contas WHERE id_conta = '$id_conta' AND competencia = '$competencia'";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0) {
            $sql = "DELETE FROM registro_valores_contas WHERE id_conta = '$id_conta' AND competencia = '$competencia'";
            $this->db->query($sql);

            return "<div class='text-danger'>Registro excluído com sucesso!</div>";
        } else {
            return "<div class='text-danger'>Esse registro não existe!</div>";
        }

    }

    public function getRegistroValorConta($id_conta, $competencia) {
        $json = array();

        $sql = "SELECT * FROM registro_valores_contas WHERE id_conta = '$id_conta' AND competencia = '$competencia'";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0) {
            $json['tem_registro'] = '1';
            $json['aviso'] = "<div class='text-success'>Registro encontrado!</div>";
            $json['valor'] = $sql->fetch()['valor'];
        } else {
            $json['tem_registro'] = '0';
            $json['aviso'] = "<div class='text-danger'>Nessa competencia não existe registro ainda</div>";
            $json['valor'] = '';
        }     

        return $json;

    }

    public function getValorConta($id_conta, $competencia) {

        $sql = "SELECT * FROM registro_valores_contas WHERE id_conta = '$id_conta' AND competencia = '$competencia'";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0) {
            return $sql->fetch()['valor'];
        } else {
            $sql = "SELECT * FROM contas WHERE id = '$id_conta'";
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0) {

                $conta = $sql->fetch();

                if($conta['periodicidade'] == '1') {
                    return $conta['orcamento_fixo'];
                } else {
                    $competencia = new Datetime('01-'.$competencia);
                    $var_teste = ($competencia->format('m') - $conta['mes'])/$conta['periodicidade'];

                    if(is_int($var_teste) || $var_teste == 0) {
                        return $conta['orcamento_fixo'];
                    } else {
                        return '0.00';
                    }
                }
            } else {
                return '0.00';
            }
        }

    }

    public function getValorReaisConta($id_conta, $competencia) {
        $conta = $this->getConta($id_conta);

        $aux = explode('-', $competencia);
        $date = new \Datetime($aux[1].'-'.$aux[0].'-01');
        $firstDay = new \Datetime($date->format('Y-m').'-01');
        if($conta['periodicidade'] > 1){
            $firstDay->modify('-'.($conta['periodicidade'] - 1).' month');
        }
        $firstDay = $firstDay->format('Y-m-d');
        $date->modify('+1 month');
        $date->modify('-1 day');
        $lastDay = $date->format('Y-m-d');

        $sql = "SELECT 
                    SUM(valor) as valor_total
                FROM lancamentos
                WHERE 
                    id_conta = $id_conta 
                        AND 
                    data >= '$firstDay' AND data <= '$lastDay'
                        AND 
                    orcamento = 0 
                        AND 
                    previsao = 0";
        $sql = $this->db->query($sql);
        $response = $sql->fetch()['valor_total'];
        if(!empty($response)) {
            return (float) $response;
        } else {
            return (float) 0.00;
        }
    }

    public function getValoresReais($id_conta, $qtd_meses) {
        $valores = array();

        $competencias = $this->getPassedCompetencias($id_conta,$qtd_meses);
        $comp_hoje = new Datetime('01-'.date('m-Y'));
        $sql = "SELECT * FROM contas WHERE id = '$id_conta'";
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0) {
            $conta = $sql->fetch();
            $aux = 0;
            for($i=1;$i<$qtd_meses;$comp_hoje->modify('-1 month')) {

                if(in_array($comp_hoje->format('m-Y'),$competencias)) {
                    $valores[$comp_hoje->format('m-Y')] = $this->getValorReaisConta($id_conta, $comp_hoje->format('m-Y')); 
                    $i++;
                }
                $aux++;

                if($aux > 100){
                    echo __METHOD__ . "Loop infinito";die;
                }
            }

        }

        return $valores;
    }

    public function getValores($id_conta, $qtd_meses) {
        $valores = array();

        $competencias = $this->getCompetencias($id_conta,$qtd_meses);
        $comp_hoje = new Datetime('01-'.date('m-Y'));

        $sql = "SELECT * FROM contas WHERE id = '$id_conta'";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0) {
            $conta = $sql->fetch();
            for($i=1;$i<$qtd_meses;$comp_hoje->modify('+1 month')) {

                if(in_array($comp_hoje->format('m-Y'),$competencias)) {
                    $valores[$comp_hoje->format('m-Y')] = $this->getValorConta($id_conta, $comp_hoje->format('m-Y')); 
                    $i++;
                }

            }

        }

        return $valores;
    }

    public function getValoresPassados($id_conta, $qtd_meses) {
        $valores = array();

        $competencias = $this->getPassedCompetencias($id_conta,$qtd_meses);
        $comp_hoje = new Datetime('01-'.date('m-Y'));

        $sql = "SELECT * FROM contas WHERE id = '$id_conta'";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0) {
            $conta = $sql->fetch();
            for($i=1;$i<$qtd_meses;$comp_hoje->modify('-1 month')) {

                if(in_array($comp_hoje->format('m-Y'),$competencias)) {
                    $valores[$comp_hoje->format('m-Y')] = $this->getValorConta($id_conta, $comp_hoje->format('m-Y')); 
                    $i++;
                }

            }

        }

        return $valores;
    }
    
}
?>