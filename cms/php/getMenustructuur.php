<?php
// ini_set('display_errors', 1);

ob_start();
// database connectie en inlogfuncties
// ===================================
include ("../login/config.php");
include ('../login/functions.php');
session_start();

function getPagesNotMenu() {

	$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
	mysqli_set_charset($mysqli, 'utf8');
	$sqlPageNotMenu = $mysqli->query("SELECT * FROM siteworkcms WHERE in_menu = 0 AND status <> 'prullenbak' ORDER BY id DESC") or die($mysqli->error.__LINE__);

    while($rowPageNotMenu = $sqlPageNotMenu->fetch_assoc()){
        if($rowPageNotMenu['id'] != "2" && $rowPageNotMenu['id'] != "69") {
            $pagesNotMenu[] = $rowPageNotMenu;
        }
    }
    return $pagesNotMenu;
}

$response = ''; 

//get the q parameter from URL
if(isset( $_GET['q'] )) { $q=$_GET["q"]; } else { $q = "";}

if (!isset($_GET['q'])) {
    foreach (getPagesNotMenu() as $notMenu) { 
        $response .= '<div class="menu-check">';
            $response .= '<input type="checkbox" id="'.$notMenu['keuze1'].'-'.$notMenu['id'].'" class="menu-check-box" name="notmenu[]" value="'.$notMenu['id'].'">';
            $response .= '<label for="'.$notMenu['keuze1'].'-'.$notMenu['id'].'">'.$notMenu['item2'].'</label>';
        $response .= '</div>';
    }
} else {
    $sqlZoek = $mysqli->query("SELECT * FROM siteworkcms WHERE (item1 LIKE '%".$q."%' or item2 LIKE '%".$q."%') AND in_menu = 0 AND status <> 'prullenbak'") or die($mysqli->error.__LINE__);

    while($rowZoek = $sqlZoek->fetch_assoc()) {
        if($rowZoek['id'] != '2' && $rowZoek['id'] != '69') {
            $response .= '<div class="menu-check">';
                $response .= '<input type="checkbox" id="'.$rowZoek['keuze1'].'-'.$rowZoek['id'].'" class="menu-check-box" name="notmenu[]" value="'.$rowZoek['id'].'">';
                $response .= '<label for="'.$rowZoek['keuze1'].'-'.$rowZoek['id'].'">'.$rowZoek['item2'].'</label>';
            $response .= '</div>';
        }
        
    }
}

//output the response
echo $response;
?>