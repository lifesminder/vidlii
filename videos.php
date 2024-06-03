<?php
require_once "_includes/init.php";

$Categories = return_categories();
$Header = array("re" => "Newest", "mv" => "Most Viewed", "md" => "Most Discussed", "tr" => "Top Rated");
$_PAGINATION = new Pagination(16,20);


if (isset($_GET["c"],$_GET["o"],$_GET["t"])) {
    if ($_GET["o"] == "re" || $_GET["o"] == "mv" || $_GET["o"] == "md" || $_GET["o"] == "tr") {
        $Current_Order = $_GET["o"];
    } else {
        $Current_Order = "re";
    }

    if ($_GET["c"] > 0 && $_GET["c"] < 16) {
        $Current_Cat = (int)$_GET["c"];
    } else {
        $Current_Cat = 0;
    }

    if ($_GET["t"] > 0 && $_GET["t"] < 4) {
        $Current_time = (int)$_GET["t"];
    } else {
        $Current_time = 0;
    }
} else {
    $Current_Cat = 0;
    $Current_Order = "re";
    $Current_time = 2;
}

//ORDER BY
if ($Current_Order == "re") {
    $ORDER_BY = "videos.uploaded_on DESC";
} elseif ($Current_Order == "mv") {
    $ORDER_BY = "videos.displayviews DESC";
} elseif ($Current_Order == "tr") {
    $ORDER_BY = "(videos.1_star + videos.2_star * 2 + videos.3_star * 3 + videos.4_star * 4 + videos.5_star * 5) DESC, videos.views DESC";
}

//CATEGORY
if ($Current_Cat == 0) {
    $WHERE = " videos.category <> 100 AND videos.url <> 'CndS9berMs3' ";
} else {
    $WHERE = " videos.category = $Current_Cat ";
}

//TIME
if ($Current_time == 0) {
    $WHERE .= " ";
} elseif ($Current_time == 1) {
    $WHERE .= " AND YEARWEEK(videos.uploaded_on)=YEARWEEK(NOW()) ";
} elseif ($Current_time == 2) {
    $WHERE .= " AND MONTH(videos.uploaded_on) = MONTH(CURDATE()) AND YEAR(videos.uploaded_on) = YEAR(CURDATE()) ";
} elseif ($Current_time == 3) {
    $WHERE .= " AND DATE(videos.uploaded_on) = CURDATE() ";
}


$Videos          = new Videos($DB, $_USER);
$Videos->Blocked = false;
$Videos->WHERE_C = " AND $WHERE";
$Videos->LIMIT   = $_PAGINATION;

if ($Current_Order !== "md") {

    $Videos->ORDER_BY = $ORDER_BY;

} else {

    $Videos->Distinct   = true;
    $Videos->JOIN       = "INNER JOIN video_comments ON videos.url = video_comments.url";
    $Videos->ORDER_BY   = "(SELECT count(DISTINCT video_comments.by_user) as amount FROM video_comments WHERE video_comments.url = videos.url) DESC";

}


$Videos->get();
$Videos = $Videos->fixed();


$Video_Amount           = new Videos($DB, $_USER);
$Video_Amount->LIMIT    = 320;
$Video_Amount->WHERE_C  = " AND $WHERE";
$Video_Amount->Uploader = true;
$Video_Amount->Blocked  = false;
$Video_Amount->Count    = true;

$_PAGINATION->Total = $Video_Amount->get();


$_PAGE->set_variables(array(
    "Page_Title"        => "Videos - VidLii",
    "Page"              => "Videos",
    "Page_Type"         => "Videos"
));
require_once "_templates/page_structure.php";