<? // database connectie en inlogfuncties
// ===================================
include ("../../login/config.php");
include ('../../login/functions.php'); 
require_once './block_translate_functions.php';

if($_POST['cmsid']) { $cms_id = $_POST['cmsid']; } 
else { $cms_id = $_GET['cmsid']; }

if($_POST['blockid']) { $block_id = $_POST['blockid']; } 
else { $block_id = $_GET['blockid']; }

if($_POST['taal']) { $taal = $_POST['taal']; }
else { $taal = $_GET['taal']; }

//block gegevenss
if($taal != 'nl') {
    $blocksData = $mysqli->query("SELECT * FROM sitework_vertaling_blocks WHERE id = '".$block_id."'") or die($mysqli->error.__LINE__);
    $rowBlockData = $blocksData->fetch_assoc();

    $tekstTekstFoto = getBlockTranslation('tekst', $taal, $rowBlockData['id'], '', $cms_id);
    $titelTekstFoto = getBlockTranslation('titel', $taal, $rowBlockData['id'], $rowBlockData['id'], $cms_id);
    $catTekstFoto = getBlockTranslation('categorie', $taal, $rowBlockData['id'], $rowBlockData['id'], $cms_id);

} else {
    $blocksData = $mysqli->query("SELECT * FROM sitework_blocks WHERE id = '".$block_id."'") or die($mysqli->error.__LINE__);
    $rowBlockData = $blocksData->fetch_assoc();

    $titelTekstFoto = $rowBlockData['titel'];
    $tekstTekstFoto = $rowBlockData['tekst'];
    $catTekstFoto = $rowBlockData['categorie'];
}

$getPagina = getTranslation('paginaurl', 'veld', $taal, $cms_id);
$getHoofdID = getTranslation('hoofdid', 'veld', $taal, $cms_id);
if($getHoofdID) {
    $getHoofdPagina = getTranslation('paginaurl', 'veld', $taal, $getHoofdID);

    if($rowinstellingen['meertaligheid'] == 'ja') {
        $paginaBlockURL = $url . '/' . $taal . '/' . $getHoofdPagina . '/' . $getPagina;
    } else {
        $paginaBlockURL = $url . '/' . $getHoofdPagina . '/' . $getPagina;
    }
} else {
    if($rowinstellingen['meertaligheid'] == 'ja') {
        $paginaBlockURL = $url . '/' . $taal . '/' . $getPagina;
    } else {
        $paginaBlockURL = $url . '/' . $getPagina;
    }
}

if($_POST['opslaan'] == 1 && $_POST['blockTaal'] == 'true') {
    $blocksData = $mysqli->query("SELECT * FROM sitework_vertaling_blocks WHERE id = '".$_POST['blockid']."'") or die($mysqli->error.__LINE__);
    $rowBlockData = $blocksData->fetch_assoc();
    $hoofdIdCount = 0;
    foreach ($_POST as $veld => $waarde) {
        $hoofdIdCount++;
        if($veld != 'opslaan-knop' AND $veld != 'opslaan' AND $veld != 'blockTaal' AND $veld != 'taal' AND $veld != 'blockid' AND $veld != 'cmsid'){
            if($hoofdIdCount == 1) {
                $hoofdidOrId = "id";
                $hoofdIdOrNotInsert = "";
            } else {
                $hoofdidOrId = "hoofdid";
                $hoofdIdOrNotInsert = "hoofdid = '" . $_POST['blockid'] . "',";
            }

            if($hoofdIdCount > 1) {
                $sqlInsertOrUpdate = $mysqli->query("SELECT * FROM sitework_vertaling_blocks WHERE $hoofdidOrId = '".$_POST['blockid']."' AND veld = '" . $veld . "' AND cms_id = '".$_POST['cmsid']."' AND block_id = ".$rowBlockData['block_id']." AND taal = '".$_POST['taal']."'") or die($mysqli->error . __LINE__);
            } else {
                $sqlInsertOrUpdate = $mysqli->query("SELECT * FROM sitework_vertaling_blocks WHERE $hoofdidOrId = '".$_POST['blockid']."' AND cms_id = '".$_POST['cmsid']."' AND block_id = ".$rowBlockData['block_id']." AND taal = '".$_POST['taal']."'") or die($mysqli->error . __LINE__);
            }
            $rowInsertOrUpdate = $sqlInsertOrUpdate->fetch_assoc();
            
            if($rowInsertOrUpdate['id']){
                if($veld == 'tekst'){
                    $waarde = str_replace("\r\n", '', $mysqli->real_escape_string($_POST['tekst']));
                }
                $sql_query = "UPDATE sitework_vertaling_blocks 
                                                            SET 
                                                                cms_id = '" . $_POST['cmsid'] . "',
                                                                block_id = '".$rowBlockData['block_id']."',
                                                                veld = '" . $veld . "',
                                                                waarde = '" . $waarde . "',
                                                                taal = '" . $_POST['taal'] . "'
                                                                WHERE id = '" . $rowInsertOrUpdate['id'] . "'";

                $sql_insert = $mysqli->query($sql_query) or die($mysqli->error . " Query: " . $sql_query . " Line: " . __LINE__);
                $rowid = $mysqli->insert_id;
            } else {
                $sql_query = "INSERT sitework_vertaling_blocks 
                                                        SET 
                                                            cms_id = '" . $_POST['cmsid'] . "',
                                                            block_id = ".$rowBlockData['block_id'].",
                                                            veld = '" . $veld . "',
                                                            waarde = '" . $waarde . "',
                                                            $hoofdIdOrNotInsert
                                                            taal = '" . $_POST['taal'] . "'";
    
                $sql_insert = $mysqli->query($sql_query) or die($mysqli->error . " Query: " . $sql_query . " Line: " . __LINE__);                             
                $rowid = $mysqli->insert_id;
            }
        }
    }

    $blocksData = $mysqli->query("SELECT * FROM sitework_vertaling_blocks WHERE id = '".$block_id."'") or die($mysqli->error.__LINE__);
    $rowBlockData = $blocksData->fetch_assoc();

    $tekstTekstFoto = getBlockTranslation('tekst', $taal, $rowBlockData['id'], '', $cms_id);
    $titelTekstFoto = getBlockTranslation('titel', $taal, $rowBlockData['id'], $rowBlockData['id'], $cms_id);
    $catTekstFoto = getBlockTranslation('categorie', $taal, $rowBlockData['id'], $rowBlockData['id'], $cms_id);
}

if($_POST['opslaan'] == 1 && $_POST['blockTaal'] == 'false') {
    $sql_insert = $mysqli->query("UPDATE sitework_blocks SET 
                        titel      = '".$mysqli->real_escape_string($_POST['titel'])."',
                        tekst      = '".str_replace('\r\n', '', $mysqli->real_escape_string($_POST['tekst']))."',
                        categorie      = '".$mysqli->real_escape_string($_POST['categorie'])."'
                         WHERE id = '".$_POST['blockid']."' ") or die($mysqli->error.__LINE__);

    $rowid = $mysqli->insert_id;

    $blocksData = $mysqli->query("SELECT * FROM sitework_blocks WHERE id = '".$_POST['blockid']."'") or die($mysqli->error.__LINE__);
    $rowBlockData = $blocksData->fetch_assoc();

    $titelTekstFoto = $rowBlockData['titel'];
    $tekstTekstFoto = $rowBlockData['tekst'];
    $catTekstFoto = $rowBlockData['categorie'];
}
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/css/stylesheet.css">
        <link rel='stylesheet' type='text/css' href='<? echo $url; ?>/cms/css/branding-stylesheet.php' />

        <link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/datepick/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/font-awesome/css/all.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,200i,300,300i,400,400i,600,600i,700,700i,900,900i">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Khand:300,400,500,600,700">

        <link rel="stylesheet" href="<? echo $url; ?>/cms/richtexteditor/rte_theme_default.css" />
        <script type="text/javascript" src="<? echo $url; ?>/cms/richtexteditor/rte.js"></script>
        <script type="text/javascript" src='<? echo $url; ?>/cms/richtexteditor/plugins/all_plugins.js'></script>
        <script type='text/javascript' src="<? echo $url; ?>/cms/richtexteditor/lang/rte-lang-nl.js"></script>

        <script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery-3.5.1.min.js"></script>
        <script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/sitework.js"></script>
        <script>
            function adjustIframe(obj) {
                function resize() {
                    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
                }
                resize(); // Adjust height initially
                setTimeout(resize, 1500); // Recheck height after 1 second
            }
        </script>
        <style>
            body,html{
                background: white;
            }
            .no-border-top{
                border-top: 0;
                border-bottom: 1px dashed #dadada;
                width: 100%;
            }
        </style>
    </head>
    <body>
        <form id="contentForm" action="<?php echo $PHP_SELF ?>?blockid=<?=$block_id;?>&taal=<?=$taal;?>&cmsid=<?=$cms_id;?>" method="post" enctype="multipart/form-data">
            <div class="content-container no-border-top mt-0 mb-30 pb-30 float-left">
                <div class="form-group">
                    <label for="tekst">Tekstinvoer</label>
                    <div id="block-thumbs">
                        <iframe src="thumbs-block.php?id=<?=$block_id;?>&img_taal=<?=$taal;?>" frameborder="0"
            class="block-iframe" onload="adjustIframe(this)"></iframe>
                    </div>
                    <div id="tekst_editor" style="min-height:400px;max-height:800px" class="inputveld invoer sitework-editor">
                        <?=$tekstTekstFoto;?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="titel">Selecteer layout</label>
                    <select name="titel" class="inputveld invoer dropdown " placeholder="Selecteer een layout">
                        <option value="">----------------------------</option>
                        <option value="tekst-links-foto-rechts" <?php if($titelTekstFoto == "tekst-links-foto-rechts"){echo "selected";} ?>>Tekst links - Foto rechts</option>                       
                        <option value="foto-links-tekst-rechts" <?php if($titelTekstFoto == "foto-links-tekst-rechts"){echo "selected";} ?>>Foto links - Tekst rechts</option>                       
                    </select>
                </div>
                <div class="form-group">
                    <label for="categorie">Selecteer verhouding</label>
                    <select name="categorie" class="inputveld invoer dropdown " placeholder="Selecteer een verhouding">
                        <option value="">----------------------------</option>
                        <option value="70-30" <?php if($catTekstFoto == "70-30"){echo "selected";} ?>>70-30</option>                       
                        <option value="50-50" <?php if($catTekstFoto == "50-50"){echo "selected";} ?>>50-50</option>  
                        <option value="30-70" <?php if($catTekstFoto == "30-70"){echo "selected";} ?>>30-70</option>                       
                    </select>
                </div>
                <div class="form-group">
                    <label for="blok-link">Ga naar block</label>
                    <div class="inputveld invoer blok-link">
                        <a target="_blank" href="<?=$paginaBlockURL;?>#block-<?=$block_id;?>"><?=$paginaBlockURL;?>#block-<?=$block_id;?></a>
                    </div>
                </div>
                <input type="hidden" id="tekst" name="tekst">
                <input type="hidden" name="opslaan" value="1">
                <?php 
                    $blockTaal = ($taal != 'nl') ? '<input type="hidden" name="blockTaal" value="true">' : '<input type="hidden" name="blockTaal" value="false">';
                    echo $blockTaal;
                ?>
                <input type="hidden" name="taal" value="<?=$taal;?>">
                <input type="hidden" name="blockid" value="<?=$block_id;?>">
                <input type="hidden" name="cmsid" value="<?=$cms_id;?>">
                <button name="opslaan-knop" type="submit" class="btn fl-left mr-10 save">Opslaan</button>
            </div>
        </form>
    </body>
</html>

<?php 
    include '../../richtexteditor/blokken-editor.php';
?>
<script>
    document.getElementById('contentForm').addEventListener('submit', function(event) {
        save_and_strip('tekst', editor1);
    });
</script>