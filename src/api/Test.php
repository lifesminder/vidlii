<?php
	namespace Vidlii\Vidlii\API;
	
	class Test extends \Vidlii\Vidlii\API {
		function index($args, $files) {
			return ["hey" => $args];
		}
	}
?>