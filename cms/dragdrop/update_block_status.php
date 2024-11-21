<?php 
// database connectie en inlogfuncties
// ===================================
include '../login/config.php';
include '../login/functions.php';
session_start();

// checken of men wel is ingelogd
// ==============================
login_check_v2();

$action = $mysqli->real_escape_string($_POST['action']); 
$checked = $mysqli->real_escape_string($_POST['checked']); 
$id = $mysqli->real_escape_string($_POST['block']);

if ($action == "actiefBlock"){
			
    if($checked == "true") {
        $query = "UPDATE sitework_block SET actief = '1' WHERE id = " . $id;
        $resultdrag = $mysqli->query($query) or die($mysqli->error.__LINE__);
        
        echo "
        <div class=\"alert alert-success\">
            Blok actief
        </div>
        ";
    } else {
        $query = "UPDATE sitework_block SET actief = '0' WHERE id = " . $id;
        $resultdrag = $mysqli->query($query) or die($mysqli->error.__LINE__);
        
        echo "
        <div class=\"alert alert-success\">
            Blok uit gezet
        </div>
        ";
    }
}
?>