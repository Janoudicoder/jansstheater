<?php
// vul hier alle databasegegevens in
// =================================

define("HOST", "localhost"); // host 
define("USER", "janssthea_db"); // gebruikersnaam database
define("PASSWORD", "c6YLjnh4HsHTagPQnz3f"); // wachtwoord database
define("DATABASE", "janssthea_db"); // naam database 

$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
// if you are connecting via TCP/IP rather than a UNIX socket remember to add the port number as a parameter
// =========================================================================================================
mysqli_set_charset($mysqli, 'utf8');

// instellingen uit cms ophalen
// ============================
$instellingen = $mysqli->query("SELECT * FROM sitework_settings WHERE id = '1'") or die($mysqli->error.__LINE__);
$rowinstellingen = $instellingen->fetch_assoc();

// Realworks instellingen
// ======================
$realworks_settings = $mysqli->query("SELECT * FROM sitework_realworks WHERE id = '1'") or die($mysqli->error.__LINE__);
$realworks_setting = $realworks_settings->fetch_assoc();

// url website
// ===========
if ($rowinstellingen['weburl'] <> "") { $url = $rowinstellingen['weburl']; }
else { $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]"."/~".$ftpuser; }

    // check alle GET en POSTS voor special characters, voorkomt mysql injection
    // =========================================================================
    foreach($_GET as $keyget => $valueget){
        if ($keyget != 'q' && preg_match('/[\'\/~`\!@\$%\^&\*\(\)\+=\{\}\[\]\|;:"\<\>,\?\\\]/', $valueget)) {
            header('Location: '.$url.''); exit;
        }
    }
    // checken of hij niet in het CMS zit! anders kun je niets opslaan
    // ===============================================================
    if (!preg_match("/cms/i", $_SERVER['PHP_SELF'])) {
        foreach($_POST as $keypost => $valuepost){
            //als een checkbox is geset gaan we eerst deze Array imploden
            if (is_array($valuepost))
                {   
                    $valuepost = implode(" en ", $valuepost);
                }
            if (preg_match('/[\~\#\$%\^\*\{\}\|\\]]/', $valuepost)) {
                header('Location: '.$url.''); exit;
            }
        }  // stript ~ # $ % ^ * { } [ ] | ; < >
    }

// bedrijfsnaam invoeren voor opmaak titels/meta
// =============================================
$sitenaam = $rowinstellingen['naamwebsite'];
$sitepakket = $rowinstellingen['cmspakket'];
$siteversion = $rowinstellingen['CMS_versie'];

// API
// ===
$versie_directory = 'https://api.sitework.nl/releases/'.$sitepakket.'/index.php';

// bedrijfscontactgegevens
// =============================================
$site_email = $rowinstellingen['websiteemail'];
$site_telnr = $rowinstellingen['websitetelnr'];

// domeinnaam
// ==========
$domein = $rowinstellingen['domeinnaam'];

// Realworks
// =========
$rw_key = $realworks_setting['rw_api'];

// google maps key
// ===============
$gmkey = $rowinstellingen['gmapskey'];

// Website beveiliging
// ===================
$ws_beveiliging = $rowinstellingen['website_beveiliging'];

// google recaptcha keys
// ===============
$hc_client_key = $rowinstellingen['hcaptcha_clientkey'];
$hc_secret_key = $rowinstellingen['hcaptcha_secretkey'];

// google recaptcha keys
// ===============
$rc_client_key = $rowinstellingen['recaptcha_clientkey'];
$rc_secret_key = $rowinstellingen['recaptcha_secretkey'];

// tijdzone op nederland zetten
// ============================
setlocale(LC_ALL, 'nl_NL');
$mysqli->query("SET lc_time_names = 'nl_NL'");
$mysqli->query("SELECT @@lc_time_names");

?>
