<?php 
//SCRIPT OPBOUWEN
ob_start();

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
 

// database connectie en inlogfuncties
// ===================================
require '../login/config.php';
include '../login/functions.php';
require_once 'PhpXlsxGenerator.php';

session_start();

// checken of men wel is ingelogd
// ==============================
login_check_v2();

if($_GET['soort_export']) {
    $export_soort = $_GET['soort_export'];
} else { $export_soort = ""; }

if($export_soort == 'form_logs') {
    $exportFile = "formulier_logs_" . date('Y-m-d_H-i-s') . ".xlsx";
    $exportData[] = array('Formulier naam', 'Formulier pagina', 'Naam', 'Email', 'Telefoonnummer', 'Datum verstuurd', 'Ip adres'); 

    if($_GET['datum_sort']) {
        $datum_filter = $_GET['datum_sort'];
    } else { $datum_filter = "DESC"; }

    if($_GET['formid']) {
        $formID = $_GET['formid'];
        $searchForm = "WHERE form_id = '" . $_GET['formid'] . "'";
    } else { $searchForm = ""; $formID = ""; }

    $haalExcelDataOp = $mysqli->query("SELECT * FROM sitework_formulieren_log ".$searchForm." ORDER BY datum_verzending ".$datum_filter.""); 
    if($haalExcelDataOp->num_rows > 0){ 
        while($row = $haalExcelDataOp->fetch_assoc()){ 
            $ExcelCheckForm = $mysqli->query("SELECT naam FROM sitework_formulieren WHERE id = '".$row['form_id']."'") or die($mysqli->error.__LINE__);
            $rowExcelCheckForm = $ExcelCheckForm->fetch_assoc();

            $lineData = array($rowExcelCheckForm['naam'], $url . $row['form_pagina'], $row['naam'], $row['email'], $row['tel'], $row['datum_verzending'], $row['ipadres']);  
            $exportData[] = $lineData;
        } 
    } 
}

if($export_soort <> "") {
    // Export data to excel and download as xlsx file 
    $xlsx = CodexWorld\PhpXlsxGenerator::fromArray( $exportData ); 
    $xlsx->downloadAs($exportFile); 

    exit;
} else {
    echo '<script>history.go(-1);</script>;';
}
?>