<?php ob_start(); 
require("../login/config.php");
include '../login/functions.php';
session_start();

// checken of men wel is ingelogd
// ==============================
login_check_v2();

$sql_Check = $mysqli->query("SELECT afgerond FROM sitework_checklist_livegang WHERE id=".$_POST["checklist"]."") or die($mysqli->error.__LINE__);
$row_Check = $sql_Check->fetch_assoc();

if($row_Check['afgerond'] == '1') {
    $sql_update = $mysqli->query("UPDATE sitework_checklist_livegang set afgerond = '0' WHERE id=".$_POST["checklist"]."") or die($mysqli->error.__LINE__);
} else {
    $sql_update = $mysqli->query("UPDATE sitework_checklist_livegang set afgerond = '1' WHERE id=".$_POST["checklist"]."") or die($mysqli->error.__LINE__);
}

// echo "
//     <div class=\"alert alert-success\">
//         Checklist is up-to-date.
//     </div>
//     ";
?>