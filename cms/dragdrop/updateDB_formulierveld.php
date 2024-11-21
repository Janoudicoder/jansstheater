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
$updateRecordsArray 	= $_POST['recordsArrayForm'];

if ($action == "updateRecordsListingsForm"){
	
	$listingCounter = 1;
	foreach ($updateRecordsArray as $recordIDValue) {
		
		$query = "UPDATE sitework_formuliervelden SET volgorde = " . $listingCounter . " WHERE id = " . $recordIDValue;
		$resultdrag = $mysqli->query($query) or die($mysqli->error.__LINE__);
		$listingCounter = $listingCounter + 1;	
	}
}