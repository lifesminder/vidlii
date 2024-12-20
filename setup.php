<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VidLii Setup</title>
    <link rel="stylesheet" href="/css/m.css">
</head>
<body>
    <header class="n_head">
        <div class="pr_hd_wrapper">
            <a href="/">
                <img src="/img/Vidlii6.png" alt="VidLii" title="VidLii - Display Yourself." id="hd_vidlii">
            </a>
            <nav>
                <ul><li><a href="#" id="pr_sel">Home</a></li></ul>
            </nav>
        </div>
    </header>
    <div class="wrapper">
        <?php
            require "vendor/autoload.php";
            session_start();
            $conn = null;

            if(!file_exists(".env")) {
                if(isset($_GET["step"]) && (int)$_GET["step"] > 1) {
                    switch((int)$_GET["step"]) {
                        case 2: {
                            if(
                                (isset($_SESSION["username"]) && $_SESSION["username"] != "") &&
                                (isset($_SESSION["host"]) && $_SESSION["host"] != "")
                            ) {
                                $conn = new mysqli($_SESSION["host"], $_SESSION["username"], $_SESSION["password"]);
                                if($conn->connect_error) {
                                    echo "Error! Reason: ".$conn->connect_error;
                                } else {
                                    if(!empty($_POST)) {
                                        $name = $_POST["name"];
                                        $dbExistance = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$name'");
                                        print_r($dbExistance);
                                        if($dbExistance->num_rows == 0) {
                                            if($conn->query("CREATE DATABASE $name") === TRUE) {
                                                $_SESSION["name"] = $name;
                                                header("Location: /setup?step=3");
                                            } else echo $conn->error;
                                        } else {
                                            echo "This DB exists";
                                        }
                                    }
        ?>
        <h2>Set Database Name</h2>
        <p>
            Make sure your Database exists and is empty.
        </p>
            <form action="/setup?step=2" method="post">
                <input type="text" name="name" placeholder="Database Name"><br>
                <button type="submit">Next</button>
            </form>
        <?php
                                    exit(0);
                                }
                            }
                            break;
                        }
                        case 3: {
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
                                    $file = file("schema.sql"); $query = "";
                                    echo "<pre>";
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
                                    echo "</pre>";
                                    if($dbErrors == 0) {
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
                                        header("Location: /");
                                    } else echo "DB wasn't imported correctly. Aborting...";
                                }
                            }
                            break;
                        }
                    }
                } else {
                    if(!empty($_POST)) {
                        if(
                            (isset($_POST["username"]) && $_POST["username"] != "") &&
                            (isset($_POST["host"]) && $_POST["host"] != "")
                        ) {
                            $_SESSION["username"] = $_POST["username"]; $_SESSION["host"] = $_POST["host"];
                            $_SESSION["password"] = (isset($_POST["password"]) && $_POST["password"] != "") ? $_POST["password"] : null;
                            
                            header("Location: /setup?step=2");
                        } else {
                            echo "You must enter DB Host and Username";
                        }
                    }
        ?>
        <h2>Set Up VidLii Instance</h2>
        <p>
            Start configuring VidLii by entering required credentials.
        </p>
            <form action="/setup" method="post">
                <input type="text" name="host" placeholder="Database Host"><br>
                <input type="text" name="username" placeholder="Username"><br>
                <input type="password" name="password" placeholder="Password"><br>
                <button type="submit">Next</button>
            </form>
        <?php
                }
            } else header("Location: /");
        ?>
    </div>
</body>
</html>