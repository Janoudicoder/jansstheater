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
$Updatetaal = $_POST['taal'];

if ($action == "taalPositie"){
	
	$listingCounter = 1;
	foreach ($Updatetaal as $taalKort) {
		$query = "UPDATE sitework_taal SET volgorde = " . $listingCounter . " WHERE actief = '1' AND taalkort = '" . $taalKort . "'";
		$resultdrag = $mysqli->query($query) or die($mysqli->error.__LINE__);
		$listingCounter = $listingCounter + 1;
	}
	echo "
    <div class=\"alert alert-success\">
        Taal volgorde opgeslagen
    </div>
    ";
}
?>