<?php
session_start();
require_once("connection.php");

if(isset($_POST['delete'])) {
    $checks = implode("','", $_POST['checkbox']);
    $query = "DELETE FROM gyventojai WHERE id in ('$checks');";
    if(mysqli_query($connection, $query)) {
        $_SESSION['success'] = "Duomenys sėkmingai ištrinti";
    }
    else {
        $_SESSION['error'] = "Nepavyko ištrinti duomenų";
    }

    header("Location: index.php");
}
?>