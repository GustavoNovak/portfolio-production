<?php
session_start();

if(isset($_SESSION['id']) && !empty($_SESSION['id'])){

} else {
	header("Location: login.php");
}

if(isset($_SESSION['atividade']) && !empty($_SESSION['atividade'])){
	unset($_SESSION['atividade']);
}

if(isset($_SESSION['categorias']) && !empty($_SESSION['categorias'])){

}else{
	header("Location: categorias.php");
}

if(isset($_SESSION['competencia']) && !empty($_SESSION['competencia'])){
	
} else {
	header("Location: parametros.php");
}

if(isset($_SESSION['estados']) && !empty($_SESSION['estados'])){

} else {
	header("Location: parametros.php");	
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
				document.querySelector("header").style.height = '350px';
				document.querySelector(".menu").style.height = '80px';
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
			<nav>
				<div class="menuMobile">
					<div class="mm_line"></div>
					<div class="mm_line"></div>
					<div class="mm_line"></div>
				</div>
				<ul>
					<li class="active"><a href="fator_r_atividades.php">Atividades</a></li>
					<li><a href="">Folhas de pagamentos anteriores</a></li>
				</ul>
			</nav>
		</div>
	</div>
</header>
<section id="banner">
	<div class="container column">
		<div class="parametros">
			<form method="post" action="fator_r_folha.php">
				<?php
				//<input type="checkbox" value="false" name=>
	$dsn = "mysql:dbname=simples_nacional_interno;host=localhost";
	$dbuser = "admin";
	$dbpass = "admin";

	try{
		$db = new PDO($dsn,$dbuser,$dbpass);

		$tem_fator_r = false;
		foreach($_SESSION['categorias'] as $categoria){

			$sql = "SELECT * FROM categorias WHERE id='".$categoria."'";
			$sql = $db->query($sql);

			$resultado = $sql->fetch();
			
			if($resultado['anexo'] == 3 || $resultado['anexo'] == 5){
				echo "Atividade - Anexo: ".$resultado['anexo']."- categoria: ".utf8_encode($resultado['nome'])."<br/><br/>";
				echo "<select name='atividade[".$categoria."]'>";
				$sql = $db->query("SELECT * FROM atividades_sujeitas_fator_r");

				$atividades = $sql->fetchAll();

				foreach($atividades as $atividade){
					echo  "<option value='".$atividade['id']."'>".utf8_encode($atividade['nome'])."</option>";
					$tem_fator_r = true;	
				}
				echo "</select><br/><br/>";	
			}	
		}

	} catch(PDOexception $e){
		echo "Falhou: ".$e->getMessage();
	}	
				if($tem_fator_r == false){
					header("Location: categorias.php");
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

