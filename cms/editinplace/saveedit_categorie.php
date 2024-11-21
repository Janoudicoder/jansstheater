<?php ob_start(); 
require("../login/config.php");
include '../login/functions.php';
session_start();

// checken of men wel is ingelogd
// ==============================
login_check_v2();

$sql_ins = $mysqli->query("UPDATE sitework_categorie set " . $_POST["catTaal"] . " = '".$_POST["editval"]."' WHERE id=".$_POST["id"]."") or die($mysqli->error.__LINE__);
?>