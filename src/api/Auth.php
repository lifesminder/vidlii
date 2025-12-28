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

        function register($args, $files = null) {
            require_once $this->dir."/_includes/init.php";

            $data = [];
            if(
                (isset($args["username"]) && $args["username"] != "") &&
                (isset($args["email"]) && $args["email"] != "") &&
                (isset($args["password"]) && $args["password"] != "")
            ) {
                $username = htmlspecialchars($args["username"]);
                $email = htmlspecialchars($args["email"]);
                $password = htmlspecialchars($args["password"]);
                $country = (isset($args["country"]) && $args["country"] != "") ? strtoupper($args["country"]) : "US";
                $birthday = (isset($args["birthday"]) && $args["birthday"] != "") ? $args["birthday"] : "1999-01-01";
                
                $exists = (bool)($this->db("SELECT count(*) from users where username = \"$username\" or displayname = \"$username\"")["data"]["count(*)"] >= 1);
                if(!$exists) {
                    $ip = user_ip();
                    $password = password_hash($password, PASSWORD_BCRYPT); // subject to change, I guess
                    $register = $this->db("INSERT into users (username, displayname, email, password, reg_date, last_login, birthday, country, 1st_latest_ip, 2nd_latest_ip) values (\"$username\", \"$username\", \"$email\", \"$password\", NOW(), NOW(), \"$birthday\", \"$country\", \"$ip\", \"$ip\")");
                    $data = $register;
                } else $data = $this->api_message(-1, "User already exists");
            } else $data = $this->api_message(-1, "Enter credentials");
            return $data;
        }
    }
?>