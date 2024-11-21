<?php 
ob_start();
header("Cache-Control: no-cache");
// session_cache_limiter('private-no-expire'); 
// database connectie en inlogfuncties
// ===================================
include ('login/config.php');
include ('login/functions.php');

// veilig een sessie starten en checken of men wel is ingelogd
// ===========================================================
session_start();
login_check_v2();

// php functies cms aanroepen
// ==========================
include 'php/functies.php';

// bijbehorende gebruiker ophalen om niveau te bepalen
// ===================================================
$sqluser = $mysqli->query("SELECT * FROM siteworkcms_gebruikers WHERE id = '".$_SESSION['id']."' ") or die($mysqli->error.__LINE__);
$rowuser = $sqluser->fetch_assoc();

if($_GET['cookie'] == 'off') {
  setcookie('pop-up', 'uit', time() + + (2 * 3600), "/"); // 7200 = 2 uur
}

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $versie_directory);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$headers = [
  'Authorization: Basic ' . $rowinstellingen['api_key_verificatie'],
  'Domain: ' . $rowinstellingen['domeinnaam'],
  'Version: ' . $rowinstellingen['CMS_versie']
];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);

if(curl_errno($ch)){
    echo 'Curl error: ' . curl_error($ch);
    exit;
}

curl_close($ch);

$data = json_decode($response, true);

?>

<!DOCTYPE html>
<html xmlns="https://www.w3.org/1999/xhtml" xml:lang="nl" lang="nl">
  <head>
    <title><? if ($rowinstellingen['branding'] == "oviiontwerp") { echo 'Ovii Ontwerp CMS'; } 
    elseif ($rowinstellingen['branding'] == "puremotion") { echo 'Puremotion CMS'; }
    elseif ($rowinstellingen['branding'] == "reclamemakers") { echo 'Reclamemakers CMS'; }
    else { echo 'Sitework CMS'; } ?> | <? echo $sitenaam; ?></title> 
    <meta charset="UTF-8"/>
    <meta name="author" content="SiteWork BV">
    <meta name="robots" content="noindex, nofollow" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="shortcut icon" href="<? echo $url; ?>/cms/sitework.ico"/>
    <link rel="icon" type="image/vnd.microsoft.icon" href="<? echo $url; ?>/cms/sitework.ico"/>
    <link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/css/stylesheet.css">
    <link rel='stylesheet' type='text/css' href='<? echo $url; ?>/cms/css/branding-stylesheet.php' />
    
    <link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/datepick/jquery-ui-date.css">
    <link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/fancybox/jquery.fancybox.min.css">
    <link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,200i,300,300i,400,400i,600,600i,700,700i,900,900i">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Khand:300,400,500,600,700">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap">

    <link rel="stylesheet" href="<? echo $url; ?>/cms/richtexteditor/rte_theme_default.css" />
    <script type="text/javascript" src="<? echo $url; ?>/cms/richtexteditor/rte.js"></script>
    <script type="text/javascript" src='<? echo $url; ?>/cms/richtexteditor/plugins/all_plugins.js'></script>
    <script type='text/javascript' src="<? echo $url; ?>/cms/richtexteditor/lang/rte-lang-nl.js"></script>

    <script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery-ui-1-12-1.min.js"></script>  
    <script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery.fancybox.js"></script>
    <script type="text/javascript" src="<? echo $url; ?>/cms/datepick/jquery-ui-date.js"></script>
    <script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery-nested-sortable.js"></script>  
    <script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/sitework.js"></script>
    <script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery.sticky-kit.min.js"></script>
  </head>
  <body>

    <div class="sectie-main">
      <div class="sectie-inner">
        <div id="topbar">
            <a href="<?=$url;?>" target="_blank" rel="noopener" rel="noreferrer">
          <? if ($rowinstellingen['branding'] == "sitework") { ?><img id="logo" src="images/sitework.svg" height="40"><? } ?>
          <? if ($rowinstellingen['branding'] == "puremotion") { ?><img id="logo" src="images/puremotion.svg" height="40"><? } ?>
          <? if ($rowinstellingen['branding'] == "reclamemakers") { ?><img id="logo" src="images/reclame-makers.svg" class="reclame" height="60"><? } ?>
          <? if ($rowinstellingen['branding'] == "oviiontwerp") { ?><img id="logo" src="images/oviiontwerp.svg" height="40"><? } ?>
            </a>
          <button class="c-hamburger c-hamburger--htx">
			      <span></span>
          </button>
          <div id="open-website">
            <?php $domainLink = explode("://", $url)[1]; ?>
            <a href="<?=$url;?>" target="_blank" rel="noopener" rel="noreferrer"><span class="domainname"><?=$domainLink;?></span><i class="far fa-external-link"></i></a>
          </div>
          <div id="nieuw">
            <a href="#" class="nieuw_menu"><span class="username">Nieuw</span><span class="fas fa-plus"></span></a>
            <div class="nieuw_opties">            
              <a href="?page=nieuwe_pagina"><span class="menu-icon fas fa-copy"></span>nieuwe pagina</a>
              <a href="?page=nieuwe_gebruiker"><span class="menu-icon fas fa-user"></span>nieuwe gebruiker</a>
            </div>
          </div>
          <div id="updates">
            <?php
            if($rowinstellingen['cmspakket'] == 'standaard' OR $rowinstellingen['api_key_verificatie'] == ""): ?>
              <a data-fancybox data-small-btn="true" data-type="inline" href="#openCmsUpdate" href="javascript:;"><span class="menu-icon fas fa-wrench"></span></a>
              <div id="openCmsUpdate" class="openCmsUpdate" style="display:none;">
                <h2>CMS updates zitten niet in uw pakket.</h2>
                Wilt u upgraden? Neem contact met ons op.<br><br>
                <a class="btn fl-left" href="https://sitework.nl/contact" target="_blank" rel="noopener" rel="noreferrer">Contact</a>
              </div>
            <?php else:
              if($data && !$data['error']){
                $updateMelding = 'update';
              } else { $updateMelding = ''; }
              ?>
              <i class="update-melding <?=$updateMelding;?>"></i>
              <a href="?page=updates"><span class="menu-icon fas fa-wrench"></span></a>
            <?php endif; ?>
          </div>
          <div id="gebruiker">
            <a href="#" class="gebruiker_menu"><span class="menu-icon fas fa-user"></span><span class="username"><?php echo $rowuser['username'] ?></span><span class="ti-angle-down"></span></a>
            <div class="gebruiker_opties">            
              <a href="?page=gebruiker_bewerken&id=<?php echo $rowuser['id']; ?>"><span class="menu-icon fas fa-cog"></span>mijn account</a>
              <a href="index.php?uitloggen=ja"><span class="menu-icon fas fa-lock"></span>uitloggen</a>
            </div>
          </div>
        </div>
        <?php include ('php/hoofdmenu.php'); ?>
        <div id="content">
          <?php  if (!$_GET['page'] or $_GET['page'] == "dashboard") { include ('php/dashboard.php'); }
          elseif ($_GET['makelaar'] == "ja") { include ('php/makelaar/'.$_GET['page'].'.php'); }
          else { include ('php/'.$_GET['page'].'.php'); } ?>

          <div class="cms-versie">
            <i>Dit CMS is gemaakt door <a href="https://sitework.nl" target="_blank" rel="noopener" rel="noreferrer">Sitework</a></i>
            <p>Versie <?=$siteversion;?></p>
          </div>
        </div>
      </div>
    </div>

    <?php
      if($data && !$data['error'] && $_COOKIE['pop-up'] <> "uit" && $rowinstellingen['api_key_verificatie'] != "" && $_GET['cookie'] != 'off' && $rowinstellingen['meldingen'] == '0') { ?>
        <div class="pop-up-update">
          <div class="bg-white rounded-md shadow-lg p6">
            <a class="close" href="maincms.php?page=dashboard&cookie=off"><i class="fas fa-plus"></i></a>
            <h2>Er is een update beschikbaar voor het CMS</h2>
            <a href="?page=updates&cookie=off" class="btn arrow">Lees meer</a>
          </div>
        </div>
      <?php } ?>
  </body>
</html>