<?php
session_start();
require 'processos_php/config_sistemanovak.php';
require 'processos_php/config_sistemanovak_programas_simulacaosimples.php';
require 'processos_php/config_simples_2018_interno.php';
require 'classes/usuarios.class.php';
require 'classes/empresas.class.php';

if(isset($_SESSION['id_usuario']) && !empty($_SESSION['id_usuario'])){
	if(isset($_POST['id_empresa']) && !empty($_POST['id_empresa'])){
		$_SESSION['id_empresa'] = $_POST['id_empresa'];
		unset($_POST['id_empresa']);
		header("Location: parametros.php");
		exit;
	}else{
		if(isset($_SESSION['id_empresa']) && !empty($_SESSION['id_empresa'])){
		
		}else{
			header("Location: login.php");
			exit;
		}
	}
}else{
	header("Location: login.php");
	exit;
}

if(isset($_SESSION['competencia']) && !empty($_SESSION['competencia'])){

} else {
	header("Location: parametros.php");
	exit;
}

if(isset($_SESSION['nova_folha']) && !empty($_SESSION['nova_folha'])){

} else {
	header("Location: parametros.php");
	exit;
}

if(isset($_SESSION['categorias']) && !empty($_SESSION['categorias'])){

} else {
	header("Location: faturamentos_anteriores.php");
	exit;
}

if(isset($_SESSION['fator_r']) && !empty($_SESSION['fator_r'])){

} else {
	header("Location: faturamentos_anteriores.php");
	exit;
}

if(isset($_SESSION['faturamento']) && !empty($_SESSION['faturamento'])){

} else {
	header("Location: faturamentos_anteriores.php");
	exit;
}

if(isset($_SESSION['folha']) && !empty($_SESSION['folha'])){

} else {
	if(!in_array(1, $_SESSION['fator_r'])) {
		
	} else {
		header("Location: folha.php");
		exit;
	}
}

if(isset($_POST['faturamento_categoria']) && !empty($_POST['faturamento_categoria'])){
	$_SESSION['faturamento_categoria'] = $_POST['faturamento_categoria'];
} else {
	if(isset($_SESSION['faturamento_categoria']) && !empty($_SESSION['faturamento_categoria'])){
	} else {
		header("Location: faturamentos_categoria.php");
	}
}

$empresa = new EmpresasSimples($_SESSION['id_empresa']);
					
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
					<li><a href="faturamentos_anteriores.php">Faturamentos anteriores</a></li>
					<li><a href="folha.php">Folhas anteriores</a></li>
					<li><a href="faturamentos_categoria.php">Faturamento por categoria</a></li>
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

			function calcularTributos($competencia,$empresa,$categorias,$fator_r,$faturamentos,$folhas,$faturamento_categoria,$nova_folha){
					require 'processos_php/config_sistemanovak.php';
					require 'processos_php/config_sistemanovak_programas_simulacaosimples.php';
					require 'processos_php/config_simples_2018_interno.php';
					$tributos_sem_simples = (0.08 + (1/12) + (1/12) + (1/36) + (1/24))*$nova_folha;

				try{
					
					$db = $pdo_simples_2018_interno;

					$estado = $empresa->getEstadoBrasil();
					//$faturamento = $_SESSION['faturamento'];
					//$faturamento_categoria = $_SESSION['faturamento_categoria'];

					//Cálculo RBT12
						$RBT12 = 0.0;
						foreach($faturamentos as $faturamento){
								$aux = string_numerico($faturamento);
								$RBT12 = $RBT12 + (float)$aux;
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
							
						}
					//-------------

					// Potando tabela

					$db = $pdo_simples_2018_interno;
					
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
						echo "<h3>".$competencia.":</h3><br/>";
						echo "Parâmetros gerais:<br/><br/>";
						echo "<table>";
						echo "	<tr>";
						echo "		<td style='text-align:center;padding:10px;font-size:14px' >RBT12</td>";
						echo "		<td style='text-align:center;padding:10px;font-size:14px'>Faixa</td>";
						
						if(in_array(1, $fator_r)) {
							echo "		<td style='text-align:center;padding:10px;font-size:14px'>Folha</td>";
						}
						
						echo "      <td style='text-align:center;padding:10px;font-size:14px'>Tributos</td>";
						echo "      <td style='text-align:center;padding:10px;font-size:14px'>Tributos/Faturamento</td>";
						echo "	</tr>";
						echo "	<tr>";
						echo "		<td style='text-align:center;padding:10px;font-size:14px'>R$ ".number_format($RBT12,'2',',','.')."</td>";
						echo "		<td style='text-align:center;padding:10px;font-size:14px'>".$faixa."</td>";
						
						if(in_array(1, $fator_r)) {
								//Cálculo de soma da folha
								$soma_folha = 0.0;
								foreach($folhas as $folha){
										$folha = $folha;
										$soma_folha = $soma_folha + $folha;
								}
								//---------------
								echo "		<td style='text-align:center;padding:10px;font-size:14px'>R$ ".number_format($soma_folha,'2',',','.')."</td>";
						}
					//--------------

						
						if(in_array(1, $fator_r)) {
								//Cálculo de soma da folha
								$soma_folha = 0.0;
								foreach($folhas as $folha){
										$folha = $folha;
										$soma_folha = $soma_folha + $folha;
								}
								//---------------
								
						}

					$simples_global = 0.0;

					$i = 0;
					foreach ($categorias as $categoria) {
						
						$sql = "SELECT * FROM categorias WHERE id='".$categoria."'";
						$sql = $db->query($sql);

						$categoria_parametros = $sql->fetch();
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
						if(in_array(1, $fator_r)) {
						if($fator_r[$categoria] == 1) {
							//Cálculo do fator R

								$fator_R = $soma_folha/$RBT12;

								echo "<small>Fator R: ".number_format(100*$fator_R,2,",","")." %</small><br/><br/>";
							//-----------------
								
								if($anexo == 3 && $fator_R < 0.28){
									$anexo_novo = 5;
								} else {
									if($anexo == 3) {
										$anexo_novo = 3;
									}
								}
								if($anexo == 5 && $fator_R > 0.28){
									$anexo_novo = 3;
								} else {
									if($anexo == 5) {
										$anexo_novo = 5;
									}	
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
								$aux1 = string_numerico($faturamento_categoria[$i]);
								$aux2 = $simples_par_novo;

								$simples_global = $simples_global + $aux1*$aux2;

								goto pular;
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

							$aux1 = string_numerico($faturamento_categoria[$i]);
							$aux2 = $simples_par;

							$simples_global = $simples_global + $aux1*$aux2;
							goto pular;

						}
		
						if ($tem_reducao_icms == true){

							$aliq['icms'] = $aliq['icms']*(1-$red_icms);
							if ($isencao_categoria['icms'] != 0){

								$simples_par = ($simples_par - $simples_par*$parametros_anexo['icms'])+($simples_par*$aliq['icms']);

							}
							$parametros_anexo['icms'] = $aliq['icms'];


						}

						$aux1 = string_numerico($faturamento_categoria[$i]);
						$aux2 = $simples_par;

						$simples_global = $simples_global + $aux1*$aux2;
						pular:

						$i++;
					}

					$tributo_total = $tributos_sem_simples + $simples_global;

				} catch(PDOexception $e){
					echo "Falhou: ".$e->getMessage();
				}
				$faturamento_total = 0.0;
				foreach ($faturamento_categoria as $value) {
					$faturamento_total = $faturamento_total + string_numerico($value);
				}
				$porcentagem_tributo = 100*$tributo_total/$faturamento_total;
				$tributo_total = "R$ ".number_format($tributo_total, 2, ',', '.');
				$porcentagem_tributo = number_format($porcentagem_tributo,2, ',', '.')." %";

				echo " 		<td style='text-align:center;padding:10px;font-size:14px'>".$tributo_total."</td>";
				echo " 		<td style='text-align:center;padding:10px;font-size:14px'>".$porcentagem_tributo."</td>";
				echo "	</tr>";
				echo " </table>";
				return array($competencia,$tributo_total,$porcentagem_tributo);

			}

			$faturamentos = $_SESSION['faturamento'];
			$folha = $_SESSION['folha'];
			$nova_folha = string_numerico($_SESSION['nova_folha']);
			$novo_faturamento = 0.0;
			foreach ($_SESSION['faturamento_categoria'] as $value) {
				$novo_faturamento = $novo_faturamento + string_numerico($value);
			}

			$mes_competencia = (int)substr($_SESSION['competencia'],0,2);
			$ano_competencia = (int)substr($_SESSION['competencia'],3,4);
			for($i=1;$i<=13;$i++){
				if($i > 1){
					if($mes_competencia == 12){
						$mes_competencia = 1;
						$ano_competencia = $ano_competencia + 1;
					} else {
						$mes_competencia = $mes_competencia + 1;
					}
					$faturamentos_antigo = $faturamentos;
					$folha_antigo = $folha;
					for($j=13-$i;$j>=1;$j--){
						
						if($j > 0){
							$faturamentos[$j-1] = $faturamentos_antigo[$j];
							$folha[$j-1] = $folha_antigo[$j];
						}
					}

					if($j >= 0){
						$faturamentos[13-$i] = $novo_faturamento;
					}
					if($j >= 0){
						$folha[13-$i] = $nova_folha;
					}
					if($mes_competencia < 10) {
						$mes = "0".$mes_competencia;
					} else {
						$mes = (string)$mes_competencia;
					}

					$resultado = calcularTributos($mes."/".$ano_competencia,$empresa,$_SESSION['categorias'],$_SESSION['fator_r'],$faturamentos,$folha,$_SESSION['faturamento_categoria'],$nova_folha);
					echo "<br/>";
				} else {
					$resultado = calcularTributos($_SESSION['competencia'],$empresa,$_SESSION['categorias'],$_SESSION['fator_r'],$faturamentos,$folha,$_SESSION['faturamento_categoria'],$nova_folha);
					echo "<br/>";
				}
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

