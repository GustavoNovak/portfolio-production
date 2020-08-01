<html>
    <head>
		<link rel="icon" href="<?php echo BASE_URL; ?>assets/images/icone_aba.png" type="image/x-icon">
        <link rel="shortcut icon" href="<?php echo BASE_URL; ?>assets/images/icone_aba.png" type="image/x-icon">
        <title>Login - Novak Financial</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<style type="text/css">
			.corpo_login {
				max-width:300px;
				margin:auto;
				padding-left:20px;
				padding-right:20px;
			}
		</style>


	</head>
	<body>
	<h2 style="text-align:center;margin-top:60px;margin-bottom:30px">Bem-Vindo!</h2>
<form method="POST" style="text-align:center" class="corpo_login">  
  <div class="form-group">
    <label for="exampleInputEmail1">Nome de usuário:</label>
    <input type="text" class="form-control" name="user" aria-describedby="emailHelp" placeholder="Insira seu usuário">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Senha:</label>
    <input type="password" class="form-control" name="senha" placeholder="Senha">
  </div>
  <a href="<?php echo BASE_URL; ?>login/cadastro" style="text-decoration: none">Cadastre-se</a><br/>
  <button type="submit" class="btn btn-primary" style="margin-top:20px;width:150px">Logar</button>
</form>
	</body>
</html>