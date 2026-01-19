<?php
    namespace Vidlii\Vidlii;

    class RSS extends API {
        function show(string $user) {
            $userInfo = $this->db("SELECT id, username, displayname from users where username = :user or displayname = :user", false, [
                "user" => $user
            ]);
            $actualLink = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]";
            if($userInfo["count"] == 1) {
                header("Content-Type: text/xml;charset=UTF-8");
                $user = $userInfo["data"]["displayname"];
                echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
                echo "<feed xmlns=\"http://www.w3.org/2005/Atom\" xmlns:media=\"http://search.yahoo.com/mrss/\">";
                echo "<link rel=\"self\" href=\"$actualLink/feed/$user.xml\"/>";
                // author block
                echo "<author><name>$user</name><uri>$actualLink/user/$user</uri></author>";
                // updated (today's date)
                echo "<updated>".gmdate("Y-m-d\TH:i:s\Z")."</updated>";
                // avatar
                echo "<logo>$actualLink/vi/ava/$user.jpg</logo>";

                $videoApi = new \Vidlii\Vidlii\API\Video($_SERVER["DOCUMENT_ROOT"]);
                $videosList = $this->db("SELECT url from videos where uploaded_by = :user and status >= 1", true, [
                    "user" => $userInfo["data"]["username"]
                ]);
                foreach($videosList["data"] as $v) {
                    $video = $videoApi->index(["id" => $v["url"]])["data"] ?? [];
                    if(count($video) > 0) {
                        echo "<entry>";
                        echo "<id>$actualLink/watch?v=".$video["url"]."</id>";
                        echo "<title>".$video["title"]."</title>";
                        echo "<link rel=\"alternate\">$actualLink/watch?v=".$video["url"]."</link>";
                        echo "<author><name>$user</name><uri>$actualLink/user/$user</uri></author>";
                        echo "<published>".gmdate("Y-m-d\TH:i:s\Z", strtotime($video["uploaded_on"]))."</published>";
                        echo "<updated>".gmdate("Y-m-d\TH:i:s\Z", strtotime($video["uploaded_on"]))."</updated>";
                        echo "<media:title>".$video["title"]."</media:title>";
                        echo "<media:content width=\"640\" height=\"360\"/>";
                        echo "<media:player url=\"$actualLink/watch?v=".$video["url"]."\" width=\"640\" height=\"360\"/>";
                        echo "<media:description>".$video["description"]."</media:description>";
                        echo "<content>".$video["description"]."</content>";
                        echo "<media:thumbnail url=\"$actualLink/vi/".$video["url"]."/hqresdefault.jpg\" width=\"640\" height=\"360\"/>";
                        echo "</entry>";
                    }
                }

                echo "</feed>";
            } else {
                http_response_code(404);
                echo "Not found";
            }
        }
    }
?>