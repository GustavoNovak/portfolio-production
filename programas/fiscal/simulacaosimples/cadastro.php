<?php
session_start();
?>

<html>

	<head>
		<title>Cálculo de simples 2018</title>
		<link rel="icon" href="assets/images/logo novak.ico" type="image/x-icon" />
		<link rel="shortcut icon" href="assets/images/logo novak.ico" type="image/x-icon" />
		<meta name="viewport" content="width=device-width,user-scalable=0"/>
		<link rel="stylesheet" type="text/css" href="assets/css/style_cadastros.css" />
	</head>

	<script type="text/javascript">
		function validar(){
			var password = form_cadastro.password.value;
			var rePassword = form_cadastro.rePassword.value;

			if (password == rePassword){

			}else{
				alert("Password e confirmar password estão diferentes");
				return false;
			}
		}
	</script>

	<body>

		<div class="topo">
			<div class="titulo1">NOVAK SISTEMAS CONTÁBEIS</div>
			<div class="titulo2">SIMPLES 2018</div>
		</div>

		<div class="corpo">

			<div class="formulario">

				<div class="titulo_formulario">
					FAÇA SEU CADASTRO
				</div>

				<div class="formulario_usuario">
					<form name="form_cadastro" method="POST" action="cadastro_informacao.php">

						<input type="text" name="user_name" placeholder="Nome de usuário" autofocus autocomplete="off" required/><br/><br/>

						<input type="password" name="password" placeholder="Senha" autocomplete="off" required/><br/><br/>
						<input type="password" name="rePassword" placeholder="Confirmar Senha" autocomplete="off" required/><br/><br/>
						<input type="email" name="email" placeholder="E-mail" required/><br/><br/>
						<div class="recuperar_senha">
							<a href="recuperarSenha.php">Recuperar senha</a>
						</div>
						<input type="submit" value="CADASTRE-SE" onclick="return validar()"/><br/><br/>
					</form>
				</div>
			</div>
		</div>

	</body>

</html>