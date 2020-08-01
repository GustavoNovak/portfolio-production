<?php
if(isset($_SESSION['id_usuario_areadocliente']) && !empty($_SESSION['id_usuario_areadocliente']) && isset($_SESSION['id_empresa_areadocliente']) && !empty($_SESSION['id_empresa_areadocliente'])){
	header("Location: menu_principal.php");
}else{
	header("Location: login.php");
}
?>