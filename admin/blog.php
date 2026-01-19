<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

    if($_USER->logged_in && ($_USER->Is_Admin || $_USER->Is_Mod)) {
        if(file_exists($_SERVER["DOCUMENT_ROOT"]."/_templates/nouveau/admin/blog.html")) {
            $Page = "Blog"; $Page_Title = "Blog";
            $twig = true;
            $twig_dest = "nouveau/admin/blog.html";
            $twig_args = [];

            if(isset($_GET["id"]) && (int)$_GET["id"] >= 0) {
                $id = (int)$_GET["id"];
                $post = $api->db("SELECT * from blog where id = $id");
                if($post["count"] == 1) {
                    if(isset($_GET["delete"]) && (bool)$_GET["delete"] == true) {
                        $delete_post = $api->db("DELETE from blog where id = $id");
                        if($delete_post["status"] == 1)
                            notification("Post deleted successfully", "/admin/blog", "green");
                    } else if(isset($_POST["blog_edit"])) {
                        $title = $_POST["blog_title"];
                        $content = $_POST["blog_content"];

                        if($title != "" && $content != "") {
                            try {
                                $update_post = $api->db("UPDATE blog set title = \"$title\", content = \"$content\" where id = $id");
                                notification("Post edited successfully", "/admin/blog", "green");
                            } catch(\Exception $e) {
                                print_r($update_post);
                            }
                            exit();
                        } else {
                            notification("Fields must be filled", "/admin/blog?id=$id", "red"); exit();
                        }
                    }

                    $twig_args["post"] = $post["data"];
                } else {
                    notification("There are no such post", "/admin/blog", "red"); exit();
                }
            } else if(isset($_GET["a"]) && trim($_GET["a"]) != "") {
                switch(strtolower($_GET["a"])) {
                    case "new": {
                        $twig_args["action"] = "new";
                        if($_SERVER["REQUEST_METHOD"] == "POST") {
                            $title = $_POST["blog_title"];
                            $content = $_POST["blog_content"];

                            $publish = $api->db("INSERT INTO blog (title, content, date) VALUES (:TITLE, :CONTENT, NOW())", false, [
                                "TITLE" => $title,
                                "CONTENT" => $content
                            ]);
                            if($publish["status"] == 1) {
                                notification("Blog Post successfully submitted!","/admin/blog", "green");
                            } else {
                                notification("Something went wrong!","/admin/blog", "red");
                            }
                            die;
                        }
                        break;
                    }
                    default: {
		                redirect("/admin/blog");
                        break;
                    }
                }
            } else {
                // Retrieve all blog posts
                $posts = $api->db("SELECT id, title, date from blog order by id desc", true);
                $twig_args["posts"] = $posts;
            }
            require_once "_templates/admin_structure.php";
        } else {
            redirect("/admin/dashboard");
        }
    } elseif ($_USER->Is_Mod || $_USER->Is_Admin) {
		redirect("/admin/login"); die();
	} else {
		redirect("/");
	}
?>