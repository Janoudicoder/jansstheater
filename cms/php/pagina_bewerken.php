<?php //checken of men wel is ingelogd:
// ====================================
login_check_v2();
?>

<script type="text/javascript">
    $(document).ready(function () {

        // toevoegen verpakking
        $(".addbutton").click(function (event) {
            event.preventDefault();
            var add_id = $(this).attr("id");
            var blockId = $('select[name=addBlock]').val();
            var paginaTaal = $('input[name=paginaTaal]').val();
            var toevoegen = true;
            var form_data =
                'add_id=' + add_id +
                '&blockId=' + blockId +
                '&paginaTaal=' + paginaTaal +
                '&toevoegen=' + toevoegen;

            $.ajax({
                type: "POST",
                url: "/cms/php/blocks/add_block.php", //URL to the delete php script
                data: form_data,
                success: function (data) {
                    $("#blocks").html(data);
                }
            });
            // alert('test');
            //return false;
            // location.reload();
            // var delayInMilliseconds = 1000; //1 second
            //
            // setTimeout(function() {
            //     //your code to be executed after 1 second
            //     location.reload();
            //    //return false;
            // }, delayInMilliseconds);
            //window.location.reload();
            // window.location.reload(true);

            // setTimeout(function(){
            //     window.location.reload();
            // });
            //window.location = window.location;

        });

        $('[data-fancybox-trigger="taaltoevoegen"]').fancybox({
            afterClose:function () {
                //parentlocation.reload(true);
                parent.location.reload(true);
            }
        });

        $("[contenteditable='true']").on("keypress paste", function (e) {
            if (this.innerHTML.length >= this.getAttribute("max")) {
                e.preventDefault();
                return false;
            }
        });

        $("[name='cfnew-veld']").keyup(function(){
            $("[name='cfnew-slug']").val($(this).val().replace(" ", "_").toLowerCase().replace(" ", "_"));
        });

    });

    function showEdit(editableObj) {
        $(editableObj).css("background","#FFF");
    }

    function saveToDatabase(editableObj,column,id) {
        $(editableObj).css("background","#e3f7ff url(./editinplace/loaderIcon.gif) no-repeat 97% 50%");

        $.ajax({
            url: "../editinplace/saveedit_customfield.php",
            type: "POST",
            data:'column='+column+'&editval='+editableObj.innerHTML+'&id='+id,
            success: function(data){
                $(editableObj).css("background","#e3f7ff");
            }
        });
    }
    // $( function() {
    //     $('.showTooltip').tooltip();
    // });
    $(function() {
        $('.showTooltip').tooltip({
            content: function() {
                return $('<div class="tooltip-content copy-tooltip" data-slug="' + $(this).attr('title') + '">' + $(this).attr('title') + '</div>');
            },
            open: function(event, ui) {
                ui.tooltip.hover(
                    function () {
                        $(this).stop(true).fadeTo(400, 1); // Keep tooltip visible on hover
                    },
                    function () {
                        $(this).fadeOut('400', function () {
                            $(this).remove();
                        });
                    }
                );
            },
            close: function(event, ui) {
                ui.tooltip.hover(
                    function () {
                        $(this).stop(true).fadeTo(400, 1); // Keep tooltip visible on hover
                    },
                    function () {
                        $(this).fadeOut('400', function () {
                            $(this).remove();
                        });
                    }
                );
            }
        });
    });
</script>
<script>
    function getAllIcons() {
        var icons = [];
        var styleSheets = document.styleSheets;
        for (var i = 0; i < styleSheets.length; i++) {
            var styleSheet = styleSheets[i];
            if (styleSheet.href && styleSheet.href.includes('all.min')) {
                var rules = styleSheet.rules || styleSheet.cssRules;
                for (var j = 0; j < rules.length; j++) {
                    var rule = rules[j];
                    if (rule.selectorText && rule.selectorText.startsWith('.fa-')) {
                        var iconName = rule.selectorText.replace('.fa-', '');
                        var iconName = rule.selectorText.replace('::before', '');
                        icons.push(iconName);
                    }
                }
            }
        }
        return icons;
    }

    // Usage
    var allIcons = getAllIcons();
</script>

<?php
if(!isset($_GET['taal'])) {
    $_GET['taal'] = "nl";
} else { $_GET['taal'] = $_GET['taal']; }

// kenmerken ophalen
// =================
if ($_POST['kenmerken']) {
    $kenmerken = $_POST['kenmerken'];
    foreach ($kenmerken as $key[] => $waarden) {
        $kenmerken_totaal[] = "$waarden";
    }

    if (!empty($kenmerken_totaal[0])) {
        $kenmerken_wegschrijven = $kenmerken_totaal[0];
    }
    if (!empty($kenmerken_totaal[1])) {
        $kenmerken_wegschrijven .= "," . $kenmerken_totaal[1];
    }
    if (!empty($kenmerken_totaal[2])) {
        $kenmerken_wegschrijven .= "," . $kenmerken_totaal[2];
    }
    if (!empty($kenmerken_totaal[3])) {
        $kenmerken_wegschrijven .= "," . $kenmerken_totaal[3];
    }
    if (!empty($kenmerken_totaal[4])) {
        $kenmerken_wegschrijven .= "," . $kenmerken_totaal[4];
    }
    if (!empty($kenmerken_totaal[5])) {
        $kenmerken_wegschrijven .= "," . $kenmerken_totaal[5];
    }
    if (!empty($kenmerken_totaal[6])) {
        $kenmerken_wegschrijven .= "," . $kenmerken_totaal[6];
    }
    if (!empty($kenmerken_totaal[7])) {
        $kenmerken_wegschrijven .= "," . $kenmerken_totaal[7];
    }
    if (!empty($kenmerken_totaal[8])) {
        $kenmerken_wegschrijven .= "," . $kenmerken_totaal[8];
    }
}

// formulieren ophalen
// =================
if ($_POST['formulieren']) {
    $formulieren = $_POST['formulieren'];
    foreach ($formulieren as $key[] => $waarden) {
        $formulieren_totaal[] = "$waarden";
    }

    if (!empty($formulieren_totaal[0])) {
        $formulieren_wegschrijven = $formulieren_totaal[0];
    }
    if (!empty($formulieren_totaal[1])) {
        $formulieren_wegschrijven .= "," . $formulieren_totaal[1];
    }
    if (!empty($formulieren_totaal[2])) {
        $formulieren_wegschrijven .= "," . $formulieren_totaal[2];
    }
    if (!empty($formulieren_totaal[3])) {
        $formulieren_wegschrijven .= "," . $formulieren_totaal[3];
    }
    if (!empty($formulieren_totaal[4])) {
        $formulieren_wegschrijven .= "," . $formulieren_totaal[4];
    }
    if (!empty($formulieren_totaal[5])) {
        $formulieren_wegschrijven .= "," . $formulieren_totaal[5];
    }
    if (!empty($formulieren_totaal[6])) {
        $formulieren_wegschrijven .= "," . $formulieren_totaal[6];
    }
    if (!empty($formulieren_totaal[7])) {
        $formulieren_wegschrijven .= "," . $formulieren_totaal[7];
    }
    if (!empty($formulieren_totaal[8])) {
        $formulieren_wegschrijven .= "," . $formulieren_totaal[8];
    }
}

$sql = $mysqli->query("SELECT *,DATE_FORMAT(datum, '%d-%m-%Y') AS datum1,DATE_FORMAT(datum2, '%d-%m-%Y') AS datum2 FROM siteworkcms WHERE id = '" . $_GET['id'] . "' ") or die($mysqli->error . __LINE__);
$row = $sql->fetch_assoc();

if($sql->num_rows <= 0) {
    header("Location: maincms.php?page=webpaginas");
    exit();
}

// pagina wijzigen
// ===============
if ($_POST['opslaan'] == 1 or $_POST['opslaan-rechts'] == 1) {

    if($_POST['bevat_customfields'] == 1) {
        $prefix = 'cf-';

        foreach ($_POST as $key => $value) {
            if (strpos($key, $prefix) === 0) {
                $customField = substr($key, strlen($prefix));
                $customField = trim($customField); // Trim before splitting

                $FieldWaardeCheck = explode('_', $customField); 
                
                if($customField != 'new'):

                    if (is_array($value)):
                        $concatenatedValue = "";  // Initialize an empty string to store concatenated values
                    
                        foreach ($value as $index => $val) {
                            $concatenatedValue .= $val . ",";  // Concatenate each value followed by a comma
                        }
                    
                        // Remove the trailing comma
                        $concatenatedValue = rtrim($concatenatedValue, ',');
                    else: 
                        $concatenatedValue = $value;  // If $value is not an array, use it as is
                    endif;
                    
                    $sqlWaardeCheck = $mysqli->query("SELECT sw_nonce FROM sitework_customfields_waardes WHERE veld_id = '" . $FieldWaardeCheck[0] . "' AND cms_id = '".$row['id']."' AND sw_nonce = '".$FieldWaardeCheck[1]."' AND taal = '".$_GET['taal']."'") or die($mysqli->error . __LINE__);
                    
                    if($sqlWaardeCheck->num_rows > 0):
                        $rowWaardeCheck = $sqlWaardeCheck->fetch_assoc();
                    
                        $sql_insertoptie = $mysqli->query("UPDATE sitework_customfields_waardes SET 
                                                            waarde = '".$concatenatedValue."' 
                                                            WHERE sw_nonce = '".$rowWaardeCheck['sw_nonce']."'") or die($mysqli->error.__LINE__);
                    else: 
                        if($concatenatedValue != "" && $concatenatedValue != null):

                            $sw_nonce = generateNonce(25);
                            $sql_insertVeldWaarde = $mysqli->query("INSERT INTO sitework_customfields_waardes SET 
                                                                    veld_id = '".$FieldWaardeCheck[0]."', 
                                                                    cms_id = '".$row['id']."', 
                                                                    waarde = '".$concatenatedValue."', 
                                                                    taal = '".$_GET['taal']."',
                                                                    sw_nonce = '".$sw_nonce."'") or die($mysqli->error.__LINE__);
                        endif;
                    endif;
                endif;
            }
        }
    }

    if($_POST['cf-new'] == 1 && $_POST['cfnew-veld'] != "") {
        $prefixCFNew = 'cfnew-';
        $customFields = []; // Initialize an empty array to store custom fields

        foreach ($_POST as $key => $value) {
          if (strpos($key, $prefixCFNew) === 0) {
            $customField = substr($key, strlen($prefixCFNew));

            if($value != null && $value != "") {
                if($customField == 'save') {
                    $value = explode("_", $value);

                    if($value[0] == 'cms') {
                        $customField = "cms_id";
                    } elseif($value[0] == 'cat') {
                        $customField = "cat";
                    } elseif($value[0] == 'kenmerk') {
                        $customField = "kenmerk";
                    } elseif($value[0] == 'template') {
                        $customField = "template_id";
                    }

                    $customFields[$customField] = $value[1];
                    continue;
                }

                $customFields[$customField] = $value;
            }
          }
        }

        $sql_insert = "INSERT INTO sitework_customfields (";
        $values = array();

        foreach ($customFields as $field => $value) {
            $sql_insert .= "`" . $field . "`, "; // Add field names to the query
            $values[] = "'" . $mysqli->real_escape_string($value) . "'"; // Escape and add values
        }

        // Remove the trailing comma from field names
        $sql_insert = rtrim($sql_insert, ", ") . ") VALUES (";

        // Add escaped values to the query
        $sql_insert .= implode(',', $values) . ")";

        $mysqli->query($sql_insert) or die($mysqli->error . __LINE__);
    }

    // verplichte velden
    // =================
    if (!$_POST['item1'] or !$_POST['item2'] or !$_POST['keuze1'] or !$_POST['paginaurl']) {
        $error = "U heeft nog niet alle verplichte velden goed ingevuld!";
        if (!$_POST['keuze1']) {
            $error_keuze1 = 'ja';
        }
        if (!$_POST['item1']) {
            $error_item1 = 'ja';
        }
        if (!$_POST['item2']) {
            $error_item2 = 'ja';
        }
        if (!$_POST['paginaurl']) {
            $error_paginaurl = 'ja';
        }
    } else {    // datum omzetten
        // ==============
        $datum = explode("-", $_POST['datum']);
        $datum2 = explode("-", $_POST['datum2']);

        // alleen bij hoofd en submenu een menutitel kunnen invoeren
        // =========================================================
        $menutitel = "item1 = '" . $mysqli->real_escape_string($_POST['item1']) . "',";

        if($_POST['datum2']){
            $datum2Post = "datum2 = '" . $datum_eng = $datum2[2] . "-" . $datum2[1] . "-" . $datum2[0] . "',";
        }

        // alleen bij meertaligheid taal bijwerken
        // =======================================
        if ($rowinstellingen['meertaligheid'] == 'ja') {
            $taalkeuze = "taal = '" . $_POST['taal'] . "',";
        }

        if ($_GET['taal'] != 'nl' AND $_GET['taal'] != '') {
            $_POST['laatste_wijziging'] = date('Y-m-d H:i:s');

            foreach ($_POST as $veld => $waarde) {
                if($veld != 'keuze1' AND $veld != 'kenmerken' AND $veld != 'opslaan' AND $veld != 'addBlock' AND $veld != 'paginaTaal' AND $veld != 'opslaan-rechts' AND $veld != 'opslaan2' AND $veld != 'koppeling' AND $veld != 'paginalink'
                AND $veld != 'cfnew-veld' AND $veld != 'cfnew-save' AND $veld != 'cfnew-type' AND $veld != 'cfnew-taal' AND $veld != 'cfnew-slug' AND $veld != 'cf-new' AND $veld != 'bevat_customfields' AND !preg_match('/^cf-/', $veld) AND $veld != ''){
                    $sqlInsertOrUpdate = $mysqli->query("SELECT id FROM sitework_vertaling WHERE veld = '" . $veld . "' AND cms_id = '".$row['id']."' AND taal = '".$_GET['taal']."'") or die($mysqli->error . __LINE__);
                    $rowInsertOrUpdate = $sqlInsertOrUpdate->fetch_assoc();

                    if($rowInsertOrUpdate['id']){
                        if($veld == 'paginaurl' && $waarde == ''){
                            $waarde = strtolower(slugify($_POST['item2']));
                        }
                        if($veld == 'tekst'){
                            $waarde = str_replace('\r\n', '', $mysqli->real_escape_string($_POST['tekst']));
                        }
                        $sql_insert = $mysqli->query("UPDATE sitework_vertaling 
                                                                SET 
                                                                    cms_id = '" . $row['id'] . "',
                                                                    veld = '" . $veld . "',
                                                                    waarde = '" . $waarde . "',
                                                                    taal = '" . $_GET['taal'] . "'
                                                                    WHERE id = '" . $rowInsertOrUpdate['id'] . "'
																 
																 ") or die($mysqli->error . __LINE__);
                        $rowid = $mysqli->insert_id;
                        $melding = "Gegevens zijn opgeslagen";
                    }else{
                        $sql_insert = $mysqli->query("INSERT sitework_vertaling 
                                                                SET 
                                                                    cms_id = '" . $row['id'] . "',
                                                                    veld = '" . $veld . "',
                                                                    waarde = '" . $waarde . "',
                                                                    taal = '" . $_GET['taal'] . "'
																 
																 ") or die($mysqli->error . __LINE__);
                        $rowid = $mysqli->insert_id;
                        $melding = "Gegevens zijn opgeslagen";
                    }
               }
            }

            header('Location: ?page=pagina_bewerken&id=' . $_GET['id'] . '&taal='.$_GET['taal'].'&opgeslagen=ja');
        } else {
            $sql_insert = $mysqli->query("UPDATE siteworkcms SET 
                              $menutitel 
                              $taalkeuze
                              item2       = '" . $mysqli->real_escape_string($_POST['item2']) . "',
                              item3       = '" . $mysqli->real_escape_string($_POST['item3']) . "',
                              item4       = '" . $mysqli->real_escape_string($_POST['item4']) . "',
                              item5       = '" . $mysqli->real_escape_string($_POST['item5']) . "',
                              keuze1      = '" . $_POST['keuze1'] . "',
                              tekst      = '" . str_replace('\r\n', '', $mysqli->real_escape_string($_POST['tekst'])) . "',
                              status      = '" . $_POST['status'] . "',
                              datum       = '" . $datum_eng = $datum[2] . "-" . $datum[1] . "-" . $datum[0] . "',
                              $datum2Post
                              paginaurl   = '" . strtolower(slugify($_POST['paginaurl'])) . "',
                              externeurl  = '" . $mysqli->real_escape_string(string: $_POST['externeurl']) . "',
                              targetlink  = '" . $_POST['targetlink'] . "',
                              kenmerken   = '" . $kenmerken_wegschrijven . "',
                              formulieren = '" . $formulieren_wegschrijven . "',
                              
                              inXML  = '" . $_POST['inXML'] . "',
                              eigenXMLurl  = '" . $mysqli->real_escape_string($_POST['eigenXMLurl']) . "' WHERE id = '" . $_GET['id'] . "' ") or die($mysqli->error . __LINE__);

            $rowid = $mysqli->insert_id;
            $melding = "Gegevens zijn opgeslagen";


            //pagina redirecten om deze te kunnen bewerken
            header('Location: ?page=pagina_bewerken&id=' . $_GET['id'] . '&opgeslagen=ja');
        }
    }
}

//Als $_GET opgeslagen=ja is de pagina correct ingevuld en opgeslagen: dus we tonen een alert
if ($_GET['opgeslagen'] == "ja") {
    echo "
  <div class=\"alert alert-success\">
    Gegevens zijn opgeslagen
  </div>
  ";
};
?>
<span id="scroll-to-cms-top"><i class="fas fa-arrow-up"></i></span>

<form id="pagina_bw_form" action="<?php echo $PHP_SELF ?>" method="post" enctype="multipart/form-data">
    <div class="box-container">
        <?php if($rowinstellingen['meertaligheid'] == 'ja'): ?>
            <div id="taalswitch">
                <?php taalmenu($row['id'], $_GET['taal']); ?>
            </div>
        <?php endif; ?>
        <div class="box box-2-3 md-box-full">
            <h3><span class="icon far fa-pencil-alt"></span>Pagina bewerken</h3>
            <?php if($rowuser['id'] == '1'): ?>
                <a href="" class="open-custom btn nieuw fl-right">Voeg velden toe</a>
            <?php endif; ?>
            <div class="content-container mt-0">
                <?php if ($error) { ?><span class="error"><?php echo $error; ?></span><?php } ?>

                <div class="form-group">
                    <label for="keuze1">Categorie</label>
                    <select name="keuze1" class="inputveld invoer dropdown <?php if ($error_keuze1) {
                        echo ' foutveld';
                    } ?>" placeholder="Selecteer een categorie">
                        <option value="<?php echo getTranslation($row['keuze1'], 'categorie', 'nl', $row['id']); ?>"><?php echo getTranslation($row['keuze1'], 'categorie', $_GET['taal'], $row['id']); ?></option>
                        <option value="">----------------------------</option>
                        <?php // categorien ophalen
                        foreach (getCategorie() as $categorien) {
                            if ($_GET['taal'] != 'nl') {
                                $categorieWaarde = htmlspecialchars(getTranslation($categorien['categorie'], 'categorie', $_GET['taal'], $row['id']));
                            } else {
                                $categorieWaarde = htmlspecialchars($categorien['categorie']);
                            }
                            echo '<option value="' . htmlspecialchars($categorien['categorie']) . '">' . $categorieWaarde . '</option>';
                        } // functie categorien?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="item1">Menutitel</label><input type="text" name="item1"
                                                               class="inputveld invoer<?php if ($error_item1) {
                                                                   echo ' foutveld';
                                                               } ?>" placeholder="" value="<?php echo getTranslation('item1', 'veld', $_GET['taal'], $row['id']); ?>"
                                                               id="item1"/>
                </div>
                <div class="form-group">
                    <label for="item2">Paginatitel</label><input type="text" name="item2"
                                                                 class="inputveld invoer<?php if ($error_item2) {
                                                                     echo ' foutveld';
                                                                 } ?>" placeholder=""
                                                                 value="<?php echo getTranslation('item2', 'veld', $_GET['taal'], $row['id']); ?>"/>
                </div>
                <?php if ($row['keuze1'] == "team" /*sticker pagina*/) { ?>
                    <div class="form-group">
                        <label for="item3">Functie</label><input type="text" name="item3" class="inputveld invoer"
                                                                 placeholder="" value="<?php echo getTranslation('item3', 'veld', $_GET['taal'], $row['id']); ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="item4">Email</label><input type="text" name="item4" class="inputveld invoer"
                                                               placeholder="" value="<?php echo getTranslation('item4', 'veld', $_GET['taal'], $row['id']); ?>"/>
                    </div>
                <?php } ?>
                <?php if ($row['id'] == "69" /*sticker pagina*/) { ?>
                    <div class="form-group">
                        <label for="item3">Sticker titel</label><input type="text" name="item3" class="inputveld invoer"
                                                                       placeholder=""
                                                                       value="<?php echo getTranslation('item3', 'veld', $_GET['taal'], $row['id']); ?>"/>
                    </div>
                <?php } ?>

                <div class="toggle-custom">
                    <strong>Maak een nieuw customfield</strong>
                    <div class="form-group">
                        <input type="text" name="cfnew-veld" class="inputveld invoer small-30 round-full" maxlength="25" placeholder="Veld naam (max. 25 characters)">
                        <select name="cfnew-save" id="cfnew-save" class="inputveld invoer small-20 round-full">
                            <option value="">Kies een koppel mogelijkheid</option>
                            <optgroup label="Pagina">
                                <option value="cms_0">Dit veld komt op alle pagina's</option>
                                <option value="cms_<?=$row['id'];?>">Huidige pagina (<?php echo getTranslation('item2', 'veld', $_GET['taal'], $row['id']); ?>)</option>
                            </optgroup>
                            <optgroup label="Categorieën">
                                <?php foreach (getCategorie() as $categorien) {
                                    if ($_GET['taal'] != 'nl') {
                                        $categorieWaarde = htmlspecialchars(getTranslation($categorien['categorie'], 'categorie', $_GET['taal'], $row['id']));
                                    } else {
                                        $categorieWaarde = htmlspecialchars($categorien['categorie']);
                                    }
                                    echo '<option value="cat_' . htmlspecialchars($categorien['categorie']) . '">' . $categorieWaarde . '</option>';
                                } ?>
                            </optgroup>
                            <optgroup label="Kenmerken">
                                <?php foreach (getKenmerken() as $kenmerken) {
                                    if ($_GET['taal'] != 'nl') {
                                        $kenmerkWaarde = htmlspecialchars(getTranslation($kenmerken, 'kenmerk', $_GET['taal'], $row['id']));
                                    } else {
                                        $kenmerkWaarde = htmlspecialchars($kenmerken);
                                    }
                                    echo '<option value="kenmerk_' . htmlspecialchars($kenmerken) . '">' . $kenmerkWaarde . '</option>';
                                } ?>
                            </optgroup>
                            <optgroup label="Templates">
                                <option value="template_">Template</option>
                            </optgroup>
                        </select>
                        <select name="cfnew-type" id="cfnew-type" class="inputveld invoer small-20 round-full">
                            <option value="">Kies een veldtype</option>
                            <option value="tekst">Tekst</option>
                            <option value="tekstveld">Tekstveld</option>
                            <option value="datum">Datum</option>
                            <option value="keuze selectie">Keuze selectie</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="radio">Radio</option>
                        </select>
                        <select name="cfnew-taal" id="cfnew-taal" class="inputveld invoer small-20 round-full">
                            <option value="">Kies een taal</option>
                            <?php
                                $sqltaal = $mysqli->query("SELECT * FROM sitework_taal WHERE actief = '1' ORDER BY taalkort DESC") or die($mysqli->error . __LINE__);
                                while($rowSelecttaal = $sqltaal->fetch_assoc()):
                                    echo '<option value="'.$rowSelecttaal['taalkort'].'">'.$rowSelecttaal['taallang'].'</option>';
                                endwhile;
                            ?>
                        </select>
                        <input type="hidden" name="cfnew-slug" value="">
                        <input type="hidden" name="cf-new" value="1">
                        <button type="submit" class="btn inputveld invoer small-10 round-full">Voeg toe</button>
                    </div>
                </div>

                <?php 
                    $customfields = getCustomFields($row['id'], $row['keuze1'], $row['kenmerken'], 0, $_GET['taal']);
                    if($customfields['count'] > 0):
                        echo '<input type="hidden" name="bevat_customfields" value="1" />';
                    endif;
                    foreach ($customfields['data'] as $customfield): ?>
                        <div class="form-group">
                            <?php
                                $waarde = getCustomFieldWaarde($customfield['id'], $row['id'], $_GET['taal']);
                                $sw_nonce = getCustomFieldNonce($customfield['id'], $row['id'], $_GET['taal']);
                                $tooltip = ($rowuser['id'] == '1') ? 'showTooltip' : '';

                                if($customfield['type'] == 'tekst'):
                                    echo '<div class="form-label '.$tooltip.'" title="'.$customfield['slug'].'">'.$customfield['veld'].'</div>';
                                    echo '<input type="text" name="cf-'.$customfield['id'].'_'.$sw_nonce.'" class="inputveld invoer" placeholder="" value="'.$waarde.'" id="customfield-'.$customfield['id'].'"/>';

                                    if($rowuser['id'] == '1'):
                                        echo '<a data-fancybox data-small-btn="true" data-type="iframe" class="btn options voorbeeld icon-only-small" href="'.$url.'/cms/php/add_subcustomfields.php?field_id='.$customfield['id'].'&cms_id='.$row['id'].'"></a>';
                                    endif;

                                elseif($customfield['type'] == 'tekstveld'):
                                    echo '<div class="form-label '.$tooltip.'" title="'.$customfield['slug'].'">'.$customfield['veld'].'</div>';
                                    echo '<div id="cf-'.$customfield['id'].'" style="min-height:400px;max-height:800px" class="inputveld invoer sitework-editor">';
                                        echo $waarde;
                                    echo '</div>';
                                    echo '<input type="hidden" id="cf-'.$customfield['id'].'_'.$sw_nonce.'" name="cf-'.$customfield['id'].'_'.$sw_nonce.'">';

                                    echo '<style>br[data-mce-bogus="1"] {display:none;}</style>';
                                    echo '<script>';
                                    echo '
                                        const editorConfigCF = {
                                            toolbar: "siteworkcustom",
                                            skin: "rounded-corner",
                                            editorResizeMode: "none",
                                            url_base: "'.$url.'/cms/richtexteditor",
                                            insertimage: {
                                                byUrl: true,   // Allow inserting image by URL
                                                upload: false, // Disable uploading images
                                                gallery: false // Disable image gallery
                                            }
                                        };
                                        var editorCF_'.$customfield['id'].' = new RichTextEditor("#cf-'.$customfield['id'].'", editorConfigCF);

                                        editorCF_'.$customfield['id'].'.attachEvent("exec_command_ctabutton", function (state, cmd, value) {
                                            state.returnValue = true;//set it has been handled

                                            var a = editorCF_'.$customfield['id'].'.insertRootParagraph("a");
                                            a.classList.add("btn");
                                            a.innerHTML = "Voeg uw link toe";
                                        });

                                        document.getElementById("pagina_bw_form").addEventListener("submit", function(event) {
                                            document.getElementById("cf-'.$customfield['id'].'_'.$sw_nonce.'").value = editorCF_'.$customfield['id'].'.getHTMLCode();
                                        });
                                        ';
                                    echo '</script>';

                                    if($rowuser['id'] == '1'):
                                        echo '<a data-fancybox data-small-btn="true" data-type="iframe" class="btn options voorbeeld icon-only-small" style="height:45px;" href="'.$url.'/cms/php/add_subcustomfields.php?field_id='.$customfield['id'].'&cms_id='.$row['id'].'"></a>';
                                    endif;

                                elseif($customfield['type'] == 'datum'):
                                    echo '<script>';
                                        echo    '$(function() {
                                                    $( "#customfield-'.$customfield['id'].'" ).datepicker();
                                                });';
                                    echo '</script>';
                                    echo '<div class="form-label '.$tooltip.'" title="'.$customfield['slug'].'">'.$customfield['veld'].'</div>';
                                    echo '<input type="text" name="cf-'.$customfield['id'].'_'.$sw_nonce.'" class="inputveld invoer" id="customfield-'.$customfield['id'].'" value="'.$waarde.'"/>';
                                    
                                    if($rowuser['id'] == '1'):
                                        echo '<a data-fancybox data-small-btn="true" data-type="iframe" class="btn options voorbeeld icon-only-small" href="'.$url.'/cms/php/add_subcustomfields.php?field_id='.$customfield['id'].'&cms_id='.$row['id'].'"></a>';
                                    endif;

                                elseif($customfield['type'] == 'keuze selectie'):
                                    echo '<div class="form-label '.$tooltip.'" title="'.$customfield['slug'].'">'.$customfield['veld'].'</div>';
                                    echo '<select name="cf-'.$customfield['id'].'_'.$sw_nonce.'" class="inputveld invoer dropdown" placeholder="" id="customfield-'.$customfield['id'].'">';
                                        echo '<option value="" >Selecteer een keuze</option>';
                                        foreach (getSubCustomFields($customfield['id']) as $subCustomfieldOption):
                                            $subwaarde = getCustomFieldWaarde($customfield['id'], $row['id'], $_GET['taal']);
                                            if($subwaarde == $subCustomfieldOption['waarde']) {
                                                $selectieCheck = "selected";
                                            } else { $selectieCheck = ""; }

                                            echo '<option value="'.$subCustomfieldOption['waarde'].'" '.$selectieCheck.'>'.$subCustomfieldOption['veld'].'</option>';
                                        endforeach;
                                    echo '</select>';

                                    if($rowuser['id'] == '1'):
                                        echo '<a data-fancybox data-small-btn="true" data-type="iframe" class="btn options voorbeeld icon-only-small" href="'.$url.'/cms/php/add_subcustomfields.php?field_id='.$customfield['id'].'&cms_id='.$row['id'].'"></a>';
                                    endif;

                                elseif($customfield['type'] == 'checkbox'):
                                    echo '<div class="form-label '.$tooltip.'" title="'.$customfield['slug'].'">'.$customfield['veld'].'</div>';
                                    echo '<div class="inputveld invoer checkbox">';
                                        foreach (getSubCustomFields($customfield['id']) as $subCustomfieldCheck):
                                            $subwaarde = getCustomFieldWaarde($customfield['id'], $row['id'], $_GET['taal']);
                                            $subArray = explode(',', $subwaarde);
                                            if(in_array($subCustomfieldCheck['waarde'], $subArray)) {
                                                $checkboxCheck = "checked";
                                            } else { $checkboxCheck = ""; }

                                            echo '<input type="checkbox" name="cf-'.$customfield['id'].'_'.$sw_nonce.'[]" id="subcustomfield-'.$subCustomfieldCheck['id'].'" value="'.$subCustomfieldCheck['waarde'].'" '.$checkboxCheck.' />';
                                            echo '<label for="subcustomfield-'.$subCustomfieldCheck['id'].'">'.$subCustomfieldCheck['veld'].'</label>';
                                        endforeach;
                                    echo '</div>';

                                    if($rowuser['id'] == '1'):
                                        echo '<a data-fancybox data-small-btn="true" data-type="iframe" class="btn options voorbeeld icon-only-small" href="'.$url.'/cms/php/add_subcustomfields.php?field_id='.$customfield['id'].'&cms_id='.$row['id'].'"></a>';
                                    endif;

                                elseif($customfield['type'] == 'radio'):
                                    echo '<div class="form-label '.$tooltip.'" title="'.$customfield['slug'].'">'.$customfield['veld'].'</div>';
                                    echo '<div class="inputveld invoer radio">';
                                        foreach (getSubCustomFields($customfield['id']) as $subCustomfieldRadio):
                                            $subwaarde = getCustomFieldWaarde($customfield['id'], $row['id'], $_GET['taal']);
                                            if($subwaarde == $subCustomfieldRadio['waarde']) {
                                                $radioCheck = "checked";
                                            } else { $radioCheck = ""; }

                                            echo '<input type="radio" name="cf-'.$customfield['id'].'_'.$sw_nonce.'" id="subcustomfield-'.$subCustomfieldRadio['id'].'" value="'.$subCustomfieldRadio['waarde'].'" '.$radioCheck.' />';
                                            echo '<label for="subcustomfield-'.$subCustomfieldRadio['id'].'">'.$subCustomfieldRadio['veld'].'</label>';
                                        endforeach;
                                    echo '</div>';

                                    if($rowuser['id'] == '1'):
                                        echo '<a data-fancybox data-small-btn="true" data-type="iframe" class="btn options voorbeeld icon-only-small" href="'.$url.'/cms/php/add_subcustomfields.php?field_id='.$customfield['id'].'&cms_id='.$row['id'].'"></a>';
                                    endif;

                                endif;
                            ?>                        
                        </div>
                <?php endforeach; ?>

                <div class="form-group">
                    <label for="tekst">Tekstinvoer</label>
                    <div id="page_tekst_editor" style="min-height:400px;max-height:800px" class="inputveld invoer sitework-editor">
                        <?php echo getTranslation('tekst', 'veld', $_GET['taal'], $row['id']); ?>
                    </div>
                    <input type="hidden" id="page_tekst" name="tekst">
                </div>
                <?php /* DEZE VELDEN STAAN STANDAARD UITGESCHAKELD
      <div class="label">Extra veld 1</div><input type="text" name="item3" class="invoerveld" placeholder="Vul hier iets in" value="<?=$row['item3']; ?>"
                />
                <div class="label">Extra veld 2</div><input type="text" name="item4" class="invoerveld"
                    placeholder="Vul hier iets in" value="<?=$row['item4']; ?>" />
                <div class="label">Extra veld 3</div><input type="text" name="item5" class="invoerveld"
                    placeholder="Vul hier iets in" value="<?=$row['item5']; ?>" />
                <div class="label">Prijs</div><input type="text" name="prijs" class="invoerveld"
                    placeholder="Vul hier iets in" value="<?=$row['prijs']; ?>" /> */ ?>
                <div class="form-group">
                    <label for="paginalink">Paginalink</label>
                    <input type="text" class="inputveld invoer" name="paginalink" readonly
                           value="<?= $url; ?>/<?php //hoofdpagina ophalen
                           $sqllink = $mysqli->query("SELECT id,paginaurl FROM siteworkcms WHERE id = '" . $row['hoofdid'] . "'") or die($mysqli->error . __LINE__);
                           $rowlink = $sqllink->fetch_assoc();

                           $paginaURL = getTranslation('paginaurl', 'veld', $_GET['taal'], $row['id']);

                           if ($rowinstellingen['meertaligheid'] == 'ja') {
                               echo $_GET['taal'] . '/';
                           } ?><?php if ($rowlink['paginaurl']) {
                               $hoofdLink = getTranslation('paginaurl', 'veld', $_GET['taal'], $rowlink['id']);
                               echo strtolower(str_replace(" ", "-", $hoofdLink)) . "/";
                           } ?><?php if ($paginaURL) {
                               echo $paginaURL;
                           } else {
                               echo strtolower(str_replace(" ", "-", $paginaURL));
                           } ?>">
                </div>

                <div class="form-group">
                    <label for="externeurl">Externe/interne link</label>
                    <input type="text" name="externeurl" class="inputveld small-70 invoer "
                           placeholder="Vul hier de interne/externe link in" value="<?php echo getTranslation('externeurl', 'veld', $_GET['taal'], $row['id']); ?>"/>
                    <div class="inputveld invoer small-30 checkbox">
                        <input type="checkbox" name="targetlink" id="targetlink-label"
                               value="ja" <?php $targetLink = getTranslation('targetlink', 'veld', $_GET['taal'], $row['id']); if ($targetLink) {
                            echo "checked";
                        } ?> />
                        <label for="targetlink-label">Open in nieuw venster</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="paginaurl">Link wijzigen</label><input type="text" id="myText" name="paginaurl"
                                                                       class="inputveld invoer<?php if ($error_paginaurl) {
                                                                           echo ' foutveld';
                                                                       } ?>" placeholder="Vul hier de nieuwe link in"
                                                                       value="<?php echo getTranslation('paginaurl', 'veld', $_GET['taal'], $row['id']); ?>"/>
                </div>

                <div class="form-group">
                    <label for="externeurl">Sitemap</label>
                    <input type="text" name="eigenXMLurl" class="inputveld small-70 invoer "
                           placeholder="bij afwijkende URL kan je hier de url voor de sitemap aanpassen"
                           value="<?php echo $row['eigenXMLurl']; ?>"/>
                    <div class="inputveld invoer small-30 checkbox">
                        <input type="checkbox" name="inXML" id="xmllink-label" value="ja" <?php
                        $inXML = getTranslation('inXML', 'veld', $_GET['taal'], $row['id']); 
                        if ($inXML) {
                            echo "checked";
                        } ?> />
                        <label for="xmllink-label">Meenemen in sitemap</label>
                    </div>
                </div>

                <a class="btn fl-left arrow mr-10" href="<?= $url; ?>/<?php if ($rowinstellingen['meertaligheid'] == 'ja') {
                    echo $_GET['taal'] . '/';
                } ?><?php if ($rowlink['paginaurl']) {
                    $hoofdLink = getTranslation('paginaurl', 'veld', $_GET['taal'], $rowlink['id']);
                    echo strtolower(str_replace(" ", "-", $hoofdLink)) . "/";
                } ?><?php if ($paginaURL) {
                    echo $paginaURL;
                } else {
                    echo strtolower(str_replace(" ", "-", $paginaURL));
                } ?>" target="_blank">Bekijk preview</a>
                <input type="hidden" name="opslaan" value="1">
                <button name="opslaan" class="btn fl-left mr-10 save" type="submit">Opslaan</button>
                <i class="warning fl-left"><span class="far fa-exclamation"></span>&nbsp;Let op, blokken apart opslaan</i>


            </div>

            <? //BLOCKS//?>
            <?php if ($rowinstellingen['blockbuilder'] == 'ja') { ?>
                <div class="content-container mt-20">
                    <h3><span class="icon far fa-plus"></span>Block toevoegen</h3>
                    <div class="form-group">
                        <label>Blocks</label>
                        <a href="" class="clickme btn fl-left nieuw browse">Block toevoegen</a>
                        <a class="btn fl-left ml-10 move-btn" data-fancybox data-small-btn="true" data-type="iframe"
                           href="php/blocks/sortering.php?id=<?= $_GET['id']; ?>&taal=<?=$_GET['taal'];?>" href="javascript:;">Volgorde blokken
                            wijzigen</a>
                        <div class="toggle-box">
                            <?php //haal alle toegevoegde blokken op
                            $sqlBlocks = $mysqli->query("SELECT * FROM sitework_block WHERE actief = '1' ORDER BY block_naam ASC") or die($mysqli->error . __LINE__);
                            ?>
                            <select name="addBlock" class="inputveld dropdown sidebar selectBlock">
                                <option value="">Selecteer een block</option>
                                <option value="">----------------------------</option>
                                <?php
                                while ($rowBlock = $sqlBlocks->fetch_assoc()) {
                                    echo '<option value="' . $rowBlock['id'] . '">' . $rowBlock['block_naam'] . '</option>';
                                }
                                ?>
                            </select>
                            <input type="hidden" name="paginaTaal" value="<?=$_GET['taal'];?>">
                            <button id="<?php echo $row['id']; ?>" class="addbutton btn fl-left">voeg toe</button>
                        </div>
                    </div>
                </div>
                <div class="content-container mt-20">
                    <div id="blocks">
                        <?php include('php/blocks/overzicht.php'); ?>
                    </div>
                </div>
            <?php } ?>
            <? // EIND BLOCKS//?>
        </div>

        <div class="box box-1-3 md-box-full" data-sticky_parent>

            <div class="sidebar-box stickysave">
                <h3><span class="icon far fa-sticky-note"></span>Pagina opslaan</h3>
                <input type="hidden" name="opslaan-rechts" value="1">
                <button name="opslaan2" class="btn fl-left mr-10 save" type="submit">Opslaan</button>
                <i class="warning fl-left"><span class="far fa-exclamation"></span>&nbsp;Let op, blokken apart opslaan</i>
            </div>

            <div class="sidebar-box sticky_previews">
                <h3><span class="icon far fa-search"></span>Bekijk preview</h3>
                <div class="">
                    <a data-fancybox data-small-btn="true" data-type="iframe" class="btn fl-left responsive-fancy desktop mr-10" href="<?= $url; ?>/<?php if ($rowinstellingen['meertaligheid'] == 'ja') {
                        echo $_GET['taal'] . '/';
                    } ?><?php if ($rowlink['paginaurl']) {
                        $hoofdLink = getTranslation('paginaurl', 'veld', $_GET['taal'], $rowlink['id']);
                        echo strtolower(str_replace(" ", "-", $hoofdLink)) . "/";
                    } ?><?php if ($paginaURL) {
                        echo $paginaURL;
                    } else {
                        echo strtolower(str_replace(" ", "-", $paginaURL));
                    } ?>" target="_blank" data-caption="Desktop scherm van - <?php if($rowinstellingen['meertaligheid'] == 'ja') { echo $row['item2'] .' ('. strtoupper($_GET['taal']) . ')'; } else { echo $row['item2']; }?>">Desktop</a>
                    <a data-fancybox data-small-btn="true" data-type="iframe" class="btn fl-left responsive-fancy tablet mr-10" href="<?= $url; ?>/<?php if ($rowinstellingen['meertaligheid'] == 'ja') {
                        echo $_GET['taal'] . '/';
                    } ?><?php if ($rowlink['paginaurl']) {
                        $hoofdLink = getTranslation('paginaurl', 'veld', $_GET['taal'], $rowlink['id']);
                        echo strtolower(str_replace(" ", "-", $hoofdLink)) . "/";
                    } ?><?php if ($paginaURL) {
                        echo $paginaURL;
                    } else {
                        echo strtolower(str_replace(" ", "-", $paginaURL));
                    } ?>" target="_blank" data-caption="Tablet scherm van - <?php if($rowinstellingen['meertaligheid'] == 'ja') { echo $row['item2'] .' ('. strtoupper($_GET['taal']) . ')'; } else { echo $row['item2']; }?>">Tablet</a>
                    <a data-fancybox data-small-btn="true" data-type="iframe" class="btn fl-left responsive-fancy mobiel" href="<?= $url; ?>/<?php if ($rowinstellingen['meertaligheid'] == 'ja') {
                        echo $_GET['taal'] . '/';
                    } ?><?php if ($rowlink['paginaurl']) {
                        $hoofdLink = getTranslation('paginaurl', 'veld', $_GET['taal'], $rowlink['id']);
                        echo strtolower(str_replace(" ", "-", $hoofdLink)) . "/";
                    } ?><?php if ($paginaURL) {
                        echo $paginaURL;
                    } else {
                        echo strtolower(str_replace(" ", "-", $paginaURL));
                    } ?>" target="_blank" data-caption="Mobiel scherm van - <?php if($rowinstellingen['meertaligheid'] == 'ja') { echo $row['item2'] .' ('. strtoupper($_GET['taal']) . ')'; } else { echo $row['item2']; }?>">Mobiel</a>
                </div>
                
            </div>

            <div class="sidebar-box">
                <h3><span class="icon far fa-cogs"></span>Publicatie</h3>
                <span class="dropdown-title">Status</span>
                <select name="status" class="inputveld full dropdown sidebar">
                    <option value="<?php echo $row['status']; ?>"><?php echo $row['status']; ?></option>
                    <option value="">----------------------------</option>
                    <option value="Actief">Actief</option>
                    <option value="Niet actief">Niet actief</option>
                    <option value="Prullenbak">Prullenbak</option>
                </select>

                <?php if ($rowinstellingen['meertaligheid'] == 'ja') { ?>
                    <span class="dropdown-title">Taal</span>
                    <select name="taal" class="inputveld full dropdown sidebar" placeholder="Selecteer een taal">

                        <?php $resulttaal = $mysqli->query("SELECT * FROM sitework_taal") or die($mysqli->error . __LINE__);
                        while ($rowtaal = $resulttaal->fetch_assoc()) { ?>

                            <option value="<?= $rowtaal['taalkort']; ?>" <?php if ($_GET['taal'] == $rowtaal['taalkort']) {
                                echo "selected";
                            } ?>><?= $rowtaal['taallang']; ?></option> 
                        <?php } ?>

                    </select>
                <?php } ?> 
                <span class="dropdown-title">Datum gepubliceerd</span>
                <input type="text" name="datum" class="inputveld full dropdown sidebar" id="datepicker"
                       placeholder="Datum" value="<?php echo $row['datum1']; ?>"/>
                <?php 
                    if ($row['keuze1'] == "nieuws") { ?>
                        <span class="dropdown-title">Zichtbaar tot en met</span>
                        <input type="text" name="datum2" class="inputveld full dropdown sidebar" id="datepicker2"
                           placeholder="Datum" value="<?php echo $row['datum2']; ?>"/> 
                <?php } ?>
            </div>
            <div class="sidebar-box">
                <h3><span class="icon far fa-images"></span>Media & Attributen</h3>
                <a class="btn fl-left full mb-10 arrow" data-fancybox data-small-btn="true" data-type="iframe"
                   href="php/media_upload.php?id=<?= $_GET['id']; ?>&block_id=0&taal=<?=$_GET['taal'];?>&media=afbeelding&upload_from=page" href="javascript:;">Afbeeldingen</a>
                <a class="btn fl-left full mb-10 arrow" data-fancybox data-small-btn="true" data-type="iframe"
                   href="php/media_upload.php?id=<?= $_GET['id']; ?>&block_id=0&taal=<?=$_GET['taal'];?>&media=document&upload_from=page" href="javascript:;">Documenten</a>
                <a class="btn fl-left full mb-10 arrow" data-fancybox data-small-btn="true" data-type="iframe"
                   href="php/metatags.php?id=<?= $_GET['id']; ?>&taal=<?=$_GET['taal'];?>" href="javascript:;">Metatags / SEO</a>
                <?php // hoofdpagina ophalen
                // ======================
                $sqllink = $mysqli->query("SELECT id,paginaurl FROM siteworkcms WHERE id = '" . $row['hoofdid'] . "'") or die($mysqli->error . __LINE__);
                $rowlink = $sqllink->fetch_assoc(); ?>
                <?php if($_GET['taal'] == 'nl'): ?>
                    <a class="btn fl-left full arrow" data-fancybox data-small-btn="true" data-type="iframe"
                        href="php/pagina_kopieren.php?id=<?= $_GET['id']; ?>&taal=<?=$_GET['taal'];?>" href="javascript:;">
                        Kopieer naar nieuw concept
                    </a>
                <?php endif; ?>
            </div>

            <div class="sidebar-box">
                <h3><span class="icon far fa-bookmark"></span>Kenmerken</h3>
                <select name=kenmerken[] class="textveld full" multiple size="5" id="kenmerkenvlak">
                    <option value="">Geen keuze</option>
                    <?php 
                        //kenmerken ophalen
                        // ===================
                        foreach (getKenmerken() as $kenmerken) { 
                            $meertaligKenmerk = getTranslation(htmlspecialchars($kenmerken), 'kenmerk', $_GET['taal'], '');
                            ?>
                            <option value="<?php echo htmlspecialchars($kenmerken);?>" <?php if (preg_match('/' . $kenmerken . '/', $row['kenmerken'])) {
                            echo "selected";
                        } ?>><?php echo $meertaligKenmerk; echo ($meertaligKenmerk != "" && $rowinstellingen['meertaligheid'] == 'ja' && $_GET['taal'] != 'nl') ? ' - ('.htmlspecialchars($kenmerken).')' : ''?></option>
                        <?php }
                    ?>
                </select>
                <div class="info full"><span class="far fa-info-circle"></span>&nbsp;&nbsp; Selecteer meerdere kenmerken door
                    de CRTL of de ⌘ toets ingedrukt te houden.
                </div>
            </div>
            <div class="sidebar-box">
                <h3><span class="icon fab fa-wpforms"></span>Formulieren</h3>
                <select name=formulieren[] class="textveld full" multiple size="5" id="kenmerkenvlak">
                    <option value="">Geen keuze</option>
                    <?php //kenmerken ophalen
                    // ===================
                    $sqlformulier = $mysqli->query("SELECT * FROM sitework_formulieren ORDER BY id ASC") or die($mysqli->error . __LINE__);
                    $forms = explode(",", $row['formulieren']);
                    while ($rowformulier = $sqlformulier->fetch_assoc()) {
                        if (in_array($rowformulier['id'], $forms)) {
                            $selected = "selected";
                        } else {
                            $selected = "";
                        } ?>
                        <option value="<?php echo $rowformulier['id']; ?>" <?php echo $selected; ?>>
                            <?php echo $rowformulier['naam']; ?></option>
                        <?php
                    } ?>
                </select>
                <div class="info full"><span class="far fa-info-circle"></span>&nbsp;&nbsp; Selecteer meerdere formulieren door
                    de CRTL of de ⌘ toets ingedrukt te houden.
                </div>
            </div>
        </div>
    </div>


    </div>
    </div>
</form>

<?php 
    include './richtexteditor/pagina-editor.php';
?>
<script>
    document.getElementById('pagina_bw_form').addEventListener('submit', function(event) {
        save_and_strip('page_tekst', editorPage);
    });
</script>