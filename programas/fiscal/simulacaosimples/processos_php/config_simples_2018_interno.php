<?php
	$dsn = "mysql:dbname=novak049_simples_2018_interno;host=localhost";
	$dbuser = "novak049_program";
	$dbpass = "Rbagente1";
try{
	$pdo_simples_2018_interno=new PDO($dsn,$dbuser,$dbpass);

}catch(PDOException $e){
	echo "ERRO: ".$e->getMessage();
}
?>