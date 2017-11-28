$(document).ready(function() {
    $("#page-nav").on("change", function() {
        var loadPage = $("#page-nav").val();
        window.location.href = "index.php?page=" + loadPage;
    });
    
    $("#back").click(function() {
        var loadPage = $("#page-nav").val();
        window.location.href = "index.php";
    });
});