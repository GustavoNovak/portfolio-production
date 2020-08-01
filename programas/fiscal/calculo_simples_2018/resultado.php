<?php
session_start();

if(isset($_SESSION['id']) && !empty($_SESSION['id'])){

} else {
	header("Location: login.php");
}

if(isset($_SESSION['categorias']) && !empty($_SESSION['categorias'])){

} else {
	header("Location: categorias.php");
}

if(isset($_SESSION['estados']) && !empty($_SESSION['estados'])){

} else {
	header("Location: parametros.php");
}

if(isset($_SESSION['competencia']) && !empty($_SESSION['competencia'])){

} else {
	header("Location: parametros.php");
}

if(isset($_SESSION['faturamento']) && !empty($_SESSION['faturamento'])){

} else {
	header("Location: faturamentos_anteriores.php");
}

if(isset($_POST['faturamento_categoria']) && !empty($_POST['faturamento_categoria'])){
	$_SESSION['faturamento_categoria'] = $_POST['faturamento_categoria'];
} else {
	if(isset($_SESSION['faturamento_categoria']) && !empty($_SESSION['faturamento_categoria'])){
	} else {
		header("Location: faturamentos_categoria.php");
	}
}

//if(isset($_SESSION['cnpj']) && !empty($_SESSION['cnpj'])){
	//header("Location: parametros.php");
//}
					
?>

<html>
<head>
	
	<link rel="icon" href="assets/images/logo novak.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="assets/images/logo novak.ico" type="image/x-icon" />	
	
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width,user-scalable=0"/>

	<link rel="stylesheet" type="text/css" href="assets/css/style_resultado.css" />

	<title>Sistema Novakcontabil</title>
	<script type="text/javascript" src="assets/js/codigo.js"></script>
	<script type="text/javascript">
	window.onload = function(){
		document.querySelector(".menuMobile").addEventListener("click",function(){
			if(document.querySelector(".menu nav ul").style.display == 'flex'){
				document.querySelector("header").style.height = 'auto';
				document.querySelector(".menu nav ul").style.display = 'none';
			} else {
				document.querySelector(".menu nav ul").style.display = 'flex';
				document.querySelector("header").style.height = '400px';
				document.querySelector(".menu").style.height = '137px';
			}
		});
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
					<li><a href="parametros.php">Parâmetros</a></li>
					<li><a href="fator_r_folha.php">Folha</a></li>
					<li><a href="faturamentos_anteriores.php">Faturamentos anteriores</a></li>
					<li ><a href="faturamentos_categoria.php">Faturamento por categoria</a></li>
					<li class="active"><a href="">Resultado</a></li>
				</ul>
			</nav>
		</div>
	</div>
</header>
<section id="banner">
	<div class="container column">
		<div class="resultado">
			
			<?php

				//Definindo funções
					function string_numerico($v){
						return floatval(str_replace(",", ".",str_replace(".", "", $v)));
					}
				//-----------------

				//Registrando faturamento na competância

					$dsn = "mysql:dbname=novak049_simples_2018;host=localhost";
					$dbuser = "novak049_program";
					$dbpass = "Rbagente1";

					try{
						$db = new PDO($dsn,$dbuser,$dbpass);
						
						$data = $_SESSION['competencia'];
						
						$valor = 0.0;
						foreach($_SESSION['faturamento_categoria'] as $valor_categoria){

							$valor = $valor + string_numerico($valor_categoria);

						}

							$sql = "SELECT * FROM registro_faturamento WHERE id_usuario='".$_SESSION['id']."' AND cnpj='".$_SESSION['cnpj']."' AND data='".$data."'";
							$sql = $db->query($sql);

							if($sql->rowCount() == 1){
								$sql = "UPDATE registro_faturamento SET faturamento='".$valor."' WHERE id_usuario='".$_SESSION['id']."' AND cnpj='".$_SESSION['cnpj']."' AND data='".$data."'";
								$sql = $db->query($sql);	
							} else {
								$sql = "INSERT INTO registro_faturamento SET  id_usuario='".$_SESSION['id']."', cnpj='".$_SESSION['cnpj']."', faturamento='".$valor."', data='".$data."'";
								$sql = $db->query($sql);					
							}

					} catch(PDOexception $e){
						echo "Falhou: ".$e->getMessage();	
					}

				//-----------------

				$dsn = "mysql:dbname=novak049_simples_2018;host=localhost";
				$dbuser = "novak049_program";
				$dbpass = "Rbagente1";

				try{
					
					$db = new PDO($dsn,$dbuser,$dbpass);

					$estado = $_SESSION['estados'];
					$faturamento = $_SESSION['faturamento'];
					$faturamento_categoria = $_SESSION['faturamento_categoria'];

					//Cálculo RBT12
						$RBT12 = 0.0;
						foreach($_SESSION['faturamento'] as $faturamento){
								$faturamento = string_numerico($faturamento);

								$RBT12 = $RBT12 + $faturamento;
						}
					//---------------
					//Encontrando a faixa
						$sql = "SELECT * FROM faixas";
						$sql = $db->query($sql);

						$faixas = $sql->fetchAll();

						$faixa = 0;
						foreach ($faixas as $faixa_aux) {
							if ( $RBT12 >= $faixa_aux['limite_inferior'] && $RBT12 <= $faixa_aux['limite_superior']){
								$faixa = $faixa_aux['faixa'];
							} 
						}
						if($faixa == 0){
							echo "O RBT12 ficou fora de todas as faixas!";
						}
					//-------------
						echo "Parâmetros gerais:<br/><br/>";
						echo "<table>";
						echo "	<tr>";
						echo "		<td style='text-align:center;padding:10px;font-size:14px' >RBT12</td>";
						echo "		<td style='text-align:center;padding:10px;font-size:14px'>Faixa</td>";
						
						if(isset($_SESSION['atividade']) && !empty($_SESSION['atividade'])){
							$calcular_folha = false;
							foreach($_SESSION['atividade'] as $atividade_id){
								if($atividade_id != 17){
									$calcular_folha = true;
								}
							}
							if($calcular_folha == true){
								echo "		<td style='text-align:center;padding:10px;font-size:14px'>Folha</td>";
							}
						}
						
						echo "	</tr>";
						echo "	<tr>";
						echo "		<td style='text-align:center;padding:10px;font-size:14px'>R$ ".number_format($RBT12,'2',',','.')."</td>";
						echo "		<td style='text-align:center;padding:10px;font-size:14px'>".$faixa."</td>";
						
						if(isset($_SESSION['atividade']) && !empty($_SESSION['atividade'])){
							$calcular_folha = false;
							foreach($_SESSION['atividade'] as $atividade_id){
								if($atividade_id != 17){
									$calcular_folha = true;
								}
							}
							if($calcular_folha == true){

								//Cálculo de soma da folha
								$soma_folha = 0.0;
								foreach($_SESSION['folha'] as $folha){
										$folha = string_numerico($folha);

										$soma_folha = $soma_folha + $folha;
								}
								//---------------

								echo "		<td style='text-align:center;padding:10px;font-size:14px'>R$ ".number_format($soma_folha,'2',',','.')."</td>";
							}
						}

						echo "	</tr>";
						echo " </table>";

					$simples_global = 0.0;

					$i = 0;
					foreach ($_SESSION['categorias'] as $categoria) {
						
						$sql = "SELECT * FROM categorias WHERE id='".$categoria."'";
						$sql = $db->query($sql);

						$categoria_parametros = $sql->fetch();

						echo "<div class='subresultado'>";
						echo 	"Anexo ".$categoria_parametros['anexo']." - ".$_SESSION['nome_atividade'][$i].":"."<br/><br/>";
						echo "</div>";

						$anexo = $categoria_parametros['anexo'];

						//Cálculos para nacional

							$sql = "SELECT * FROM anexos_nacionais WHERE id_anexo='".$anexo."' AND faixa='".$faixa."'";
							$sql = $db->query($sql);

							$parametros_anexo = $sql->fetch();
										
							$aliq_n = $parametros_anexo['aliquota_nominal'];
							$VD = $parametros_anexo['valor_deduzir'];
							$aliq = array(
									"irpj" => $parametros_anexo['irpj'],
									"csll" => $parametros_anexo['csll'],
									"cofins" => $parametros_anexo['cofins'],
									"pis" => $parametros_anexo['pis'],
									"cpp" => $parametros_anexo['cpp'],
									"ipi" => $parametros_anexo['ipi'],
									"iss" => $parametros_anexo['iss'],
									"icms" => $parametros_anexo['icms']
								);

							$simples_tot = ($RBT12*$aliq_n - $VD)/$RBT12;

							$sql = "SELECT irpj, csll, cofins, pis, cpp, ipi, iss, icms FROM categorias WHERE id='".$categoria_parametros['id']."'";
							$sql = $db->query($sql);

							$isencao_categoria = $sql->fetch();

							$soma_aliq = 0.0;
							foreach ($isencao_categoria as $key => $value) {
								if (!is_numeric($key)){	
									if($value == 0){
										$soma_aliq = $soma_aliq + $parametros_anexo[$key];
									}
								}
							}

							$simples_par = $simples_tot*(1-$soma_aliq);

						//-----------------------------

						//Cálculo com fator R
						if(isset($_SESSION['atividade'][$categoria]) && !empty($_SESSION['atividade'][$categoria]) && isset($_SESSION['folha']) && !empty($_SESSION['folha'])){
						if($_SESSION['atividade'][$categoria] != 6){
							//Cálculo do fator R
								//Cálculo de soma da folha
								$soma_folha = 0.0;
								foreach($_SESSION['folha'] as $folha){
										$folha = string_numerico($folha);

										$soma_folha = $soma_folha + $folha;
								}
								//---------------

								$fator_R = $soma_folha/$RBT12;
							//-----------------
							if($fator_R >= 0.28){
								
								if($anexo == 3){
									$anexo_novo = 5;
								}
								if($anexo == 5){
									$anexo_novo = 3;
								}

								$sql = "SELECT * FROM anexos_nacionais WHERE id_anexo='".$anexo_novo."' AND faixa='".$faixa."'";
								$sql = $db->query($sql);

								$parametros_anexo_novo = $sql->fetch();
								
								$aliq_n_novo = $parametros_anexo_novo['aliquota_nominal'];
								$VD_novo = $parametros_anexo_novo['valor_deduzir'];	
								$aliq_novo = array(
										"irpj" => $parametros_anexo_novo['irpj'],
										"csll" => $parametros_anexo_novo['csll'],
										"cofins" => $parametros_anexo_novo['cofins'],
										"pis" => $parametros_anexo_novo['pis'],
										"cpp" => $parametros_anexo_novo['cpp'],
										"ipi" => $parametros_anexo_novo['ipi'],
										"iss" => $parametros_anexo_novo['iss'],
										"icms" => $parametros_anexo_novo['icms']
									);

								$simples_tot_novo = ($RBT12*$aliq_n_novo- $VD_novo)/$RBT12;

								$sql = "SELECT irpj, csll, cofins, pis, cpp, ipi, iss, icms FROM categorias WHERE id='".$categoria_parametros['id']."'";
								$sql = $db->query($sql);

								$soma_aliq_novo = 0.0;
								foreach ($isencao_categoria as $key => $value) {
									if (!is_numeric($key)){	
										if($value == 0){
											$soma_aliq_novo = $soma_aliq_novo + $parametros_anexo_novo[$key];
										}
									}
								}

								$simples_par_novo = $simples_tot_novo*(1-$soma_aliq_novo);

								echo"<table style='width:100%''>";
								echo"	<tr class='celula_titulo'>";
								echo"	    <th>IRPJ</th>";
								echo"	    <th>CSLL</th>"; 
								echo"	    <th>COFINS</th>";
								echo"	    <th>PIS</th>";
								echo"	    <th>CPP</th>";
								echo"	    <th>IPI</th>";
								echo"	    <th>ISS</th>";
								echo"	    <th>ICMS</th>";
								echo"	  </tr>";
								echo"	  <tr>";
								
								foreach ($isencao_categoria as $key => $value) {
									if (!is_numeric($key)){	
										if($value == 0){
								echo"	    <th class='celula_valor'>0,00%</th>";
										}else{
											$aliq_n_i = number_format($simples_tot_novo*$parametros_anexo_novo[$key]*100,'2',',',' ');


								echo"	    <th class='celula_valor'>".$aliq_n_i."%</th>";			
										}
									}
								}



								echo"	  </tr>";
								echo"	  <tr>";
								$faturamento_categoria[$i] = str_replace(",",".",str_replace(".", "", $faturamento_categoria[$i]));
								foreach ($isencao_categoria as $key => $value) {
									if (!is_numeric($key)){	
										if($value == 0){
								echo"	    <th class='celula_valor'>R$ 0,00"."</th>";
										}else{
											$aliq_n_i = $simples_tot_novo*$parametros_anexo_novo[$key];
											$valor = number_format($aliq_n_i*$faturamento_categoria[$i],'2',',','.');

								echo"	    <th class='celula_valor'>R$ ".$valor."</th>";			
										}
									}
								}

								echo"  	</tr>";
								echo" </table><br/>";

								echo "<div style='font-size:12px'>Fator R: ";
								echo number_format($fator_R*100,'2',',',' ')."%</div><br/>";

								echo "<div style='font-size:12px'>Alíquota efetiva: ";
								echo number_format($simples_par_novo*100,'2',',',' ')."%</div><br/>";

								echo "<div style='font-size:12px'>Valor de simples: ";
								echo "R$ ".number_format($simples_par_novo*$faturamento_categoria[$i],'2',',','.')."</div><br/>";

								$simples_global = $simples_global + $simples_par_novo*$faturamento_categoria[$i];

								goto pular;

							}
						}
						}
						//--------------------

						$red_icms = 0.0;
						$tem_reducao_icms = false;
						if(($anexo == 1 || $anexo == 2) && $estado == 1){
						//Cálculos redução de icms para Paraná

							$sql = "SELECT * FROM anexos_parana WHERE id_anexo='".$anexo."' AND faixa='".$faixa."'";
							$sql = $db->query($sql);

							$resultado = $sql->fetch();

							$aliq_n_pr = $resultado['aliquota_nominal'];
							$VD_pr = $resultado['valor_deduzir'];

							$red_icms = (1-(($RBT12*$aliq_n_pr - $VD_pr)/($RBT12*$aliq_n - $VD))*(1/$aliq['icms']));

							$aliq_icms_pr_nova = $simples_tot*$aliq['icms']*(1-$red_icms);

							$sql = "SELECT aliquota FROM aliquotas_icms_antigo_pr WHERE limite_inferior <= '".$RBT12."' AND limite_superior >= '".$RBT12."'";
							$sql = $db->query($sql);	

							if($sql->rowCount() > 0){

								$aliq_icms_pr_antiga = $sql->fetch();

							}

							if($aliq_icms_pr_nova > $aliq_icms_pr_antiga['aliquota']*1.2){
								$red_icms = (1 - ($aliq_icms_pr_antiga['aliquota']*1.2/($simples_tot*$aliq['icms'])));
							}

							$tem_reducao_icms = true;

						//--------------------
						}
						$tem_limitacao_iss = false;
						
						if( ($anexo == 4 && $faixa == 5 && $simples_tot > 0.125) || ($anexo == 3 && $faixa == 5 && $simples_tot > 0.1492537) ){
							$sql = "SELECT * FROM anexos_nacionais WHERE id_anexo='".$anexo."' AND faixa='7'";
							$sql = $db->query($sql);

							$parametros_anexo = $sql->fetch();	

							$aliq_limitacao_iss = array(
									"irpj" => $parametros_anexo['irpj'],
									"csll" => $parametros_anexo['csll'],
									"cofins" => $parametros_anexo['cofins'],
									"pis" => $parametros_anexo['pis'],
									"cpp" => $parametros_anexo['cpp'],
									"ipi" => $parametros_anexo['ipi'],
									"iss" => $parametros_anexo['iss'],
									"icms" => $parametros_anexo['icms']
								);

							echo"<table style='width:100%''>";
							echo"	<tr class='celula_titulo'>";
							echo"	    <th>IRPJ</th>";
							echo"	    <th>CSLL</th>"; 
							echo"	    <th>COFINS</th>";
							echo"	    <th>PIS</th>";
							echo"	    <th>CPP</th>";
							echo"	    <th>IPI</th>";
							echo"	    <th>ISS</th>";
							echo"	    <th>ICMS</th>";
							echo"	  </tr>";
							echo"	  <tr>";
							
							foreach ($isencao_categoria as $key => $value) {
								if (!is_numeric($key)){	
									if($value == 0){
							echo"	    <th class='celula_valor'>0,00%</th>";
									}else{
										if($key == "iss"){
											$aliq_n_i = number_format(5.0,'2',',',' ');
										}else{
											$aliq_n_i = number_format(($simples_tot-0.05)*$parametros_anexo[$key]*100,'2',',',' ');
										}

							echo"	    <th class='celula_valor'>".$aliq_n_i."%</th>";			
									}
								}
							}

							echo"	  </tr>";
							echo"	  <tr>";

							foreach ($isencao_categoria as $key => $value) {
								if (!is_numeric($key)){	
									if($value == 0){
							echo"	    <th class='celula_valor'>R$ 0,00"."</th>";
									}else{
										$aliq_n_i = ($simples_tot-0.05)*$parametros_anexo[$key];
										$faturamento_categoria[$i] = str_replace(",",".",str_replace(".", "", $faturamento_categoria[$i]));
										$valor = number_format($aliq_n_i*$faturamento_categoria[$i],'2',',','.');

							echo"	    <th class='celula_valor'>R$ ".$valor."</th>";			
									}
								}
							}

							echo"  	</tr>";
							echo" </table><br/>";

							if ($tem_reducao_icms == true){
								echo "<div style='font-size:12px'>Redução de ICMS: ";
								if($aliq['icms'] == 0){
									echo "Isento</div><br/>";	
								}else{
									echo number_format($red_icms*100,'2',',',' ')."%</div><br/>";
								}
							}

							echo "<div style='font-size:12px'>Alíquota efetiva: ";
							echo number_format($simples_par*100,'2',',',' ')."%</div><br/>";

							echo "<div style='font-size:12px'>Valor de simples: ";
							echo number_format($simples_par*$faturamento_categoria[$i],'2',',',' ')."%</div><br/>";

							$simples_global = $simples_global + $simples_par*$faturamento_categoria[$i];

							goto pular;

						}
		
						if ($tem_reducao_icms == true){

							$aliq['icms'] = $aliq['icms']*(1-$red_icms);
							if ($isencao_categoria['icms'] != 0){

								$simples_par = ($simples_par - $simples_par*$parametros_anexo['icms'])+($simples_par*$aliq['icms']);

							}
							$parametros_anexo['icms'] = $aliq['icms'];


						}
						echo"<table style='width:100%''>";
						echo"	<tr class='celula_titulo'>";
						echo"	    <th>IRPJ</th>";
						echo"	    <th>CSLL</th>"; 
						echo"	    <th>COFINS</th>";
						echo"	    <th>PIS</th>";
						echo"	    <th>CPP</th>";
						echo"	    <th>IPI</th>";
						echo"	    <th>ISS</th>";
						echo"	    <th>ICMS</th>";
						echo"	  </tr>";
						echo"	  <tr>";
						
						foreach ($isencao_categoria as $key => $value) {
							if (!is_numeric($key)){	
								if($value == 0){
						echo"	    <th class='celula_valor'>0,00%</th>";
								}else{
									$aliq_n_i = number_format($simples_tot*$parametros_anexo[$key]*100,'2',',',' ');


						echo"	    <th class='celula_valor'>".$aliq_n_i."%</th>";			
								}
							}
						}



						echo"	  </tr>";
						echo"	  <tr>";
						$faturamento_categoria[$i] = str_replace(",",".",str_replace(".", "", $faturamento_categoria[$i]));
						foreach ($isencao_categoria as $key => $value) {
							if (!is_numeric($key)){	
								if($value == 0){
						echo"	    <th class='celula_valor'>R$ 0,00"."</th>";
								}else{
									$aliq_n_i = $simples_tot*$parametros_anexo[$key];
									$valor = number_format($aliq_n_i*$faturamento_categoria[$i],'2',',','.');

						echo"	    <th class='celula_valor'>R$ ".$valor."</th>";			
								}
							}
						}

						echo"  	</tr>";
						echo" </table><br/>";

						if ($tem_reducao_icms == true && $isencao_categoria['icms'] != 0){
							echo "<div style='font-size:12px'>Redução de ICMS: ";
							if($aliq['icms'] == 0){
								echo "Isento</div><br/>";	
							}else{
								echo number_format($red_icms*100,'2',',',' ')."%</div><br/>";
							}
						}

						echo "<div style='font-size:12px'>Alíquota efetiva: ";
						echo number_format($simples_par*100,'2',',',' ')."%</div><br/>";

						echo "<div style='font-size:12px'>Valor de simples: ";
						echo "R$ ".number_format($simples_par*$faturamento_categoria[$i],'2',',','.')."</div><br/>";

						$simples_global = $simples_global + $simples_par*$faturamento_categoria[$i];

						pular:

						$i++;

			}

					echo "<br/><br/>";
					echo "<div style='font-size:14px;font-weight:bold;'>Valor total de simples: ";
					echo "R$ ".number_format($simples_global,'2',',','.')."</div><br/>";

				} catch(PDOexception $e){
					echo "Falhou: ".$e->getMessage();
				}	

			?>

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

