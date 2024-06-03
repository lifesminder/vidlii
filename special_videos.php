<?php
require_once "_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires ($_GET["t"])
if (!isset($_GET["t"])) { redirect("/"); exit(); }


$Categories = return_categories();
$Header = array("f" => "Featured", "b" => "Being Watched Now");
$_PAGINATION = new Pagination(16,20);


if ($_GET["t"] === "f" || $_GET["t"] === "b") {
    $Current_Order = $_GET["t"];
} else {
    $Current_Order = "f";
}

if (isset($_GET["c"]) && $_GET["c"] > 0 && $_GET["c"] < 16) {
    $Current_Cat = (int)$_GET["c"];
} else {
    $Current_Cat = 0;
}

//CATEGORY
if ($Current_Cat == 0) {
    $WHERE = "";
} else {
    $WHERE = "AND videos.category = $Current_Cat";
}

if ($Current_Order == "f") {
    //$Videos             = $DB->execute("SELECT url, title, views, length, uploaded_by, 1_star, 2_star, 3_star, 4_star, 5_star FROM videos $WHERE AND featured = 1 ORDER BY videos.uploaded_on DESC LIMIT $_PAGINATION->From,$_PAGINATION->To");
    //$_PAGINATION->Total = $DB->execute("SELECT count(url) as amount FROM videos WHERE $WHERE AND featured = 1 LIMIT 320", true)["amount"];


    $Videos             = new Videos($DB, $_USER);
    $Videos->WHERE_C    = "AND videos.featured = 1 $WHERE";
    $Videos->ORDER_BY   = "videos.uploaded_on DESC";
    $Videos->LIMIT      = $_PAGINATION;
    $Videos->get();
    $Videos             = $Videos->fixed();


    $Videos_Amount             = new Videos($DB, $_USER);
    $Videos_Amount->WHERE_C    = "AND videos.featured = 1 $WHERE";
    $Videos_Amount->SELECT     = "videos.url";
    $Videos_Amount->Count      = true;

    $_PAGINATION->Total        = $Videos_Amount->get();

    $Videos_Title       = "Featured Videos";

} else {
    $Videos            = new Videos($DB, $_USER);
    $Videos->Blocked   = false;
    $Videos->JOIN      = "INNER JOIN recently_viewed ON videos.url = recently_viewed.url";
    $Videos->ORDER_BY  = "recently_viewed.time_viewed DESC";
    $Videos->LIMIT     = 16;
    $Videos->get();

    $Videos = $Videos->fixed();


    $Videos_Title   = "Videos Being Watched";

}


$_PAGE->set_variables(array(
    "Page_Title"        => "$Videos_Title - VidLii",
    "Page"              => "Special_Videos",
    "Page_Type"         => "Videos"
));
require_once "_templates/page_structure.php";