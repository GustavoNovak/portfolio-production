<?php
if(count($previsao) > 0){
$competencias_grafico = array_keys($previsao);
$valores = array_values($previsao);

foreach ($competencias_grafico as $key => $value) {
  $competencias_grafico[$key] = "'".$value."'";
}

$competencias_grafico = '['.implode(', ', $competencias_grafico).']';
$valores = '['.implode(', ', $valores).']';
}
?>

<?php 
$f = new Fundos();

?>
<div class="row tela_inteira" style="margin-top:6px;">
	<div style="width:100%;padding-left:5px;padding-right:5px">
		<div class="linha_superior" style="width:100%"></div>
	</div>
	<div class="col-sm-5">
		<div class="titulo_bloco_menor content-row-center-center">
			LANÇAR:
		</div>
		<div class="recebimento_pagamento_superior">
			<div class="recebimento_pagamento content-row-space-around-center">
				<a class="btn_recebimento content-row-center-center" href="<?php echo BASE_URL; ?>lancamento/cadastrar_recebimento">RECEBIMENTO</a>
				<a class="btn_pagamento content-row-center-center" href="<?php echo BASE_URL; ?>lancamento/cadastrar_pagamento">PAGAMENTO</a>
			</div>
		</div>
		<div class="linha_superior col-sm-12"></div>
		<div class="titulo_bloco_menor content-row-center-center">
			CONTAS:
		</div>
		<div class="recebimento_pagamento_superior content-flex">
			<div class="contas_pagar_receber" style="padding-right:2.5px;">
				<div class="subtitulo_bloco_menor content-row-center-center">
					A RECEBER
				</div>
				<div class="subsubtitulo_bloco_menor content-row-center-center">
					<?php echo 'R$ '.number_format($contas_a_receber,2,',','.'); ?>
				</div>
			</div>
			<div class="contas_pagar_receber" style="padding-left:2.5px;">
				<div class="subtitulo_bloco_menor content-row-center-center">
					A PAGAR
				</div>
				<div class="subsubtitulo_bloco_menor content-row-center-center">
					<?php echo 'R$ '.number_format($contas_a_pagar,2,',','.'); ?>
				</div>
			</div>
		</div>

		<?php if(count($fundos) > 0): ?>
		<div class="linha_superior col-sm-12"></div>
		<div class="titulo_bloco_menor content-row-center-center">
			SALDO DAS CONTAS:
		</div>
		<div class="recebimento_pagamento_superior">
			<div class="contas_bancarias">
				<?php foreach($fundos as $fundo): ?>
				<?php if($fundo['tipo'] != '3'): ?>
					<?php if($fundo['saldo_atual'] >= 0): ?>
					<div class="conta_bancaria content-row-center-left">
						<div class="metade_conta_bancaria" style="padding-right:2.5px">
							<div class="nome_conta_positiva content-row-center-center"><?php echo $fundo['nome']; ?></div>
						</div>
						<div class="metade_conta_bancaria" style="padding-left:2.5px">
							<div class="saldo_conta_positiva content-row-center-center"><?php echo '+R$ '.number_format($fundo['saldo_atual'],2,',','.'); ?></div>
						</div>
					</div>
					<?php else: ?>
					<div class="conta_bancaria content-row-center-left">
						<div class="metade_conta_bancaria" style="padding-right:2.5px">
							<div class="nome_conta_negativa content-row-center-center"><?php echo $fundo['nome']; ?></div>
						</div>
						<div class="metade_conta_bancaria" style="padding-left:2.5px">
							<div class="saldo_conta_negativa content-row-center-center"><?php echo '-R$ '.number_format(-$fundo['saldo_atual'],2,',','.'); ?></div>
						</div>
					</div>
					<?php endif; ?>
				<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endif; ?>

		<?php if(count($ultimas_faturas) > 0): ?>
		<div class="linha_superior col-sm-12"></div>
		<div class="titulo_bloco_menor content-row-center-center">
			PRÓXIMAS FATURAS:
		</div>
		<div class="recebimento_pagamento_superior">
			<div class="contas_bancarias">
					<?php $cor = 1; ?>
					<?php foreach($ultimas_faturas as $key => $ultima_fatura): ?>
						<?php
						foreach($ultima_fatura as $key3 => $valor_reais) {
							$dia = $key3;
							$valor = $valor_reais;
							goto pular2;
						}
						pular2:
						?>
						<?php if($cor == 1): ?>
							<div class="conta_bancaria content-row-center-left">
								<div class="metade_conta_bancaria" style="padding-right:2.5px">
									<div class="nome_conta_cinza_claro content-row-center-center"><?php echo $f->getNome('',$key); ?></div>
								</div>
								<div class="metade_conta_bancaria" style="padding-left:2.5px">
									<div class="saldo_conta_cinza_claro content-row-center-center"><?php echo $dia.' - R$ '.number_format($valor,2,',','.'); ?></div>
								</div>
							</div>
							<?php $cor = 0; ?>
						<?php else: ?>
							<div class="conta_bancaria content-row-center-left">
								<div class="metade_conta_bancaria" style="padding-right:2.5px">
									<div class="nome_conta_cinza_escuro content-row-center-center"><?php echo $f->getNome('',$key); ?></div>
								</div>
								<div class="metade_conta_bancaria" style="padding-left:2.5px">
									<div class="saldo_conta_cinza_escuro content-row-center-center"><?php echo $dia.' - R$ '.number_format($valor,2,',','.'); ?></div>
								</div>
							</div>
							<?php $cor = 1; ?>
						<?php endif; ?>
					<?php endforeach; ?>
			</div>
		</div>
		<?php endif; ?>
	</div>
	<div class="col-sm-7 grafico" style="height:100%">
		<div class="titulo_grafico content-row-center-space-between">
			<img src="<?php echo BASE_URL; ?>assets/images/icone_previsao.png" />
			<div class="titulo_previsao">PREVISÃO SALDO TOTAL</div>
		</div>
			<div class="chart-container" style="padding-top:60px;padding-bottom:60px;background-color:#e2e2e2;padding-left:60px;padding-right:60px;">
    			<canvas id="chart"></canvas>
			</div>
	</div>
</div>

<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/chart.min.js"></script>
<?php
if(count($previsao) > 0){
	?>
<script type="text/javascript">
      window.onload = function(){
        
      	altura_total = $('.tela_inteira').height();
      	altura = altura_total - 135;
      	altura = altura + 'px';
      	$('.grafico').css('height', altura);

        var contexto = document.getElementById("chart").getContext("2d");

        var data = {
          labels: <?php echo $competencias_grafico; ?>,
          datasets: [{
            backgroundColor: "rgba(150,166,181,1)",
            borderColor: "rgba(255,255,255,1)",
            borderWidth: 0,
            hoverBackgroundColor: "rgba(255,255,255,1)",
            hoverBorderColor: "rgba(255,255,255,1)",
            lineTension: 0,
            data: <?php echo $valores; ?>,
            fill:true,
          }]
        };

        var options = {
          maintainAspectRatio: false,
          scales: {
            yAxes: [{
              stacked: true,
              gridLines: {
                display: true,
                color: "rgba(71,68,69,0.2)"
              },
              ticks: {
                    fontColor: "#2f2f34",
                    fontFamily: "gothic",
              }
            }],
            xAxes: [{
              gridLines: {
                display: true,
                color: "rgba(71,68,69,0.2)"
              },
              ticks: {
                    fontColor: "#2f2f34",
                    fontFamily: "gothic",
              }
            }]
          	},
          	legend: {
		        display: false
		    }
        };

        new Chart(contexto, {
          type: 'line',
          data: data,
          options: options
        });

      }
</script>
	<?php
}
?>
