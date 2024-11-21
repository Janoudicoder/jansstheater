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

if($_GET['field_id']) {
    $fieldID = $_GET['field_id'];
} else { echo "<script>parent.$.fancybox.close();</script>"; }

if($_GET['cms_id']) {
    $cmsID = $_GET['cms_id'];
} else { echo "<script>parent.$.fancybox.close();</script>"; }

$sqlcf_opties = $mysqli->query("SELECT * FROM sitework_customfields_opties WHERE koppel_id = '".$fieldID."' ORDER BY id ASC") or die($mysqli->error . __LINE__);

$sqlcf = $mysqli->query("SELECT * FROM sitework_customfields WHERE id = '".$fieldID."'") or die($mysqli->error . __LINE__);
$rowCF = $sqlcf->fetch_assoc();

if($_POST['save-cf']) {
    $veld = $_POST['cf-name-veld'];
    $koppelmogelijkheid = $_POST['cf-koppel-veld'];
    $type = $_POST['cf-type-veld'];
    $slug = $_POST['cf-slug-veld'];
    $taal = $_POST['cf-taal-veld'];

    $koppelWaardes = explode("_", $koppelmogelijkheid);
    $koppel = "";

    if($koppelWaardes[0] == 'cms') {
        $koppel = "cms_id        = '".$mysqli->real_escape_string($koppelWaardes[1]). "', cat = NULL, kenmerk = NULL, template_id = NULL";
    } elseif($koppelWaardes[0] == 'cat') {
        $koppel = "cat        = '".$mysqli->real_escape_string($koppelWaardes[1]). "', cms_id = NULL, kenmerk = NULL, template_id = NULL";
    } elseif($koppelWaardes[0] == 'kenmerk') {
        $koppel = "kenmerk        = '".$mysqli->real_escape_string($koppelWaardes[1]). "', cms_id = NULL, cat = NULL, template_id = NULL";
    } elseif($koppelWaardes[0] == 'template') {
        $koppel = "template_id        = '".$mysqli->real_escape_string($koppelWaardes[1]). "', cms_id = NULL, cat = NULL, kenmerk = NULL";
    }

    $sql_insertoptie = $mysqli->query("UPDATE sitework_customfields SET 
                                        veld        = '".$mysqli->real_escape_string($veld). "',
                                        ".$koppel.",
                                        type      = '".$mysqli->real_escape_string($type). "',
                                        slug      = '".$mysqli->real_escape_string($slug)."',
                                        taal     = '".$taal. "' WHERE id = '".$fieldID."'") or die($mysqli->error.__LINE__);													  
	
    header('Location: ?field_id='.$fieldID.'&cms_id='.$cmsID.'&opslaan=ja');
}

if($_POST['new-subname'] != "" && $_POST['new-subvalue'] != "") {
    $name = $_POST['new-subname'];
    $value = $_POST['new-subvalue'];

    $sql_insertoptie = $mysqli->query("INSERT sitework_customfields_opties SET 
                                        veld        = '".$mysqli->real_escape_string($name). "',
                                        waarde      = '".$mysqli->real_escape_string($value). "',
                                        koppel_id     = '".$fieldID. "'") or die($mysqli->error.__LINE__);													  
	
	$rowid = $mysqli->insert_id;  
    header('Location: ?field_id='.$fieldID.'&cms_id='.$cmsID.'&toegevoegd=ja');
}

if ($_GET['delid']) {
	$mysqli->query("DELETE FROM sitework_customfields_opties WHERE id = '".$_GET['delid']."' ") or die($mysqli->error.__LINE__);
	header('Location: ?field_id='.$fieldID.'&cms_id='.$cmsID.'&verwijderd=ja');
}

if ($_GET['delVeldId']) {
    $mysqli->query("DELETE FROM sitework_customfields WHERE id = '".$_GET['delVeldId']."' ") or die($mysqli->error.__LINE__);
	$mysqli->query("DELETE FROM sitework_customfields_opties WHERE koppel_id = '".$_GET['delVeldId']."' ") or die($mysqli->error.__LINE__);
    $mysqli->query("DELETE FROM sitework_customfields_waardes WHERE veld_id = '".$_GET['delVeldId']."' ") or die($mysqli->error.__LINE__);

	// header('Location: ?field_id='.$fieldID.'&cms_id='.$cmsID.'&fulldelete=ja');
    echo "<script>parent.window.location.reload(true);</script>";
    echo "<script>parent.$.fancybox.close();</script>";
}

if($_GET['toegevoegd'] == 'ja'){ 
	echo "
		<div class=\"alert alert-success\">
			Suboptie is opgeslagen
		</div>
	";
}
if($_GET['toegevoegd'] == 'nee'){ 
	echo "
		<div class=\"alert alert-error\">
			Suboptie is niet opgeslagen, probeer opnieuw.
		</div>
	";
}
if($_GET['opslaan'] == 'ja'){ 
	echo "
		<div class=\"alert alert-success\">
			Veld is opgeslagen
		</div>
	";
}
if($_GET['verwijderd'] == 'ja'){ 
	echo "
		<div class=\"alert alert-info\">
			Optie is verwijderd
		</div>
	";
}
if($_GET['fulldelete'] == 'ja'){ 
	echo "
		<div class=\"alert alert-info\">
			Veld met waardes zijn verwijderd
		</div>
	";
}

$sqlPage = $mysqli->query("SELECT id,item2 FROM siteworkcms WHERE id = '".$cmsID."'") or die($mysqli->error . __LINE__);
$rowPage = $sqlPage->fetch_assoc();
?>

<TITLE>SiteWork CMS afbeelding bewerken</TITLE>
<meta charset="UTF-8" />
<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/cms/css/stylesheet.css">
<link rel='stylesheet' type='text/css' href='<?php echo $url; ?>/cms/css/branding-stylesheet.php' />
<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/cms/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/cms/font-awesome/css/all.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/cms/css/themify-icons.css">

<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery-ui-1-12-1.min.js"></script>  
<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery.fancybox.js"></script>
<script type="text/javascript" src="<? echo $url; ?>/cms/datepick/jquery-ui-date.js"></script>
<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery-nested-sortable.js"></script>  
<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/sitework.js"></script>
<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery.sticky-kit.min.js"></script>
<script>
$(document).ready(function(){
    $("[name='cf-name-veld']").keyup(function(){
        $("[name='cf-slug-veld']").val($(this).val().replace(" ", "_").toLowerCase().replace(" ", "_"));
    });
});

function ConfirmDeleteOptie() {
    return confirm('Weet u zeker dat u deze optie wilt verwijderen?'); 
} 
function ConfirmDeleteVeld() {
    return confirm('Weet u zeker dat u dit veld wilt verwijderen?'); 
} 
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

function showEdit(editableObj) {
    $(editableObj).css("background","#FFF");
}

function saveToDatabase(editableObj,column,id) {
    $(editableObj).css("background","#FFF url(../editinplace/loaderIcon.gif) no-repeat right");

    $.ajax({
        url: "../editinplace/saveedit_suboptiecf.php",
        type: "POST",
        data:'column='+column+'&editval='+editableObj.innerHTML+'&id='+id,
        success: function(data){
            $(editableObj).css("background","#FDFDFD");
        }
    });
}
</script>
<div class="fancybox-wrap" style="width:100%;">
    <div class="box-container">
        <?php if($rowCF['type'] == 'radio' || $rowCF['type'] == 'keuze selectie' || $rowCF['type'] == 'checkbox'): ?>
            <div class="box box-1-3 md-box-full">
        <?php else: ?>
            <div class="box box-2-3 md-box-full">
        <?php endif; ?>
            <h3><span class="icon fas fa-wrench"></span>Pas je veld aan</h3>
            <a class="btn delete fl-right" href="<?=$PHP_SELF;?>?field_id=<?=$fieldID;?>&cms_id=<?=$cmsID;?>&delVeldId=<?=$fieldID;?>" onclick="return ConfirmDeleteVeld();" title="Verwijderen">Verwijder veld</a>
                <form action="<?=$PHP_SELF;?>?field_id=<?=$fieldID;?>&cms_id=<?=$cmsID;?>" method="POST">
                    <div class="form-group">
                        <input type="text" name="cf-name-veld" class="inputveld invoer full-no-label round-full" maxlength="25" placeholder="Veld naam (max. 25 characters)" value="<?=$rowCF['veld'];?>">
                    </div>
                    <div class="form-group">
                        <select name="cf-koppel-veld" id="cf-koppel-veld" class="inputveld invoer full-no-label round-full">
                            <optgroup label="Pagina">
                                <option value="cms_0" <?php echo ($rowCF['cms_id'] == '0' && $rowCF['cms_id'] != null) ? 'selected' : ''; ?>>Dit veld komt op alle pagina's</option>
                                <option value="cms_<?=$cmsID;?>" <?php echo ($rowCF['cms_id'] == $cmsID) ? 'selected' : ''; ?>><?=$rowPage['item2'];?></option>
                            </optgroup>
                            <optgroup label="Categorieën">
                                <?php 
                                    $sqlcat = $mysqli->query("SELECT * FROM sitework_categorie") or die($mysqli->error . __LINE__);
                                    while ($rowcat = $sqlcat->fetch_assoc()) {
                                        $categorieWaarde = htmlspecialchars($rowcat['categorie']);
                                        $selected = ($rowCF['cat'] == htmlspecialchars($rowcat['categorie'])) ? 'selected' : '';
                                    
                                        echo '<option value="cat_' . htmlspecialchars($rowcat['categorie']) . '" '.$selected.'>' . $categorieWaarde . '</option>';
                                    } 
                                ?>
                            </optgroup>
                            <optgroup label="Kenmerken">
                                <?php 
                                    $sqlKen = $mysqli->query("SELECT * FROM sitework_kenmerken") or die($mysqli->error.__LINE__);
                                    while($rowKen = $sqlKen->fetch_assoc()){
                                        $kenmerkWaarde = htmlspecialchars($rowKen['kenmerk']);
                                        $selected = ($rowCF['kenmerk'] == htmlspecialchars($rowKen['kenmerk'])) ? 'selected' : '';

                                        echo '<option value="kenmerk_' . htmlspecialchars($rowKen['kenmerk']) . '" '.$selected.'>' . $kenmerkWaarde . '</option>';
                                    } 
                                ?>
                            </optgroup>
                            <optgroup label="Templates">
                                <option value="template_" <?php echo ($rowCF['template_id'] == '1') ? 'selected' : ''; ?>>Template</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="cf-type-veld" id="cf-type-veld" class="inputveld invoer full-no-label round-full">
                            <option value="tekst" <?php echo ($rowCF['type'] == 'tekst') ? 'selected' : ''; ?>>Tekst</option>
                            <option value="tekstveld" <?php echo ($rowCF['type'] == 'tekstveld') ? 'selected' : ''; ?>>Tekstveld</option>
                            <option value="datum" <?php echo ($rowCF['type'] == 'datum') ? 'selected' : ''; ?>>Datum</option>
                            <option value="keuze selectie" <?php echo ($rowCF['type'] == 'keuze selectie') ? 'selected' : ''; ?>>Keuze selectie</option>
                            <option value="checkbox" <?php echo ($rowCF['type'] == 'checkbox') ? 'selected' : ''; ?>>Checkbox</option>
                            <option value="radio" <?php echo ($rowCF['type'] == 'radio') ? 'selected' : ''; ?>>Radio</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="cf-taal-veld" id="cf-taal-veld" class="inputveld invoer full-no-label round-full">
                            <option value="">Kies een taal</option>
                            <?php
                                $sqltaal = $mysqli->query("SELECT * FROM sitework_taal WHERE actief = '1' ORDER BY taalkort DESC") or die($mysqli->error . __LINE__);
                                while($rowSelecttaal = $sqltaal->fetch_assoc()):
                                    $selected = ($rowCF['taal'] == $rowSelecttaal['taalkort']) ? 'selected' : '';
                                    echo '<option value="'.$rowSelecttaal['taalkort'].'" '.$selected.'>'.$rowSelecttaal['taallang'].'</option>';
                                endwhile;
                            ?>
                        </select>
                    </div>
                    <input type="hidden" name="save-cf" value="1">
                    <input type="hidden" name="cf-slug-veld" value="">
                    <button type="submit" class="btn save inputveld invoer w-fit round-full">Opslaan</button>
                </form>
        </div>
        <?php if($rowCF['type'] == 'radio' || $rowCF['type'] == 'keuze selectie' || $rowCF['type'] == 'checkbox'): ?>
            <div class="box box-2-3 md-box-full">
                <h3><span class="icon fas fa-level-down-alt"></span>Voeg subopties toe voor: <strong><?=$rowCF['veld'];?></strong></h3>
                <span class="clickme btn nieuw fl-right">Nieuw</span>
                <div class="toggle-box">
                    <form action="<?=$PHP_SELF;?>?field_id=<?=$fieldID;?>&cms_id=<?=$cmsID;?>" method="POST">
                        <div class="form-group">
                            <label for="">Subveld</label>
                            <input type="text" class="inputveld invoer small-30" name="new-subname" placeholder="Naam van het veld">
                            <input type="text" class="inputveld invoer small-50 round-full" name="new-subvalue" placeholder="Waarde van het veld">
                            <button type="submit" class="inputveld invoer small-10 btn">Voeg toe</button>
                        </div>
                    </form>
                </div>
                <div class="row subcf type">
                    <div class="col">Veld naam</div>
                    <div class="col">Veld waarde</div>
                    <div class="col center">Verwijder</div>
                </div>
                <?php
                    while ($rowcf_opties = $sqlcf_opties->fetch_assoc()) {
                        echo '<div class="row subcf">';
                            echo '<div class="col" contenteditable="true" onblur="saveToDatabase(this,\'veld\',\''.$rowcf_opties['id'].'\')" onclick="showEdit(this);">'.$rowcf_opties['veld'].'</div>';
                            echo '<div class="col" contenteditable="true" onblur="saveToDatabase(this,\'waarde\',\''.$rowcf_opties['id'].'\')" onclick="showEdit(this);">'.$rowcf_opties['waarde'].'</div>';
                            echo '<div class="col center"><a class="delete" href="'.$PHP_SELF.'?field_id='.$fieldID.'&cms_id='.$cmsID.'&delid='.$rowcf_opties['id'].'" onclick="return ConfirmDeleteOptie();" title="Verwijderen"><span class="far fa-trash"></span></a></div>';
                        echo '</div>';
                    }
                ?>
            </div>
        <?php endif; ?>
        <div class="box box-1-3 md-box-full">
            <div class="info full"><span class="far fa-info-circle"></span>&nbsp;&nbsp;
                Als er geen taal word geselecteerd, word het veld op elke taalpagina weergeven.
            </div>
        </div>
    </div>
</div>