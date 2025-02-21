<?php
    namespace Vidlii\Vidlii\API;

    class Auth extends \Vidlii\Vidlii\API {
        function login($args, $files = null) {
            require_once $this->dir."/_includes/init.php";

            $data = [];
            if(
                (isset($args["username"]) && $args["username"] != "") &&
                (isset($args["password"]) && $args["password"] != "")
            ) {
                $username = htmlspecialchars($args["username"]);
                $password = htmlspecialchars($args["password"]);

                $exists = (bool)$this->db("SELECT count(*) from users where username = \"$username\" or displayname = \"$username\" or email = \"$username\"");
                if($exists) {
                    $real_password = $this->db("SELECT password from users where username = \"$username\" or displayname = \"$username\" or email = \"$username\"")["data"]["password"];
                    $valid = password_verify($password, $real_password);
                    if($valid) {
                        $_USER->username = $username;
                        if($_USER->login()) $data = $this->api_message(0, "Access Granted");
                        else $data = $this->api_message(-1, "Invalid Username or Password");
                    } else $data = $this->api_message(-1, "Invalid Username or Password");
                } else $data = $this->api_message(-1, "Invalid Username or Password");
            } else $data = $this->api_message(-1, "Enter credentials");
            
            return $data;
        }
    }
?>