<?php ob_start() ?>

<?php
session_start();

// Importando function curlExec()
function curlExec($url, $post = NULL, array $header = array()){
 
    //Inicia o cURL
    $ch = curl_init($url);
 
    //Pede o que retorne o resultado como string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
    //Envia cabeçalhos (Caso tenha)
    if(count($header) > 0) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
 
    //Envia post (Caso tenha)
    if($post !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
 
    //Ignora certificado SSL
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
    //Manda executar a requisição
    $data = curl_exec($ch);
 
    //Fecha a conexão para economizar recursos do servidor
    curl_close($ch);
 
    //Retorna o resultado da requisição
 
    return $data;
}

//---------------------------------

if(isset($_SESSION['id']) && !empty($_SESSION['id'])){

} else {
	header("Location: login.php");
}

if(isset($_SESSION['folha']) && !empty($_SESSION['folha'])){
	unset($_SESSION['folha']);
}

if(isset($_POST['competencia']) && !empty($_POST['competencia'])){
	$_SESSION['competencia'] = $_POST['competencia'];
} else {
	if(isset($_SESSION['competencia']) && !empty($_SESSION['competencia'])){
	}else{
		header("Location: parametros.php");
	}
}

if(isset($_POST['cnpj']) && !empty($_POST['cnpj'])){
	
	$_SESSION['cnpj'] = $_POST['cnpj'];
	$cnpj = str_replace(".", "",$_SESSION['cnpj']);
	$cnpj = str_replace("/", "",$cnpj);
	$cnpj = str_replace("-", "",$cnpj);
	$_SESSION['cnpj'] = $cnpj;
	$teste = curlExec("http://receitaws.com.br/v1/cnpj/".$cnpj);
	$obj = json_decode($teste);
	$estado = $obj->uf;
	$status = $obj->status;
	
	if($status != "ERROR"){
	
	if ($estado = "PR"){
		$_SESSION['estados'] = 1;		
	}else{
		$_SESSION['estados'] = 6;
	}
	
	$dsn = "mysql:dbname=novak049_simples_2018;host=localhost";
	$dbuser = "novak049_program";
	$dbpass = "Rbagente1";
	$tem_folha = false;
	try{
		$db = new PDO($dsn,$dbuser,$dbpass);
		
		$_SESSION['cnae'] = array();
		$_SESSION['nome_atividade'] = array();
		
		$atividade_principal = $obj->atividade_principal;
		foreach ($atividade_principal as $a) {
   			if($a->code != "00.00-0-00"){
	   			
	   			$_SESSION['cnae'][] = str_replace("-", "",str_replace(".", "",$a->code));
	   			$_SESSION['nome_atividade'][] = str_replace("-", "",str_replace(".", "",$a->text));
   			}
		}
		
		$atividades_secundarias = $obj->atividades_secundarias;

		foreach ($atividades_secundarias as $a) {
			if($a->code != "00.00-0-00"){
			
				$_SESSION['cnae'][] = str_replace("-", "",str_replace(".", "",$a->code));
				$_SESSION['nome_atividade'][] = str_replace("-", "",str_replace(".", "",$a->text));
			
			}
		}
		

		
		$_SESSION['categorias'] = array();
		foreach($_SESSION['cnae'] as $cnae){
			$sql = "SELECT * FROM cnae WHERE cnae='".$cnae."'";
			$sql = $db->query($sql);
	
			$resultado = $sql->fetch();
			
			if($sql->rowCount() > 0){			
				if($resultado['fator_r'] == 1){
					$tem_folha = true;
					$_SESSION['atividade'][$resultado['id_categoria']] = 16;	
				}else{
					$_SESSION['atividade'][$resultado['id_categoria']] = 17;	
				}
				
				$_SESSION['categorias'][]= $resultado['id_categoria'];			
			}else{
				$_SESSION['cnae_nao_encontrado'] = true;
				//enviar email de requisição de adição de CNAE no banco de dados!

				$para = "programador@novakcontabil.com.br";
				$assunto = "ADICIONAR CNAE: ".$cnae." NO BD!";
				$corpo = "Adicione o seguinte CNAE no banco de dados:    ".$cnae;
				$cabecalho = "From: suporte@contabilidadenovak.com.br"."\r\n".
							 "Reply-To: "."programador@novakcontabil.com.br"."\r\n".
							 "X-Mailer: PHP/".phpversion();
			
				mail($para, $assunto, $corpo, $cabecalho);
				
				//------------
				
				
				header("Location: parametros.php");
			}		
		}
		
		$sql = "SELECT * FROM cnae WHERE cnae='".$categoria."'";
		$sql = $db->query($sql);

		$resultado = $sql->fetch();
			

	} catch(PDOexception $e){
		echo "Falhou: ".$e->getMessage();
	}

	if($tem_folha == false){
	
		header("Location: faturamentos_anteriores.php");
	}
	
	}else{
		$message = $obj->message;
		if($message == "CNPJ inválido"){
			$_SESSION['cnpj_invalido'] = "true";
			header("Location: parametros.php");	
		}else{
			$_SESSION['erro_na_receita'] = "true";
			header("Location: parametros.php");				
		}
	}
	
	
	//-------------------
		
}else{
	if(isset($_SESSION['cnpj']) && !empty($_SESSION['cnpj'])){
	
	}else{
		header("Location: parametros.php");
	}	

}

?>

<html>
<head>
	
	<link rel="icon" href="assets/images/logo novak.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="assets/images/logo novak.ico" type="image/x-icon" />	
	
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width,user-scalable=0"/>

	<link rel="stylesheet" type="text/css" href="assets/css/style_faturamentos.css" />

	<title>Sistema Novakcontabil</title>

	<script type="text/javascript">
	window.onload = function(){
		document.querySelector(".menuMobile").addEventListener("click",function(){
			if(document.querySelector(".menu nav ul").style.display == 'flex'){
				document.querySelector("header").style.height = 'auto';
				document.querySelector(".menu nav ul").style.display = 'none';
			} else {
				document.querySelector(".menu nav ul").style.display = 'flex';
				document.querySelector("header").style.height = '350px';
				document.querySelector(".menu").style.height = '80px';
			}
		});
	}

	function isNumber(n) {
	    return !isNaN(parseFloat(n)) && isFinite(n);
	}

	function verificarMoeda(){
		if (isNumber(event.key) == true | event.key == "," | event.keyCode == 8 | event.keyCode == 13 | event.keyCode == 9){
			
		} else {
			return false;
		}
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
					<li class="active"><a href="./">Folha</a></li>
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
		<div class="faturamento">
			<form method="post" action="faturamentos_anteriores.php">
				
				<?php
					
	$dsn = "mysql:dbname=novak049_simples_2018;host=localhost";
	$dbuser = "novak049_program";
	$dbpass = "Rbagente1";

	try{
		$db = new PDO($dsn,$dbuser,$dbpass);
		$sql = "SELECT * FROM registro_folha WHERE id_usuario='".$_SESSION['id']."' AND cnpj='".$_SESSION['cnpj']."'";
		$sql = $db->query($sql);

		//if($sql->rowCount() > 0){

			$resultado = $sql->fetchAll();

			$mes_competencia = (int)substr($_SESSION['competencia'],0,2);
			$ano_competencia = (int)substr($_SESSION['competencia'],3,4);
			
			for($i=1;$i<=12;$i++){

				if($mes_competencia == 1){
					$mes_competencia = 12;
					$ano_competencia = $ano_competencia - 1;
				} else {
					$mes_competencia = $mes_competencia - 1;
				}

				$faturamento_ja_informado = false;
				foreach ($resultado as $faturamento){
					$mes = (int)substr($faturamento['data'],0,2);
					$ano = (int)substr($faturamento['data'],3,4);

					if($mes == $mes_competencia && $ano == $ano_competencia){
						$faturamento_ja_informado = true;
						$valor = $faturamento['folha'];
					}
				}

				if($faturamento_ja_informado == false){
					if($mes_competencia >= 10){
						echo $mes_competencia."/".$ano_competencia.":"." R$ <input type='text'  data-thousands='.' data-decimal=',' class='dinheiroo' name='folha[]' autocomplete='off' maxlength='90' required/><br/><br/>";
					}else{
						echo "0".$mes_competencia."/".$ano_competencia.":"." R$ <input type='text'  data-thousands='.' data-decimal=',' class='dinheiroo' name='folha[]' autocomplete='off' maxlength='90' required/><br/><br/>";
					}
				} else {
					if($mes_competencia >= 10){
						echo $mes_competencia."/".$ano_competencia.":"." R$ <input type='text'  data-thousands='.' data-decimal=',' class='dinheiroo' name='folha[]' value='".number_format($valor,2,',','.')."' autocomplete='off' maxlength='90' required/><br/><br/>";
					}else{
						echo "0".$mes_competencia."/".$ano_competencia.":"." R$ <input type='text'  data-thousands='.' data-decimal=',' class='dinheiroo' name='folha[]' value='".number_format($valor,2,',','.')."' autocomplete='off' maxlength='90' required/><br/><br/>";
					}
				}
			}

		//} else {
			//echo "Você ainda não registrou nenhum faturamento";
		//}



	} catch(PDOexception $e){
		echo "Falou: ".$e->getMessage();
	}
	
				?>
			
			<div class="botao_proximo">
				<input type="submit" value="Próximo" />
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
    $('input.dinheiroo').maskMoney({allowZero: true});
  })
</script>

</html>

