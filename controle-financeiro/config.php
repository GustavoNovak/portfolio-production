<?php
require 'environment.php';

$config = array();

if(ENVIRONMENT == 'development') {
	define("BASE_URL", "http://localhost/controleFinanceiroCasa/");
	$config['dbname'] = 'controle_financeiro_casa';
	$config['host'] = 'localhost';
	$config['dbuser'] = 'root';
	$config['dbpass'] = 'root';
} else {
	define("BASE_URL", "http://sistemas.contabilidadenovak.com.br/controle-financeiro/");	
	$config['dbname'] = 'novak049_controle_financeiro';
	$config['host'] = 'localhost';
	$config['dbuser'] = 'novak049_program';
	$config['dbpass'] = 'Rbagente1';
}

global $db;
try {
	$db = new PDO("mysql:dbname=".$config['dbname'].";host=".$config['host'], $config['dbuser'], $config['dbpass']);
} catch(PDOException $e) {
	echo "ERRO: ".$e->getMessage();
	exit;
}


?>