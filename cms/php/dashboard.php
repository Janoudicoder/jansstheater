<?php
// checken of men wel is ingelogd
// ==============================
login_check_v2();

// cookiemelding actief ja/nee
// ==============================
$cookiemelding = $mysqli->query("SELECT * FROM sitework_cookies WHERE id = '1'") or die($mysqli->error.__LINE__);
$rowcookiemelding = $cookiemelding->fetch_assoc();

// laatste 5 ingelogde gebruikers ophalen
// ======================================
$sqllogin = $mysqli->query(" SELECT *,DATE_FORMAT(datum, '%d-%m-%Y') AS datum_inlog, DATE_FORMAT(datum, '%H:%i') AS tijd_inlog FROM sitework_login_log ORDER BY datum DESC LIMIT 5") or die($mysqli->error.__LINE__);

// laatste 5 gewijzigde pagina's ophalen
// =====================================
$sqllast = $mysqli->query("SELECT *,DATE_FORMAT(laatste_wijziging, '%d-%m-%Y') AS lastedit_datum ,DATE_FORMAT(laatste_wijziging, '%H:%i uur') AS lastedit_tijd FROM siteworkcms WHERE status <> 'prullenbak' ORDER BY laatste_wijziging DESC LIMIT 5") or die($mysqli->error.__LINE__);

// leeftijd website berekenen
// ==========================
$date1 = new DateTime($rowinstellingen['livegezet_datum']);
$date2 = new DateTime(date('Y-m-d'));
$interval = $date1->diff($date2);

$sdate = $rowinstellingen['livegezet_datum'];
$edate = date('Y-m-d');

$date_diff = abs(strtotime($edate) - strtotime($sdate));

$jaren = floor($date_diff / (365*60*60*24));
$maanden = floor(($date_diff - $jaren * 365*60*60*24) / (30*60*60*24));
$dagen = floor(($date_diff - $jaren * 365*60*60*24 - $maanden*30*60*60*24)/ (60*60*24));

?>

<div class="box-container">
    <div class="box box-1-3 md-box-full sm-box-full">
        <h3 class="!full"><span class="icon far fa-user"></span>Welkom <?=$rowuser['username']; ?></h3>
        <p>Met SiteWork CMS kunt u, uw website volledig beheren. Zo kunt u bijvoorbeeld nieuwe pagina`s aanmaken en bewerken, foto`s toevoegen en documenten plaatsen.<br /><br />
        Ook kunt u wat meer geavanceerde wijzigingen doorvoeren, zoals het wijzigen van URL`s en meta data
        t.b.v. het optimaliseren van de vindbaarheid in zoekmachines.<br /><br />
        Veel succes met het beheren van uw website!</p>
    </div>
    <div class="box box-1-3 md-box-1-2 sm-box-full">
        <h3><span class="icon far fa-info-circle"></span>Websiteinfo</h3>
        <div class="row webinfo">
            <span class="fat">Domeinnaam:</span> <?php echo $url; ?>
        </div>
        <?php if($rowuser['id'] == '1'): ?>
            <div class="row webinfo">
                <span class="fat">Provider:</span> Sitework B.V.
            </div>
            <div class="row webinfo">
                <span class="fat">Host name:</span> <?php  echo gethostname(); ?>
            </div>
            <div class="row webinfo">
                <span class="fat">Host ip:</span> <?php echo $_SERVER['SERVER_ADDR']; ?>
            </div>
            <div class="row webinfo">
                <span class="fat">SSL geactiveerd:</span> <?php if ( isset($_SERVER['HTTPS']) ) { echo 'ja'; } else {echo 'nee';} ?>
            </div>
        <?php endif; ?>
        <div class="row webinfo">
            <span class="fat">Cookiemelding:</span> <?php if ($rowcookiemelding['status'] == "actief") { echo 'ingeschakeld'; } else echo 'uitgeschakeld'; ?>
        </div>
        <div class="row webinfo">
            <span class="fat">Live sinds:</span>
            <?php if ($rowinstellingen['livegezet_datum'] <> '0000-00-00'){
                if ($jaren > 0 && $maanden > 0 && $dagen > 0) printf("%d jaar, %d maand(en), %d dag(en)", $jaren, $maanden, $dagen);
                elseif ($jaren > 0 && $maanden == 0 && $dagen > 0) printf("%d jaar, %d dag(en)", $jaren, $dagen);
                elseif ($jaren == 0 && $maanden > 0 && $dagen >= 0) printf("%d maand(en), %d dag(en)", $maanden, $dagen);
                elseif ($jaren == 0 && $maanden == 0 && $dagen == 0) { printf("vandaag!"); }
                else { printf("%d dag(en)", $dagen); }
            } else { echo 'in ontwikkeling';}
            ?>
        </div>
    </div>
    <div class="box box-1-3 md-box-1-2 sm-box-full ">
        <h3><span class="icon far fa-lock"></span>Laatst ingelogd</h3>
        <div class="row login type">
            <div class="col">datum</div>
            <div class="col extra-sm-mob-hide">tijd</div>
            <div class="col">naam</div>
            <?php /* <div class="col">ip adres</div> */ ?>
        </div>
        <?php while ($rowlogin = $sqllogin->fetch_assoc()){
        // bijbehorende gebruiker ophalen
        // ===================================================
        $sqluser = $mysqli->query("SELECT * FROM siteworkcms_gebruikers WHERE id = '".$rowlogin['user_id']."' ") or die ($mysqli->error.__LINE__);
        $rowuser = $sqluser->fetch_assoc(); ?>

        <div class="row login">
            <div class="col"><?php echo $rowlogin['datum_inlog']; ?></div>
            <div class="col extra-sm-mob-hide"><?php echo $rowlogin['tijd_inlog']; ?></div>
            <div class="col"><?php echo $rowuser['username']; ?></div>
            <?php /* <div class="col"><?php echo $rowlogin['ip']; ?></div> */ ?>
        </div>

        <?	} ?>
    </div>
</div>

<div class="box-container">
    <div class="box box-2-3 md-box-full sm-box-full">
        <h3><span class="icon far fa-clock"></span>Laatst gewijzigde pagina's</h3>
        <div class="row laatstgewijzigd type">
            <div class="col">menutitel</div>
            <div class="col sm-ipad-hide">categorie</div>
            <div class="col sm-mob-hide">datum</div>
            <div class="col sm-ipad-hide">tijd</div>
            <div class="col sm-mob-hide center">actief ja/nee</div>
            <div class="col center">bewerk</div>
        </div>

        <?php while ($rowlast = $sqllast->fetch_assoc()){ ?>
            <div class="row laatstgewijzigd">
                <div class="col"><?php echo ucfirst($rowlast['item1']); ?></div>
                <div class="col sm-ipad-hide"><?php echo $rowlast['keuze1']; ?></div>
                <div class="col sm-mob-hide"><?php echo $rowlast['lastedit_datum']; ?></div>
                <div class="col sm-ipad-hide"><?php echo $rowlast['lastedit_tijd']; ?></div>
                <div class="col sm-mob-hide center"><?php if ($rowlast['status'] == "Actief") { echo '<span class="actief"></span>'; } else { echo '<span class="inactief"></span>'; } ?></div>
                <div class="col center"><a class="edit" href="?page=pagina_bewerken&id=<?php echo $rowlast['id']; ?>"><span class="far fa-edit"></span></a></div>
            </div>
        <?php } ?>
    </div>

    <div class="box box-1-3 md-box-full sm-box-full">
        <h3 class="!full"><span class="icon far fa-envelope"></span>Stel een vraag</h3>
            <?php include ('php/steluwvraag.php'); ?>
    </div>
</div>
