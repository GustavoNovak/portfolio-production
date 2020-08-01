<?php
session_start();
	$dsn = "mysql:dbname=novak049_sistemanovak;host=localhost";
	$dbuser = "novak049_program";
	$dbpass = "Rbagente1";
try{
	$pdo=new PDO($dsn,$dbuser,$dbpass);

}catch(PDOException $e){
	echo "ERRO: ".$e->getMessage();
}
?>