<?php
session_start();

$code = md5(time());

//enviar email

	$para = $_POST['email'];
	$assunto = "Confirmação de cadastro no sistema de cálculo de simples 2018 - Novakcontabil";
	$corpo = "Abra o link a seguir e sua conta será ativada:    ".
"sistemas.contabilidadenovak.com.br/programas/fiscal/calculo_simples_2018/ativarConta.php?code=".$code;
	$cabecalho = "From: suporte@contabilidadenovak.com.br"."\r\n".
				 "Reply-To: ".$_POST['email']."\r\n".
				 "X-Mailer: PHP/".phpversion();

	mail($para, $assunto, $corpo, $cabecalho);

//------------

//cadastrar

if(isset($_POST['email']) && !empty($_POST['email'])){

	$email = addslashes($_POST['email']);
	$usuario = addslashes($_POST['user_name']);
	$password = md5(addslashes($_POST['password']));

	$dsn = "mysql:dbname=novak049_simples_2018;host=localhost";
	$dbuser = "novak049_program";
	$dbpass = "Rbagente1";

	try{
		$db = new PDO($dsn,$dbuser,$dbpass);
		$sql = "SELECT * FROM usuarios WHERE email='".$email."'";
		$sql = $db->query($sql);

		$sql = "INSERT INTO confirmacao_conta SET user_name='".$usuario."', password='".$password."', email='".$email."', code='".$code."', ativado='0'";
		$sql = $db->query($sql);

	} catch(PDOexception $e){
		echo "Falhou: ".$e->getMessage();
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
			<img src="assets/images/logo com slogan vertical.png" width="300px"/>
			<div class="titulo2">SIMPLES 2018</div>
		</div>

		<div class="corpo">

			<div class="formulario">

				<div class="titulo_formulario">
					INFORMAÇÃO
				</div>

				<div class="formulario_usuario">
					<form name="form_cadastro" method="POST" action="login.php">
						<br/><h2>Utilize o link enviado no seu e-mail para ativar a sua conta!</h2><br/>
						<input type="submit" value="LOGAR"/><br/><br/>
					</form>
				</div>
			</div>
		</div>

	</body>

</html>