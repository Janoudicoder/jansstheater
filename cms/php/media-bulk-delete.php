<?php

//SCRIPT OPBOUWEN
ob_start();

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
 
// database connectie en inlogfuncties
// ===================================
require("../login/config.php");
require("../ftp/config.php");
include '../login/functions.php';

session_start();

// checken of men wel is ingelogd
// ==============================
login_check_v2();

if (isset($_POST['media_ids']) && $_POST['media_ids']) {
    $bulkDelete = $_POST['media_ids'];

    foreach ($bulkDelete as $media):
        $sql2 = $mysqli->query("SELECT * FROM sitework_mediabibliotheek WHERE id = '".$media."'") or die($mysqli->error.__LINE__);
        $row2 = $sql2->fetch_assoc();
        
        if($row2['media'] == 'afbeelding') {
            //eerst de mappen open zetten
            ftp_site($ftpstream, $pad_img_open);
            ftp_site($ftpstream, $pad_webp_open);

            unlink($path.$row2['naam'].".".$row2['ext']); //foto verwijderen
            unlink($path.$row2['naam']."_tn.".$row2['ext']); //thumbnail verwijderen
            unlink($path.$row2['naam']."_mid.".$row2['ext']); //foto mid verwijderen
            unlink($path.$row2['naam']."_full.".$row2['ext']); //foto full verwijderen

            unlink($path."webp/".$row2['naam'].".webp"); //foto webp verwijderen
            unlink($path."webp/".$row2['naam']."_tn.webp"); //foto webp thumbs verwijderen
            unlink($path."webp/".$row2['naam']."_mid.webp"); //foto webp mid verwijderen
            unlink($path."webp/".$row2['naam']."_full.webp"); //foto webp full verwijderen

            ftp_site($ftpstream, $pad_img_dicht);
            ftp_site($ftpstream, $pad_webp_dicht);

            $sql_delImages = $mysqli->query("DELETE FROM sitework_img WHERE naam = '".$media."' ") or die($mysqli->error.__LINE__);
        } else {
            ftp_site($ftpstream,$pad_doc_open);

            unlink($doc_path.$row2['naam'].".".$row2['ext']); //document verwijderen

            ftp_site($ftpstream,$pad_doc_dicht);

            $sql_delDocs = $mysqli->query("DELETE FROM sitework_doc WHERE url = '".$media."' ") or die($mysqli->error.__LINE__);
        }

        $sql_del = $mysqli->query("DELETE FROM sitework_mediabibliotheek WHERE id = '".$media."' ") or die($mysqli->error.__LINE__);
    endforeach;
}
?>