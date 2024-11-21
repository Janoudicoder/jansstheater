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

$sqlChecklistUser = $mysqli->query("SELECT * FROM sitework_checklist_livegang WHERE id = '1' ") or die($mysqli->error.__LINE__);
$rowChecklistUser = $sqlChecklistUser->fetch_assoc();

$sqlChecklist = $mysqli->query("SELECT id,livegang_punt,afgerond FROM sitework_checklist_livegang WHERE id != '1' ") or die($mysqli->error.__LINE__);

if($_POST['checklist_afgerond'] == '1') {
    if($rowChecklistUser['livegang_punt'] <> "") {
        $current_time = date('Y-m-d H:i:s');
        $query = "UPDATE sitework_checklist_livegang SET afgerond = '1', datum_afgerond = '".$current_time."' WHERE id = '1'";
        $mysqli->query($query) or die($mysqli->error.__LINE__);

        echo "
        <div class=\"alert alert-success\">
            De checklist is afgerond.
        </div>
        ";
    } else {
        echo "
        <div class=\"alert alert-error\">
            Voeg een medewerker toe die de checklist heeft uitgevoerd.
        </div>
        ";
    }
}

if($_POST['wijzig_persoon'] == '1') {

    if($_POST['persoon_livegang'] <> "") {
        $query = "UPDATE sitework_checklist_livegang SET livegang_punt = '" . $_POST['persoon_livegang'] . "' WHERE id = '1'";
        $mysqli->query($query) or die($mysqli->error.__LINE__);

        header('Location: /cms/php/checklist_livegang.php?gewijzigd=1');
    } else {
        echo "
        <div class=\"alert alert-error\">
            Persoon is niet gewijzigd, geef een naam mee.
        </div>
        ";
    }    
}

if($_GET['gewijzigd'] == '1') {
    echo "
    <div class=\"alert alert-success\">
        Persoon is gewijzigd.
    </div>
    ";
}

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
        <div class="box box-2-3 md-box-full">
            <h3><span class="icon fas fa-tasks"></span>Checklist livegang</h3>
            <?php while($rowChecklist = $sqlChecklist->fetch_assoc()): ?>
                <div class="form-box checklist">
                    <input type="checkbox" name="checklist" value="<?=$rowChecklist['id'];?>" id="<?=$rowChecklist['id'];?>" <?php echo ($rowChecklist['afgerond'] == 1) ? 'checked' : '' ?> <?php echo ($rowChecklistUser['afgerond'] == '1') ? 'disabled' : '' ?>>
                    <span class="checkmark"></span>
                    <label for="<?=$rowChecklist['id'];?>"><?=$rowChecklist['livegang_punt'];?></label>
                </div>
            <?php endwhile; ?>
            <div class="info full">
                <span class="far fa-info-circle"></span>&nbsp;&nbsp; <strong>Algemene aandachtspunten</strong><br><i>Een website is niet klaar als het alleen technish werkt! Let dus ook op</i><br>
                <ul style="list-style:none;">
                    <i>&emsp;-&emsp;Typfouten</i><br />
                    <i>&emsp;-&emsp;Heb zorg en aandacht voor je werk</i><br />
                    <i>&emsp;-&emsp;Check de website met het ontwerp</i><br />
                    <i>&emsp;-&emsp;Content en teksten zorgvuldig plaatsen en opmaken</i><br />
                    <i>&emsp;-&emsp;Werken de links en gaan ze naar de juiste pagina?</i><br />
                </ul>
            </div>
        </div>
        <div class="box box-1-3 md-box-full title stickysave">
            <div id="succesMelding"></div>
            <h3><span class="icon fas fa-user-check"></span>Checklist afgerond door</h3>
            <?php if($rowChecklistUser['afgerond'] == '0'): ?>
                <a href="" class="clickme btn edit fl-right">Wijzig persoon</a>
            <?php endif; ?>
            <?php if($rowChecklistUser['livegang_punt'] <> ''): ?>
                <div class="sidebar-box">
                    <h4><?=$rowChecklistUser['livegang_punt'];?></h4>
                </div>
            <?php endif; ?>
            <?php if($rowChecklistUser['afgerond'] == '0'): ?>
                <div class="toggle-box">
                    <form action="<?php echo $PHP_SELF;?>" method="post" class="">
                        <input type="text" class="inputveld invoer" name="persoon_livegang" id="persoon_livegang">
                        <input type="hidden" name="wijzig_persoon" value="1">
                        <button type="submit" class="btn fl-right save">Wijzigen</button>
                    </form>
                </div>
            <?php endif; ?>
            <?php if($rowChecklistUser['afgerond'] == '1'): ?>
                <h3 class="mt-20"><span class="icon fas fa-hourglass-half"></span>Checklist afgerond op</h3>
                <div class="sidebar-box">
                    <h4>
                        <?php 
                            $date = date('d-m-Y | H:i:s', strtotime($rowChecklistUser['datum_afgerond']));
                            echo $date;
                        ?>
                    </h4>
                </div>
            <?php endif; ?>
            <?php if($rowChecklistUser['afgerond'] == '0'): ?>
                <form action="<?php echo $PHP_SELF; ?>" method="post" onsubmit="return validateChecklist();">
                    <input type="hidden" name="checklist_afgerond" value="1">
                    <button type="submit" class="btn fl-left save mt-20">Rond checklist af</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
function validateChecklist() {
    var checkboxes = document.getElementsByName('checklist');
    var totalCheckboxes = checkboxes.length;
    var checkedCount = 0;

    // Loop through each checkbox
    for (var i = 0; i < totalCheckboxes; i++) {
        if (checkboxes[i].checked) {
            checkedCount++;
        }
    }

    // If all checkboxes are checked, return true
    if (checkedCount === totalCheckboxes) {
        return true;
    } else {
        alert("De hele checklist is nog niet afgerond, maak de checklist af voordat je deze opslaat.");
        return false;
    }
}

$("input[name='checklist']").on('change', function (event) {
    event.preventDefault();

    // Initialize FormData object
    var formData = new FormData();

    // Check if the checkbox is checked
    formData.append('checklist', $(this).val());

    // Configure the AJAX request
    $.ajax({
        type: "POST",
        url: "/cms/editinplace/saveedit_checklist.php", // Replace with your actual URL
        data: formData,
        contentType: false, // Set to avoid default form data processing
        processData: false, // Prevent jQuery from pre-processing data
        success: function (data) {

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("Upload failed:", textStatus, errorThrown);
        }
    });
});
</script>