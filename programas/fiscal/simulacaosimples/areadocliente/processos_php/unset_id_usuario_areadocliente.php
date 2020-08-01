<?php
if(isset($_SESSION['id_usuario_areadocliente']) && !empty($_SESSION['id_usuario_areadocliente'])){
	unset($_SESSION['id_usuario_areadocliente']);
}
header("Location: ../login.php")
?>