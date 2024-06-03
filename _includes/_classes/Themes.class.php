<?php
class Themes {
    public  $Themes = [],
            $Header = false;                                                    //1 = DEFAULT | 2 = COMPACT | false = NOT SET YET (USED FOR CHECKING IF A THEME ALREADY SET IT OR NOT SO ITS NOT BEING OVERWRITTEN)

    private $DB,
            $_USER;


    public function __construct(Database $DB, User $_USER) {


        $this->DB       = $DB;
        $this->_USER    = $_USER;


        if (isset($_COOKIE["css"]) && !empty($_COOKIE["css"])) {                //SEE IF THE USER HAS A THEMES CSS COOKIE AND IF NOT, DONT DO ANYTHING AND USE THE CHOSEN HEADER
			$Themes         = explode(",", $_COOKIE["css"]);            //PUT THE THEMES IDS OF THE THEMES COOKIE (separated by ,) INTO A TEMPORARY ARRAY
            $_COOKIE["css"] = "";                                               //EMPTY THE THEMES COOKIE SO THAT IT CAN BE PROPERLY AND LEGALLY REBUILD IN THE INSTALLATION FUNCTION

            foreach ($Themes as $Theme) {

                $this->install_theme($Theme);                                   //INSTALL AND CHECK EVERY THEME IN THE TEMPORARY THEMES ARRAY

            }

        } else {
            if (isset($_COOKIE["css"])) { unset($_COOKIE["css"]); }
            setcookie("css", NULL, -1, "/");

            if (isset($_COOKIE["hd"])) {

                $this->Header = 2;

            } else {

                $this->Header = 1;

            }

        }


    }


    public function install_theme(string $Theme): bool {
        if (!$this->_USER->logged_in || $this->_USER->Is_Admin || $this->_USER->Is_Mod) {   //YOU CAN ONLY USE NON ACCEPTED YOU OWN. IF YOU'RE NOT LOGGED IN OR NOT ADMIN/MOD, DONT EVEN CHECK IT

            $Theme = $this->DB->execute("SELECT url, header FROM themes WHERE url = :URL AND accepted = 1", true, [":URL" => $Theme]);

        } else {

            $Theme = $this->DB->execute("SELECT url, header FROM themes WHERE url = :URL AND (accepted = 1 OR owner = :USERNAME)", true, [":URL" => $Theme, ":USERNAME" => $this->_USER->username]);

        }


        if ($this->DB->RowNum == 1) {                                               //CHECK IF THEME HAS BEEN FOUND AND IF NOT, DONT ADD IT

            $Header = $Theme["header"];
            $Theme  = $Theme["url"];

            if (!$this->Header) {                                                   //IF HEADER HASN'T BEEN SET BY ANOTHER THEME YET, SET IT TO THE THEMES HEADER

                switch ($Header) {

                    case "2" :
                        $this->Header = 1;
                        break;

                    case "3" :
                        $this->Header = 2;
                        break;
                    case "1" :                                                      //1 = THE THEME RESPECTS THE USERS HEADER SELECTION
                        if (isset($_COOKIE["hd"]))   { $this->Header = 2; }
                        else                         { $this->Header = 1; }
                        break;

                }

            }

            if (!in_array($Theme, $this->Themes)) {                                 //DON'T ADD THE THEME AGAIN IF IT'S ALREADY INSTALLED

                if (count($this->Themes) == 0) {                                    //IF NO THEMES ARE INSTALLED, JUST SET THE THEMES COOKIE EQUAL TO THE THEME

                    $_COOKIE["css"] = $Theme;

                } else {
                    $_COOKIE["css"] .= ",".$Theme;                                  //IF THERE ARE THEMES INSTALLED ALREADY, ADD A COMMA BEFORE THE TO BE INSTALLED THEMES FOR SEPARATION

                }

                $this->Themes[] = str_replace("(", "", str_replace("'", "", str_replace(")", "", $Theme)));
                setcookie("css", $_COOKIE["css"], time() + (86400 * 31), "/");          //SET THE ACTUAL COOKIE EQUAL TO THE PREVIOUSLY SET COOKIE VARIABLE (31 days)

                return true;

            }

        }


        return false;


    }


    public function uninstall_theme(string $Install_Theme): void {

        if (count($this->Themes) > 1) {                                         //IF THEMES ARE ALREADY INSTALLED

            $_COOKIE["css"]   = "";
            $Installed_Themes = $this->Themes;
            $this->Themes     = [];


            foreach ($Installed_Themes as $Theme) {

                if ($Theme != $Install_Theme) {

                    $this->install_theme($Theme);                               //INSTALL EVERYTHING EXCEPT FOR THE ONE TO BE UNINSTALLED

                }

            }

        } else {                                                                // IF NOT THEMES ARE INSTALLED RIGHT NOW

            unset($_COOKIE["css"]);                                             //JUST UNSET EVERYTHING BECAUSE THE ONLY THEME THAT'S INSTALLED, IS THE ONE TO BE UNINSTALLED
            setcookie("css", NULL, -1, "/");
            $this->Themes = [];

        }

        $this->DB->modify("UPDATE themes SET installs = installs - 1 WHERE url = :URL", [":URL" => $Install_Theme]);




    }


    public function has_installed_theme(string $Theme): bool {


        if (in_array($Theme, $this->Themes)) {

            return true;

        }

        return false;


    }


    public function has_installed_themes(): bool {


        if (count($this->Themes) > 0) {

            return true;

        }

        return false;


    }


    //ECHOS OUT THE INSTALLED THEMES SO THAT THEY'RE LOADED VIA CSS FILE LINKING
    public function load_themes(): void {


        if ($this->has_installed_themes()) {

            foreach ($this->Themes as $Theme) {

                echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"/usfi/css/$Theme.css\">";

            }

        }


    }


    //RETURNS AN ARRAY OF ALL INSTALLED THEMES AND ITS INFORMATION
    public function themes_array(): array {

        $Themes = sql_IN_fix($this->Themes);


        return $this->DB->execute("SELECT * FROM themes WHERE url IN ($Themes)");


    }

}