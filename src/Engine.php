<?php
    namespace Vidlii\Vidlii;

    class Engine {
        private $api;

        function __construct() {
            $this->api = new \Vidlii\Vidlii\API($_SERVER["CONFIG_ROOT"]);
        }

        function template($file, $args = [], $return = false) {
            include_once "_includes/init.php";

            // Custom filters and functionality
            $filter = new \Twig\TwigFilter("url_decode", function($string) {
                return urldecode($string);
            });
            $helpArticle = new \Twig\TwigFunction('help', function($article) {
                $article = $this->api->db("SELECT id from help where guid = \"$article\"");
                if($article["count"] == 1) {
                    $id = $article["data"]["id"];
                    return "/help/{$id}";
                }
                return "/help";
            });

            $path = "_templates";
            $loader = new \Twig\Loader\FilesystemLoader("$path/");
            $twig = new \Twig\Environment($loader);
            $twig->addExtension(new \Twig\Extra\Intl\IntlExtension());
            $twig->addFilter($filter);
            $twig->addFunction($helpArticle);
            $parsedown = new \Parsedown();
			$fullfile = "$path/$file";

            // Fetch configuration
            $settings = $this->api->db("SELECT * from settings", true);
            $args["config"] = [];
            if($settings["count"] >= 1) {
                foreach($settings["data"] as $key) {
                    $args["config"][$key["name"]] = $key["value"];
                }
            }
            $args["config"]["top_text"] = $parsedown->text($args["config"]["top_text"]);
            $args["config"]["header"] = $_THEMES->Header;
            $args["config"]["title"] = (isset($_ENV["title"]) && $_ENV["title"] != "") ? $_ENV["title"] : "VidLii";
            $args["config"]["slogan"] = (isset($_ENV["slogan"]) && $_ENV["slogan"] != "") ? $_ENV["slogan"] : "VidLii - Display Yourself";
            $args["config"]["logo"] = $LOGO_VALUE;
            $args["q"] = (isset($_GET["q"]) && $_GET["q"] != "") ? $_GET["q"] : null;
            $args["session"] = $this->api->session();
            $args["cookie"] = $_COOKIE;

            // Modify some "keywords" defined by us
            if(isset($args["content"])) {
                $args["content"] = $parsedown->text($args["content"]);
            }

            if(!$return) {
                if(file_exists($fullfile)) echo $twig->render($file, $args);
                else $this->template("error.html");
            } else {
                if(file_exists($fullfile)) return $twig->render($file, $args);
                else return -1;
            }
		}
    }
?>