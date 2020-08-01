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

if(isset($_SESSION['competencia']) && !empty($_SESSION['competencia'])){

} else {
	header("Location: parametros.php");
	exit;
}

if(isset($_SESSION['nova_folha']) && !empty($_SESSION['nova_folha'])){

} else {
	header("Location: parametros.php");
	exit;
}

if(isset($_SESSION['categorias']) && !empty($_SESSION['categorias'])){

} else {
	header("Location: faturamentos_anteriores.php");
	exit;
}

if(isset($_SESSION['fator_r']) && !empty($_SESSION['fator_r'])){

} else {
	header("Location: faturamentos_anteriores.php");
	exit;
}

if(isset($_SESSION['faturamento']) && !empty($_SESSION['faturamento'])){

} else {
	header("Location: faturamentos_anteriores.php");
	exit;
}

if(isset($_POST['folha']) && !empty($_POST['folha'])){
	$_SESSION['folha'] = $_POST['folha'];
} else {
	if(isset($_SESSION['folha']) && !empty($_SESSION['folha'])){

	} else {
		if(!in_array(1, $_SESSION['fator_r'])) {

		} else {
			header("Location: folha.php");
			exit;	
		}
	}
}

$usuario = new Usuarios($_SESSION['id_usuario']);
$empresa = new EmpresasSimples($_SESSION['id_empresa']);
					
?>

<html>
<head>
	
	<link rel="icon" href="assets/images/logo novak.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="assets/images/logo novak.ico" type="image/x-icon" />	
	
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width,user-scalable=0"/>

	<link rel="stylesheet" type="text/css" href="assets/css/style_faturamentos.css" />

	<title>Sistema Novakcontabil</title>
	<script type="text/javascript" src="assets/js/codigo.js"></script>
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
					<li><a href="faturamentos_anteriores.php">Faturamentos anteriores</a></li>
					<li><a href="">Folhas anteriores</a></li>
					<li class="active"><a href="">Faturamento por categoria</a></li>
					<li><a href="">Resultado</a></li>
				</ul>
			</nav>
		</div>
	</div>
</header>
<section id="banner">
	<div class="container column">
		<div class="faturamento">
			<form name="form" method="post" action="resultado.php">
				<?php
					
					if(count($empresa->getCategorias()) == 1){
						echo 	"Insira aqui o seu novo valor de faturamento para simulação:<br/><br/><br/>";
						echo "<div class='faturamento_categoria'>";
						echo "R$ <input type='text' name='faturamento_categoria[]' data-thousands='.'' data-decimal=',' class='currency' autocomplete='off' required/>";
						echo "</div><br/>";						
					} else {
						echo 	"Insira aqui o seu novo valor de faturamento em cada categoria para simulação:<br/><br/><br/>";
						foreach ($empresa->getCategorias() as $value) {
							$sql = $pdo_simples_2018_interno->prepare("SELECT * FROM categorias WHERE id = :id");
							$sql->bindValue(":id",$value);
							$sql->execute();
							
							$categoria = $sql->fetch();

							echo "<div class='faturamento_categoria'>";
							echo 	"Anexo ".$categoria['anexo']." - ".$categoria['nome'].":"."<br/><br/>";
							echo "R$ <input type='text' name='faturamento_categoria[]' data-thousands='.'' data-decimal=',' class='currency' autocomplete='off' required/>";
							echo "</div><br/>";	
						}

					}
				?>
			
			<div class="botao_proximo">
				<input type="submit" value="Próximo"/>
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

