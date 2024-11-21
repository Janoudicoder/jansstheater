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

error_log(print_r($_POST, true));

if (isset($_POST['media_id'])) {
    $media_soort = $_POST['media_soort'];
    $media_id = explode('-', $_POST['media_id']);
    $mediabieb_id = $media_id[0];
    $soort_id = $media_id[1];
    $media_cms_id = $_POST['media_cms_id'];
    $media_block_id = $_POST['media_block_id'];

    $media = $mysqli->prepare("SELECT * FROM sitework_mediabibliotheek WHERE id = ?") or die($mysqli->error . __LINE__);
    $media->bind_param("i", $mediabieb_id);
    $media->execute();
    $result = $media->get_result();
    $media_data = $result->fetch_assoc();

    if($media_soort && $media_soort == 'afbeelding') {
        $data = $mysqli->prepare("SELECT * FROM sitework_img WHERE id = ? AND cms_id = ? AND block_id = ?") or die($mysqli->error . __LINE__);
        $data->bind_param("iii", $soort_id, $media_cms_id, $media_block_id);
    } elseif($media_soort && $media_soort == 'document') {
        $data = $mysqli->prepare("SELECT * FROM sitework_doc WHERE id = ? AND cms_id = ?") or die($mysqli->error . __LINE__);
        $data->bind_param("ii", $soort_id, $media_cms_id);
    }

    $data->execute();
    $get_result_data = $data->get_result();
    $result_data = $get_result_data->fetch_assoc();
    
    if ($media_data) {
        $response = array(
            'status' => 'success',
            'media' => $media_data,
            'details' => $result_data,
            'message' => 'Media data retrieved successfully.'
        );
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'No media found with the provided ID.'
        );
    }
    
    header('Content-Type: application/json');
    
    echo json_encode($response);
} elseif(isset($_POST['bg_id'])) {
    $BG_ID = $_POST['bg_id'];

    $BG = $mysqli->prepare("SELECT * FROM siteworkcms_background WHERE id = ?") or die($mysqli->error . __LINE__);
    $BG->bind_param("i", $BG_ID);
    $BG->execute();
    $result_BG = $BG->get_result();
    $BG_data = $result_BG->fetch_assoc();

    $media = $mysqli->prepare("SELECT * FROM sitework_mediabibliotheek WHERE id = ?") or die($mysqli->error . __LINE__);
    $media->bind_param("i", $BG_data['media_id']);
    $media->execute();
    $result = $media->get_result();
    $media_data = $result->fetch_assoc();
    
    if ($BG_data) {
        $response = array(
            'status' => 'success',
            'bg' => $BG_data,
            'media' => $media_data,
            'message' => 'Media data retrieved successfully.'
        );
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'No media found with the provided ID.'
        );
    }
    
    header('Content-Type: application/json');
    
    echo json_encode($response);
} else {
    $response = array(
        'status' => 'error',
        'message' => 'No media ID provided.'
    );
    header('Content-Type: application/json');
    echo json_encode($response);
}

