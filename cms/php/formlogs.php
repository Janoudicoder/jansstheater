<?php 
//SCRIPT OPBOUWEN
ob_start();

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
 

// database connectie en inlogfuncties
// ===================================
require("../login/config.php");
include '../login/functions.php';
session_start();

// checken of men wel is ingelogd
// ==============================
login_check_v2();

if($_GET['max']) {
    $limit = $_GET['max'];
} else { $limit = 25; }

if($_POST['datum_filter']) {
    $datum_filter = $_POST['datum_filter'];
} else { $datum_filter = "DESC"; }

if($_POST['form_filter']) {
    $formID = $_POST['form_filter'];
    $searchForm = "WHERE form_id = '" . $_POST['form_filter'] . "'";
} else { $searchForm = ""; $formID = ""; }

$allLogs = $mysqli->query("SELECT id FROM sitework_formulieren_log ORDER BY datum_verzending DESC") or die($mysqli->error.__LINE__);
$totalLogs = $allLogs->num_rows;

$formLogs = $mysqli->query("SELECT * FROM sitework_formulieren_log ".$searchForm." ORDER BY datum_verzending ".$datum_filter." LIMIT ".$limit."") or die($mysqli->error.__LINE__);
$logsShow = $formLogs->num_rows;

?>

<TITLE>SiteWork CMS afbeelding bewerken</TITLE>
<meta charset="UTF-8" />
<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/cms/css/stylesheet.css">
<link rel='stylesheet' type='text/css' href='<?php echo $url; ?>/cms/css/branding-stylesheet.php' />
<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/cms/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/cms/font-awesome/css/all.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/cms/css/themify-icons.css">

<script type="text/javascript" src="<?php echo $url; ?>/cms/jquery/files/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/sitework.js"></script>
<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery.sticky-kit.min.js"></script>
<script type="text/javascript" src="<?php echo $url; ?>/cms/jquery/files/jquery-ui-1-12-1.min.js"></script>
<script>
// Alert voor opgeslagen
// ======================
window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function() {
        $(this).remove();
    });
}, 4000);

$(".alert").on("click", function() {
    $(this).fadeTo("slow", 0);
});
</script>
<div class="fancybox-wrap" style="width:100%;" data-sticky_parent>
    <div class="box-container">
        <div class="box box-full md-box-full">
            <h3><span class="icon fas fa-clipboard-list"></span>Formulieren logs</h3>
            <?php if($rowinstellingen['export'] == 'ja'): ?>
                <a href="<?php echo $url; ?>/cms/php/export.php?soort_export=form_logs&formid=<?=$formID;?>&datum_sort=<?=$datum_filter;?>" class="btn fl-right">Exporteer formulierlogs</a>
            <?php endif; ?>
            <div class="fl-right">
                <form id="filters" method="post" action="/cms/php/formlogs.php" action="<?=$PHP_SELF;?>">
                    <select class="inputveld mr-10 dropdown extra-pad full-mob filter-class" name="form_filter">
                        <option value="">Kies een formulier</option>
                        <?php $forms = $mysqli->query("SELECT id,naam FROM sitework_formulieren") or die($mysqli->error.__LINE__);
                            while($rowForms = $forms->fetch_assoc()): ?>
                                <option value="<?=$rowForms['id'];?>" <?php echo ($formID == $rowForms['id']) ? 'selected' : '' ?>><?=$rowForms['naam'];?></option>
                            <?php endwhile;?>
                    </select>   
                    <select class="inputveld mr-10 dropdown extra-pad full-mob filter-class" name="datum_filter">
                        <option value="DESC" <?php echo ($datum_filter == 'DESC') ? 'selected' : '' ?>>Datum aflopend</option>
                        <option value="ASC" <?php echo ($datum_filter == 'ASC') ? 'selected' : '' ?>>Datum oplopend</option>
                    </select>
                </form>
            </div>
            <div class="row formlogs type">
                <div class="col">Formulier</div>
                <div class="col">Naam</div>
                <div class="col">E-mail</div>
                <div class="col">Telefoon</div>
                <div class="col">Pagina</div>
                <div class="col center">Datum verstuurd</div>
                <div class="col center">IP adres</div>
            </div>
            <?php while($rowformLogs = $formLogs->fetch_assoc()): 
            $form = $mysqli->query("SELECT naam FROM sitework_formulieren WHERE id = '".$rowformLogs['form_id']."'") or die($mysqli->error.__LINE__);
            $rowFormNaam = $form->fetch_assoc();
            ?>
            <div class="row formlogs">
                <!-- Formulier naam -->
                <div class="col"><?=$rowFormNaam['naam'];?></div>
                <!-- Verstuurde main informatie -->
                <div class="col"><?=$rowformLogs['naam'];?></div>
                <div class="col"><?=$rowformLogs['email'];?></div>
                <div class="col"><?=$rowformLogs['tel'];?></div>
                <div class="col"><?=$rowinstellingen['weburl'];?><?=$rowformLogs['form_pagina'];?></div>
                <div class="col center"><?=$rowformLogs['datum_verzending'];?></div>
                <div class="col center"><?=$rowformLogs['ipadres'];?></div>
            </div>
            <?php endwhile; ?>
            <?php if($totalLogs > $limit): ?>
                <div id="laad_meer">
                    <p><?=$logsShow;?> van de <?=$totalLogs;?> logs weergeven</p>
                    <a class="btn" href="/cms/php/formlogs.php?max=<?php echo $limit + 25;?>#laad_meer">Laad meer</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Get all elements with the class "filter-class"
    var filterElements = document.querySelectorAll('.filter-class');

    // Loop through each element and perform actions
    filterElements.forEach(function(element) {
        // Add event listener for change event
        element.addEventListener('change', function() {
            // Perform actions when any element with class "filter-class" changes
            // For example, you can access the value of the changed element like this:
            $('#filters').submit();
            // You can perform any other actions you need here
        });
    });
</script>