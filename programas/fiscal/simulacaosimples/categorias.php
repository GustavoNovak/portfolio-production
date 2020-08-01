<?php
session_start();

if(isset($_SESSION['id']) && !empty($_SESSION['id'])){

} else {
	header("Location: login.php");
}

if(isset($_SESSION['categorias']) && !empty($_SESSION['categorias'])){
	unset($_SESSION['categorias']);
}

if(isset($_POST['competencia']) && !empty($_POST['competencia'])){
	$_SESSION['competencia'] = $_POST['competencia'];
	$_SESSION['cod_empresa'] = $_POST['cod_empresa'];
} else {
	if(isset($_SESSION['competencia']) && !empty($_SESSION['competencia'])){
	} else {
		header("Location: parametros.php");
	}
}

if(isset($_POST['estados']) && !empty($_POST['estados'])){
	$_SESSION['estados'] = $_POST['estados'];
} else {
	if(isset($_SESSION['estados']) && !empty($_SESSION['estados'])){
	} else {
		header("Location: parametros.php");	
	}
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

	<link rel="stylesheet" type="text/css" href="assets/css/style_menu.css" />

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
					<li><a href="parametros.php">Parâmetros</a></li>
					<li class="active"><a href="">Categorias</a></li>
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
		<div class="faturamento">
			<form method="post" action="faturamentos_anteriores.php">
				<?php
				//<input type="checkbox" value="false" name=>
	$dsn = "mysql:dbname=novak049_simples_2018_interno;host=localhost";
	$dbuser = "novak049_program";
	$dbpass = "Rbagente1";

	try{
		$db = new PDO($dsn,$dbuser,$dbpass);

		$sql = $db->query("SELECT * FROM categorias WHERE anexo='1'");

		if($sql->rowCount() > 0){

			echo "<div class='anexo'>";
			$resultado = $sql->fetchAll();
				echo "Anexo 1:<br/><br/>";
			foreach ($resultado as $nome_categoria){

				echo "<label class='container2'>".utf8_encode($nome_categoria['nome']);
	  			echo	"<input type='checkbox' name='categorias[]' value='".utf8_encode($nome_categoria['id'])."'/>";
	  			echo		"<span class='checkmark'></span>";
				echo "</label>";

			}

		} else {
			echo "Você não informou nenhuma categoria no anexo 1";
		}
			echo "</div>";

		$sql = $db->query("SELECT * FROM categorias WHERE anexo='2'");

		if($sql->rowCount() > 0){
				echo "<div class='anexo'>";
			$resultado = $sql->fetchAll();
				echo "Anexo 2:<br/><br/>";
			foreach ($resultado as $nome_categoria){

				echo "<label class='container2'>".utf8_encode($nome_categoria['nome']);
	  			echo	"<input type='checkbox' name='categorias[]' value='".utf8_encode($nome_categoria['id'])."'/>";
	  			echo		"<span class='checkmark'></span>";
				echo "</label>";

			}

		} else {
			echo "Você não informou nenhuma categoria no anexo 2";
		}
			echo "</div>";
		$sql = $db->query("SELECT * FROM categorias WHERE anexo='3'");

		if($sql->rowCount() > 0){
				echo "<div class='anexo'>";
			$resultado = $sql->fetchAll();
				echo "Anexo 3:<br/><br/>";
			foreach ($resultado as $nome_categoria){

				echo "<label class='container2'>".utf8_encode($nome_categoria['nome']);
	  			echo	"<input type='checkbox' name='categorias[]' value='".utf8_encode($nome_categoria['id']."'/>");
	  			echo		"<span class='checkmark'></span>";
				echo "</label>";

			}

		} else {
			echo "Você não informou nenhuma categoria no anexo 3";
		}
			echo "</div>";
		$sql = $db->query("SELECT * FROM categorias WHERE anexo='4'");

		if($sql->rowCount() > 0){
				echo "<div class='anexo'>";
			$resultado = $sql->fetchAll();
				echo "Anexo 4:<br/><br/>";
			foreach ($resultado as $nome_categoria){

				echo "<label class='container2'>".utf8_encode($nome_categoria['nome']);
	  			echo	"<input type='checkbox' name='categorias[]' value='".utf8_encode($nome_categoria['id'])."'/>";
	  			echo		"<span class='checkmark'></span>";
				echo "</label>";

			}

		} else {
			echo "Você não informou nenhuma categoria no anexo 4";
		}
			echo "</div>";
		$sql = $db->query("SELECT * FROM categorias WHERE anexo='5'");

		if($sql->rowCount() > 0){
				echo "<div class='anexo'>";
			$resultado = $sql->fetchAll();
				echo "Anexo 5:<br/><br/>";
			foreach ($resultado as $nome_categoria){

				echo "<label class='container2'>".utf8_encode($nome_categoria['nome']);
	  			echo	"<input type='checkbox' name='categorias[]' value='".utf8_encode($nome_categoria['id'])."'/>";
	  			echo		"<span class='checkmark'></span>";
				echo "</label>";

			}

		} else {
			echo "Você não informou nenhuma categoria no anexo 5";
		}
			echo "</div>";

	} catch(PDOexception $e){
		echo "Falhou: ".$e->getMessage();
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
</html>

