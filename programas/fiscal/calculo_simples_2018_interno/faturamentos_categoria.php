<?php
session_start();

if(isset($_SESSION['id']) && !empty($_SESSION['id'])){

} else {
	header("Location: login.php");
}

if(isset($_SESSION['faturamento_categoria']) && !empty($_SESSION['faturamento_categoria'])){
	unset($_SESSION['faturamento_categoria']);
}

if(isset($_SESSION['categorias']) && !empty($_SESSION['categorias'])){

} else {
	header("Location: categorias.php");
}

if(isset($_SESSION['estados']) && !empty($_SESSION['estados'])){

} else {
	header("Location: parametros.php");
}

if(isset($_SESSION['competencia']) && !empty($_SESSION['competencia'])){

} else {
	header("Location: parametros.php");
}

if(isset($_POST['faturamento']) && !empty($_POST['faturamento'])){
	$_SESSION['faturamento'] = $_POST['faturamento'];
} else {
	if(isset($_SESSION['faturamento']) && !empty($_SESSION['faturamento'])){
	} else {
		header("Location: faturamentos_anteriores.php");
	}
}
	// Atualizando os registros de faturamento

	$dsn = "mysql:dbname=simples_nacional_interno;host=localhost";
	$dbuser = "admin";
	$dbpass = "admin";

	try{
		$db = new PDO($dsn,$dbuser,$dbpass);
		
		$mes_competencia = (int)substr($_SESSION['competencia'],0,2);
		$ano_competencia = (int)substr($_SESSION['competencia'],3,4);
		
		for($i=0;$i<=11;$i++){
			
			$valor = str_replace(".","", $_SESSION['faturamento'][$i]);
			$valor = str_replace(",",".", $valor);

			if($mes_competencia == 1){
				$mes_competencia = 12;
				$ano_competencia = $ano_competencia - 1;
			} else {
				$mes_competencia = $mes_competencia - 1;
			}

			if($mes_competencia >= 10){
				$data = $mes_competencia."/".$ano_competencia;
			}else{
				$data = "0".$mes_competencia."/".$ano_competencia;
			}

			$sql = "SELECT * FROM registro_faturamento WHERE id_usuario='".$_SESSION['id']."' AND cod_empresa='".$_SESSION['cod_empresa']."' AND data='".$data."'";
			$sql = $db->query($sql);

			if($sql->rowCount() == 1){
				$sql = "UPDATE registro_faturamento SET faturamento='".$valor."' WHERE id_usuario='".$_SESSION['id']."' AND cod_empresa='".$_SESSION['cod_empresa']."' AND data='".$data."'";
				$sql = $db->query($sql);	
			} else {
				$sql = "INSERT INTO registro_faturamento SET  id_usuario='".$_SESSION['id']."', cod_empresa='".$_SESSION['cod_empresa']."', faturamento='".$valor."', data='".$data."'";
				$sql = $db->query($sql);					
			}
		} 
	} catch(PDOexception $e){
		echo "Falhou: ".$e->getMessage();	
	}
		//----------------------------------
					
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
					<li><a href="categorias.php">Categorias</a></li>
					<li><a href="faturamentos_anteriores.php">Faturamentos anteriores</a></li>
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
					$dsn = "mysql:dbname=simples_nacional_interno;host=localhost";
					$dbuser = "admin";
					$dbpass = "admin";
					try{
						$db = new PDO($dsn,$dbuser,$dbpass);

					} catch(PDOexception $e){
						echo "Falou: ".$e->getMessage();
					}	
					
					foreach($_SESSION['categorias'] as $categoria){
						
						$sql = "SELECT * FROM categorias WHERE id='".$categoria."'";
						$sql = $db->query($sql);

						$categoria = $sql->fetch();

						echo "<div class='faturamento_categoria'>";
						echo 	"Anexo ".$categoria['anexo']." - ".utf8_encode($categoria['nome']).":"."<br/><br/>";
						echo "R$ <input type='text' name='faturamento_categoria[]' data-thousands='.'' data-decimal=',' class='currency' autocomplete='off' required/>";
						echo "</div><br/>";
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

