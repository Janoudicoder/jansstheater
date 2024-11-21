<?php ob_start();
// database connectie en inlogfuncties
// ===================================
include("../login/config.php");
include("../ftp/config.php");
include('../login/functions.php');
session_start();

// checken of men wel is ingelogd
// ==============================
login_check_v2();

setlocale(LC_ALL, 'nl_NL');
$path = "../../img/"; // upload directory
$doc_path = "../../doc/"; // upload directory
// img settings
$valid_formats = array("jpg", "JPG", "JPEG", "jpeg", "png", "PNG", "svg", "SVG", "gif"); // "jpg", "png", "gif", "zip", "bmp"


if($_GET['imgUse'] == 'no') {
    $parameterImgUse = "&imgUse=no";
    $nietInGebruik = "&nietInGebruik=ja";
} else { $parameterImgUse = ''; $nietInGebruik = ''; }

// pagina wijzigen
// ===============
if ($_POST['opslaan'] == 1) {

    $sql_insert = $mysqli->query("UPDATE sitework_mediabibliotheek SET 	bijschrift		= '".$mysqli->real_escape_string($_POST['bijschrift'])."',
                                                                        beschrijving		= '".$mysqli->real_escape_string($_POST['beschrijving'])."'
	                                                                    WHERE id = '".$_GET['media_id']."' ") or die($mysqli->error.__LINE__);

    $rowid = $mysqli->insert_id;
    $melding = "Wijzigingen zijn opgeslagen";

    echo "
		<div class=\"alert alert-success fancybox\">
			Wijzigingen zijn opgeslagen
		</div>
	";
}

if ($_POST['bewerken'] == 1) {
    if($_POST['media'] == 'afbeelding') {
        // afbeeldingen in database updaten
        // =====================================
        if(!$_POST['hoofdtitel']){
            $hoofdtitel = '';
        }else{
            $hoofdtitel = $_POST['hoofdtitel'];
        }
        if(!$_POST['subtitel']){
            $subtitel = '';
        }else{
            $subtitel = $_POST['subtitel'];
        }
        if(!$_POST['link']){
            $externeLink = '';
        }else{
            $externeLink = $_POST['link'];
        }
        if(!$_POST['linknaam']){
            $externeLinkNaam = '';
        }else{
            $externeLinkNaam = $_POST['linknaam'];
        }
        if(!$_POST['bijschrift']){
            $bijschrift = '';
        }else{
            $bijschrift = $_POST['bijschrift']; 
        }
        $sql_update_img = $mysqli->query("UPDATE sitework_img set 
                                                    hoofdtitel = '".$mysqli->real_escape_string($hoofdtitel)."',
                                                    subtitel = '".$mysqli->real_escape_string($subtitel)."',
                                                    link = '".$mysqli->real_escape_string($externeLink)."',
                                                    linknaam = '".$mysqli->real_escape_string($externeLinkNaam)."',
                                                    afbsoort='".$_POST['afbsoort']."' WHERE id = '".$_POST['bewerk_id']."'
                                                ")
        or die($mysqli->error.__LINE__);

        echo "
        <div class=\"alert alert-success fancybox\">
            Afbeelding succesvol opgelagen
        </div>";

    } elseif($_POST['media'] == 'document') {
        if(!$_POST['doc_naam']){
            $doc_naam = '';
        }else{
            $doc_naam = $_POST['doc_naam'];
        }
        $sql_update_doc = $mysqli->query("UPDATE sitework_doc set naam = '$doc_naam' WHERE id = '".$_POST['bewerk_id']."'") 
        or die($mysqli->error.__LINE__);

        echo "
        <div class=\"alert alert-success fancybox\">
            Document succesvol opgelagen
        </div>";

    }
}

if($_GET['niet_pos'] == 'ja') {
    echo "
    <div class=\"alert alert-success fancybox\">
        U heeft meerdere afbeeldingen in dit blok, dus kunt u geen positie wijzigen.
    </div>";
}

if($_GET['del_media'] == 'ja') {
    echo "
    <div class=\"alert alert-success\">
        Media is verwijderd
    </div>";
}

// afbeelding verwijderen
// ======================
if (isset($_GET['bewerk_id']) && isset($_GET['delete_ontkoppel_id'])) {
    if($_GET['media'] == 'afbeelding') {
        $sql_del = $mysqli->query("DELETE FROM sitework_img WHERE id = '".$_GET['delete_ontkoppel_id']."' ") or die($mysqli->error.__LINE__);
    } elseif($_GET['media'] == 'document') {
        $sql_del = $mysqli->query("DELETE FROM sitework_doc WHERE id = '".$_GET['delete_ontkoppel_id']."' ") or die($mysqli->error.__LINE__);
    }

    header('Location: media_upload.php?id='.$_GET['id'].'&block_id='.$_GET['block_id'].'&taal='.$_GET['taal'].'&media='.$_GET['media'].'');

}

if (isset($_GET['delete_id']) && isset($_GET['nietInGebruik']) == 'ja') {
    $sql2 = $mysqli->query("SELECT * FROM sitework_mediabibliotheek WHERE id = '".$_GET['delete_id']."'") or die($mysqli->error.__LINE__);
    $row2 = $sql2->fetch_assoc();

    if($row2['media'] == 'afbeelding') {
        //eerst de mappen open zetten
        ftp_site($ftpstream, $pad_img_open);
        ftp_site($ftpstream, $pad_webp_open);

        unlink($path.$row2['naam'].".".$row2['ext']); //foto verwijderen
        unlink($path.$row2['naam']."_tn.".$row2['ext']); //thumbnail verwijderen
        unlink($path.$row2['naam']."_mid.".$row2['ext']); //foto mid verwijderen
        unlink($path.$row2['naam']."_full.".$row2['ext']); //foto full verwijderen

        unlink($path."webp/".$row2['naam'].".webp"); //foto webp verwijderen
        unlink($path."webp/".$row2['naam']."_tn.webp"); //foto webp thumbs verwijderen
        unlink($path."webp/".$row2['naam']."_mid.webp"); //foto webp mid verwijderen
        unlink($path."webp/".$row2['naam']."_full.webp"); //foto webp full verwijderen

        ftp_site($ftpstream, $pad_img_dicht);
        ftp_site($ftpstream, $pad_webp_dicht);
    } else {
        ftp_site($ftpstream,$pad_doc_open);

        unlink($doc_path.$row2['naam'].".".$row2['ext']); //document verwijderen

        ftp_site($ftpstream,$pad_doc_dicht);
    }
    
    $sql_del = $mysqli->query("DELETE FROM sitework_mediabibliotheek WHERE id = '".$_GET['delete_id']."' ") or die($mysqli->error.__LINE__);

    // echo '<script>parent.$(".box-container").append("<div class=\'alert alert-success\'>Media is verwijderd</div>")</script>';
    echo "<script>parent.$.fancybox.close();</script>";
}

if (isset($_GET['delete_id'])) {
    $sql2 = $mysqli->query("SELECT * FROM sitework_mediabibliotheek WHERE id = '".$_GET['delete_id']."'") or die($mysqli->error.__LINE__);
    $row2 = $sql2->fetch_assoc();
    
    if($row2['media'] == 'afbeelding') {
        //eerst de mappen open zetten
        ftp_site($ftpstream, $pad_img_open);
        ftp_site($ftpstream, $pad_webp_open);

        unlink($path.$row2['naam'].".".$row2['ext']); //foto verwijderen
        unlink($path.$row2['naam']."_tn.".$row2['ext']); //thumbnail verwijderen
        unlink($path.$row2['naam']."_mid.".$row2['ext']); //foto mid verwijderen
        unlink($path.$row2['naam']."_full.".$row2['ext']); //foto full verwijderen

        unlink($path."webp/".$row2['naam'].".webp"); //foto webp verwijderen
        unlink($path."webp/".$row2['naam']."_tn.webp"); //foto webp thumbs verwijderen
        unlink($path."webp/".$row2['naam']."_mid.webp"); //foto webp mid verwijderen
        unlink($path."webp/".$row2['naam']."_full.webp"); //foto webp full verwijderen

        ftp_site($ftpstream, $pad_img_dicht);
        ftp_site($ftpstream, $pad_webp_dicht);

        $sql_delImages = $mysqli->query("DELETE FROM sitework_img WHERE naam = '".$_GET['delete_id']."' ") or die($mysqli->error.__LINE__);
    } else {
        ftp_site($ftpstream,$pad_doc_open);

        unlink($doc_path.$row2['naam'].".".$row2['ext']); //document verwijderen

        ftp_site($ftpstream,$pad_doc_dicht);

        $sql_delDocs = $mysqli->query("DELETE FROM sitework_doc WHERE url = '".$_GET['delete_id']."' ") or die($mysqli->error.__LINE__);
    }

    $sql_del = $mysqli->query("DELETE FROM sitework_mediabibliotheek WHERE id = '".$_GET['delete_id']."' ") or die($mysqli->error.__LINE__);
        
    echo "<script>parent.location.reload(true);</script>";
    echo "<script>parent.$.fancybox.close();</script>";
}

// afbeelding ophalen
// ==================
$imagePages = [];
$imageBlocks = [];

if($_GET['noimg'] == 'ja') {
    $rowmedia['id'] = $_GET['media_id'];
    $rowmedia['naam'] = 'noimg';
    $rowmedia['ext'] = 'jpg';
    $rowmedia['media'] = 'afbeelding';
    $rowmedia['bijschrift'] = 'Deze afbeelding bestaat niet';
    $rowmedia['beschrijving'] = 'Deze afbeelding bestaat niet';
    $rowmedia['datum_geupload'] = time();
} else {
    if(isset($_GET['bewerk_id'])) {
        if($_GET['media'] == 'afbeelding') {
            $sqlmediaImg = $mysqli->query("SELECT * FROM sitework_img WHERE id = '".$_GET['bewerk_id']."' ") or die($mysqli->error.__LINE__);
            $rowmediaImg = $sqlmediaImg->fetch_assoc(); 

            $sqlmedia = $mysqli->query("SELECT * FROM sitework_mediabibliotheek WHERE id = '".$rowmediaImg['naam']."' ") or die($mysqli->error.__LINE__);
            $rowmedia = $sqlmedia->fetch_assoc(); 
        } else {
            $sqlmediaDoc = $mysqli->query("SELECT * FROM sitework_doc WHERE id = '".$_GET['bewerk_id']."' ") or die($mysqli->error.__LINE__);
            $rowmediaDoc = $sqlmediaDoc->fetch_assoc();
            
            $sqlmedia = $mysqli->query("SELECT * FROM sitework_mediabibliotheek WHERE id = '".$rowmediaDoc['url']."' ") or die($mysqli->error.__LINE__);
            $rowmedia = $sqlmedia->fetch_assoc(); 
        }
    } else {
        $sqlmedia = $mysqli->query("SELECT * FROM sitework_mediabibliotheek WHERE id = '".$_GET['media_id']."' ") or die($mysqli->error.__LINE__);
        $rowmedia = $sqlmedia->fetch_assoc(); 

        if(!in_array($rowmedia['ext'], $valid_formats)) {
            $sqlMediaUse = $mysqli->query("SELECT * FROM sitework_doc WHERE url = '".$_GET['media_id']."'") or die($mysqli->error.__LINE__);
            $rowMediaUse = $sqlMediaUse->fetch_assoc();
        } else {
            $sqlImagesCheck = $mysqli->query("SELECT * FROM sitework_img WHERE naam = '" . $_GET['media_id'] . "'") or die($mysqli->error . __LINE__);

            while ($rowImagesCheck = $sqlImagesCheck->fetch_assoc()) {
                $pageId = $rowImagesCheck['cms_id'];
                $blockId = $rowImagesCheck['block_id'];

                if($blockId != 0 && $blockId != '0'){
                    // Get block data (only block_naam)
                    $sqlBlocksCheck = $mysqli->query("SELECT * FROM sitework_blocks WHERE id = '$blockId' ") or die($mysqli->error . __LINE__);
                    while($rowBlocksCheck = $sqlBlocksCheck->fetch_assoc()) {
                        $sqlBlockCheck = $mysqli->query("SELECT * FROM sitework_block WHERE id = '".$rowBlocksCheck['block_id']."' ") or die($mysqli->error . __LINE__);
                        $rowBlockCheck = $sqlBlockCheck->fetch_assoc();

                        $imageBlocks[$rowBlockCheck['block_naam']] = $rowBlockCheck['block_naam']; // Store only block_naam value
                    }
                } else {
                    // Get page data (only item1)
                    $sqlPagesCheck = $mysqli->query("SELECT item1 FROM siteworkcms WHERE id = '$pageId'") or die($mysqli->error . __LINE__);
                    while($rowPagesCheck = $sqlPagesCheck->fetch_assoc()) {
                        $imagePages[$rowPagesCheck['item1']] = $rowPagesCheck['item1']; // Store only item1 value
                    }
                }
            }
        }
    }
}

$paginasString = "";
if (count($imagePages) > 0 && !in_array(null, $imagePages)) {
  $paginasString = "Deze afbeelding word hier gebruikt:\n";
  foreach ($imagePages as $page) {
    $paginasString .= $page . "\n";
  }
}

$blokkenString = "";
if (count($imageBlocks) > 0 && !in_array(null, $imageBlocks)) {
  $blokkenString = "Deze afbeelding word in deze blokken gebruikt:\n";
  foreach ($imageBlocks as $block) {
    $blokkenString .= $block . "\n";
  }
}
?>

<TITLE>SiteWork CMS afbeelding bewerken</TITLE>
<meta charset="UTF-8" />
<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/cms/css/stylesheet.css">
<link rel='stylesheet' type='text/css' href='<?php echo $url; ?>/cms/css/branding-stylesheet.php' />
<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/cms/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/font-awesome/css/all.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/cms/css/themify-icons.css">

<!-- javascript / jquery wordt hier aangeroepen /-->
<script type="text/javascript" src="<?php echo $url; ?>/cms/jquery/files/jquery-3.5.1.min.js"></script>
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

function ConfirmDelete() {
    return confirm('Weet u zeker dat u dit mediabestand wilt verwijderen?');
}

function confirmPermanentDelete(url) {
    const paginas = `<?php echo $paginasString; ?>`; // Access data from PHP variable
    const blokken = `<?php echo $blokkenString; ?>`;  // Access data from PHP variable

    const message = `Weet u zeker dat u dit mediabestand wilt verwijderen?\n\n${paginas}\n${blokken}`;
    if(confirm(message)) {
        window.location.href = url;
    }
    return false; 
}
function confirmPermanentDeleteNoUse(url) {
    if(confirm('Weet u zeker dat u dit mediabestand wilt verwijderen?')) {
        window.location.href = url;
        // window.location.reload();
        // setInterval(DeleteConfirmMessage, 1000);

        // function DeleteConfirmMessage() {
        //     $('.box-container').append("<div class=\"alert alert-success\">Media is verwijderd</div>");
        // }
    }
    return false;
}
</script>
<?php if(isset($_GET['media_id'])): ?>
    <div class="fancybox-wrap">
        <div class="box-container">
            <?php if(isset($_GET['imgUse']) && $_GET['imgUse'] == 'no'): ?>
                <div class="box box-full">
                    <h3 class="big"><span class="icon danger fas fa-exclamation-circle"></span><?php echo ($rowmedia['media'] == 'document') ? 'Dit document' : 'Deze afbeelding' ?> word niet gebruikt</h3>
                    <span class="btn fl-right delete" onclick="confirmPermanentDeleteNoUse('<?php echo $PHP_SELF;?>?delete_id=<?=$rowmedia['id'];?><?=$nietInGebruik;?>')">Permanent verwijderen</span>
                </div>
            <?php endif; ?>
            <div class="box box-1-2 md-box-full">
                <h3><span class="icon fas fa-<? echo ($rowmedia['media'] == 'document') ? 'file' : 'images' ;?>"></span><?=ucfirst($rowmedia['media']);?></h3>
                <div class="afbeelding">
                    <?php if($rowmedia['media'] == 'afbeelding'): ?>
                        <img src="../../img/<?php echo $rowmedia['naam'] . "_mid.". $rowmedia['ext']; ?>" width="100%" border="0" />
                    <?php else: ?>
                        <?php if($rowmedia['ext'] == 'pdf'): ?>
                            <iframe src="../../doc/<? echo $rowmedia['naam']; ?>.<? echo $rowmedia['ext'];?>" width="100%" height="500" frameborder="0"></iframe>
                        <?php else: ?>
                            <iframe src="https://view.officeapps.live.com/op/embed.aspx?src=<?=$rowinstellingen['weburl'];?>/doc/<? echo $rowmedia['naam']; ?>.<? echo $rowmedia['ext'];?>" width="100%" height="500" frameborder="0"></iframe>
                        <?php endif; ?> <!-- &action=embedview&wdbipreview=true -->
                    <?php endif; ?>
                </div>
                <div class="file_url form-group">
                    <label for="">Bestand URL</label>
                    <?php if($rowmedia['media'] == 'afbeelding'): ?>
                        <input type="text" class="inputveld invoer" id="bestandurl" value="<?=$rowinstellingen['weburl'];?>/img/<?=$rowmedia['naam'];?>.<?=$rowmedia['ext'];?>" disabled>
                    <?php else: ?>
                        <input type="text" class="inputveld invoer" id="bestandurl" value="<?=$rowinstellingen['weburl'];?>/doc/<?=$rowmedia['naam'];?>.<?=$rowmedia['ext'];?>" disabled>
                    <?php endif; ?>
                </div>
                <span id="copy-bestandurl" class="btn fl-left copy">URL naar klembord kopiëren</span>
                <div id="copied" class="copy-message"></div>
            </div>
            <div class="box box-1-2 md-box-full">
                <h3><span class="icon fas fa-cogs"></span>Eigenschappen</h3>
                <div class="afbeelding-info">
                    <div class="row">
                        <span class="fat">Geüpload op:</span>
                        <?php $geupload = strftime("%d %B %Y", strtotime($rowmedia['datum_geupload'])); echo $geupload; ?>
                    </div>
                    <div class="row">
                        <span class="fat">Bestandsnaam:</span>
                        <?php $bestandje = $rowmedia['naam'].".". $rowmedia['ext']; echo $bestandje; ?>
                    </div>
                    <?php if($rowmedia['media'] != 'document'): ?>
                        <div class="row">
                            <span class="fat">Afmetingen:</span>
                            <?php 
                                list($width, $height, $type, $attr) = getimagesize("../../img/".$bestandje); 
                                echo $width." x ".$height." pixels<br>";  
                            ?>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <span class="fat">Bestandstype:</span> <?php $finfo = finfo_open(FILEINFO_MIME_TYPE); 
                        if($rowmedia['media'] == 'document') {
                            foreach (glob("../../doc/".$bestandje) as $filename) {
                                echo finfo_file($finfo, $filename) . "\n";
                            } finfo_close($finfo);
                        } else {
                            foreach (glob("../../img/".$bestandje) as $filename) {
                                echo finfo_file($finfo, $filename) . "\n";
                            } finfo_close($finfo);
                        }
                        ?><br>
                    </div>
                    <div class="row">
                        <span class="fat">Bestandsgrootte:</span>
                        <?php 
                        if($rowmedia['media'] == 'document') {
                            $filename = '../../doc/'.$bestandje; 
                        } else {
                            $filename = '../../img/'.$bestandje; 
                        }
                        
                        echo round(filesize($filename)/1024, 2) . ' Kb'; ?>
                    </div>
                </div>
                <form action="<?php echo $PHP_SELF ?>?media_id=<?=$_GET['media_id'];?><?=$parameterImgUse;?>" method="POST">
                    <div class="form-group">
                        <label for="bijschrift">Bijschrift (alt)</label>
                        <textarea name="bijschrift" class="inputveld media-textarea" id="bijschrift" cols="30" rows="3"><?php echo $rowmedia['bijschrift']; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="beschrijving">Beschrijving</label>
                        <textarea name="beschrijving" class="inputveld media-textarea" id="beschrijving" cols="30" rows="3"><?php echo $rowmedia['beschrijving']; ?></textarea>
                    </div>
                    <div class="form-group">
                        <?php if($rowmedia['media'] == 'afbeelding'): ?>
                            <a class="btn fl-left download" href="<?=$rowinstellingen['weburl'];?>/img/<?=$rowmedia['naam'];?>.<?=$rowmedia['ext'];?>" download>Download afbeelding</a>
                        <?php else: ?>
                            <a class="btn fl-left download" href="<?=$rowinstellingen['weburl'];?>/doc/<?=$rowmedia['naam'];?>.<?=$rowmedia['ext'];?>" download>Download document</a>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="opslaan" value="1">
                    <a class="btn fl-left back mr-10" href="javascript: history.go(-1);">Terug </a>
                    <span class="btn fl-left delete mr-10" onclick="confirmPermanentDelete('<?php echo $PHP_SELF;?>?delete_id=<?=$rowmedia['id'];?><?=$nietInGebruik;?>')">Permanent verwijderen</span>
                    <button name="opslaanbut" class="btn fl-left save" type="submit">Wijzigingen opslaan</button>
                </form>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="fancybox-wrap">
        <div class="box-container">
            <div class="box box-1-2 md-box-full">
                <h3><span class="icon fas fa-<? echo ($_GET['media'] == 'document') ? 'file' : 'images' ;?>"></span><?=ucfirst($_GET['media']);?></h3>
                <div class="afbeelding">
                    <?php if($_GET['media'] == 'afbeelding'): ?>
                        <img src="../../img/<?php echo $rowmedia['naam'] . "_mid.". $rowmedia['ext']; ?>" width="100%" border="0" />
                    <?php else: ?>
                        <?php if($rowmedia['ext'] == 'pdf'): ?>
                            <iframe src="../../doc/<? echo $rowmedia['naam']; ?>.<? echo $rowmedia['ext'];?>" width="100%" height="500" frameborder="0"></iframe>
                        <?php else: ?>
                            <iframe src="https://view.officeapps.live.com/op/embed.aspx?src=<?=$rowinstellingen['weburl'];?>/doc/<? echo $rowmedia['naam']; ?>.<? echo $rowmedia['ext'];?>" width="100%" height="500" frameborder="0"></iframe>
                        <?php endif; ?> <!-- &action=embedview&wdbipreview=true -->
                    <?php endif; ?>
                </div>
                <div class="file_url form-group">
                    <label for="">Bestand URL</label>
                    <?php if($_GET['media'] == 'afbeelding'): ?>
                        <input type="text" class="inputveld invoer" id="bestandurl" value="<?=$rowinstellingen['weburl'];?>/img/<?=$rowmedia['naam'];?>.<?=$rowmedia['ext'];?>" disabled>
                    <?php else: ?>
                        <input type="text" class="inputveld invoer" id="bestandurl" value="<?=$rowinstellingen['weburl'];?>/doc/<?=$rowmedia['naam'];?>.<?=$rowmedia['ext'];?>" disabled>
                    <?php endif; ?>
                </div>
                <span id="copy-bestandurl" class="btn fl-left copy">URL naar klembord kopiëren</span>
                <div id="copied" class="copy-message"></div>
            </div>
            <div class="box box-1-2 md-box-full">
                <h3><span class="icon fas fa-cogs"></span>Eigenschappen</h3>
                <?php
                    if($_GET['block_id']) {
                        $formAction = $PHP_SELF .'?id='.$_GET['id'].'&block_id='.$_GET['block_id'].'&taal='.$_GET['taal'].'&bewerk_id='.$_GET['bewerk_id'].'&media='.$_GET['media'];
                    } else {
                        $formAction = $PHP_SELF .'?bewerk_id='.$_GET['bewerk_id'].'&media='.$_GET['media'];
                    }
                ?>
                <form action="<?php echo $formAction ?>" method="POST">
                    <?php if($_GET['media'] == 'afbeelding'): ?>
                        <?php if($_GET['block_id']){
                            echo '<input type="hidden" name="afbsoort" value="block">';
                        }else{ ?>
                            <div class="form-group">
                                <label for="afbsoort">Soort</label>
                                <select name="afbsoort" class="inputveld invoer dropdown<?php if ($error_afb) {
                                        echo ' foutveld';
                                    } ?>" id="afbsoort">
                                    <option value="">Selecteer afbeeldingstype</option>
                                    <option <?
                                    if ($rowmediaImg['afbsoort']=="hoofdfoto") {
                                        echo "selected" ;
                                        }
                                    ?>
                                        value="hoofdfoto">hoofdfoto</option>
                                    <option <?
                                        if ($rowmediaImg['afbsoort']=="zijkant") {
                                            echo "selected" ;
                                        }
                                    ?> value="zijkant">zijkant 
                                    </option>
                                    <option <?
                                        if ($rowmediaImg['afbsoort']=="galerij") {
                                            echo "selected" ;
                                        }
                                    ?> value="galerij">galerij
                                    </option>
                                    <option <?
                                        if ($rowmediaImg['afbsoort']=="uitgelicht") {
                                            echo "selected" ;
                                        }
                                    ?>
                                        value="uitgelicht">uitgelicht</option>
                                    <option <?
                                        if ($rowmediaImg['afbsoort']=="logo") {
                                            echo "selected" ;
                                        }
                                    ?> value="logo">logo</option>
                                </select>
                            </div>
                        <?php } ?>
                        <?php if ($rowinstellingen['afbeeldingopties'] == 'ja' && $rowinstellingen['hoofdtitelveld'] == 'ja') {?>
                        <div class="form-group">
                            <label for="hoofdtitel">Hoofdtitel</label><input type="text" name="hoofdtitel"
                                class="inputveld invoer" placeholder="Hoofdtitel" maxlength="200" value="<?=$rowmediaImg['hoofdtitel'];?>" />
                        </div>
                        <?php } ?>
                        <?php if ($rowinstellingen['afbeeldingopties'] == 'ja' && $rowinstellingen['subtitelveld'] == 'ja') {?>
                        <div class="form-group">
                            <label for="subtitel">Subtitel</label><input type="text" name="subtitel" class="inputveld invoer"
                                placeholder="Subtitel" maxlength="200" value="<?=$rowmediaImg['subtitel'];?>" />
                        </div>
                        <?php } ?>
                        <?php if ($rowinstellingen['afbeeldingopties'] == 'ja' && $rowinstellingen['linkveld'] == 'ja') {?>
                        <div class="form-group">
                            <label for="subtitel">Link</label><input type="text" name="link" class="inputveld invoer"
                                placeholder="Link (incl https://)" maxlength="200" value="<?=$rowmediaImg['link'];?>" />
                        </div>
                        <div class="form-group">
                            <label for="linknaam">Link naam</label><input type="text" name="linknaam" class="inputveld invoer"
                                placeholder="Link (bijvoorbeeld 'lees meer')" maxlength="300" value="<?=$rowmediaImg['linknaam'];?>" />
                        </div> 
                        <?php } ?>
                    <?php else: ?>
                        <div class="form-group">
                            <label for="doc_naam">Naam</label><input type="text" name="doc_naam" class="inputveld invoer"
                                placeholder="Naam van de document" maxlength="50" value="<?=$rowmediaDoc['naam'];?>" />
                        </div>
                    <?php endif; ?>

                    <input type="hidden" name="media" value="<?=$_GET['media'];?>">
                    <input type="hidden" name="bewerk_id" value="<?php echo $_GET['bewerk_id'] ?>">
                    <input type="hidden" name="bewerken" value="1">

                    <a class="btn fl-left back mr-10" href="javascript: history.go(-1);">Terug </a>
                    <a class="delete-image btn fl-left delete mr-10" href="?id=<?=$_GET['id'];?>&block_id=<?=$_GET['block_id'];?>&taal=<?=$_GET['taal'];?>&bewerk_id=<?=$_GET['bewerk_id'];?>&media=<?=$_GET['media'];?>&delete_ontkoppel_id=<?=$_GET['bewerk_id'];?>"
                        onclick='return ConfirmDelete();'>Verwijderen</a>
                    <button name="opslaan" class="btn fl-left save mr-10" type="submit">Opslaan</button>
                    <!-- <a class="btn fl-left" data-fancybox data-small-btn="true" data-type="iframe" href="afbeelding_positioneren.php?id=<?//=$_GET['id'];?>&block_id=<?//=$_GET['block_id'];?>&taal=<?//=$_GET['taal'];?>&bewerk_id=<?//=$_GET['bewerk_id'];?>" href="javascript:;">Posistioneer je afbeelding</a> -->
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    const inputField = document.getElementById("bestandurl");
    const copyButton = document.getElementById("copy-bestandurl");
    const messageCopy = document.getElementById("copied");

    copyButton.addEventListener("click", () => {
        const textToCopy = inputField.value;
        navigator.clipboard.writeText(textToCopy)
            .then(() => {
                messageCopy.textContent = "Gekopieerd!"; // Use textContent
            })
            .catch(err => {
                console.error("Failed to copy text:", err);
            });
    });
</script>