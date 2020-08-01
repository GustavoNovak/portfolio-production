<?php
$competencias_grafico = array_keys($previsao);
$valores = array_values($previsao);

foreach ($competencias_grafico as $key => $value) {
  $competencias_grafico[$key] = "'".$value."'";
}

$competencias_grafico = '['.implode(', ', $competencias_grafico).']';
$valores = '['.implode(', ', $valores).']';

$competencias_grafico_com_lancamentos_previsao = array_keys($previsao_com_lancamentos_previsao);
$valores_com_lancamentos_previsao = array_values($previsao_com_lancamentos_previsao);

foreach ($competencias_grafico_com_lancamentos_previsao as $key => $value) {
  $competencias_grafico_com_lancamentos_previsao[$key] = "'".$value."'";
}

$competencias_grafico_com_lancamentos_previsao = '['.implode(', ', $competencias_grafico_com_lancamentos_previsao).']';
$valores_com_lancamentos_previsao = '['.implode(', ', $valores_com_lancamentos_previsao).']';

?>
<h4>Será considerado 12% juros a.m para saldo negativo</h4>
<h1>Previsão de valor presente real: </h1>
<br/>

<div class="chart-container">
    <canvas id="chart"></canvas>
</div>

<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/chart.min.js"></script>
<script type="text/javascript">
      window.onload = function(){
        
        var contexto = document.getElementById("chart").getContext("2d");

        var data = {
          labels: <?php echo $competencias_grafico; ?>,
          datasets: [{
            label: "Valores da conta",
            backgroundColor: "rgba(0,0,0,0)",
            borderColor: "rgba(0,0,0,1)",
            borderWidth: 2,
            hoverBackgroundColor: "rgba(0,0,0,0)",
            hoverBorderColor: "rgba(0,0,0,1)",
            lineTension: 0,
            data: <?php echo $valores; ?>,
            fill:false,
          },
          {
            label: "Valores da conta com lançamentos de provisão",
            backgroundColor: "rgba(0,0,0,0.2)",
            borderColor: "rgba(150,0,0,1)",
            borderWidth: 2,
            hoverBackgroundColor: "rgba(0,0,0,0)",
            hoverBorderColor: "rgba(0,0,0,1)",
            lineTension: 0,
            data: <?php echo $valores_com_lancamentos_previsao; ?>,
            fill:false,
          }]
        };

        var options = {
          responsive:true,
          maintainAspectRatio: false,
          scales: {
            yAxes: [{
              stacked: false,
              gridLines: {
                display: true,
                color: "rgba(0,0,0,0.5)"
              }
            }],
            xAxes: [{
              gridLines: {
                display: true
              }
            }]
          }
        };

        new Chart(contexto, {
          type: 'line',
          data: data,
          options: options
        });

      }
</script>