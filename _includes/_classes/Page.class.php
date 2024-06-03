<?php
    class Page {
        public  $Page_Title,
                $Page_Description,
                $Page_Tags,
                $Page,
                $Page_Type,
                $Show_Search,
                $Current_Page;

        public $Errors  = array();

        function __construct() {
            $this->Current_Page = str_replace("/","",str_replace(".php","",$_SERVER["PHP_SELF"]));

            if (!in_array($this->Current_Page,array("forgot_password","login","logout","register","upload_video","uploader","settings","inbox","results","embed")) and strpos($this->Current_Page,"ajax") === false and strpos($_SERVER['REQUEST_URI'],"usfi") === false) {
                $_SESSION["previous_page"] = $_SERVER['REQUEST_URI'];
            }
        }


        public function set_variables($Page_Settings) {
            if (isset($Page_Settings["Page_Title"]))          {  $this->Page_Title        = $Page_Settings["Page_Title"]; }
            else { $this->Page_Title = $this->Current_Page." - VidLii"; }

            if (isset($Page_Settings["Page_Description"]))    {  $this->Page_Description  = $Page_Settings["Page_Description"]; }
            else { $this->Page_Description = "Watch, upload and share your favorite videos with the entire world in an easy to use and friendly environment."; }

            if (isset($Page_Settings["Page_Tags"]))           {  $this->Page_Tags         = $Page_Settings["Page_Tags"]; }
            else { $this->Page_Tags = "video, sharing, camera, upload, social, friends"; }

            if (isset($Page_Settings["Page_Type"])) { $this->Page_Type = $Page_Settings["Page_Type"]; }
            else { $this->Page_Type = "Home"; }

            if (isset($Page_Settings["Page"])) { $this->Page = $Page_Settings["Page"]; }
            else { die("Missing Page"); }

            if (isset($Page_Settings["Show_Search"])) { $this->Show_Search = $Page_Settings["Show_Search"]; }
            else { $this->Show_Search = true; }
        }

        public function add_error($Error) {
            $this->Errors[] = $Error;
        }

        public function return_errors() {
            return $this->Errors;
        }

        public function has_errors() {
            if (count($this->Errors) > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function has_error($Input) {
            if (isset($this->Errors[$Input])) {
                return true;
            } else {
                return false;
            }
        }
    }