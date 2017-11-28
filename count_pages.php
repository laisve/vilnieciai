<?php
$rows_per_page = 100;
$pageNum = 1;

if(isset($_GET['page'])) {
    $pageNum = $_GET['page'];
}

$offset = ($pageNum - 1) * $rows_per_page;

$qr = "SELECT COUNT(*) as total FROM gyventojai;";
$row = fetch_record($qr);
$numrows = $row['total'];

$maxpage = ceil($numrows/$rows_per_page);

$self = $_SERVER['PHP_SELF'];
$nav = '';

for($page = 1; $page <= $maxpage; $page++) {
    if($page == $pageNum) {
        $nav .= "<option selected>$page</option>";
    }
    else {
        $nav .= "<option value='". $page . "'>" . $page . "</option>";
    }
}

if($pageNum > 1) {
    $page = $pageNum - 1;
    
    $prev = "<li class='page-item'><a class='page-link' href=\"$self?page=$page\">Ankstesnis</a></li>";
    $first = "<li class='page-item'><a class='page-link' href=\"$self?page=1\">Pirmas</a></li>";
}
else {
    $prev = '&nbsp;';
    $first = '&nbsp;';
}

if($pageNum < $maxpage) {
    $page = $pageNum + 1;
    $next = "<li class='page-item'><a class='page-link' href=\"$self?page=$page\">Kitas</a></li>";
    $last = "<li class='page-item'><a class='page-link' href=\"$self?page=$maxpage\">Paskutinis</a></li>";
}
else {
    $next = '&nbsp;';
    $last = '&nbsp;';
}
