<? // checken of men wel is ingelogd
// =================================
login_check_v2();

// cookieinstellingen ophalen
// ==========================
$sqlcookie = $mysqli->query("SELECT * FROM sitework_cookies") or die($mysqli->error.__LINE__);
$rowcookie = $sqlcookie->fetch_assoc(); 

$sqlcookieScripts = $mysqli->query("SELECT script_name, id FROM sitework_cookies_scripts") or die($mysqli->error.__LINE__);

// cookie melding wijzigen
// ========================
if($_POST['opslaan'] == 1){
		
	$sql_updatecookie = $mysqli->query("UPDATE  sitework_cookies SET 
                                                voorkeur_naleving =     '".$mysqli->real_escape_string($_POST['cookie_voorkeur_naleving'])."',
                                                cookie_naam  =          '".$mysqli->real_escape_string($_POST['cookie_naam'])."',
                                                display_soort  =        '".$mysqli->real_escape_string($_POST['cookie_display'])."',
                                                thema  =                '".$mysqli->real_escape_string($_POST['thema'])."',
                                                btn_tekst  =            '".$mysqli->real_escape_string($_POST['btn_tekst'])."',
                                                btn_background  =       '".$mysqli->real_escape_string($_POST['btn_background'])."',
                                                privacy_link  =         '".$mysqli->real_escape_string($_POST['privacy_link'])."',
                                                status  =               '".$mysqli->real_escape_string($_POST['cookieactief'])."' 
                                                WHERE id = '1'") or die($mysqli->error.__LINE__);													  
	$rowidcookie = $mysqli->insert_id;  
    header('Location: ?page=cookie&opgeslagen=ja');	
}
if($_POST['opslaan'] == 2) {
    $cookieScript = $_POST['cookie_script_inhoud'];
    $script_type = $_POST['cookie_script_type'];

    if(strpos($cookieScript, '<script>') !== false && strpos($cookieScript, '</script>') !== false) {
        $cookieScript = str_replace("<script>","<script type=\"text/javascript\" data-cookie-consent=\"".$script_type."\">",$cookieScript);

        // Fetch existing script names
        $sqlcookieScriptsCheck = $mysqli->query("SELECT script_name FROM sitework_cookies_scripts") or die($mysqli->error.__LINE__);

        // Check for duplicates
        $isDuplicate = false;
        while ($row = $sqlcookieScriptsCheck->fetch_assoc()) {
            if ($row['script_name'] === $_POST['cookie_script_name']) {
                $isDuplicate = true;
                break;
            }
        }

        // Proceed with insertion only if no duplicate found
        if (!$isDuplicate) {
            $sql_updatecookiescript = $mysqli->query("INSERT INTO sitework_cookies_scripts SET 
                                                    cookie_id =         '".$rowcookie['id']."',
                                                    script_name  =      '".$mysqli->real_escape_string($_POST['cookie_script_name'])."',
                                                    script_type  =      '".$mysqli->real_escape_string($_POST['cookie_script_type'])."',
                                                    script_value  =     '".$mysqli->real_escape_string($cookieScript)."'") or die($mysqli->error.__LINE__);                                                   
            $rowidcookiescript = $mysqli->insert_id;  
            header('Location: ?page=cookie&script=ja'); 
        } else {
            // Handle the duplicate script name case (e.g., display an error message)
            header('Location: ?page=cookie&script=duplicate');
        }
    } else {
        header('Location: ?page=cookie&script=nee');    
    }
} 
if($_GET['delscript'] <> "" && $_GET['delscript'] <> ''){
    $mysqli->query("DELETE FROM sitework_cookies_scripts WHERE id = '".$_GET['delscript']."' ") or die($mysqli->error.__LINE__);
    header('Location: ?page=cookie&script=verwijderd');
};
if($_GET['opgeslagen'] == "ja"){
    echo "
    <div class=\"alert alert-success\">
        Cookie is opgeslagen
    </div>
    ";
};
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
if($_GET['script'] == "duplicate"){
    echo "
    <div class=\"alert alert-error\">
        Cookie script met deze naam bestaat al, probeer het opnieuw
    </div>
    ";
};
if($_GET['script'] == "verwijderd"){
    echo "
    <div class=\"alert alert-info\">
        Uw cookie is verwijderd
    </div>
    ";
};
?>

<div class="box-container">
	<div class="box box-2-3 lg-box-full">
	    <h3><span class="icon fas fa-cookie-bite"></span>Instellingen cookiemelding</h3>
		<div class="content-container mt-0">   
            <form action="<? echo $PHP_SELF ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">    
                    <label for="coockieactief">Cookiemelding activeren</label>
                    <div class="inputveld invoer radio">
                        <input name="cookieactief" type="radio" id="cookieactief_actief" class="radio-button" value="actief" <? if ($cookieactief =="actief" or $rowcookie['status'] == "actief") { echo "checked"; } ?>><label for="cookieactief_actief">ja</label>
                        <input name="cookieactief" type="radio" id="cookieactief_inactief" class="radio-button" value="inactief" <? if ($cookieactief =="inactief" or $rowcookie['status'] == "inactief") { echo "checked"; } ?>><label for="cookieactief_inactief">nee</label>
                    </div>
                </div>
                <div class="form-box types naast">    
                    <label for="cookie_voorkeur_naleving" class="naast-velden">Kies uw voorkeur voor naleving</label>
                    <div class="type_opties w-80">
                        <input name="cookie_voorkeur_naleving" type="radio" id="voorkeur_richtlijn" class="" value="implied" <? if ($rowcookie['voorkeur_naleving'] == "implied") { echo "checked"; } ?>>
                        <label for="voorkeur_richtlijn" class="type_optie">
                            <div class="img" style="background-image: url(<?=$url;?>/cms/images/cookie-icons.svg)" title="richtlijn"></div>
                            <h3>ePrivacy-richtlijn</h3>
                            <small>JavaScript-scripts worden automatisch geladen.</small>
                        </label>
                        <input name="cookie_voorkeur_naleving" type="radio" id="voorkeur_directive" class="" value="express" <? if ($rowcookie['voorkeur_naleving'] == "express") { echo "checked"; } ?>>
                        <label for="voorkeur_directive" class="type_optie">
                            <div class="img" style="background-image: url(<?=$url;?>/cms/images/cookie-icons.svg)" title="directive"></div>
                            <h3>GDPR + ePrivacy Directive</h3>
                            <small>Getagde JavaScript-scripts worden pas geladen nadat de gebruiker op "Ik ga akkoord" heeft geklikt.</small>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="cookie_naam">Cookie naam</label>
                    <input type="text" class="inputveld invoer" name="cookie_naam" placeholder="voer hier de cookie naam in" value="<? echo  $rowcookie['cookie_naam'];  ?>">
                </div>
                <div class="form-box types naast">    
                    <label for="cookie_display" class="naast-velden">Cookie soort display op website</label>
                    <div class="type_opties w-80">
                        <input name="cookie_display" type="radio" id="cookie_display_simple" class="" value="simple" <? if ($rowcookie['display_soort'] == "simple") { echo "checked"; } ?>>
                        <label for="cookie_display_simple" class="type_optie">
                            <div class="img" style="background-image: url(<?=$url;?>/cms/images/cookie-icons.svg)" title="Eenvoudig"></div>
                            <h3>Eenvoudig dialoogvenster</h3>
                        </label>
                        <input name="cookie_display" type="radio" id="cookie_display_headline" class="" value="headline" <? if ($rowcookie['display_soort'] == "headline") { echo "checked"; } ?>>
                        <label for="cookie_display_headline" class="type_optie">
                            <div class="img" style="background-image: url(<?=$url;?>/cms/images/cookie-icons.svg)" title="kop"></div>
                            <h3>Dialoog kop</h3>
                        </label>
                        <input name="cookie_display" type="radio" id="cookie_display_interstitial" class="" value="interstitial" <? if ($rowcookie['display_soort'] == "interstitial") { echo "checked"; } ?>>
                        <label for="cookie_display_interstitial" class="type_optie">
                            <div class="img" style="background-image: url(<?=$url;?>/cms/images/cookie-icons.svg)" title="inter-dialoog"></div>
                            <h3>Interstitiële dialoog</h3>
                        </label>
                        <input name="cookie_display" type="radio" id="cookie_display_standalone" class="" value="standalone" <? if ($rowcookie['display_soort'] == "standalone") { echo "checked"; } ?>>
                        <label for="cookie_display_standalone" class="type_optie">
                            <div class="img" style="background-image: url(<?=$url;?>/cms/images/cookie-icons.svg)" title="inter-standalone"></div>
                            <h3>Interstitiële standalone</h3>
                        </label>
                    </div>
                </div>
                <div class="form-group">    
                    <label for="thema">Kleuren pallet</label>
                    <div class="inputveld invoer radio">
                        <input name="thema" type="radio" id="thema_light" class="radio-button" value="light" <? if ($rowcookie['thema'] == "light") { echo "checked"; } ?>><label for="thema_light">Licht</label>
                        <input name="thema" type="radio" id="thema_dark" class="radio-button" value="dark" <? if ($rowcookie['thema'] == "dark") { echo "checked"; } ?>><label for="thema_dark">Donker</label>
                    </div>
                </div>
                <div class="form-group">    
                    <label for="cookie_kleur">Kleuren knoppen</label>
                    <div class="inputveld invoer kleur">
                        <div class="kleur-input"><input type="color" name="btn_background" id="btn_background" value="<?=$rowcookie['btn_background'];?>"></div><label for="btn_background">Knop achtergrondkleur</label>
                        <div class="kleur-input"><input type="color" name="btn_tekst" id="btn_tekst" value="<?=$rowcookie['btn_tekst'];?>"></div><label for="btn_tekst">Knop tekst kleur</label>
                    </div>
                    <span class="btn voorbeeld" style="color: <?=$rowcookie['btn_tekst'];?> !important;background-color: <?=$rowcookie['btn_background'];?> !important;">Voorbeeld knop</span>
                </div>
                <div class="form-group">
                    <label for="privacy_link">Privacy verklaring</label>
                    <input type="url" class="inputveld invoer" name="privacy_link" placeholder="Als u hier niks invult word er geen link toegevoegd, anders wel" value="<? echo  $rowcookie['privacy_link'];  ?>">
                </div>
                <button class="btn fl-left save" name="opslaan" type="submit">Opslaan</button>
                <input type="hidden" name="opslaan" value="1" >
            </form>
        </div>
    </div>
    <div class="box box-1-3 lg-box-full">
	    <h3><span class="icon fas fa-code"></span>Cookie aanvullende scripts</h3>
		<div class="content-container mt-0">   
            <form action="<? echo $PHP_SELF ?>" method="post" enctype="multipart/form-data">
                <div class="form-box types">    
                    <label for="cookie_script_type">Cookie script type</label>
                    <div class="type_opties">
                        <input name="cookie_script_type" type="radio" id="script_type_strictly-necessary" class="" value="strictly-necessary" checked>
                        <label for="script_type_strictly-necessary" class="type_optie">
                            <h3>Strikt noodzakelijk</h3>
                            <small>(bijv. cookies gerelateerd aan het inloggen op een account)</small>
                        </label>
                        <input name="cookie_script_type" type="radio" id="script_type_functionality" class="" value="functionality">
                        <label for="script_type_functionality" class="type_optie">
                            <h3>Functionaliteit</h3>
                            <small>(d.w.z. keuzes van gebruikers onthouden)</small>
                        </label>
                        <input name="cookie_script_type" type="radio" id="script_type_tracking" class="" value="tracking">
                        <label for="script_type_tracking" class="type_optie">
                            <h3>Volgen en prestaties</h3>
                            <small>(bijv. Google Analytics)</small>
                        </label>
                        <input name="cookie_script_type" type="radio" id="script_type_targeting" class="" value="targeting">
                        <label for="script_type_targeting" class="type_optie">
                            <h3>Targeting en reclame</h3>
                            <small>(ie. Google AdSense)</small>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="cookie_script_name">Script naam</label>
                    <input type="text" class="inputveld invoer" name="cookie_script_name" placeholder="voer hier uw script naam in">
                </div>
                <div class="form-group">
                    <pre><textarea name="cookie_script_inhoud" id="cookie_script_inhoud" class="inputveld textarea" cols="30" rows="10" placeholder="Voer hier uw script in"></textarea></pre>
                </div>
                <button class="btn fl-left nieuw" name="opslaan" type="submit">Voeg script toe</button>
                <input type="hidden" name="opslaan" value="2" >
            </form>
            <div class="show-scripts">
                <?php while($rowcookieScripts = $sqlcookieScripts->fetch_assoc()): ?>
                    <span>
                        <a data-fancybox data-small-btn="true" data-type="iframe"
                            href="php/script_bewerken.php?script_id=<?=$rowcookieScripts['id'];?>" href="javascript:;">
                            <h4><?=$rowcookieScripts['script_name'];?></h4>
                        </a>
                        <div onclick="confirmRemoveScript(<?=$rowcookieScripts['id'];?>)" class="btn delete">Verwijder script</div>
                    </span>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<script>
function confirmRemoveScript(id) {
    if (confirm("Weet u zeker dat u deze script wilt verwijderen?")) {
        window.location.href = '?page=cookie&delscript=' + id;
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