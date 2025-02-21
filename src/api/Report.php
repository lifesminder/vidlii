<?php
    namespace Vidlii\Vidlii\API;

    class Report extends \Vidlii\Vidlii\API {
		function index($args, $files = null) {
			$data = [];
            if($this->session()["session"] != -1) {
                if(
                    (isset($args["to"]) && $args["to"] != "") &&
                    (isset($args["what"]) && $args["what"] != "")
                ) {
                    $to = htmlspecialchars($args["to"]);
                    switch(strtolower($args["what"])) {
                        case "pfp": $what = "picture"; break;
                        case "bgd": $what = "background"; break;
                        default: $data = $this->api_message(-1, "Invalid report type"); exit(); break;
                    }
                    $why = (isset($args["why"]) && $args["why"] != "") ? "\"".htmlspecialchars($args["why"])."\"" : null;
    
                    $exists = $this->db("SELECT id from users where username = \"$to\" or displayname = \"$to\"");
                    if($exists["count"] == 1) {
                        if($from["session"] != -1) {
                            $from = $this->session()["user"]["id"];
                            $to = $exists["data"]["id"];
                            if($from != $to) {
                                $report_exists = $this->db("SELECT count(*) from reports where report_from = $from and report_to = $to and report_type = \"$what\"");
                                if($report_exists["data"]["count(*)"] == 0) {
                                    $report = $this->db("INSERT into reports (report_from, report_to, report_type, report_description) values ($from, $to, \"$what\", \"$why\")");
                                    if($report["status"] == 1) $data = $this->api_message(0, "User reported successfully");
                                    else $data = $report;
                                } else $data = $this->api_message(-1, "You already reported this user on this topic");
                            } else $data = $this->api_message(-1, "You cannot report yourself");
                        } else $data = $this->api_message(-1, "You must log in to report");
                    } else $data = $this->api_message(-1, "User does not exist");
                } else $data = $this->api_message(-1, "Forbidden");
            } else $data = $this->api_message(-2, "You need to sign in in order to report");
			return $data;
		}

    }
?>