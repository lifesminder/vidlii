<?php
    namespace Vidlii\Vidlii;

    class Engine {
        function template($file, $args = [], $return = false) {
            $path = "_templates";
            $loader = new \Twig\Loader\FilesystemLoader("$path/");
            $twig = new \Twig\Environment($loader); $twig->addExtension(new \Twig\Extra\Intl\IntlExtension());
			$fullfile = "$path/$file";
            
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