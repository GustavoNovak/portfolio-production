<?php
require 'processos_php/config.php';

if(isset($_SESSION['id_usuario_areadocliente']) && !empty($_SESSION['id_usuario_areadocliente'])){
	if(isset($_POST['id_empresa']) && !empty($_POST['id_empresa'])){
		$_SESSION['id_empresa_areadocliente'] = $_POST['id_empresa'];
		unset($_POST['id_empresa']);
		header("Location: menu_principal.php");
	}else{
		if(isset($_SESSION['id_empresa_areadocliente']) && !empty($_SESSION['id_empresa_areadocliente'])){
		
		}else{
			header("Location: login.php");
		}
	}
}else{
	header("Location: login.php");
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
		<link rel="stylesheet" href="css/style_menu_principal.css" />
		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript">
		
		function mudarZIndex(){
			document.getElementById("load").style.zIndex = "-1";
		}
			
		function loading(){
	     		$('#load').css('opacity','0');
	     		setTimeout(mudarZIndex,500);
		}	
		</script>
	</head>
	<body onload="loading()">	
		<div id="load" class="text-center"><img src="images/91.gif" style="width:50px"/></div>
		<nav id="background_menu" class="navbar navbar-fixed-top">
			<div class="container" id="menu_sup" style="color:#FFFFFF;">
				<img id="botao_mobile" src="images/ÍCONE MENU NOVO.PNG" class="pull-right menu-mobile" onClick="abrirMenu()"/>
				<div class="navbar-header" style="display:flex;display:-webkit-flex;">
					<div class="mensagens" onClick="abrirMensagens()">03</div>
					<img id="icone_escolher_empresa" src="images/seta empresa escolhida 11x16.png" onClick="abrirMenuEmpresas(this)" style="cursor:pointer;height:19px;width:14px;margin-top:20.5px;margin-left:17px"/>
					
					<div id="nome_empresa">
					<?php
					$sql = $pdo->prepare("SELECT * FROM empresas WHERE id = :id ");
					$sql->bindValue(":id",$_SESSION['id_empresa_areadocliente']);
					$sql->execute();
								
					$empresa = $sql->fetch();
					
					echo $empresa['nome'];
					?></div>
					<div id="escolher_empresa_base">
						<?php
						$sql = $pdo->prepare("SELECT * FROM associacao_empresa_usuario_areadocliente WHERE id_usuario = :id_usuario ");
						$sql->bindValue(":id_usuario",$_SESSION['id_usuario_areadocliente']);
						$sql->execute();
						if($sql->rowCount() > 0){
							$id_empresas = $sql->fetchAll();
							foreach($id_empresas as $emp){
								$sql = $pdo->prepare("SELECT * FROM empresas WHERE id = :id ");
								$sql->bindValue(":id",$emp['id_empresa']);
								$sql->execute();
								
								$empresa = $sql->fetch();
								
								echo  	"<div class='escolher_empresa_base_2'>
										<div id='empresa_".$emp['id_empresa']."' class='escolher_empresa_opcao' onClick='escolherEmpresa(this)'>".$empresa['nome']."</div>
									</div>";
														
							}
						}
						?>
					</div>
					
				</div>
			</div>
			<div id="menu_principal"></div>
		</nav>
		<div id="menu">
			<ul>
				<li><a class="menu_lateral" href="http://contabilidadenovak.com.br">DOCUMENTOS</a></li>
				<li><a class="menu_lateral" href="http://contabilidadenovak.com.br/#servicos">SOLICITAÇÃO DE SERVIÇO</a></li>
				<li><a class="menu_lateral" href="http://contabilidadenovak.com.br/#quem_somos">ATUALIZAR CADASTRO</a></li>
				<li><a class="menu_lateral" href="http://contabilidadenovak.com.br/#contato">MENSAGENS</a></li>
				<li><a class="menu_lateral" href="http://contabilidadenovak.com.br/duvidas contabeis.php">SUAS ATIVIDADES</a></li>
				<li><a class="menu_lateral" href="http://contabilidadenovak.com.br/programas.php">INFORMAR ESTOQUE</a></li>
				<li><a class="menu_lateral" href="login.php">LOGOUT</a></li>
			</ul>
		</div>
		<div id="menu2">
			<ul>
				<li><a class="menu_lateral" href="http://contabilidadenovak.com.br">Enviar as folhas referentes a 03/2018</a></li>
				<li><a class="menu_lateral" href="http://contabilidadenovak.com.br/#servicos">Enviar ofx referente a 03/2018</a></li>
				<li><a class="menu_lateral" href="http://contabilidadenovak.com.br/#quem_somos">Atualize suas informações na receita</a></li>
			</ul>
		</div>
		<div class="site" onClick="fecharMenu()">	
			<div class="row" style="padding-left:7px;padding-right:7px;margin-top:65px">
				<div class="base_icones_menu col-sm-4">
      					<div class="text-center icones_menu">
      						<div class="base_imagem_icone"><img src="images/documentos 136x106(1).png" style="height:60%"/></div>
      						<div class="titulo_icones_menu text-center">DOCUMENTOS</div>
      					</div>
    				</div>
    				<div class="base_icones_menu col-sm-4">
      					<div class="text-center icones_menu">
      						<div class="base_imagem_icone"><img src="images/recursos humanos 134x131.png" style="height:60%"/></div>
      						<div class="titulo_icones_menu text-center">RECURSOS HUMANOS</div>
      					</div>     
    				</div>
    				<div id="tamanho_atendimento" class="base_icones_menu col-sm-4">
      					<div class="text-center icones_menu">
      						<div class="base_imagem_icone"><img src="images/indicadores 110x138.png" style="height:60%"/></div>
      						<div class="titulo_icones_menu text-center">INDICADORES</div>
      					</div>      
    				</div>
			</div>
			<div class="row" style="padding-left:7px;padding-right:7px">
				<div class="base_icones_menu col-sm-4">
      					<div class="text-center icones_menu">
      						<div class="base_imagem_icone"><img src="images/configurações 135x135.png" style="height:60%"/></div>
      						<div class="titulo_icones_menu text-center">CONFIGURAÇÕES</div>
      					</div>      					
    				</div>
    				<div class="base_icones_menu col-sm-4">
      					<div class="text-center icones_menu">
      						<div class="base_imagem_icone"><img src="images/agenda 134x135.png" style="height:60%"/></div>
      						<div class="titulo_icones_menu text-center">AGENDAS</div>
      					</div>      
    				</div>
    				<div class="base_icones_menu col-sm-4">
      					<div class="text-center icones_menu">
      						<div class="base_imagem_icone"><img src="images/duvidas 128x128.png" style="height:60%"/></div>
      						<div class="titulo_icones_menu text-center">DÚVIDAS</div>
      					</div>     
    				</div>
			</div>
			<div class="atendimento_online" ><img src="images/icone novak chat 29x29.png" style="height:29px;height:29px"/>atendimento online</div>
		</div>
	</body>
	<script type="text/javascript">
		function atualizarAlturaIcones(){
			var alturaJanela = $(window).height();
			var icones_menu = document.getElementsByClassName("base_icones_menu");
			var atendimentoOnline = document.getElementsByClassName("atendimento_online");
			var alturaInutil = 120;
			var alturaIconeMenu = alturaJanela - alturaInutil;
			var divsTeste = Array.prototype.filter.call(icones_menu, function(i) {
			    i.style.height = (alturaIconeMenu/2) + "px";
			});
		}
		atualizarAlturaIcones();
		document.getElementById("escolher_empresa_base").style.height = "0px";
		function abrirMenuEmpresas(botao_escolher){
			var menu_escolher = document.getElementById("escolher_empresa_base");
			var empresas = document.getElementsByClassName("escolher_empresa_base_2");
			if(menu_escolher.style.height == "0px"){
				menu_escolher.style.height = (empresas.length * 36) + "px";
				document.getElementById("icone_escolher_empresa").style.width = "19px";
				document.getElementById("icone_escolher_empresa").style.height = "14px";
				document.getElementById("icone_escolher_empresa").style.marginTop = "23px";
				document.getElementById("icone_escolher_empresa").style.marginLeft = "14.5px";
				document.getElementById("nome_empresa").style.marginLeft = "7.5px";
				botao_escolher.src = "images/seta escolher empresa 16x11.png";
			}else{
				document.getElementById("icone_escolher_empresa").style.width = "14px";
				document.getElementById("icone_escolher_empresa").style.height = "19px";
				document.getElementById("icone_escolher_empresa").style.marginTop = "20.5px";
				document.getElementById("icone_escolher_empresa").style.marginLeft = "17px";
				document.getElementById("nome_empresa").style.marginLeft = "10px";
				menu_escolher.style.height = "0px";
				botao_escolher.src = "images/seta empresa escolhida 11x16.png";
			}
		}
		function escolherEmpresa(empresa){
			id_empresa = empresa.id.replace("empresa_","");
			sendPost('menu_principal.php',{id_empresa: id_empresa});
		}
		document.getElementById("menu2").style.width = "0px";
		function abrirMensagens(){
			//var atendimentoOnline = document.getElementsByClassName("atendimento_online");	
			if (document.getElementById("menu2").style.width == "0px"){
				//atendimentoOnline[0].style.marginRight = "240px";
				document.getElementById("menu2").style.width = "240px";
				var larguraMenu = document.getElementById("menu2").clientWidth;
				var largura = $(window).width(); //Largura da janela
				
				if (largura >= 1200) {
					var padding = 810 - (largura/2);
					document.getElementById("menu_sup").style.paddingLeft = padding + "px";
				} else if (largura < 1200 && largura >= 992) {
				    var padding = 710 - (largura/2);
					document.getElementById("menu_sup").style.paddingLeft = padding + "px";
				} else if (largura < 992 && largura >= 768) {
				    var padding = 610 - (largura/2);
					document.getElementById("menu_sup").style.paddingLeft = padding + "px";
				} else {
				    if(largura <= 600){
						document.getElementById("menu_sup").style.paddingLeft = "180px";
						document.getElementById("menu2").style.width = "180px";
					}else{
				    	document.getElementById("menu_sup").style.paddingLeft = "240px";
					}
				}
				
			}else{
				var larguraMenu = document.getElementById("menu2").clientWidth;
				document.getElementById("menu2").style.width = "0px";
				document.getElementById("menu_sup").style.paddingLeft = "40px";

			}	
			if (document.getElementById("menu").style.width != "0px"){
				document.getElementById("menu").style.width = "0px";
				document.getElementById("menu_sup").style.paddingRight = "15px";
				document.getElementById("botao_mobile").src = "images/ÍCONE MENU NOVO.PNG"
			}
		}	
		function fecharMensagens(){
			if (document.getElementById("menu2").style.width != "0px"){
				document.getElementById("menu2").style.width = "0px";
				document.getElementById("menu_sup").style.paddingLeft = "40px";
			}		
		}		
		document.getElementById("menu").style.width = "0px";
		function abrirMenu(){
			//var atendimentoOnline = document.getElementsByClassName("atendimento_online");	
			if (document.getElementById("menu").style.width == "0px"){
				//atendimentoOnline[0].style.marginRight = "240px";
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
			if (document.getElementById("menu2").style.width != "0px"){
				document.getElementById("menu2").style.width = "0px";
				document.getElementById("mensagens").style.marginLeft = "40px";
			}	

		}
		function fecharMenu(){
			if (document.getElementById("menu").style.width != "0px"){
				document.getElementById("menu").style.width = "0px";
				document.getElementById("menu_sup").style.paddingRight = "15px";
				document.getElementById("botao_mobile").src = "images/ÍCONE MENU NOVO.PNG"
			}
			fecharMensagens()
		}
		window.addEventListener("resize", atualizarAlturaIcones);
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

