<?php
    namespace Vidlii\Vidlii;

    class CDN extends API {
        function thumbnail($id, $params = []) {
            $thumbnail = $this->db("SELECT thumbnail from videos where url = '$id'");
            $photo = null;

            if($thumbnail["count"] == 1 && $thumbnail["data"]["thumbnail"] != "") {
                $photo = base64_decode($thumbnail["data"]["thumbnail"]);
            } else {
                $photo = file_get_contents($_SERVER["DOCUMENT_ROOT"]."/img/no_th.jpg");
            }

            header("Content-Type: image/jpeg");
            header("Cache-Control: no-cache");
            $im = imagecreatefromstring($photo);
            if($im == false) {
                $photo = file_get_contents($_SERVER["DOCUMENT_ROOT"]."/img/no_th.jpg");
                $im = imagecreatefromstring($photo);
            }
            imagejpeg($im, NULL, (isset($params["q"]) && (int)$params["q"] >= 0 && (int)$params["q"] <= 100) ? (int)$params["q"] : 80);
            imagedestroy($im);
        }
        
        function avatar($id, $params = []) {
            $avatar = $this->db("SELECT avatar from users where username = '$id'");
            if($avatar["count"] >= 1 && $avatar["data"]["avatar"] != "") {
                $avatar = base64_decode($avatar["data"]["avatar"]);
            } else {
                $avatar = file_get_contents($_SERVER["DOCUMENT_ROOT"]."/img/no.png");
            }
            header("Content-Type: image/jpeg");
            header("Cache-Control: no-cache");
            $im = imagecreatefromstring($avatar);
            $im = imagescale($im, 150, 150);
            imagejpeg($im, NULL, (isset($params["q"]) && (int)$params["q"] >= 0 && (int)$params["q"] <= 100) ? (int)$params["q"] : 80);
            imagedestroy($im);
        }
    }
?>