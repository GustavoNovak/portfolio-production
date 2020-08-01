<h4>Será considerado 12% juros a.m para saldo negativo</h4>
<br/>
<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/chart.min.js"></script>
<script type="text/javascript">
      window.onload = function(){
</script>
<?php $cn = new Cenarios(); ?>
<?php if(count($cenarios) == 0){
	echo "<h1>Você não tem nenhum cenário cadastrado!";
} ?>
<?php foreach ($previsao_cenario as $key_previsao => $previsao): ?>
  <?php echo "<h1>Previsão cenário ".$cn->getNome($key_previsao); ?>
<?php
$competencias_grafico = array_keys($previsao);
$valores = array_values($previsao);

foreach ($competencias_grafico as $key => $value) {
  $competencias_grafico[$key] = "'".$value."'";
}

$competencias_grafico = '['.implode(', ', $competencias_grafico).']';
$valores = '['.implode(', ', $valores).']';
?>
<div class="chart-container">
    <canvas id="chart<?php echo $key_previsao; ?>"></canvas>
</div>
<script type="text/javascript">

        var contexto = document.getElementById("chart<?php echo $key_previsao; ?>").getContext("2d");

        var data = {
          labels: <?php echo $competencias_grafico; ?>,
          datasets: [{
            label: "Valores",
            backgroundColor: "rgba(0,0,0,0.2)",
            borderColor: "rgba(0,0,0,1)",
            borderWidth: 2,
            hoverBackgroundColor: "rgba(0,0,0,0)",
            hoverBorderColor: "rgba(0,0,0,1)",
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
                color: "rgba(255,99,132,0.2)"
              }
            }],
            xAxes: [{
              gridLines: {
                display: false
              }
            }]
          }
        };

        new Chart(contexto, {
          type: 'line',
          data: data,
          options: options
        });
</script>
      
<?php endforeach; ?>
<script type="text/javascript">
  }
</script>