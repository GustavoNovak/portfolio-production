<?php
class Core {
	public function run() {

		/*
		controller padrão -> homeController
		action padrão -> index

		1 = controller
		2 = action
		3,4,5 = parâmetros
		*/
		$url = '/'.((isset($_GET['url']))?$_GET['url']:'');

		$params = array();

		if(!empty($url) && $url != '/') {
			$url = explode('/',$url);
			array_shift($url);

			$currentController = $url[0].'Controller';
			array_shift($url);

			if(isset($url[0]) && $url[0] != '') {
				$currentAction = $url[0];
				array_shift($url);
			} else {
				$currentAction = 'index';
			}

			if(count($url) > 0) {
				if($url[0] != '') {
					$params = $url;
				}
			} 

		} else {
			$currentController = 'homeController';
			$currentAction = 'index';
		}

		// $c = new GaleriaController();

		$c = new $currentController();

		call_user_func_array(array($c, $currentAction), $params);

	}
}
?>