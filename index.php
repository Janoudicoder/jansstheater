<?php 
require "cms/login/config.php";
session_start(); 
ob_start(); 

// eerst checken of de site online staat
// =====================================
($sitelive = $mysqli->prepare(
    "SELECT offline FROM sitework_settings WHERE id = ? "
)) or die($mysqli->error . __LINE__);
$siteliveId = "1";
$sitelive->bind_param("i", $siteliveId);
$sitelive->execute();
$sitelive->store_result();
$sitelive->bind_result($siteStatus);
$sitelive->fetch();
if ($siteStatus == "ja") {
    header("Location: /offline.php");
}

// standaard taal instellen
// ========================
if (!$_GET["taal"]) {
    $_GET["taal"] = "nl";
} 
// toevoeging query voor meertaligheid indien ingesteld cms
// ========================================================
$taalSubmenuCheck = "";
if ($rowinstellingen["meertaligheid"] == "ja") {
    $taalquery = "AND taal LIKE '" . $_GET["taal"] . "'";
    $taalSubmenuCheck = $_GET["taal"] . "/";
}

// Taaltemplate met vertalingen voor knoppen en teksten
// ====================================================
include 'php/taaltemplate.php';

// cookiemelding laten zien ja/nee
// ===============================
($cookiemelding = $mysqli->prepare(
    "SELECT status FROM sitework_cookies WHERE id = ? "
)) or die($mysqli->error . __LINE__);
$cookieId = "1";
$cookiemelding->bind_param("i", $cookieId);
$cookiemelding->execute();
$cookiemelding->store_result();
$cookiemelding->bind_result($cookieStatus);
$cookiemelding->fetch();

// voorwaarde startpagina ophalen
// ==============================
if (!$_GET["page"] && !$_GET["title"]) {
    ($sql = $mysqli->prepare(
        "SELECT *,DATE_FORMAT(datum, '%W %e %M %Y') as datum1 FROM siteworkcms WHERE id = ? $taalquery AND status = 'actief'"
    )) or die($mysqli->error . __LINE__);
    $voorwaarde = 1;
    $sql->bind_param("i", $voorwaarde);
} elseif($_GET['taal'] != 'nl') {
    ($sql = $mysqli->prepare(
        "SELECT * FROM sitework_vertaling WHERE veld = 'paginaurl' AND waarde = ? $taalquery")) or die($mysqli->error . __LINE__);
    $voorwaarde = $_GET["title"];
    $sql->bind_param("s", $voorwaarde);
} elseif($_GET['title'] == 'clear' || ($_GET["page"] == 'wonen' && $_GET['title'] != 'page') || isset($_GET['beginnenbij'])) {
    ($sql = $mysqli->prepare(
        "SELECT *,DATE_FORMAT(datum, '%W %e %M %Y') as datum1 FROM siteworkcms WHERE paginaurl = ? $taalquery AND status = 'actief'"
    )) or die($mysqli->error . __LINE__);
    $voorwaarde = $_GET["page"];
    $sql->bind_param("s", $voorwaarde);
} else {
    ($sql = $mysqli->prepare(
        "SELECT *,DATE_FORMAT(datum, '%W %e %M %Y') as datum1 FROM siteworkcms WHERE paginaurl = ? $taalquery AND status = 'actief'"
    )) or die($mysqli->error . __LINE__);
    $voorwaarde = $_GET["title"];
    $sql->bind_param("s", $voorwaarde);
}
$sql->execute();
$result = $sql->get_result();
$row = $result->fetch_assoc();

if($_GET['taal'] != 'nl') { $row['id'] = $row['cms_id']; }
// redirect naar 404 als pagina niet in cms voor komt
if (!$row["id"] and $_GET["page"] != "nieuws" AND $_GET["page"] != "updates" AND $_GET['page'] <> 'zoeken' AND $_GET["page"] != "woning" AND $_GET["page"] != "wonen") {
    $paginaNietGevondenCheck = $mysqli->query("SELECT waarde FROM sitework_vertaling WHERE cms_id = '2' AND veld = 'paginaurl' AND taal = '".$_GET['taal']."'") or die($mysqli->error.__LINE__);
    $PNGcheck = $paginaNietGevondenCheck->fetch_assoc();

    if($paginaNietGevondenCheck->num_rows > 0) {
        header("Location: " . $url . "/" . $PNGcheck['waarde']);
    } else {
        header("Location: " . $url . "/pagina-niet-gevonden");
    }
}

if($_GET['taal'] != 'nl') { 
    $vertaalID = $row['id'];
    $sqlVertaal = $mysqli->prepare("SELECT veld, waarde FROM sitework_vertaling WHERE cms_id = ?");
    $sqlVertaal->bind_param("i", $vertaalID);
    $sqlVertaal->execute();
    $resultVertaal = $sqlVertaal->get_result();

    $row = [];
    $row['id'] = $vertaalID;

    while ($rowVertaal = $resultVertaal->fetch_assoc()) {
        $row[$rowVertaal['veld']] = $rowVertaal['waarde'];
    }
}

$post_id = $row['id'];
$post_taal = $_GET['taal'];

//zorg dat sub pagina alleen toegankelijk is via 1 url (dus domeinnaam/hoofdid/subid ipv domeinnaam/subid)
if ($row["keuze1"] == "submenu") {
    //haal pagina url op van hoofdid
    ($hoofdidPagina = $mysqli->prepare(
        "SELECT paginaurl FROM siteworkcms WHERE id = ? "
    )) or die($mysqli->error . __LINE__);
    $voorwaardeHoofdid = $row["hoofdid"];
    $hoofdidPagina->bind_param("i", $voorwaardeHoofdid);
    $hoofdidPagina->execute();
    $resultHoofdidPagina = $hoofdidPagina->get_result();
    $rowhoofdidPgaina = $resultHoofdidPagina->fetch_assoc();

    if (
        !preg_match(
            "/" . $rowhoofdidPgaina["paginaurl"] . "/",
            $_SERVER["REQUEST_URI"]
        )
    ) {
        header(
            "Location: " .
            $url .
            "/" .
            $taalSubmenuCheck .
            "" .
            $rowhoofdidPgaina["paginaurl"] .
            "/" .
            $row["paginaurl"] .
            "/"
        );
    }
}

// Functies voor vertalingen en data ophalen
// =========================================
include_once("php/functions.php");

if(get_meertaligheid() == true && !str_contains($_SERVER['REDIRECT_QUERY_STRING'], $post_taal)) {
    header(
        "Location: " .
        home_url()
    ); 
}

// afbeelding ophalen voor delen socialmedia
// =========================================
include "php/social-image.php";

// metatags
// ========
include "php/metatags.php";

// zoekopties makelaar
// ===================
if (get_setting("makelaar") == true && $_GET['page'] == 'woning') {
    include "php/realworks/woning-detail-api.php";
} elseif (get_setting("makelaar") == false && $_GET['page'] == 'woning') {
    header(
        "Location: " .
        home_url()
    ); 
}

if (get_setting("makelaar") == true) {
    include "php/zoekopties.php";
}
?>

<!DOCTYPE html>
<html xmlns="https://www.w3.org/1999/xhtml" xml:lang="<?php echo $post_taal;?>" lang="<?php echo $post_taal;?>">

<head>
    <title><?php echo $titel; ?> | <?php echo $sitenaam; ?></title>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="language" content="<?php $metaTaal = getCountryFullName($post_taal); echo $metaTaal?>" />
    <meta name="keywords" content="<?php echo $keywords; ?>" />
    <meta name="subject" content="<?php echo $titel;  ?>" />
    <meta name="description" content="<?php echo $description;  ?>" />
    <meta name="author" content="SiteWork Lochem - 0573 200 100" />
    <?php if (get_setting('livegezet') == '0000-00-00' OR get_setting('livegezet') == 'nee') { ?>
        <meta name="robots" content="noindex,nofollow" />
    <?php } else { ?>
        <meta name="robots" content="ALL,INDEX,FOLLOW" />
    <?php } ?>
    <meta name="revisit-after" content="1" />
    <link rel="canonical" href="<?php echo canonical_link($_SERVER['HTTP_HOST']); ?>" />
    <link rel="shortcut icon" href="<?php echo get_url(); ?>/favicon.ico" />
    <link rel="icon" type="image/vnd.microsoft.icon" href="<?php echo get_url(); ?>/favicon.ico" />

    <!-- open graph fb -->
    <meta property="og:site_name" content="<?php echo $sitenaam; ?>" />
    <meta property="og:title" content="<?php echo $titel; ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo $_SERVER["REQUEST_URI"]; ?>" />
    <meta property="og:image" content="<?php echo $socialimage; ?>" />
    <meta property="og:description" content="<?php echo str_replace('"', "'", $description); ?>" />
    <meta property="og:locale" content="<?php $metaLocale = getLocaleCode($post_taal); echo $metaLocale?>" />

    <!-- summary card twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <?php 
        $twitterUser = get_twitterUser(); 
        if($twitterUser) {
            echo '<meta name="twitter:site" content="@'.$twitterUser.'" /> ';
            echo '<meta name="twitter:creator" content="@'.$twitterUser.'" /> ';
        }
    ?>
    <meta name="twitter:title" content="<?php echo $titel; ?>" />
    <meta name="twitter:description" content="<?php echo str_replace('"', "'", $description); ?>" />
    <meta name="twitter:image" content="<?php echo $socialimage; ?>" />

    <!-- Preload -->
        <!-- Scripts -->
        <link rel="preload" href="<?php echo get_url(); ?>/jquery/jquery-3.3.1.min.js" as="script">
        <link rel="preload" href="<?php echo get_url(); ?>/jquery/sitework.min.js" as="script">

        <!-- Style -->
        <link rel="preload" href="<?php echo get_url(); ?>/fa/css/all.min.css" as="style" />
        <link rel="preload" href="<?php echo get_url(); ?>/css/stylesheet.css" as="style" />
        <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" as="style">
    <!-- Einde Preload -->

    <!-- stylesheets --> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alegreya:ital,wght@0,400..900;1,400..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo get_url(); ?>/fa/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo get_url(); ?>/css/stylesheet.css" />
    
    <!-- jquery files -->
    <script type="text/javascript" src="<?php echo get_url(); ?>/jquery/jquery-3.3.1.min.js"></script>
    <!-- <script src='https://www.google.com/recaptcha/api.js' defer></script> -->
    <script>
        function handleImageError(img) {
            var fallbackImageUrl = '<?php echo get_url();?>/img/noimg.jpg'; // Replace with your fallback image URL
            $(img).attr('src', fallbackImageUrl);
            $(img).parent().find('source').remove(); // Remove source elements within the same parent
            
            // Update the href attribute of the parent a element
            $(img).closest('a.block-img').attr('href', fallbackImageUrl);
        }
    </script>

    <?php 
    // if (get_setting("makelaar") == true) {
    //     include "php/realworks/woning-detail-api.php";
    // }
    if ($rowinstellingen['uanummer']) {
        include_once("php/analyticstracking.php");
    } 
    ?>
</head>

<body>
    <?php if ($cookieStatus == "actief") {
        include "cookie/cookie.php";
    } ?>
    
    <header><?php include('php/header.php'); ?></header>
    <main>
        <?php 
        if (get_id() == "1") {
            include('php/startpagina.php');
        } elseif(get_id() == '91') {
            include('php/zoeken.php');
        } elseif ($_GET['page'] == 'woning') {
            include 'php/realworks/woningen-detail.php';
        } else {
            include('php/vervolgpagina.php');
        } ?>
    </main>
    <footer><?php include('php/footer.php'); ?></footer>
    <nav id="mobile-cta">
        <ul>
            <li><a href="tel:<?=$site_telnr;?>" aria-label="Neem telefonisch contact op"><i class="fal fa-phone-alt"></i></a></li>
            <li><a href="mailto:<?=$site_email;?>" aria-label="Contacteer ons via de mail"><i class="fal fa-envelope"></i></a></li>
            <li><span id="open_preferences_center" class="cursor-pointer"><i class="fal fa-cookie-bite"></i></span></li>
            <li><span class="cursor-pointer scroll-to-top"><i class="fal fa-chevron-circle-up"></i></span></li>
        </ul>
    </nav>
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
    <script type="text/javascript" src="<?php echo get_url(); ?>/jquery/sitework.min.js"></script>
</body>

</html>
