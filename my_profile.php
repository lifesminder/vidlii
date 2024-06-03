<?php
require_once "_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
if (!$_USER->logged_in)         { redirect("/login"); exit(); }


if (isset($_POST["update_info"])) {
    $_GUMP->validation_rules(array(
        "website"       => "valid_url|max_len,128",
        "day"           => "is_day",
        "month"         => "is_month",
        "year"          => "is_year",
        "occupation"    => "max_len,128",
        "schools"       => "max_len,128",
        "interests"     => "max_len,128",
        "movies"        => "max_len,128",
        "music"         => "max_len,128",
        "books"         => "max_len,128",
        "about"         => "max_len,500"
    )); 

    $_GUMP->filter_rules(array(
        "website"       => "trim|NoHTML",
        "day"           => "trim",
        "month"         => "trim",
        "year"          => "trim",
        "occupation"    => "trim|NoHTML",
        "schools"       => "trim|NoHTML",
        "interests"     => "trim|NoHTML",
        "movies"        => "trim|NoHTML",
        "music"         => "trim|NoHTML",
        "books"         => "trim|NoHTML",
        "about"         => "trim|NoHTML"
    ));

    $Validation = $_GUMP->run($_POST);

    if ($Validation) {
        $Day        = (int)$Validation["day"];
        $Month      = (int)$Validation["month"];
        $Year       = (int)$Validation["year"];
        $Birthday   = "$Year-$Month-$Day";

        if (get_age($Birthday) >= 13) {

            if (get_age($Birthday) >= 120) {
                $Birthday = "1999-01-01";
            }

			$age = $_POST["show_age"] ? 1 : 0;
			$country = $_POST["show_country"] ? 1 : 0;
			$signin = $_POST["show_signin"] ? 1 : 0;
			
            $DB->modify("UPDATE users SET about = :ABOUT, website = :WEBSITE, birthday = :BIRTHDAY, i_occupation = :OCCUPATION, i_schools = :SCHOOLS, i_interests = :INTERESTS, i_movies = :MOVIES, i_music = :MUSIC, i_books = :BOOKS, a_age = :AGE, a_country = :COUNTRY, a_last = :SIGNIN WHERE username = :USERNAME",
                       [":ABOUT" => $Validation["about"], ":WEBSITE" => $Validation["website"], ":BIRTHDAY" => $Birthday, ":USERNAME" => $_USER->username, ":OCCUPATION" => $Validation["occupation"], ":SCHOOLS" => $Validation["schools"], ":INTERESTS" => $Validation["interests"], ":MOVIES" => $Validation["movies"], ":MUSIC" => $Validation["music"], ":BOOKS" => $Validation["books"], ":AGE" => $age, ":COUNTRY" => $country, ":SIGNIN" => $signin]);
            notification("Profile successfully updated!","/my_profile","green"); exit();
        } else {
            notification("You must be at least 13 years old to use VidLii!","/my_profile","red"); exit();
        }
    }
}

//GET INFO
$Info = $_USER->get_profile();


//DATE STUFF
$Months_Array = array('January' => 1,'February' => 2,'March' => 3,'April' => 4,'May' => 5,'June' => 6,'July' => 7,'August' => 8,'September' => 9,'October' => 10,'November' => 11,'December' => 12);

$Birthday = $Info["birthday"];
$Birth_Year = date("Y",strtotime($Birthday));
$Birth_Month = ltrim(date("m",strtotime($Birthday)),0);
$Birth_Day = ltrim(date("d",strtotime($Birthday)),0);


if (!empty($Info["avatar"])) {
    $Avatar = "/watch?v=".$Info["avatar"];
} else {
    $Avatar = "";
}



if (isset($Info)) {
    $Channel_Version = $Info["channel_version"];
} else {
    $Channel_Version = $Info["channel_version"];
}

if ($Channel_Version > 2) {
    redirect("/my_account");
}


$Account_Title = "Profile Setup";


$_PAGE->set_variables(array(
    "Page_Title"        => "My Profile - VidLii",
    "Page"              => "Profile",
    "Page_Type"         => "Home",
    "Show_Search"       => false
));
require_once "_templates/settings_structure.php";
