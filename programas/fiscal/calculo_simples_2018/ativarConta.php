<?php
session_start();

//cadastrar

$code = $_GET['code'];

	$dsn = "mysql:dbname=novak049_simples_2018;host=localhost";
	$dbuser = "novak049_program";
	$dbpass = "Rbagente1";
	$cadastro_sucesso = false;
	try{
		$db = new PDO($dsn,$dbuser,$dbpass);
		$sql = "SELECT * FROM confirmacao_conta WHERE code='".$code."'";
		$sql = $db->query($sql);
		
		if($sql->rowCount() > 0){
		
			$conta = $sql->fetch();
			$cadastro_sucesso = true;
			$conta_ja_ativada = false;
			if($conta['ativado']==0){
			
				$sql = "INSERT INTO usuarios SET user_name='".$conta['user_name']."', password='".$conta['password']."', email='".$conta['email']."'";
				$sql = $db->query($sql);	
				
				$sql = "UPDATE confirmacao_conta SET ativado='1' WHERE code='".$code."'";
				$sql = $db->query($sql);
			
			}else{
				$conta_ja_ativada = true;
			}
		
		}


	} catch(PDOexception $e){
		echo "Falhou: ".$e->getMessage();
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
						<br/><h2>
						<?php
						if($cadastro_sucesso == true){
							if($conta_ja_ativada == false){
								echo "Conta ativada com sucesso!";	
							}else{
								echo "Esse link já foi ativado";	
							}
						
						}else{
							echo "Esse link está quebrado, sua conta não foi ativada!";
						}
						?></h2><br/>
						<input type="submit" value="LOGAR"/><br/><br/>
					</form>
				</div>
			</div>
		</div>

	</body>

</html>