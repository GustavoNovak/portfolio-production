<?php
session_start();

$usuario_existe = true;

if(isset($_SESSION['id']) && !empty($_SESSION['id'])){
	unset($_SESSION['id']);
}

if(isset($_POST['user_name']) && !empty($_POST['user_name'])){
	$user_name = addslashes($_POST['user_name']);
	$password = md5(addslashes($_POST['password']));

	$dsn = "mysql:dbname=simples_nacional_interno;host=localhost";
	$dbuser = "admin";
	$dbpass = "admin";

	try{
		$db = new PDO($dsn,$dbuser,$dbpass);

		$sql = $db->query("SELECT * FROM usuarios WHERE user_name='$user_name' AND password='$password'");

		if($sql->rowCount() > 0){

			$dado = $sql->fetch();

			$_SESSION['id']= $dado['id'];

			header("Location: index.php");

		} else {
			$usuario_existe = false;
		}



	} catch(PDOexception $e){
		echo "Falou: ".$e->getMessage();
	}
}

?>

<html>

	<head>
		<title>Cálculo de simples</title>
		<link rel="icon" href="assets/images/logo novak.ico" type="image/x-icon" />
		<link rel="shortcut icon" href="assets/images/logo novak.ico" type="image/x-icon" />
		<meta name="viewport" content="width=device-width,user-scalable=0"/>
		<link rel="stylesheet" type="text/css" href="assets/css/style_login.css" />
	</head>

	<body>

		<div class="topo">
			<img src="assets/images/logo com slogan vertical.png" width="200px"/>
			<div class="titulo2">SIMPLES</div>
		</div>

		<div class="corpo">

			<div class="formulario">

				<div class="titulo_formulario">
					FAÇA SEU LOGIN
				</div>

				<div class="formulario_usuario">
					<form method="POST">

						<input type="text" name="user_name" placeholder="Nome de usuário" autofocus autocomplete="off"/><br/><br/>

						<input type="password" name="password" placeholder="Senha"/><br/><br/>
						<div class="recuperar_senha">
							<a href="cadastro.php">Cadastre-se já</a>
							<a href="recuperarSenha.php">Recuperar senha</a>
						</div>
						<input type="submit" value="LOGAR" /><br/><br/>
					</form>
				</div>

				<?php
					
					if($usuario_existe == false){
						echo "<div class='usuario_nao_encontrado'>Usuário ou senha estão incorretos!</div>";
					}	
				?>
			</div>
		</div>

	</body>

</html>