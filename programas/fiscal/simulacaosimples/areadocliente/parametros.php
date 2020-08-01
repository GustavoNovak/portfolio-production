<?php
session_start();

if(isset($_SESSION['id']) && !empty($_SESSION['id'])){

} else {
	header("Location: login.php");
}

if(isset($_SESSION['competencia']) && !empty($_SESSION['competencia'])){
	unset($_SESSION['competencia']);
} 

if(isset($_SESSION['estados']) && !empty($_SESSION['estados'])){
	unset($_SESSION['estados']);
}

if(isset($_SESSION['atividade']) && !empty($_SESSION['atividade'])){
	unset($_SESSION['atividade']);
}

if(isset($_SESSION['folha']) && !empty($_SESSION['folha'])){
	unset($_SESSION['folha']);
}

?>

<html>
<head>
	
	<link rel="icon" href="assets/images/logo novak.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="assets/images/logo novak.ico" type="image/x-icon" />	
	
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width,user-scalable=0"/>

	<link rel="stylesheet" type="text/css" href="assets/css/style_parametro.css" />

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
	<script type="text/javascript">
	$(document).ready(function(){	$("#cod_empresa").mask("999.9");});
	$(document).ready(function(){	$("#competencia").mask("99/9999");});
	</script>

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
					<li class="active"><a href="">Parâmetros</a></li>
					<li><a href="./">Categorias</a></li>
					<li><a href="">Faturamentos anteriores</a></li>
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
			<form name="form" method="post" action="categorias.php">
				Competência:<br/><br/>
				<input type="text" id="competencia" name="competencia" placeholder="dd/aaaa" autocomplete="off"  pattern="\d{2}/\d{4}" required autofocus/><br/><br/>
				Código da empresa:<br/><br/>
				<input type="text" id="cod_empresa" name="cod_empresa" placeholder="999.9" autocomplete="off"  pattern="\d{3}.\d{1,}" required autofocus/><br/><br/>
				Estado(UF) da sua empresa:<br/><br/>
				<?php
					

	$dsn = "mysql:dbname=novak049_simples_2018_interno;host=localhost";
	$dbuser = "novak049_program";
	$dbpass = "Rbagente1";

	try{
		$db = new PDO($dsn,$dbuser,$dbpass);

		$sql = $db->query("SELECT * FROM estados");

		if($sql->rowCount() > 0){

			$resultado = $sql->fetchAll();
			
			echo "<select name='estados'>";
			foreach ($resultado as $estados){
				echo  "<option value='".$estados['id']."'>".utf8_encode($estados['nome'])."</option>";
			}
			echo "</select>";

		} else {
			echo "não existe nenhuma categoria";
		}



	} catch(PDOexception $e){
		echo "Falou: ".$e->getMessage();
	}	
				?>
			<div class="botao_proximo">
				<input type="submit" value="Próximo" onclick="return validar()"/>
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
</html>

