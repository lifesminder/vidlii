<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VidLii Setup</title>
    <link rel="stylesheet" href="/css/m.css">
</head>
<body>
    <div class="wrapper">
        <header class="n_head">
            <div class="pr_hd_wrapper">
                <a href="/">
                    <img src="/img/Vidlii6.png" alt="VidLii" title="VidLii - Display Yourself." id="hd_vidlii">
                </a>
                <nav>
                    <ul>
                        <a href="/setup"<?php if(!isset($_GET["step"])) { ?> id="pr_sel"<?php } ?>><li>Setup</li></a>
                        <a href="/setup?step=2"<?php if(isset($_GET["step"]) && (int)$_GET["step"] == 2) { ?> id="pr_sel"<?php } ?>><li>Configure</li></a>
                    </ul>
                </nav>
                <nav id="sm_nav">
                    <a href="https://github.com/lifesminder/vidlii" target="_blank">GitHub</a>
                </nav>
                <div class="pr_hd_bar"></div>
            </div>
        </header>
        <main class="bottom_wrapper">
        <?php
            require "vendor/autoload.php";
            session_start();
            $conn = null;
            
            if(!file_exists(".env")) {
                if(isset($_GET["step"]) && (int)$_GET["step"] > 1) {
                    switch((int)$_GET["step"]) {
                        case 2: {
                            $dbErrors = 0;
                            if(
                                (isset($_SESSION["username"]) && $_SESSION["username"] != "") &&
                                (isset($_SESSION["name"]) && $_SESSION["name"] != "") &&
                                (isset($_SESSION["host"]) && $_SESSION["host"] != "")
                            ) {
                                $conn = new mysqli($_SESSION["host"], $_SESSION["username"], $_SESSION["password"], $_SESSION["name"]);
                                if($conn->connect_error) {
                                    echo "Error! Reason: ".$conn->connect_error;
                                } else {
                                    if(!empty($_POST)) {
                                        if(
                                            (isset($_POST["title"]) && $_POST["title"] != "") &&
                                            (isset($_POST["slogan"]) && $_POST["slogan"] != "") &&
                                            (isset($_POST["adminuser"]) && $_POST["adminuser"] != "") &&
                                            (isset($_POST["adminemail"]) && $_POST["adminemail"] != "") &&
                                            (isset($_POST["adminpass"]) && $_POST["adminpass"] != "") &&
                                            (isset($_POST["adminpass2"]) && $_POST["adminpass2"] != "")
                                        ) {
                                            // configure database
                                            $file = file("schema.sql"); $query = "";
        ?>
        <h2>All set!</h2>
        <p>Now you can use your VidLii instance!</p>
        <a href="/" class="btn">Go to VidLii</a>
        <?php
                                            echo "<details style=\"margin: 8px 0 8px 0\">
                                                    <summary style=\"cursor: pointer\">Details of SQL</summary>
                                                    <pre>";
                                            foreach($file as $line) {
                                                $startWith = substr(trim($line), 0, 2); $endWith = substr(trim($line), -1, 1);
                                                if(empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
                                                    continue;
                                                }
                                                $query .= $line;
                                                $query = str_replace("\r\n", "", $query);
                                                if($endWith == ';') {
                                                    if($conn->query($query) === TRUE) {
                                                        echo "DONE! ".$query."\n";
                                                    } else {
                                                        $dbErrors++;
                                                        echo "ERROR: ".$conn->error."\n";
                                                    }
                                                    $query = "";
                                                }
                                            }
                                            echo "</pre>
                                                </details>";

                                            if(!file_exists(".env")) {
                                                $file = fopen(".env", "w");
                                                $database = "database = \"mysqli://";
                                                if(isset($_SESSION["password"]) && $_SESSION["password"] != "") $database .= urlencode($_SESSION["username"]).":".urlencode($_SESSION["password"]);
                                                else $database .= urlencode($_SESSION["username"]);
                                                $database .= "@".$_SESSION["host"]."/".$_SESSION["name"]."?charset=utf8mb4\"\n";
                                                fwrite($file, $database);
                                                fwrite($file, "host = \"".$_SESSION["host"]."\"\n");
                                                fwrite($file, "db = \"".$_SESSION["name"]."\"\n");
                                                fwrite($file, "username = \"".$_SESSION["username"]."\"\n");
                                                if(isset($_SESSION["password"]) && $_SESSION["password"] != "")
                                                    fwrite($file, "password = \"".$_SESSION["password"]."\"\n");
                                                fclose($file);
                                            } else {
                                                echo "DB wasn't imported correctly. Aborting...";
                                                exit(0);
                                            }

                                            // and then
                                            $title = $_POST["title"]; $slogan = $_POST["slogan"];
                                            $admin_username = $_POST["adminuser"]; $admin_email = $_POST["adminemail"]; $admin_password = $_POST["adminpass"]; $admin_password2 = $_POST["adminpass2"];

                                            $insert_title = $conn->query("INSERT into options (option_name, value) values ('title', '$title')");
                                            $insert_slogan = $conn->query("INSERT into options (option_name, value) values ('slogan', '$slogan')");

                                            if($insert_title === TRUE && $insert_slogan === TRUE) {
                                                $register_admin = $conn->query("INSERT INTO users (username,displayname,email,password,reg_date,last_login,birthday,1st_latest_ip,country, partner, is_admin, is_mod) VALUES ('$admin_username', '$admin_username', '$admin_email', '".password_hash($admin_password, PASSWORD_BCRYPT)."', NOW(), NOW(), '1910-01-01', '127.0.0.1', 'US', 1, 1, 1)");
                                                if($register_admin === TRUE) {
                                                    header("Location: /setup?step=3");
                                                } else {
                                                    echo "<div style=\"border: 1.5px solid red;padding:6px;text-align:center;margin-bottom:11px;font-size:14px;font-weight:bold\">Unexpected error: ".$conn->error."</div>";
                                                }
                                            } else {
                                                echo "<div style=\"border: 1.5px solid red;padding:6px;text-align:center;margin-bottom:11px;font-size:14px;font-weight:bold\">Unexpected error: ".$conn->error."</div>";
                                            }
                                            exit(0);
                                        } else {
                                            echo "<div style=\"border: 1.5px solid red;padding:6px;text-align:center;margin-bottom:11px;font-size:14px;font-weight:bold\">You must fill all fields</div>";
                                        }
                                    }
        ?>
        <h2>Configure VidLii</h2>
        <p>
            Set up VidLii's crucial stuff, such as name, description and administrator account to manage instance at the best!
        </p>
        <div class="you_wnt" id="re_box">
            <div>
                <form action="/setup?step=2" method="post">
                    <div style="margin: 0.5rem">
                        <label for="title">Title: </label>
                        <input type="text" name="title" placeholder="Title of your Instance" value="VidLii">
                    </div>
                    <div style="margin: 0.5rem">
                        <label for="slogan">Slogan: </label>
                        <input type="text" name="slogan" placeholder="Slogan of your Instance" value="Display Yourself!">
                    </div>
                    <hr>
                    <div style="margin: 0.5rem">
                        <label for="adminuser">Username: </label>
                        <input type="text" name="adminuser" placeholder="Your Username">
                    </div>
                    <div style="margin: 0.5rem">
                        <label for="adminemail">E-Mail: </label>
                        <input type="text" name="adminemail" placeholder="Your E-Mail">
                    </div>
                    <div style="margin: 0.5rem">
                        <label for="adminpass">Password: </label>
                        <input type="password" name="adminpass" placeholder="Your Password">
                    </div>
                    <div style="margin: 0.5rem">
                        <label for="adminpass2">Confirm Password: </label>
                        <input type="password" name="adminpass2" placeholder="Confirm Your Password">
                    </div>
                    <div style="margin: 0.5rem; display: flex; align-items: center;">
                        <a href="/setup" class="btn">Back</a>
                        <button type="submit">Next</button>
                    </div>
                </form>
            </div>
        </div>
        <?php
                                }
                            } else header("Location: /setup");
                            break;
                        }
                    }
                } else {
                    if(!empty($_POST)) {
                        $message = "";
                        if(
                            (isset($_POST["username"]) && $_POST["username"] != "") &&
                            (isset($_POST["host"]) && $_POST["host"] != "")
                        ) {
                            $_SESSION["username"] = $_POST["username"]; $_SESSION["host"] = $_POST["host"]; $_SESSION["name"] = $_POST["name"];
                            $_SESSION["password"] = (isset($_POST["password"]) && $_POST["password"] != "") ? $_POST["password"] : null;
                            
                            if(
                                (isset($_SESSION["username"]) && $_SESSION["username"] != "") &&
                                (isset($_SESSION["name"]) && $_SESSION["name"] != "") &&
                                (isset($_SESSION["host"]) && $_SESSION["host"] != "")
                            ) {
                                try {
                                    $conn = new mysqli($_SESSION["host"], $_SESSION["username"], $_SESSION["password"]) or die("Cannot connect to DB");
                                } catch(\Exception $e) {
                                    header("Location: /setup");
                                }

                                if($conn->connect_error) {
                                    $message = "Error! Reason: ".$conn->connect_error;
                                } else {
                                    if(!empty($_POST)) {
                                        $name = $_SESSION["name"];
                                        $dbExistance = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$name'");
                                        if($dbExistance->num_rows == 0) {
                                            if($conn->query("CREATE DATABASE $name") === TRUE) {
                                                $_SESSION["name"] = $name;
                                                header("Location: /setup?step=2");
                                            } else $message = $conn->error;
                                        } else {
                                            $message = "This DB exists. Choose another name, or delete/rename existing DB.";
                                        }
                                    }
                                    exit(0);
                                }
                            }

                            header("Location: /setup?step=2");
                        } else {
                            $message = "<div style=\"border: 1.5px solid red;padding:6px;text-align:center;margin-bottom:11px;font-size:14px;font-weight:bold\">You must enter DB Host and Username</div>";
                        }
                        if($message != "")
                            echo $message;
                    }
        ?>
        <h2>Welcome to VidLii's Setup!</h2>
        <p>
            This is the start of your VidLii Open Source instance, which can be available both locally and public!
        </p>
        <p>
            Now, let's start with configuring VidLii by entering database credentials.
        </p>
        <div class="you_wnt" id="re_box">
            <div>
                <form action="/setup" method="post">
                    <div style="margin: 0.5rem">
                        <label for="host">Hostname: </label>
                        <input type="text" name="host" placeholder="Database Host">
                    </div>
                    <div style="margin: 0.5rem">
                        <label for="host">Username: </label>
                        <input type="text" name="username" placeholder="Username">
                    </div>
                    <div style="margin: 0.5rem">
                        <label for="host">Password: </label>
                        <input type="password" name="password" placeholder="Password">
                    </div>
                    <div style="margin: 0.5rem">
                        <label for="host">Name of DB: </label>
                        <input type="text" name="name" placeholder="Name of DB" value="vidlii">
                    </div>
                    <div style="margin: 0.5rem; display: flex; align-items: center;">
                        <button type="submit">Next</button>
                    </div>
                </form>
            </div>
        </div>
        <?php
                }
            } else header("Location: /");
        ?>
        </main>
    </div>
</body>
</html>