<?php
$competencias_grafico = array_keys($valores_conta);
$valores = array_values($valores_conta);

foreach ($competencias_grafico as $key => $value) {
  $competencias_grafico[$key] = "'".$value."'";
}

$competencias_grafico = '['.implode(', ', $competencias_grafico).']';
$valores = '['.implode(', ', $valores).']';
?>
<form method="POST" style="margin-top:50px" class="corpo_login">  
  <div class="form-group">
    <label for="conta">Conta:</label>
    <select class="form-control" name="conta" id="conta" data-url="<?php echo BASE_URL; ?>">
      <?php foreach($contas as $conta): ?>
      <option value="<?php echo $conta['id']; ?>" <?php echo ($id_conta == $conta['id'])?'selected="selected"':''; ?>><?php echo $conta['nome']; ?> - <?php if($conta['tipo'] == '1'){echo "Entrada";}else{echo "Saída";} ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="form-group">
        <label for="competencia">Competências:</label>
        <select class="form-control" name="competencia" id="competencia" data-url="<?php echo BASE_URL; ?>">
          <?php foreach($competencias as $competencia): ?>
          <option value='<?php echo $competencia; ?>'><?php echo $competencia; ?></option>
          <?php endforeach; ?>
        </select>
  </div>
  <div class="form-group">
    <label for="valor">Valor:</label>
    <input type="text" class="form-control" name="valor" placeholder="Insira valor da conta no mês informado">
  </div> 
  <div id="aviso"> 
  <?php
    if(!empty($aviso)) {
        echo $aviso;
    }
  ?>
  </div>
  <div style="display:flex;justify-content:space-between">
    <button id="btn_cadastrar" type="submit" class="btn btn-default" style="margin-top:20px;width:150px">Cadastrar</button>
    <div id="botao_excluir"></div>
  </div>
</form>
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

      }
</script>
<script type="text/javascript" src="<?php echo BASE_URL; ?>models/jQuery/cadastro_conta.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/select2.min.js"></script>
<script type="text/javascript">
      $(document).ready(function() {

        $("#conta").select2({});
        $("#competencia").select2({});

        $('select[name=competencia]').change(function(){
          alterar_competencia();
        });

        alterar_competencia();

      });

      function alterar_competencia() {
          var url = $('select[name=competencia').attr('data-url');
          var competencia = $('select[name=competencia]').val();
          var id_conta = $('select[name=conta]').val();

          $.ajax({
            type:'POST',
            url:url+'ajax/form_editar_valor_conta',
            data:{competencia:competencia, id_conta:id_conta},
            dataType:'json',
            success:function(json){
              $('#aviso').html(json.aviso);

              if(json.tem_registro == '1') {
                $('#botao_excluir').html('<button id="btn_excluir" class="btn btn-danger" style="float:right;margin-top:20px;width:150px" data-url="<?php echo BASE_URL; ?>">Excluir</button>');
                
              $('#btn_excluir').click(function(e){
                e.preventDefault();
                var competencia = $('select[name=competencia]').val();
                var id_conta = $('select[name=conta]').val();

                $.ajax({
                  type:'POST',
                  url:url+'ajax/excluir_valor_conta',
                  data:{competencia:competencia, id_conta:id_conta},
                  dataType:'html',
                  success:function(data){
                    $('#aviso').html(data);
                    $('#botao_excluir').html('');
                    $('input[name=valor]').attr("value",'');
                    $('#btn_cadastrar').html('Cadastrar');
                  },
                  error:function(){
                    $('#aviso').html('<div class="text-danger">Houve um erro, não é possível deletar esse valor!</div>');
                  }
                });

              });


                $('#btn_cadastrar').html('Editar');
                $('input[name=valor]').attr("value",json.valor);
              } else {
                $('#botao_excluir').html('');
                $('#btn_cadastrar').html('Cadastrar');
                $('input[name=valor]').attr("value",'');
              }
            },
            error:function(){
              $('#aviso').html('<div class="text-danger">Houve um erro, não foi possível carregar esse valor!</div>');
            }
          });
        }
</script>


