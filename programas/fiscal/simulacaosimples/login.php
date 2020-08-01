<?php
session_start();
require 'processos_php/config_sistemanovak.php';
require 'processos_php/config_sistemanovak_programas_simulacaosimples.php';
require 'processos_php/config_simples_2018_interno.php';

if(isset($_SESSION['id_usuario']) && !empty($_SESSION['id_usuario']) && isset($_SESSION['id_empresa']) && !empty($_SESSION['id_empresa'])){
	unset($_SESSION['id_usuario']);
	unset($_SESSION['id_empresa']);
}


$usuario_invalido = false;
$sem_empresa_cadastrada = false;
$tem_mais_que_uma_empresa = false;
if(isset($_POST['usuario']) && !empty($_POST['usuario']) && isset($_POST['senha']) && !empty($_POST['senha'])){
	
	$sql = $pdo->prepare("SELECT * FROM usuarios WHERE login = :usuario AND senha = :senha ");
	$sql->bindValue(":usuario",addslashes($_POST['usuario']));
	$sql->bindValue(":senha",md5(addslashes($_POST['senha'])));
	$sql->execute();
	if($sql->rowCount() > 0){
		$usuario = $sql->fetch();
		$_SESSION['id_usuario'] = $usuario['id'];
		$sql = $pdo->prepare("SELECT * FROM associacao_empresa_usuario WHERE id_usuario = :id_usuario ");
		$sql->bindValue(":id_usuario",$usuario['id']);
		$sql->execute();
		if($sql->rowCount() > 0){
			if($sql->rowCount() > 1){
				$empresa = $sql-> fetchAll();
				$tem_mais_que_uma_empresa = true;
			}else{
				$empresa = $sql-> fetch();
				$_SESSION['id_empresa'] = $empresa['id_empresa'];
				header("Location:parametros.php");
			}
		}else{
			$sem_empresa_cadastrada = true;
		}
	}else{
		$usuario_invalido = true;
	}
}

?>


<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Area do cliente</title>
		<link rel="icon" href="images/ÍCONE LOGO-02 nova-10.png" type="image/x-icon" />
		<link rel="shortcut icon" href="images/ÍCONE LOGO-02 nova-10.png" type="image/x-icon" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="css/bootstrap.min.css" />
		<link rel="stylesheet" href="css/style.css" />
		<link rel="stylesheet" href="css/style_login.css" />
		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript">
		
		function mudarZIndex(){
			document.getElementById("load").style.zIndex = "-1";
		}
			
		function loading(){
	     		$('#load').css('opacity','0');
	     		setTimeout(mudarZIndex,1000);
		}	
		</script>
	</head>
	<body onload="loading()">	
		<div id="modal_escolher_empresa" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title text-center titulo_modal">ESCOLHA SUA EMPRESA</h4>
					</div>
					<div class="modal-body">
						<div class="text-center" style="margin-top:22px">
							<?php
							if($tem_mais_que_uma_empresa == true){
								foreach($empresa as $emp){
									$sql = $pdo->prepare("SELECT * FROM empresas WHERE id = :id_empresa ");
									$sql->bindValue(":id_empresa",$emp['id_empresa']);
									$sql->execute();
									
									$dados_empresa = $sql->fetch();
									echo "<div class='empresa_modal text-center' id='".$emp['id_empresa']."' onClick='selecionarEmpresa(this)' >".$dados_empresa['nome']."</div>";
								}
							}
							?>
							<button class="btn btn-primary botao_contato botao_modal" style="margin-bottom:5px" onClick="escolherEmpresa()">ESCOLHER</button>
							<div id='aviso_selecionar_empresa' class='text-center' style='width:100%;color:#BC2012;height:25px;line-height:25px;opacity:0'>Você deve selecionar uma empresa</div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="load" class="text-center"><img src="images/91.gif" style="width:50px"/></div>
		<nav id="background_menu" class="navbar navbar-fixed-top">
			<div class="container" id="menu_sup" style="color:#FFFFFF;">
				<img id="botao_mobile" src="images/ÍCONE MENU NOVO.PNG" class="pull-right menu-mobile" onClick="abrirMenu()"/>
				<div class="navbar-header">
					<p class="navbar-brand nome-empresa" style="padding:0px;color:#FFF;margin-bottom:20px">NOVAK CONTABILIDADE</p>
				</div>
			</div>
			<div id="menu_principal"></div>
		</nav>
		<div id="menu">
			<ul>
				<li><a class="menu_lateral" href="http://contabilidadenovak.com.br">HOME</a></li>
				<li><a class="menu_lateral" href="http://contabilidadenovak.com.br/#servicos">SERVIÇOS</a></li>
				<li><a class="menu_lateral" href="http://contabilidadenovak.com.br/#quem_somos">QUEM SOMOS</a></li>
				<li><a class="menu_lateral" href="http://contabilidadenovak.com.br/#contato">CONTATO</a></li>
				<li><a class="menu_lateral" href="http://contabilidadenovak.com.br/duvidas contabeis.php">DÚVIDAS CONTÁBEIS</a></li>
				<li><a class="menu_lateral" href="http://contabilidadenovak.com.br/programas.php">PROGRAMAS</a></li>
				<li><a class="isDisabled">PLANOS</a></li>
				<li><a class="isDisabled">ÁREA DO CLIENTE</a></li>
			</ul>
		</div>
		<div class="site" onClick="fecharMenu()">	
			<div class="titulo text-center" style="margin-top:67.5px;background-color:#BC2012">Área do cliente</div>
			<form method="POST" class="container" style="height:auto;margin-top:100px;">
				<input name="usuario" class="modal_email text-center" type="text" placeholder="Nome de usuário"/>
				<input name="senha" class="modal_email text-center" type="password" placeholder="Senha" />
				<button type="submit" class="btn btn-primary botao_contato botao_anexar" style="margin-bottom:5px">ACESSAR</button>
				<?php
				if($usuario_invalido == true){
					echo "<div class='text-center' style='width:100%;color:#BC2012;height:25px;line-height:25px'>Usuário ou senha inválidos</div>";
				}
				if($sem_empresa_cadastrada == true){
					echo "<div class='text-center' style='width:100%;color:#BC2012;height:25px;line-height:25px'>Sua conta não tem nenhuma empresa cadastrada</div>";
				}
				?>
			</form>
			<div class="text-center" style="margin-top:50px;">
				<img src="images/LOGO LOGIN AREA CLIENTE 119X113px-31.jpg" />
			</div>
		</div>
	</body>
	<script type="text/javascript">	
		<?php
		if($tem_mais_que_uma_empresa == true){
			echo "$(document).ready(function() {
		   		$('#modal_escolher_empresa').modal('show');
			});";
		}
		?>
		document.getElementById("menu").style.width = "0px";
		function selecionarEmpresa(empresa){
			id_empresa = empresa.id;
			var empresas = document.getElementsByClassName("empresa_modal text-center");
			var divsTeste = Array.prototype.filter.call(empresas, function(i) {
			    i.style.color = '#6D6D6D';
			});
			empresa.style.color = "#BC2012";
		}		
		function escolherEmpresa(){
			if (typeof id_empresa !== 'undefined') {
				sendPost('parametros.php',{id_empresa: id_empresa});
			}else{
				document.getElementById("aviso_selecionar_empresa").style.opacity = "1";
			}
		}
		function abrirMenu(){	
			if (document.getElementById("menu").style.width == "0px"){
				document.getElementById("menu").style.width = "240px";
				var larguraMenu = document.getElementById("menu").clientWidth;
				var largura = $(window).width(); //Largura da janela
				
				
				
				document.getElementById("botao_mobile").src = "images/ÍCONE X-04.PNG"
				
				if (largura >= 1200) {
					var padding = 810 - (largura/2);
					document.getElementById("menu_sup").style.paddingRight = padding + "px";
				} else if (largura < 1200 && largura >= 992) {
				    var padding = 710 - (largura/2);
					document.getElementById("menu_sup").style.paddingRight = padding + "px";
				} else if (largura < 992 && largura >= 768) {
				    var padding = 610 - (largura/2);
					document.getElementById("menu_sup").style.paddingRight = padding + "px";
				} else {
				    if(largura <= 600){
						document.getElementById("menu_sup").style.paddingRight = "180px";
						document.getElementById("menu").style.width = "180px";
					}else{
				    	document.getElementById("menu_sup").style.paddingRight = "240px";
					}
				}
				
			}else{
				var larguraMenu = document.getElementById("menu").clientWidth;
				document.getElementById("menu").style.width = "0px";
				document.getElementById("menu_sup").style.paddingRight = "15px";
				document.getElementById("botao_mobile").src = "images/ÍCONE MENU NOVO.PNG"

			}

		}
		function fecharMenu(){
			if (document.getElementById("menu").style.width != "0px"){
				document.getElementById("menu").style.width = "0px";
				document.getElementById("menu_sup").style.paddingRight = "15px";
				document.getElementById("botao_mobile").src = "images/ÍCONE MENU NOVO.PNG"
			}
		}
	</script>
	<script type="text/javascript">
	        if(!window.sendPost){
	            window.sendPost = function(url, obj){
	                //Define o formulário
	                var myForm = document.createElement("form");
	                myForm.action = url;
	                myForm.method = "post";
	 
		        for(var key in obj) {
			     var input = document.createElement("input");
			     input.type = "text";
			     input.value = obj[key];
			     input.name = key;
			     myForm.appendChild(input);			
		        }
	                //Adiciona o form ao corpo do documento
	                document.body.appendChild(myForm);
	                //Envia o formulário
	                myForm.submit();
	            }    
	        } 
        </script>

</html>