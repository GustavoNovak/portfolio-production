<?php
session_start();
require 'processos_php/config_sistemanovak.php';
require 'processos_php/config_sistemanovak_programas_simulacaosimples.php';
require 'processos_php/config_simples_2018_interno.php';
require 'classes/usuarios.class.php';
require 'classes/empresas.class.php';

function string_numerico($v){
	return floatval(str_replace(",", ".",str_replace(".", "", $v)));
}

if(isset($_SESSION['id_usuario']) && !empty($_SESSION['id_usuario'])){
	if(isset($_POST['id_empresa']) && !empty($_POST['id_empresa'])){
		$_SESSION['id_empresa'] = $_POST['id_empresa'];
		unset($_POST['id_empresa']);
		header("Location: parametros.php");
		exit;
	}else{
		if(isset($_SESSION['id_empresa']) && !empty($_SESSION['id_empresa'])){
		
		}else{
			header("Location: login.php");
			exit;
		}
	}
}else{
	header("Location: login.php");
	exit;
}

if(isset($_POST['competencia']) && !empty($_POST['competencia']) && isset($_POST['nova_folha']) && !empty($_POST['nova_folha'])){
	$_SESSION['competencia'] = $_POST['competencia'];
	$_SESSION['nova_folha'] = $_POST['nova_folha'];
} else {
	if(isset($_SESSION['competencia']) && !empty($_SESSION['competencia']) && isset($_SESSION['nova_folha']) && !empty($_SESSION['nova_folha'])){
	
	} else {
		header("Location: parametros.php");
		exit;
	}
}

if(isset($_SESSION['faturamento']) && !empty($_SESSION['faturamento'])){
	unset($_SESSION['faturamento']);
}

$usuario = new Usuarios($_SESSION['id_usuario']);
$empresa = new EmpresasSimples($_SESSION['id_empresa']);

$_SESSION['categorias'] = $empresa->getCategorias();
$_SESSION['fator_r'] = $empresa->getFatorR();

$cod_empresa = substr($empresa->getCodigo(),3,5);

$sql = "SELECT * FROM registro_faturamento WHERE cod_empresa=".$cod_empresa."";
$sql = $pdo_simples_2018_interno->query($sql);

		//if($sql->rowCount() > 0){

			$resultado = $sql->fetchAll();

			$mes_competencia = (int)substr($_SESSION['competencia'],0,2);
			$ano_competencia = (int)substr($_SESSION['competencia'],3,4);
			$precisa_informar = false;
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
						$_SESSION['faturamento'][] = number_format($faturamento['faturamento'],'2',',','.');
						$faturamento_ja_informado = true;
					}
				}

				if($faturamento_ja_informado == false){
					$precisa_informar = true;
				}
			}

			if($precisa_informar == false) {
				header("Location: folha.php");
				exit;
			} else {
				unset($_SESSION['faturamento']);
			}

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
				document.querySelector("header").style.height = '400px';
				document.querySelector(".menu").style.height = '137px';
			}
		});
	}
	</script>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
  	<script src="jquery.maskMoney.js" type="text/javascript"></script>

</head>
<body>

<header>
	<div class="container">
		<div class="menu">
				<div href="login.php" class="logout">
					<a href="login.php">Logout</a>
				</div>
			<nav>
				<div class="menuMobile">
					<div class="mm_line"></div>
					<div class="mm_line"></div>
					<div class="mm_line"></div>
				</div>
				<ul>
					<img src="assets/images/logo com slogan vertical.png" height="50" />
					<li><a href="parametros.php">Parâmetros</a></li>
					<li class="active"><a href="">Faturamentos anteriores</a></li>
					<li ><a href="">Folhas anteriores</a></li>
					<li><a href="">Faturamento por categoria</a></li>
					<li><a href="">Resultado</a></li>
				</ul>
			</nav>
		</div>
	</div>
</header>
<section id="banner">
	<div class="container column">
		<div class="faturamento">
			Informe seus faturamentos anteriores:<br/><br/>
			<form method="post" action="folha.php">
				
			<?php

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
						$valor = $faturamento['faturamento'];
					}
				}

				if($faturamento_ja_informado == false){
					if($mes_competencia >= 10){
						echo $mes_competencia."/".$ano_competencia.":"."R$<input type='text'  data-thousands='.'' data-decimal=',' class='currency' name='faturamento[]' autocomplete='off' maxlength='90' required/><br/><br/>";
					}else{
						echo "0".$mes_competencia."/".$ano_competencia.":"." R$<input type='text'  data-thousands='.'' data-decimal=',' class='currency' name='faturamento[]' autocomplete='off' maxlength='90' required/><br/><br/>";
					}
				} else {
					if($mes_competencia >= 10){
						echo $mes_competencia."/".$ano_competencia.":"." R$<input type='text'  data-thousands='.'' data-decimal=',' class='currency' name='faturamento[]' value='".number_format($valor,2,',','.')."' autocomplete='off' maxlength='90' required/><br/><br/>";
					}else{
						echo "0".$mes_competencia."/".$ano_competencia.":"." R$<input type='text'  data-thousands='.'' data-decimal=',' class='currency' name='faturamento[]' value='".number_format($valor,2,',','.')."' autocomplete='off' maxlength='90' required/><br/><br/>";
					}
				}
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

