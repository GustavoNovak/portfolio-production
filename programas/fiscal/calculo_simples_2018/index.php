<?php
session_start();

if(isset($_SESSION['id']) && !empty($_SESSION['id'])){
	header("Location: parametros.php");
} else {
	header("Location: login.php");
}

?>