<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

    if($_USER->logged_in && ($_USER->Is_Admin || $_USER->Is_Mod)) {
        if(file_exists($_SERVER["DOCUMENT_ROOT"]."/_templates/nouveau/admin/blog.html")) {
            $Page_Title = "Blog";
            $twig = true;
            $twig_dest = "nouveau/admin/blog.html";
            $twig_args = [];

            require_once "_templates/admin_structure.php";
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
                            // echo "UPDATE blog set title = \"$title\" and content = \"$content\" where id = $id";
                            $update_post = $api->db("UPDATE blog set title = \"$title\", content = \"$content\" where id = $id");
                            if($update_post["status"] == 1)
                                notification("Post edited successfully", "/admin/blog", "green");
                            else
                                print_r($update_post);
                            exit();
                        } else {
                            notification("Fields must be filled", "/admin/blog?id=$id", "red"); exit();
                        }
                    }

                    $twigs_args = ["post" => $post["data"]];
                } else {
                    notification("There are no such post", "/admin/blog", "red"); exit();
                }
            } else {
                // Retrieve all blog posts
                $posts = $api->db("SELECT id, title, date from blog order by id desc", true);
                $twigs_args = ["posts" => $posts];
            }
        } else {
            redirect("/admin/dashboard");
        }
    } elseif ($_USER->Is_Mod || $_USER->Is_Admin) {
		redirect("/admin/login"); die();
	} else {
		redirect("/");
	}
?>