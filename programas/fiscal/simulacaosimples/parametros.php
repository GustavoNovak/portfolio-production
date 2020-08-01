<?php
session_start();
require 'processos_php/config_sistemanovak.php';
require 'processos_php/config_sistemanovak_programas_simulacaosimples.php';
require 'processos_php/config_simples_2018_interno.php';
require 'classes/usuarios.class.php';
require 'classes/empresas.class.php';

if(isset($_SESSION['id_usuario']) && !empty($_SESSION['id_usuario'])){
	if(isset($_POST['id_empresa']) && !empty($_POST['id_empresa'])){
		$_SESSION['id_empresa'] = $_POST['id_empresa'];
		unset($_POST['id_empresa']);
		header("Location: parametros.php");
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

if(isset($_SESSION['competencia']) && !empty($_SESSION['competencia'])){
	unset($_SESSION['competencia']);
} 

if(isset($_SESSION['faturamento']) && !empty($_SESSION['faturamento'])){
	unset($_SESSION['faturamento']);
}

if(isset($_SESSION['folha']) && !empty($_SESSION['folha'])){
	unset($_SESSION['folha']);
}

if(isset($_SESSION['faturamento_categoria']) && !empty($_SESSION['faturamento_categoria'])){
	unset($_SESSION['faturamento_categoria']);
}

$usuario = new Usuarios($_SESSION['id_usuario']);
$empresa = new Empresas($_SESSION['id_empresa']);
?>

<html>
<head>
	
		<meta charset="utf-8" />
		<title>Simulação Simples Nacional</title>
		<link rel="icon" href="images/ÍCONE LOGO-02 nova-10.png" type="image/x-icon" />
		<link rel="shortcut icon" href="images/ÍCONE LOGO-02 nova-10.png" type="image/x-icon" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="css/bootstrap.min.css" />
		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<link rel="stylesheet" type="text/css" href="assets/css/style_parametro.css" />
	<script type="text/javascript">
	window.onload = function(){
		document.querySelector(".menuMobile").addEventListener("click",function(){
			if(document.querySelector(".menu nav ul").style.display == 'flex'){
				document.querySelector("header").style.height = 'auto';
				document.querySelector(".menu nav ul").style.display = 'none';
			} else {
				document.querySelector(".menu nav ul").style.display = 'flex';
				document.querySelector("header").style.height = '400px';
			}
		});
	}
	function isNumber(n) {
	    return !isNaN(parseFloat(n)) && isFinite(n);
	}

	function verificarCompetencia(){
		if (isNumber(event.key) == true | event.keyCode == 8 | event.keyCode == 13 | event.keyCode == 9 | event.keyCode == 193 ){
			
		} else {
			return false;
		}
	}
	</script>


	<script src="assets/js/jquery-1.2.6.pack.js" type="text/javascript"></script><script src="assets/js/jquery.maskedinput-1.1.4.pack.js" type="text/javascript" /></script>
	<script src="jquery.maskMoney.js" type="text/javascript"></script>

	<script type="text/javascript">
		$(document).ready(function(){	$("#competencia").mask("99/9999");});
		$(function() {$('input.currency').maskMoney({allowZero: true});})
	</script>

</head>
<body>

<header>
	<div class="container">
		<div class="menu" style="display:flex;">
				<div  href="login.php" class="logout">
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
					<li class="active"><a href="">Parâmetros</a></li>
					<li><a href="">Faturamentos anteriores</a></li>
					<li><a href="">Folhas anteriores</a></li>
					<li><a href="">Faturamento por categoria</a></li>
					<li><a href="">Resultado</a></li>
				</ul>
			</nav>
		</div>
	</div>
</header>
<section id="banner">
	<div class="container column">
		<div class="parametros">
			<form name="form" method="post" action="faturamentos_anteriores.php">
				Insira a competência da simulação:<br/><br/>
				<input type="text" id="competencia" name="competencia" placeholder="dd/aaaa" autocomplete="off"  pattern="\d{2}/\d{4}" required autofocus/><br/><br/>
				Insira a sua nova folha de pagamento:<br/><br/>
				<input type="text" class="currency" data-thousands='.'' data-decimal=',' name="nova_folha" placeholder="20000,34" autocomplete="off" maxlength="90" required/>
			<div class="botao_proximo">
				<input type="submit" value="Próximo" onclick="return validar()"/>
			</div>
			</form>
		</div>
	</div>
</section>
<div class="container" style="justify-content:start">
	<?php echo "Usuário logado: ".$usuario->getNome()."<br/>Empresa logada: ".utf8_encode($empresa->getNome());  ?>
</div>
</body>
</html>

