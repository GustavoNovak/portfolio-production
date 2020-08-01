<?php
session_start();

if(isset($_SESSION['id']) && !empty($_SESSION['id'])){

} else {
	header("Location: login.php");
}

if(isset($_SESSION['folha']) && !empty($_SESSION['folha'])){
	unset($_SESSION['folha']);
}

if(isset($_SESSION['estados']) && !empty($_SESSION['estados'])){

} else {
	header("Location: parametros.php");
}

if(isset($_SESSION['competencia']) && !empty($_SESSION['competencia'])){

} else {
	header("Location: parametros.php");
}

if(isset($_SESSION['categorias']) && !empty($_SESSION['categorias'])){

} else {
		header("Location: categorias.php");
}

if(isset($_POST['atividade']) && !empty($_POST['atividade'])){
	$_SESSION['atividade'] = $_POST['atividade'];
	$calcular_folha = false;
	foreach($_SESSION['atividade'] as $atividade_id){
		if($atividade_id != 17){
			$calcular_folha = true;
		}
	}
	if($calcular_folha == false){
		unset($_SESSION['atividade']);
		header("Location: faturamentos_anteriores.php");
	}
} else {
	if(isset($_SESSION['atividade']) && !empty($_SESSION['atividade'])){
		$calcular_folha = false;
		foreach($_SESSION['atividade'] as $atividade_id){
			if($atividade_id != 17){
				$calcular_folha = true;
			}
		}
		if($calcular_folha == false){
			unset($_SESSION['atividade']);
			header("Location: faturamentos_anteriores.php");
		}
	}else{
		header("Location: fator_r_atividades.php");
	}
}

// Verificando se tem Fator R:

	$dsn = "mysql:dbname=novak049_simples_2018;host=localhost";
	$dbuser = "novak049_program";
	$dbpass = "Rbagente1";

	try{
		$db = new PDO($dsn,$dbuser,$dbpass);

		$tem_fator_r = false;
		foreach($_SESSION['categorias'] as $categoria){

			$sql = "SELECT * FROM categorias WHERE id='".$categoria."'";
			$sql = $db->query($sql);

			$resultado = $sql->fetch();
			
			if($resultado['anexo'] == 3 || $resultado['anexo'] == 5){
				$sql = $db->query("SELECT * FROM atividades_sujeitas_fator_r");

				$atividades = $sql->fetchAll();

				foreach($atividades as $atividade){
					$tem_fator_r = true;	
				}
			}	
		}

	} catch(PDOexception $e){
		echo "Falhou: ".$e->getMessage();
	}	
	if($tem_fator_r == false){
		header("Location: categorias.php");
	}

//---------------------------

?>

<html>
<head>
	
	<link rel="icon" href="assets/images/logo novak.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="assets/images/logo novak.ico" type="image/x-icon" />	
	
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width,user-scalable=0"/>

	<link rel="stylesheet" type="text/css" href="assets/css/style_faturamentos.css" />

	<title>Sistema Novakcontabil</title>

	<script type="text/javascript">
	window.onload = function(){
		document.querySelector(".menuMobile").addEventListener("click",function(){
			if(document.querySelector(".menu nav ul").style.display == 'flex'){
				document.querySelector("header").style.height = 'auto';
				document.querySelector(".menu nav ul").style.display = 'none';
			} else {
				document.querySelector(".menu nav ul").style.display = 'flex';
				document.querySelector("header").style.height = '350px';
				document.querySelector(".menu").style.height = '80px';
			}
		});
	}

	function isNumber(n) {
	    return !isNaN(parseFloat(n)) && isFinite(n);
	}

	function verificarMoeda(){
		if (isNumber(event.key) == true | event.key == "," | event.keyCode == 8 | event.keyCode == 13 | event.keyCode == 9){
			
		} else {
			return false;
		}
	}
	</script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
  	<script src="jquery.maskMoney.js" type="text/javascript"></script>

</head>
<body>

<header>
	<div class="container">
		<div class="menu">
			<nav>
				<div class="menuMobile">
					<div class="mm_line"></div>
					<div class="mm_line"></div>
					<div class="mm_line"></div>
				</div>
				<ul>
					<li><a href="fator_r_atividades.php">Atividades</a></li>
					<li class="active"><a href="fator_r_folhas">Folhas de pagamentos anteriores</a></li>
				</ul>
			</nav>
		</div>
	</div>
</header>
<section id="banner">
	<div class="container column">
		<div class="faturamento">
			<form method="post" action="faturamentos_anteriores.php">
				
				<?php
					
	$dsn = "mysql:dbname=novak049_simples_2018_interno;host=localhost";
	$dbuser = "novak049_program";
	$dbpass = "Rbagente1";

	try{
		$db = new PDO($dsn,$dbuser,$dbpass);
		$sql = "SELECT * FROM registro_folha WHERE id_usuario='".$_SESSION['id']."' AND cod_empresa='".$_SESSION['cod_empresa']."'";
		$sql = $db->query($sql);

		//if($sql->rowCount() > 0){

			$resultado = $sql->fetchAll();

			$mes_competencia = (int)substr($_SESSION['competencia'],0,2);
			$ano_competencia = (int)substr($_SESSION['competencia'],3,4);
			
			for($i=1;$i<=12;$i++){

				if($mes_competencia == 1){
					$mes_competencia = 12;
					$ano_competencia = $ano_competencia - 1;
				} else {
					$mes_competencia = $mes_competencia - 1;
				}

				$faturamento_ja_informado = false;
				foreach ($resultado as $faturamento){
					$mes = (int)substr($faturamento['data'],0,2);
					$ano = (int)substr($faturamento['data'],3,4);

					if($mes == $mes_competencia && $ano == $ano_competencia){
						$faturamento_ja_informado = true;
						$valor = $faturamento['folha'];
					}
				}

				if($faturamento_ja_informado == false){
					if($mes_competencia >= 10){
						echo $mes_competencia."/".$ano_competencia.":"."R$<input name='folha' type='text' data-thousands='.' data-decimal=',' class='currency' autocomplete='off' maxlength='90' required/><br/><br/>";
					}else{
						echo "0".$mes_competencia."/".$ano_competencia.":"." R$<input type='text' name='folha[]' data-thousands='.' data-decimal=',' class='currency' autocomplete='off' maxlength='90' required/><br/><br/>";
					}
				} else {
					if($mes_competencia >= 10){
						echo $mes_competencia."/".$ano_competencia.":"." R$<input type='text' name='folha[]' data-thousands='.' data-decimal=',' class='currency' value='".number_format($valor,2,',','.')."' autocomplete='off' maxlength='90' required/><br/><br/>";
					}else{
						echo "0".$mes_competencia."/".$ano_competencia.":"." R$<input type='text' name='folha[]' data-thousands='.' data-decimal=',' class='currency' value='".number_format($valor,2,',','.')."' maxlength='90' autocomplete='off' required/><br/><br/>";
					}
				}
			}

		//} else {
			//echo "Você ainda não registrou nenhum faturamento";
		//}



	} catch(PDOexception $e){
		echo "Falou: ".$e->getMessage();
	}	
				?>
			
			<div class="botao_proximo">
				<input type="submit" value="Próximo" />
			</div>
			</form>
		</div>
	</div>
</section>

<section id="geral">
	<div class="container">
			<div class="widget">
				<div class="widget_title">
					<div class="widget_title_text">Siga-nos no facebook</div>
					<div class="widget_title_bar"></div>
				</div>
				<div classt="widget_body">
					<iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fcontabilidadenovak%2F&tabs&width=350&height=154&small_header=true&adapt_container_width=true&hide_cover=false&show_facepile=true&appId" width="350" height="154" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
				</div>
			</div>
		</aside>
	</div>
</section>

</body>

<script>
  $(function() {
    $('input.currency').maskMoney({allowZero: true});
  })
</script>

</html>

