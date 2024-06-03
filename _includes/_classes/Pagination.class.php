<?php
    class Pagination {
        public $Max_Pages;
        public $Total_Pages;
        public $Current_Page;
        public $From, $To;
        public $Total;

        function __construct($Item_Per_Page,$Max_Pages = 99,$Page_Var = NULL) {
            if (isset($Page_Var)) {
                $_GET["p"] = $Page_Var;
            }


            $this->Max_Pages = $Max_Pages;
            if (isset($_GET["p"])) {
                if ($_GET["p"] < 1 or $_GET["p"] > $this->Max_Pages) {
                    $this->Current_Page = 1;
                } else {
                    $this->Current_Page = (int)$_GET["p"];
                }
            } else {
                $this->Current_Page = 1;
            }
            $this->From = ($this->Current_Page - 1) * $Item_Per_Page;
            $this->To   = $Item_Per_Page;
        }

        public function show($Total = NULL,$var1,$Clean = false) {

            if ($Total !== NULL) {
                $this->Total_Pages = ceil($Total / $this->To);
            } else {
                $this->Total_Pages = ceil($this->Total / $this->To);
            }

            if ($this->Current_Page > $this->Total_Pages and $this->Current_Page !== 1) {
                if (!$Clean) {
                    redirect("?$var1");
                } else {
                    redirect("$var1");
                }
            }

            if ($this->Total_Pages == 0) { $this->Total_Pages = 1; }

            if (!empty($var1)) {
                if ($Clean === true) {
                    $var1 = $var1 . "/";
                } else {
                    $var1 = "?".$var1 . "&p=";
                }
            } else {
                if ($Clean === true) {
                    $var1 = "/";
                } else {
                    $var1 = "?p=";
                }
            }

            for ($x = 1;$x <= $this->Total_Pages && $x <= $this->Max_Pages;$x++) {
                if ($this->Current_Page !== $x) {
                    echo "<div class='p_no'><a href='$var1$x'>". $x . "</a></div> ";
                } else {
                    echo "<div class='p_yes'>". $x . "</div> ";
                }
            }
        }

        public function new_show($Total = NULL,$var1,$Clean = false) {

            if ($Total !== NULL) {
                $this->Total_Pages = ceil($Total / $this->To);
            } else {
                $this->Total_Pages = ceil($this->Total / $this->To);
            }

            if ($this->Current_Page > $this->Total_Pages and $this->Current_Page !== 1) {
                if (!$Clean) {
                    redirect("?$var1");
                } else {
                    redirect("$var1");
                }
            }

            if ($this->Total_Pages == 0) { $this->Total_Pages = 1; }

            if ($this->Total_Pages > $this->Max_Pages) { $this->Total_Pages = $this->Max_Pages; }

            if (!empty($var1)) {
                if ($Clean === true) {
                    $var1 = $var1 . "/";
                } else {
                    $var1 = "?".$var1 . "&p=";
                }
            } else {
                if ($Clean === true) {
                    $var1 = "/";
                } else {
                    $var1 = "?p=";
                }
            }

            if ($this->Current_Page !== 1) {
                if ($this->Current_Page == 2) {
                    $s_var1 = rtrim($var1,"/");
                    $s_var1 = rtrim($s_var1,"&p=");
                    echo "<a href='".$s_var1."'>Prev</a> ";
                } else {
                    $Previous_Page = $this->Current_Page - 1;
                    echo "<a href='".$var1."".$Previous_Page."'>Prev</a> ";
                }
            }

            if ($this->Current_Page == 1) {
                for ($x = 1;$x <= $this->Total_Pages && $x <= 5;$x++) {
                    if ($this->Current_Page !== $x) {
                        echo "<a href='$var1$x'>". $x . "</a> ";
                    } else {
                        echo "<span class='p_yes'>". $x . "</span> ";
                    }
                }
            } elseif ($this->Current_Page == 2) {
                $s_var1 = rtrim($var1,"/");
                $s_var1 = rtrim($s_var1,"&p=");
                echo "<a href='".$s_var1."'>1</a> ";
                for ($x = 2;$x <= $this->Total_Pages && $x <= 5;$x++) {
                    if ($this->Current_Page !== $x) {
                        echo "<a href='$var1$x'>". $x . "</a> ";
                    } else {
                        echo "<span class='p_yes'>". $x . "</span> ";
                    }
                }
            } elseif ($this->Current_Page == 3) {
                $s_var1 = rtrim($var1,"/");
                $s_var1 = rtrim($s_var1,"&p=");
                echo "<a href='".$s_var1."'>1</a> <a href='".$var1."2'>2</a> ";
                for ($x = 3;$x <= $this->Total_Pages && $x <= 5;$x++) {
                    if ($this->Current_Page !== $x) {
                        echo "<a href='$var1$x'>". $x . "</a> ";
                    } else {
                        echo "<span class='p_yes'>". $x . "</span> ";
                    }
                }
            } elseif ($this->Current_Page > 3) {
                $Previous_Page = $this->Current_Page - 1;
                $Previous_Previous_Page = $this->Current_Page - 2;

                echo "<a href='".$var1."$Previous_Previous_Page'>$Previous_Previous_Page</a> <a href='".$var1."$Previous_Page'>$Previous_Page</a> ";
                for ($x = $this->Current_Page;$x <= $this->Total_Pages && $x <= $this->Current_Page + 2;$x++) {
                    if ($this->Current_Page !== $x) {
                        echo "<a href='$var1$x'>". $x . "</a> ";
                    } else {
                        echo "<span class='p_yes'>". $x . "</span> ";
                    }
                }
            }

            if ($this->Current_Page < $this->Total_Pages) {
                $Next_Page = $this->Current_Page + 1;
                echo "<a href='".$var1."".$Next_Page."'>Next</a> ";
            }
        }

        public function out_of() {
            $Out_Of = $this->Total;
            if ($this->Total > 0) {
                $OO_From = $this->From + 1;
            } else {
                $OO_From = $this->From;
            }
            $OO_To   = $this->Current_Page * $this->To;
            if ($OO_To > $this->Total) {
                $OO_To -= ($OO_To - $this->Total);
            }
            return "$OO_From-$OO_To of $Out_Of";

        }
    }