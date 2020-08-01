<?php
session_start();

if(isset($_SESSION['id']) && !empty($_SESSION['id'])){

} else {
	header("Location: login.php");
}

if(isset($_SESSION['competencia']) && !empty($_SESSION['competencia'])){
	unset($_SESSION['competencia']);
} 

if(isset($_SESSION['estados']) && !empty($_SESSION['estados'])){
	unset($_SESSION['estados']);
}

if(isset($_SESSION['atividade']) && !empty($_SESSION['atividade'])){
	unset($_SESSION['atividade']);
}

if(isset($_SESSION['folha']) && !empty($_SESSION['folha'])){
	unset($_SESSION['folha']);
}

if(isset($_SESSION['cnpj']) && !empty($_SESSION['cnpj'])){
	unset($_SESSION['cnpj']);
}

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER['HTTP_USER_AGENT']);
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
	<script src="assets/js/jquery-1.2.6.pack.js" type="text/javascript"></script><script src="assets/js/jquery.maskedinput-1.1.4.pack.js" type="text/javascript" /></script>
	<?php
	
$eh_celular = isMobile();

if($eh_celular == 0){
	echo "	<script type='text/javascript'>
	$(document).ready(function(){	$('#cnpj').mask('99.999.999/9999-99');});
	$(document).ready(function(){	$('#competencia').mask('99/9999');});
	</script>";
}else{
	echo "	<script type='text/javascript'>
	function cnpj(v){
	    v=v.replace(/\D/g,'');                           //Remove tudo o que não é dígito
	    v=v.replace(/^(\d{2})(\d)/,'$1.$2');             //Coloca ponto entre o segundo e o terceiro dígitos
	    v=v.replace(/^(\d{2})\.(\d{3})(\d)/,'$1.$2.$3'); //Coloca ponto entre o quinto e o sexto dígitos
	    v=v.replace(/\.(\d{3})(\d)/,'.$1/$2');           //Coloca uma barra entre o oitavo e o nono dígitos
	    v=v.replace(/(\d{4})(\d)/,'$1-$2');              //Coloca um hífen depois do bloco de quatro dígitos
	    if (v.length > 18) {
	        v=v.substring(0,18);
	    }         
	    return v;
	}
	function comp(v){
    	    v=v.replace(/\D/g,'');                    //Remove tudo o que não é dígito
	    v=v.replace(/(\d{2})(\d)/,'$1/$2');
	    v=v.replace(/(\d{2})(\d{2})$/,'$1$2');
	    if (v.length > 7) {
	        v=v.substring(0,7);
	    } 
            return v;
        }
	
	function mascaracnpj(o,f){
	    v_obj=o;
	    v_fun=f;
	    setTimeout('execmascara()',1)
	}

	function execmascara(){
	    v_obj.value=cnpj(v_obj.value);
	}
	function mascaracomp(o,f){
	    v_obj=o;
	    v_fun=f;
	    setTimeout('execmascaracomp()',1)
	}

	function execmascaracomp(){
	    v_obj.value=comp(v_obj.value);
	}</script>";
}
	
	?>
	
        <script type="text/javascript">
	
	window.onload = function(){
		document.querySelector(".menuMobile").addEventListener("click",function(){
			if(document.querySelector(".menu nav ul").style.display == 'flex'){
				document.querySelector("header").style.height = 'auto';
				document.querySelector(".menu nav ul").style.display = 'none';
			} else {
				document.querySelector(".menu nav ul").style.display = 'flex';
				document.querySelector("header").style.height = '400px';
			}
		});
	}
	function isNumber(n) {
	    return !isNaN(parseFloat(n)) && isFinite(n);
	}

	function verificarCompetencia(){
		if (isNumber(event.key) == true | event.keyCode == 8 | event.keyCode == 13 | event.keyCode == 9 | event.keyCode == 193 ){
			
		} else {
			return false;
		}
	}
	</script>

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
					<li class="active"><a href="">Parâmetros</a></li>
					<li><a href="./">Folha</a></li>
					<li><a href="">Faturamentos anteriores</a></li>
					<li><a href="">Faturamento por categoria</a></li>
					<li><a href="">Resultado</a></li>
				</ul>
			</nav>
		</div>
	</div>
</header>
<section id="banner">
	<div class="container column">
		<div class="parametros">
			<form name="form" method="post" action="fator_r_folha.php">
				Competência:<br/><br/>
				<input type="text" id="competencia" name="competencia" autocomplete="off"  pattern="\d{2}/\d{4}" 
<?php

if($eh_celular != 0){

echo "onkeyup='mascaracomp(this,comp);' placeholder='99/9999'";

}
 
?> 
autofocus/><br/><br/>
				CNPJ da sua empresa:<br/><br/>
				<input id="cnpj" type="text" name="cnpj" autocomplete="off"  pattern="\d{2}.\d{3}.\d{3}/\d{4}-\d{2}" 
<?php 

if($eh_celular != 0){

echo "onkeyup='mascaracnpj(this,cnpj);' placeholder='99.999.999/9999-99'";

}

?>

/><br/><br/>
				<?php   
				if(isset($_SESSION['cnpj_invalido']) && !empty($_SESSION['cnpj_invalido'])){
					echo "<div style='font-size:12px;color:red;'>O CNPJ que você informou é inválido</div>";
					unset($_SESSION['cnpj_invalido']);
				} 	
				if(isset($_SESSION['erro_na_receita']) && !empty($_SESSION['erro_na_receita'])){
					echo "<div style='font-size:12px;color:red;'>Aguarde alguns instantes até o sistema responder</div>";
					unset($_SESSION['erro_na_receita']);
				} 
				if(isset($_SESSION['cnae_nao_encontrado']) && !empty($_SESSION['cnae_nao_encontrado'])){
					echo "<div style='font-size:12px;color:red;'>Não temos suas atividades em nosso banco de dados, iremos inseri-las dentro de 24 horas, tente amanhã novamente!</div>";
					unset($_SESSION['cnae_nao_encontrado']);
				} 
				?>
				<div class="botao_proximo">
					<input type="submit" value="Próximo" onclick="return validar()" autocomplete="off"/>
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
</html>

