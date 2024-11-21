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
$parent_id = $mysqli->real_escape_string($_POST['parent_id']);
$item_id = $mysqli->real_escape_string($_POST['item_id']);
$updateRecordsArray = $_POST['recordsArraySub'];

if ($action == "updateRecordsListingsSub"){
	
	$listingCounter = 1;
	foreach ($updateRecordsArray as $recordIDValue) {
		
		$query = "UPDATE siteworkcms SET menu_volgorde = " . $listingCounter . " WHERE hoofdid <> '0'";
		$resultdrag = $mysqli->query($query) or die($mysqli->error.__LINE__);
		$listingCounter = $listingCounter + 1;
	}
	$queryUpdate = "UPDATE siteworkcms SET hoofdid = ".$parent_id." WHERE id = " . $item_id;
	$resultUpdateDrag = $mysqli->query($queryUpdate) or die($mysqli->error.__LINE__);
	echo "
    <div class=\"alert alert-success\">
        Submenu opgeslagen
    </div>
    ";
}
