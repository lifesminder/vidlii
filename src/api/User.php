<?php
	namespace Vidlii\Vidlii\API;
	
	class User extends \Vidlii\Vidlii\API {
		function index($args, $files) {
			$data = [];
			if(isset($args["u"]) && $args["u"] != "") {
				$username = $args["u"];
				$user = $this->db("SELECT * from users where username = '$username' or displayname = '$username'");
				if($user["count"] >= 1) {
					$data["status"] = 1;
					$data["data"] = $user["data"];
					unset($data["data"]["password"]);
				} else $data = $this->api_message(-1, "User Not Found");
			} else $data = $this->api_message(-1, "Enter Username");
			return $data;
		}
	}
?>