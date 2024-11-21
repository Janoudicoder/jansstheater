<?php ob_start(); 
require("../login/config.php");
include '../login/functions.php';
session_start();
// checken of men wel is ingelogd
// ==============================
login_check_v2();

if($_POST['editval'] == 0) {
    $sql_ins = $mysqli->query("UPDATE sitework_formuliervelden set " . $_POST["column"] . " = 0 WHERE id=".$_POST["id"]."") or die($mysqli->error.__LINE__);
} else {
    $sql_ins = $mysqli->query("UPDATE sitework_formuliervelden set " . $_POST["column"] . " = '".$_POST["editval"]."' WHERE id=".$_POST["id"]."") or die($mysqli->error.__LINE__);
}
?>