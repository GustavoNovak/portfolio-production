<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/chart.min.js"></script>
<script type="text/javascript">
      window.onload = function(){
</script>
<?php foreach ($nome_contas as $id_conta => $nome): ?>

<?php endforeach; ?>
<?php $c = new Contas(); ?>
<?php if(count($contas) == 0){
  echo "<h1>Você não tem nenhuma conta cadastrada!";
} ?>
<?php foreach ($nome_contas as $id_conta => $nome): ?>
  <?php echo "<h1>Conta: ".$nome."</h1>"; ?>
<?php
$competencias_grafico = array_keys($valores_gastos_conta[$id_conta]);
$valores_reais = array_values($valores_gastos_conta[$id_conta]);
$valores_previsao = array_values($valores_previsao_conta[$id_conta]);

foreach ($competencias_grafico as $key => $value) {
  $competencias_grafico[$key] = "'".$value."'";
}

$competencias_grafico = '['.implode(', ', $competencias_grafico).']';
$valores_reais = '['.implode(', ', $valores_reais).']';
$valores_previsao = '['.implode(', ', $valores_previsao).']';
?>
<div class="chart-container">
    <canvas id="chart<?php echo $id_conta; ?>"></canvas>
</div>
<script type="text/javascript">

        var contexto = document.getElementById("chart<?php echo $id_conta; ?>").getContext("2d");

        var data = {
          labels: <?php echo $competencias_grafico; ?>,
          datasets: [{
            label: "Real",
            backgroundColor: "rgba(0,0,0,0.2)",
            borderColor: "rgba(0,0,0,1)",
            borderWidth: 2,
            hoverBackgroundColor: "rgba(0,0,0,0)",
            hoverBorderColor: "rgba(0,0,0,1)",
            lineTension: 0,
            data: <?php echo $valores_reais; ?>,
            fill:true,
          },
          {
            label: "Previsão",
            backgroundColor: "rgba(0,0,0,0.2)",
            borderColor: "rgba(150,0,0,1)",
            borderWidth: 2,
            hoverBackgroundColor: "rgba(0,0,0,0)",
            hoverBorderColor: "rgba(0,0,0,1)",
            lineTension: 0,
            data: <?php echo $valores_previsao; ?>,
            fill:false,
          }]
        };

        var options = {
          reponsive: true,
          maintainAspectRatio: false,
          scales: {
            yAxes: [{
              stacked: false,
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