<?php ob_start();
// database connectie en inlogfuncties
// ===================================
require("../login/config.php");
include '../login/functions.php';
session_start();

// checken of men wel is ingelogd
// ==============================
login_check_v2();

if($_GET['script'] == "ja"){
    echo "
    <div class=\"alert alert-success\">
        Cookie script is opgeslagen
    </div>
    ";
};
if($_GET['script'] == "nee"){
    echo "
    <div class=\"alert alert-error\">
        Cookie script is niet opgeslagen, probeer het opnieuw
    </div>
    ";
};

if($_POST['opslaan'] == 'update') {
    $script_id = $_POST['script_id'];
    $script_name = $_POST['cookie_script_name'];
    $script_type = $_POST['cookie_script_type'];
    $cookieScript = $_POST['cookie_script_inhoud'];

    if(strpos($cookieScript, '<script>') !== false && strpos($cookieScript, '</script>') !== false) {
        $cookieScript = str_replace("<script>","<script type=\"text/javascript\" data-cookie-consent=\"".$script_type."\">",$cookieScript);

        $queryScript = "UPDATE sitework_cookies_scripts SET 
                    script_name = '".$script_name."', 
                    script_type = '".$script_type."', 
                    script_value = '".$cookieScript."' WHERE id = " . $script_id;

        $resultScript = $mysqli->query($queryScript) or die($mysqli->error.__LINE__);

        header("Location: script_bewerken.php?script_id=" . $script_id . "&script=ja");
    } else {
        header("Location: script_bewerken.php?script_id=" . $script_id . "&script=nee");    
    }
}

if(isset($_GET['delscript']) && $_GET['delscript'] <> "") {
    $mysqli->query("DELETE FROM sitework_cookies_scripts WHERE id = '".$_GET['delscript']."' ") or die($mysqli->error.__LINE__);
    echo "<script>parent.$.fancybox.close();</script>";
}

$queryScripts = $mysqli->query("SELECT * FROM sitework_cookies_scripts WHERE id = '".$_GET['script_id']."' order by id") or die($mysqli->error.__LINE__);
$rowScripts = $queryScripts->fetch_assoc();

$rowScripts['script_value'] = str_replace("<script type=\"text/javascript\" data-cookie-consent=\"".$rowScripts['script_type']."\">", "<script>", $rowScripts['script_value']);
?>

<TITLE>SiteWork CMS afbeelding upload</TITLE>
<meta charset="UTF-8" />
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/css/stylesheet.css">
<link rel='stylesheet' type='text/css' href='<?php echo $url; ?>/cms/css/branding-stylesheet.php' />
<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/cms/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/font-awesome/css/all.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/cms/css/themify-icons.css">

<script type="text/javascript" src="<?php echo $url; ?>/cms/jquery/files/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="<?php echo $url; ?>/cms/jquery/files/jquery-ui-1-12-1.min.js"></script>

<div class="fancybox-wrap">
    <div class="box-container">
        <div class="box box-full md-box-full">
            <h3><span class="icon fas fa-code"></span>Script bewerken/bekijken</h3>
            <?php if ($error) { ?><span class="error"><?php echo $error; ?></span><?php } ?>
            <form action="<? echo $PHP_SELF ?>" method="post" enctype="multipart/form-data">
                <div class="form-box types">    
                    <label for="cookie_script_type">Cookie script type</label>
                    <div class="type_opties">
                        <input name="cookie_script_type" type="radio" id="script_type_strictly-necessary" class="" value="strictly-necessary" <?php echo ($rowScripts['script_type'] == 'strictly-necessary') ? 'checked' : '' ?>>
                        <label for="script_type_strictly-necessary" class="type_optie">
                            <h3>Strikt noodzakelijk</h3>
                            <small>(bijv. cookies gerelateerd aan het inloggen op een account)</small>
                        </label>
                        <input name="cookie_script_type" type="radio" id="script_type_functionality" class="" value="functionality" <?php echo ($rowScripts['script_type'] == 'functionality') ? 'checked' : '' ?>>
                        <label for="script_type_functionality" class="type_optie">
                            <h3>Functionaliteit</h3>
                            <small>(d.w.z. keuzes van gebruikers onthouden)</small>
                        </label>
                        <input name="cookie_script_type" type="radio" id="script_type_tracking" class="" value="tracking" <?php echo ($rowScripts['script_type'] == 'tracking') ? 'checked' : '' ?>>
                        <label for="script_type_tracking" class="type_optie">
                            <h3>Volgen en prestaties</h3>
                            <small>(bijv. Google Analytics)</small>
                        </label>
                        <input name="cookie_script_type" type="radio" id="script_type_targeting" class="" value="targeting" <?php echo ($rowScripts['script_type'] == 'targeting') ? 'checked' : '' ?>>
                        <label for="script_type_targeting" class="type_optie">
                            <h3>Targeting en reclame</h3>
                            <small>(ie. Google AdSense)</small>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="cookie_script_name">Script naam</label>
                    <input type="text" class="inputveld invoer" name="cookie_script_name" placeholder="voer hier uw script naam in" value="<?=$rowScripts['script_name'];?>">
                </div>
                <div class="form-group">
                    <pre><textarea name="cookie_script_inhoud" id="cookie_script_inhoud" class="inputveld textarea" cols="30" rows="10" placeholder="Voer hier uw script in"><?=$rowScripts['script_value'];?></textarea></pre>
                </div>
                <button class="btn fl-left save" type="submit">update script</button>
                <input type="hidden" name="script_id" value="<?=$_GET['script_id'];?>">
                <input type="hidden" name="opslaan" value="update" >
            </form>
            <div onclick="confirmRemoveScript(<?=$_GET['script_id'];?>)" class="btn delete fl-right">Verwijder script</div>
        </div>
    </div>
</div>

<script>
function confirmRemoveScript(id) {
    if (confirm("Weet u zeker dat u deze script wilt verwijderen?")) {
        window.location.href = 'script_bewerken.php?delscript=' + id;
    }
    return false;
}
document.getElementById('cookie_script_inhoud').addEventListener('keydown', function(e) {
    if (e.key == 'Tab') {
        e.preventDefault();
        var start = this.selectionStart;
        var end = this.selectionEnd;

        // set textarea value to: text before caret + tab + text after caret
        this.value = this.value.substring(0, start) +
        "\t" + this.value.substring(end);

        // put caret at right position again
        this.selectionStart =
        this.selectionEnd = start + 1;
    }
});
</script>