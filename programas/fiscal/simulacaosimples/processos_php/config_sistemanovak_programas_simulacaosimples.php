<?php
	$dsn = "mysql:dbname=novak049_sistemanovak_programas_simulacaosimples;host=localhost";
	$dbuser = "novak049_program";
	$dbpass = "Rbagente1";
try{
	$pdo_programas_simulacaosimples = new PDO($dsn,$dbuser,$dbpass);

}catch(PDOException $e){
	echo "ERRO: ".$e->getMessage();
}
?>