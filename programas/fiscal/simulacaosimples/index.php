<?php
if(isset($_SESSION['id_usuario']) && !empty($_SESSION['id_usuario']) && isset($_SESSION['id_empresa']) && !empty($_SESSION['id_empresa'])){
	header("Location: parametros.php");
}else{
	header("Location: login.php");
}
?>