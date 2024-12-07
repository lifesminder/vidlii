<?php
class Videos {
    public      $WHERE_P            = false,
        $WHERE_C                    = "",
        $SELECT                     = "videos.*",
        $ORDER_BY                   = false,
        $STATUS                     = 2,
        $Private_Videos             = false,
        $Unlisted_Videos            = false,
        $Banned_Users               = false,
        $Shadowbanned_Users         = false,
        $LIMIT                      = 20,
        $JOIN                       = "",
        $Uploader                   = true,
        $Distinct                   = false,
        $Blocked                    = true,
        $Execute                    = [],
        $Count                      = false,
        $Group_By                   = "",
        $Racism                     = true,
        $Count_Column               = "videos.title";

    public static   $Videos,
                    $Amount;

    private         $DB,
                    $_USER,
                    $_LANG;


    function __construct(\Vidlii\Vidlii\DB $DB, User $_USER) {
        $this->DB       = $DB;
        $this->_USER    = $_USER;
    }

    public function get() {
        if (!$this->Blocked) {
            $Username = $this->_USER->username;
            $Logged_In = $this->_USER->logged_in;
        }


        if (is_a($this->LIMIT,"Pagination")) { $LIMIT = "LIMIT ".$this->LIMIT->From.", ".$this->LIMIT->To; }
        elseif ($this->LIMIT === false)                { $LIMIT = ""; }
        else                                           { $LIMIT = "LIMIT ".$this->LIMIT; }

        if ($this->ORDER_BY === false) {
            $ORDER_BY = "";
        } else {
            $ORDER_BY = "ORDER BY $this->ORDER_BY";
        }

        if ($this->Distinct) {
            $Distinct = "DISTINCT";
        } else {
            $Distinct = "";
        }

        $WHERE = "WHERE ";
        if ($this->STATUS === 2) {
            $WHERE .= "(videos.status = 2 OR videos.status IS NULL)";
        } elseif ($this->STATUS === 1) {
            $WHERE .= "(videos.status = 1 OR videos.status IS NULL)";
        } elseif ($this->STATUS === 0) {
            $WHERE .= "(videos.status = 0 OR videos.status IS NULL)";
        } elseif ($this->STATUS === 3) {
            $WHERE .= "(videos.status = -2 OR videos.status = 0 OR videos.status = 1 OR videos.status = 2 OR videos.status IS NULL)";
        }

        if ($this->Private_Videos === false && $WHERE != "WHERE ") { $WHERE .= " AND "; }
        if ($this->Private_Videos === false) { $WHERE .= "(videos.privacy <> 1 OR videos.privacy IS NULL)"; }
        if ($this->Unlisted_Videos === false && $WHERE != "WHERE ") { $WHERE .= " AND "; }
        if ($this->Unlisted_Videos === false) { $WHERE .= "(videos.privacy <> 2 OR videos.privacy IS NULL)"; }
        if ($this->Banned_Users === false && $WHERE != "WHERE ") { $WHERE .= " AND "; }
        if ($this->Banned_Users === false) { $WHERE .= "(videos.banned_uploader = 0 OR videos.banned_uploader IS NULL)"; }
        if ($this->Shadowbanned_Users === false && $WHERE != "WHERE ") { $WHERE .= " AND "; }
        if ($this->Shadowbanned_Users === false) { $WHERE .= "(videos.shadowbanned_uploader = 0 OR videos.shadowbanned_uploader IS NULL) "; }
        if ($this->Blocked === false && $Logged_In && $WHERE != "WHERE ")  { $WHERE .= " AND "; }
        if ($this->Blocked === false && $Logged_In)                        { $WHERE .= "(users_block.blocker IS NULL)"; }

        if (!empty($WHERE) && $WHERE !== "WHERE " && $this->WHERE_P !== false) { $WHERE .= " AND "; }
        if ($this->WHERE_P !== false && is_array($this->WHERE_P) && count($this->WHERE_P) > 0) {
            $Amount     = count($this->WHERE_P);
            $Count      = 0;
            foreach ($this->WHERE_P as $P => $Value) {
                $Count++;
                $WHERE .= "$P = :".str_replace(".","",$P);
                if ($Amount !== $Count) { $WHERE .= " AND "; }
                $this->Execute[":".str_replace(".","",$P)] = $Value;
            }
        }

        if ($this->LIMIT === "LIMIT 1") { $Single = true; } else { $Single = false; }

        if ($this->Uploader) { $this->SELECT .= ", users.displayname, users.partner, users.avatar"; $this->JOIN .= " LEFT JOIN users ON videos.uploaded_by = users.username"; }
        if ($this->Blocked === false && $Logged_In)  {  $this->SELECT .= ", users_block.blocker"; $this->JOIN .= " LEFT JOIN users_block ON (('$Username' = users_block.blocker AND videos.uploaded_by = users_block.blocked) OR ('$Username' = users_block.blocked AND videos.uploaded_by = users_block.blocker)) ";}

        if ($this->Count) { $this->SELECT = "count($this->Count_Column) as amount"; $Single = true; }

		// DEBUG
        //echo "SELECT $Distinct $this->SELECT FROM videos $this->JOIN $WHERE $this->WHERE_C $ORDER_BY $LIMIT<br><br>";
        $Videos = $this->DB->execute("SELECT $Distinct $this->SELECT FROM videos $this->JOIN $WHERE $this->WHERE_C $this->Group_By $ORDER_BY $LIMIT",$Single,$this->Execute);
        $Videos = $this->DB->execute("SELECT $Distinct $this->SELECT FROM videos $this->JOIN $WHERE $this->WHERE_C $this->Group_By $ORDER_BY $LIMIT",$Single,$this->Execute);

		static::$Amount = $this->DB->RowNum;

        if (!$this->Count) {
            if (static::$Amount > 0) {
                $this::$Videos = $Videos;
                return true;
            } else {
                $this::$Videos = false;
            }
        } else {
            $this::$Amount = $Videos["amount"];
            return $Videos["amount"];
        }
        return false;
    }

    public function fixed(bool $Thumbnail = true) {
        if (!isset(static::$Videos)) { throw new Exception("errV.C.P"); }
        if (static::$Videos === false) { return false; }

        foreach(static::$Videos as $Video => $Value) {
            if (($this->_USER->logged_in && $this->_USER->username === static::$Videos[$Video]["uploaded_by"]) || static::$Videos[$Video]["privacy"] == 0) {
                $Can_see = true;
            } else {
                $Can_see = false;
            }

            if (isset(static::$Videos[$Video]["banned_uploader"]) && static::$Videos[$Video]["banned_uploader"] == 1) {
                $Banned_Uploader = true;
            } else {
                $Banned_Uploader = false;
            }

            if (!isset(static::$Videos[$Video]["title"]) || empty(static::$Videos[$Video]["title"]) || $Banned_Uploader) {
                static::$Videos[$Video]["title"] = "Deleted Video";
            } elseif (!$Can_see) {
                static::$Videos[$Video]["title"] = "Private Video";
            }

            if (!isset(static::$Videos[$Video]["description"]) || empty(static::$Videos[$Video]["description"]) || $Banned_Uploader) {
                static::$Videos[$Video]["description"] = false;
            } elseif (!$Can_see) {
                static::$Videos[$Video]["description"] = "This is a private video";
            }

            if (!isset(static::$Videos[$Video]["tags"]) || empty(static::$Videos[$Video]["tags"]) || !$Can_see || $Banned_Uploader) {
                static::$Videos[$Video]["tags"] = false;
            }

            if (!isset(static::$Videos[$Video]["uploaded_by"]) || empty(static::$Videos[$Video]["uploaded_by"]) || !$Can_see || $Banned_Uploader) {
                static::$Videos[$Video]["uploaded_by"]      = "???";
            }

            if (!isset(static::$Videos[$Video]["displayname"]) || empty(static::$Videos[$Video]["displayname"]) || !$Can_see || $Banned_Uploader) {
                static::$Videos[$Video]["displayname"]      = "???";
            }

            if (!isset(static::$Videos[$Video]["views"]) || !$Can_see || $Banned_Uploader) {
                static::$Videos[$Video]["views"]    = 0;
            } else {
                static::$Videos[$Video]["views"]    = static::$Videos[$Video]["displayviews"];
            }
            if (!isset(static::$Videos[$Video]["comments"]) || !$Can_see || $Banned_Uploader) {
                static::$Videos[$Video]["comments"] = 0;
            }
            if (!isset(static::$Videos[$Video]["length"]) || !$Can_see || $Banned_Uploader) {
                static::$Videos[$Video]["length"]   = "";
            } else {
                static::$Videos[$Video]["seconds"]  = static::$Videos[$Video]["length"];
                static::$Videos[$Video]["length"]   = seconds_to_time(static::$Videos[$Video]["length"]);
            }

            if (!isset(static::$Videos[$Video]["url"]) || empty(static::$Videos[$Video]["url"]) || $Banned_Uploader) {
                static::$Videos[$Video]["url"] = "";
                static::$Videos[$Video]["1_star"] = 0;
                static::$Videos[$Video]["2_star"] = 0;
                static::$Videos[$Video]["3_star"] = 0;
                static::$Videos[$Video]["4_star"] = 0;
                static::$Videos[$Video]["5_star"] = 0;
            } elseif (!$Can_see) {
                static::$Videos[$Video]["1_star"] = 0;
                static::$Videos[$Video]["2_star"] = 0;
                static::$Videos[$Video]["3_star"] = 0;
                static::$Videos[$Video]["4_star"] = 0;
                static::$Videos[$Video]["5_star"] = 0;
            }

            if (!isset(static::$Videos[$Video]["uploaded_on"]) || empty(static::$Videos[$Video]["uploaded_on"]) || $Banned_Uploader) {
                static::$Videos[$Video]["uploaded_on"] = "1869-02-15 00:00:01";
            }

            if ($Thumbnail) {
                $url = static::$Videos[$Video]["url"];
                $Image = "/vi/$url/default.jpg";
                static::$Videos[$Video]["thumbnail"] = 'src="'.$Image.'" alt="'.str_replace('"', "", str_replace("'", "", static::$Videos[$Video]["title"])).'" title="'.str_replace('"', "", str_replace("'", "",static::$Videos[$Video]["title"])).'"';
            }
        }
        
        return ($this->LIMIT !== 1) ? static::$Videos : static::$Videos[0];
    }
}