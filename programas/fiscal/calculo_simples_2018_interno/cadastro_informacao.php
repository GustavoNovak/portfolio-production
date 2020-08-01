<?php
session_start();

//enviar email

//------------

//cadastrar

if(isset($_POST['email']) && !empty($_POST['email'])){

	$email = addslashes($_POST['email']);
	$usuario = addslashes($_POST['user_name']);
	$password = md5(addslashes($_POST['password']));

	$dsn = "mysql:dbname=simples_nacional_interno;host=localhost";
	$dbuser = "admin";
	$dbpass = "admin";

	try{
		$db = new PDO($dsn,$dbuser,$dbpass);
		$sql = "SELECT * FROM usuarios WHERE email='".$email."'";
		$sql = $db->query($sql);

		if($sql->rowCount() == 0){
			$sql = "INSERT INTO usuarios SET user_name='".$usuario."', password='".$password."', email='".$email."'";
			$sql = $db->query($sql);
		} else {
			header("Location: cadastro.php");
		}

	} catch(PDOexception $e){
		echo "Falou: ".$e->getMessage();
	}	

}else{
	header("Location: cadastro.php");
}



//---------
  


?>

<html>

	<head>
		<title>Cálculo de simples 2018</title>
		<link rel="icon" href="assets/images/logo novak.ico" type="image/x-icon" />
		<link rel="shortcut icon" href="assets/images/logo novak.ico" type="image/x-icon" />
		<meta name="viewport" content="width=device-width,user-scalable=0"/>
		<link rel="stylesheet" type="text/css" href="assets/css/style_cadastro.css" />
	</head>

	<script type="text/javascript">

	</script>

	<body>

		<div class="topo">
			<div class="titulo1">NOVAK SISTEMAS CONTÁBEIS</div>
			<div class="titulo2">SIMPLES 2018</div>
		</div>

		<div class="corpo">

			<div class="formulario">

				<div class="titulo_formulario">
					INFORMAÇÃO
				</div>

				<div class="formulario_usuario">
					<form name="form_cadastro" method="POST" action="login.php">
						<br/><h2>Cadastro realizado com sucesso!</h2><br/>
						<input type="submit" value="LOGAR"/><br/><br/>
					</form>
				</div>
			</div>
		</div>

	</body>

</html>