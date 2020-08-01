<?php
require 'config.php';

session_save_path('minhas_sessions');
ini_set('session.gc_maxlifetime', '28800');
session_start();

spl_autoload_register(function($class){

	if(file_exists('controllers/'.$class.'.php')) {
		require 'controllers/'.$class.'.php';	
	} else if(file_exists('models/'.$class.'.php')) {
		require 'models/'.$class.'.php';	
	} else if(file_exists('core/'.$class.'.php')) {
		require 'core/'.$class.'.php';	
	}

});

$core = new Core();
$core->run();

?>