<?php
$valid_formats = array("jpg", "JPG", "JPEG", "jpeg", "png", "PNG", "svg", "SVG", "gif"); // "jpg", "png", "gif", "zip", "bmp"
$max_file_size = 1024*4000; // 4MB
$pad = "../../img/"; // upload directory
$max_thumb = 450; // maximale hoogte dan wel breedte van de thumb.
$max_mid = 700; // maximale hoogte dan wel breedte van de thumb.
$max_afbeelding = 2500; // maximale hoogte dan wel breedte van de afbeelding.
$count = 0;
$max_x = 100;
$max_y = 100;

$error = '';
// Imaging
class imaging
{
    // Variables
    private $img_input;
    private $img_output;
    private $img_src;
    private $format;
    private $quality = 100;
    private $x_input;
    private $y_input;
    private $x_output;
    private $y_output;
    private $resize;

    // Set image
    public function set_img($img)
    {
        // Find format
        $ext = strtoupper(pathinfo($img, PATHINFO_EXTENSION));
        // JPEG image
        if(is_file($img) && ($ext == "JPG" OR $ext == "JPEG"))
        {

            $this->format = $ext;
            $this->img_input = ImageCreateFromJPEG($img);
            $this->img_src = $img;
        }
        // PNG image
        elseif(is_file($img) && $ext == "PNG")
        {
            $this->format = $ext;
            $this->img_input = ImageCreateFromPNG($img);
            $this->img_src = $img;
        }
        // GIF image
        elseif(is_file($img) && $ext == "GIF")
        {
            $this->format = $ext;
            $this->img_input = ImageCreateFromGIF($img);
            $this->img_src = $img;
        }
        // Get dimensions
        $this->x_input = imagesx($this->img_input);
        $this->y_input = imagesy($this->img_input);
    }

    // Set maximum image size (pixels)
        // public function set_size($max_x = 100,$max_y = 100)
    public function set_size($max_x,$max_y)
    {
        // Resize
        // if($this->x_input > $max_x || $this->y_input > $max_y)
        // {
            $a= $max_x / $max_y;
            $b= $this->x_input / $this->y_input;
            if ($a<$b)
            {
                $this->x_output = $max_x;
                $this->y_output = ($max_x / $this->x_input) * $this->y_input;
            }
            else
            {
                $this->y_output = $max_y;
                $this->x_output = ($max_y / $this->y_input) * $this->x_input;
            }
            // Ready
            $this->resize = TRUE;
        // }
        // // Don't resize      
        // else { $this->resize = FALSE; }
    }
    // Set image quality (JPEG only)
    public function set_quality($quality)
    {
        if(is_int($quality))
        {
            $this->quality = $quality;
        }
    }
    // Save image
    public function save_img($path)
    {
        // Resize
        if($this->resize)
        {
            $this->img_output = ImageCreateTrueColor($this->x_output, $this->y_output);

            if (($this->format == "GIF") || ($this->format == "PNG")) {
                imagealphablending($this->img_output, false);
                imagesavealpha($this->img_output, true);
                $transparent = imagecolorallocatealpha($this->img_output, 255, 255, 255, 127);
                imagefilledrectangle($this->img_output, 0, 0, $this->x_output, $this->y_output, $transparent);
            }
            ImageCopyResampled($this->img_output, $this->img_input, 0, 0, 0, 0, $this->x_output, $this->y_output, $this->x_input, $this->y_input);
        }
        // Save JPEG
        if($this->format == "JPG" OR $this->format == "JPEG")
        {
            if($this->resize) { 
                imageJPEG($this->img_output, $path, $this->quality); 
            } else { 
                copy($this->img_src, $path); 
            }
        }
        // Save PNG
        elseif($this->format == "PNG")
        {   
            imagealphablending($this->img_output, false);
            imagesavealpha($this->img_output, true);
            if($this->resize) { 
                imagePNG($this->img_output, $path); 
            }
            else { copy($this->img_src, $path); }
        }
        // Save GIF
        elseif($this->format == "GIF")
        {
            if($this->resize) { imageGIF($this->img_output, $path); }
            else { copy($this->img_src, $path); }
        }
    }
    // Get width
    public function get_width()
    {
        return $this->x_input;
    }
    // Get height
    public function get_height()
    {
        return $this->y_input;
    }
    // Clear image cache 
    public function clear_cache()
    {
        @ImageDestroy($this->img_input);
            @ImageDestroy($this->img_output); 
    }
}
class thumbnail extends imaging {
    private $image;
    private $width;
    private $height;
    private $type;

    function __construct($image,$width,$height,$type) {
        if($type == 'thumb'){
            $pref = "_tn";
        }else if($type == 'mid'){
            $pref = "_mid";
        }else{
            $pref = "";
        }
        parent::set_img($image);
        parent::set_quality(80);
        parent::set_size($width,$height);
        $this->thumbnail = "../../img/".pathinfo($image, PATHINFO_FILENAME).$pref.".".pathinfo($image, PATHINFO_EXTENSION);
        parent::save_img($this->thumbnail);
        convertImageToWebP($this->thumbnail, "../../img/webp/".pathinfo($image, PATHINFO_FILENAME).$pref.".webp", 80);
        parent::clear_cache();
        }
    function __toString() {
            return $this->thumbnail;
    }
}

//create img
function img($url, $src, $ext, $alt, $width, $height, $maxdivwidth)
{
    $nosize = "";
    if (empty($width) and empty($height)) {
        $aspectratioDiv = number_format(0);
        $nosize = true;
    } else {
        $aspectratioCalc = $height / $width;
        $aspectratio = $aspectratioCalc * 100; 
        $aspectratioDiv = number_format($aspectratio, 2, '.', '');
    }
    if ($maxdivwidth < '700') {
        $defaultsrc = $url."/img/webp/".$src.".webp";
    } elseif ($maxdivwidth < '900') {
        $defaultsrc = $url."/img/webp/".$src.".webp";
    } else {
        $defaultsrc = $url."/img/webp/".$src.".webp";
    }
    if($ext == "png" OR $ext == "PNG"){
        $sourcemediathumb = "<source media=\"(max-width: 700px)\" data-srcset=\"{$url}/img/{$src}_tn.png\" type=\"image/png\">";
        $sourcemediamid = "<source media=\"(max-width: 900px)\" data-srcset=\"{$url}/img/{$src}_mid.png\" type=\"image/png\">";
        $sourcemedialarge = "<source media=\"(min-width: 900px)\" data-srcset=\"{$url}/img/{$src}.png\" type=\"image/png\">";
    }else if($ext == "JPG" OR $ext == "jpg"){
        $sourcemediathumb = "<source media=\"(max-width: 700px)\" data-srcset=\"{$url}/img/{$src}_tn.jpg\" type=\"image/jpeg\">";
        $sourcemediamid = "<source media=\"(max-width: 900px)\" data-srcset=\"{$url}/img/{$src}_mid.jpg\" type=\"image/jpeg\">";
        $sourcemedialarge = "<source media=\"(min-width: 900px)\" data-srcset=\"{$url}/img/{$src}.jpg\" type=\"image/jpeg\">";
    }else if($ext == "JPEG" OR $ext == "jpeg"){
        $sourcemediathumb = "<source media=\"(max-width: 700px)\" data-srcset=\"{$url}/img/{$src}_tn.jpeg\" type=\"image/jpeg\">";
        $sourcemediamid = "<source media=\"(max-width: 900px)\" data-srcset=\"{$url}/img/{$src}_mid.jpeg\" type=\"image/jpeg\">";
        $sourcemedialarge = "<source media=\"(min-width: 900px)\" data-srcset=\"{$url}/img/{$src}.jpeg\" type=\"image/jpeg\">";
    }
    if($nosize == true){
        echo "
            <picture>
                {$sourcemediathumb}
                {$sourcemediamid}
                {$sourcemedialarge}
                <img src=\"{$defaultsrc}\" alt=\"{$alt}\" class=\"lazy\" width=\"{$width}\" height=\"{$height}\">
            </picture>
    ";
    }else{
        echo "
        <div class=\"picture-container\">
            <picture>
                {$sourcemediathumb}
                {$sourcemediamid}
                {$sourcemedialarge}
                <img src=\"{$defaultsrc}\" alt=\"{$alt}\" class=\"lazy\" width=\"{$width}\" height=\"{$height}\">
            </picture>
        </div>
    ";
    }
}
function convertImageToWebP($source, $destination, $quality=80)
{
    $extension = pathinfo($source, PATHINFO_EXTENSION);
    if ($extension == 'jpeg' || $extension == 'jpg' || $extension == 'JPG') {
        $image = imagecreatefromjpeg($source);
    } elseif ($extension == 'gif') {
        $image = imagecreatefromgif($source);
    } elseif ($extension == 'png'|| $extension == 'PNG') {
        $image = imagecreatefrompng($source);
        // $image = imagecreatefrompng($source);
        // $image = imagepalettetotruecolor($image);
        // $image = imagealphablending($image, true);
        // $image = imagesavealpha($image, true);
    }
    return imagewebp($image, $destination, $quality);
}



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


if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
    
    if(isset($_POST['media_toevoegen']) == 'ja') {
        $mediaKeuze = $_POST['media-keuze'];
        $media = $_POST['media'];
        $multiple_media = $_POST['meerdere_media_toevoegen'];

        // echo '<pre>' . var_dump($_POST['media-keuze']) . '</pre>';
        // echo '<pre>' . var_dump($_POST) . '</pre>';

        if($media == 'afbeelding') {
            // afbeeldingen in database wegschrijven
            // =====================================
            if(!$_POST['afbsoort_new']) {
                $error = "kies eerst een afbeelding soort";
            } else {                
                if($multiple_media <> 'ja') {
                    if(!$_POST['hoofdtitel_new']){
                        $hoofdtitel = '';
                    }else{
                        $hoofdtitel = $_POST['hoofdtitel_new'];
                    }
                    if(!$_POST['subtitel_new']){
                        $subtitel = '';
                    }else{
                        $subtitel = $_POST['subtitel_new'];
                    }
                    if(!$_POST['link_new']){
                        $externeLink = '';
                    }else{
                        $externeLink = $_POST['link_new'];
                    }
                    if(!$_POST['linknaam_new']){
                        $externeLinkNaam = '';
                    }else{
                        $externeLinkNaam = $_POST['linknaam_new'];
                    }
                } else {
                    $hoofdtitel = '';
                    $subtitel = '';
                    $externeLink = '';
                    $externeLinkNaam = '';
                }

                $volgorde = '';
                if(!$volgorde){
                    $volgorde = 0;
                }
                if($_POST['taal'] != 'nl') {
                    $img_taal = $_POST['taal'];
                } else {
                    $img_taal = "nl";
                }

                foreach ($mediaKeuze as $newMedia) {
                    $mediaSave = explode('-', $newMedia);
                    $mediaId = $mediaSave[1];

                    $sql_insert_img = $mysqli->query("INSERT INTO sitework_img SET 
                                                        cms_id='" . $_GET['id'] . "',
                                                        block_id='" . $_GET['block_id'] . "',
                                                        naam='" . $mysqli->real_escape_string($mediaId) . "',
                                                        hoofdtitel='" . $mysqli->real_escape_string($hoofdtitel) . "',
                                                        subtitel='" . $mysqli->real_escape_string($subtitel) . "',
                                                        link='" . $mysqli->real_escape_string($externeLink) . "',
                                                        linknaam='" . $mysqli->real_escape_string($externeLinkNaam) . "',
                                                        afbsoort='" . $_POST['afbsoort_new'] . "',
                                                        volgorde='" . $volgorde . "',
                                                        img_taal='" . $img_taal . "'
                                                    ")
                    or die($mysqli->error . __LINE__);
                }

                echo "
                <div class=\"alert alert-success fancybox\">
                    Afbeelding(en) succesvol opgelagen
                </div>";
            }

        } elseif($media == 'document') {
            if(!$volgorde){
                $volgorde = 0;
            }
            if(!$_POST['doc_naam_new']){
                $doc_naam = '';
            }else{
                $doc_naam = $_POST['doc_naam_new'];
            }

            foreach ($mediaKeuze as $newMedia) {
                $mediaSave = explode('-', $newMedia);
                $mediaId = $mediaSave[1];
            
                $block_id_value = isset($block_id) && !empty($block_id) ? $block_id : 'NULL';
            
                $sql_insert_doc = $mysqli->query("INSERT INTO sitework_doc SET 
                                                  naam = '$doc_naam',
                                                  cms_id = '" . $_GET['id'] . "',
                                                  volgorde = '$volgorde',
                                                  block_id='" . $_GET['block_id'] . "',
                                                  url = '$mediaId'") 
                                                  or die($mysqli->error . __LINE__);
            }
            

            echo "
            <div class=\"alert alert-success fancybox\">
                Document(en) succesvol opgelagen
            </div>";
        }

    } elseif(isset($_POST['media_bewerken']) == 'ja') {
        $huidigeMedia = explode('-', $_POST['huidige-media']);
        $media = $huidigeMedia[0];
        $mediaId = $huidigeMedia[1];

        // echo '<pre>' . var_dump($huidigeMedia) . '</pre>';
        // echo '<pre>' . var_dump($_POST) . '</pre>';

        if($media == 'afbeelding') {
            // afbeeldingen in database wegschrijven
            // =====================================
            if(!$_POST['afbsoort']) {
                $error = "kies eerst een afbeelding soort";
            } else {
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
                if($_POST['taal'] != 'nl') {
                    $img_taal = $_POST['taal'];
                } else {
                    $img_taal = "nl";
                }
                $sql_insert_img = $mysqli->query("UPDATE sitework_img set 
                                                            hoofdtitel = '".$mysqli->real_escape_string($hoofdtitel)."',
                                                            subtitel = '".$mysqli->real_escape_string($subtitel)."',
                                                            link = '".$mysqli->real_escape_string($externeLink)."',
                                                            linknaam = '".$mysqli->real_escape_string($externeLinkNaam)."',
                                                            afbsoort='".$_POST['afbsoort']."',
                                                            img_taal = '".$img_taal."' WHERE id = '".$mediaId."'
                                                        ")
                or die($mysqli->error.__LINE__);

                echo "
                <div class=\"alert alert-success fancybox\">
                    Afbeelding succesvol bewerkt
                </div>";
            }

        } elseif($media == 'document') {
            if(!$_POST['doc_naam']){
                $doc_naam = '';
            }else{
                $doc_naam = $_POST['doc_naam'];
            }
            $sql_insert_doc = $mysqli->query("UPDATE sitework_doc set naam = '$doc_naam' WHERE id = '".$mediaId."'") or die($mysqli->error.__LINE__);

            echo "
            <div class=\"alert alert-success fancybox\">
                Document succesvol bewerkt
            </div>";

        }
    }
}
 if (isset($message)) { 
     echo "
     <div class=\"alert alert-error fancybox\">";
         if (isset($message)) {
         foreach ($message as $msg) {
         printf("<p>%s", $msg);
             }
         }
     echo "</div>";
 } 

// afbeelding verwijderen
// ======================
if (isset($_GET['delete_id'])) {
    if($_GET['media'] == 'afbeelding') {
        $sql_del = $mysqli->query("DELETE FROM sitework_img WHERE id = '".$_GET['delete_id']."' ") or die($mysqli->error.__LINE__);
    } elseif($_GET['media'] == 'document') {
        $sql_del = $mysqli->query("DELETE FROM sitework_doc WHERE id = '".$_GET['delete_id']."' ") or die($mysqli->error.__LINE__);
    }

    header('Location: media_upload.php?id='.$_GET['id'].'&block_id='.$_GET['block_id'].'&taal='.$_GET['taal'].'&media='.$_GET['media'].'&upload_from='.$_GET['upload_from'].'');

}

$mediaSoort = $_GET['media'];

// Accepteer per media soort
if($mediaSoort == 'afbeelding') {
    $accept = 'image/*';
} else {
    $accept = '.xlsx,.xls,.doc, .docx,.ppt, .pptx,.txt,.pdf';
}

$sql = $mysqli->query("SELECT * FROM sitework_mediabibliotheek WHERE media = '".$mediaSoort."' ORDER BY id DESC LIMIT 100") or die ($mysqli->error.__LINE__);
$rows = $sql->num_rows;

?>
<TITLE>SiteWork CMS afbeelding upload</TITLE>
<meta charset="UTF-8" />
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/css/stylesheet.css">
<link rel='stylesheet' type='text/css' href='<? echo $url; ?>/cms/css/branding-stylesheet.php' />

<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/datepick/jquery-ui-date.css">
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/fancybox/jquery.fancybox.min.css">
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/font-awesome/css/all.min.css">

<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery-ui-1-12-1.min.js"></script>  
<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery.fancybox.js"></script>
<script type="text/javascript" src="<? echo $url; ?>/cms/datepick/jquery-ui-date.js"></script>
<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery-nested-sortable.js"></script>  
<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/sitework.js"></script>
<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery.sticky-kit.min.js"></script>
<script>
    function handleImageError(img, id) {
        var fallbackImageUrl = '/img/noimg.jpg'; // Replace with your fallback image URL
        $(img).attr('src', fallbackImageUrl);
        
        // Update the href attribute of the parent a element
        $(img).closest('a').attr('href', '/cms/php/media_bewerken.php?media_id=' + id + '&noimg=ja');
    }
</script>
<script>
// Alert voor opgeslagen
// ======================
// window.setTimeout(function() {
//     $(".alert").fadeTo(500, 0).slideUp(500, function() {
//         $(this).remove();
//     }); 
// }, 4000);

// $(".alert").on("click", function() {
//     $(this).fadeTo("slow", 0);
// });
</script>
<script type="text/javascript">
function ConfirmDelete() {
    return confirm('Weet u zeker dat u deze afbeelding wilt verwijderen?');
}

// volgorde van afbeeldingen verslepen en opslaan
// ==============================================
$(function() {
    $(".content-container #update_records").sortable({
        opacity: 0.6,
        cursor: 'move',
        update: function() {
            var order = $(this).sortable("serialize") + '&action=updateRecordsListings';
            $.post("../dragdrop/update_image_DB.php", order, function(theResponse) {
                $("#contentLeft").append(theResponse);
            });
        }
    });
});

function initializeTooltips() {
    $('.showImgInfo').tooltip({
        content: function() {
            var infotekst = '<div class="afbeelding-info">';
                    infotekst += '<div class="row">';
                        infotekst += '<span class="fat"><b>Geüpload op:</b> '+$(this).attr('data-geupload')+'</span>';
                    infotekst += '</div>';
                    infotekst += '<div class="row">';
                        infotekst += '<span class="fat"><b>Bestandsnaam:</b> '+$(this).attr('data-naam')+'</span>';
                    infotekst += '</div>';
                    infotekst += '<div class="row">';
                            infotekst += '<span class="fat"><b>Afmetingen:</b> '+$(this).attr('data-afmetingen')+'</span>';
                    infotekst += '</div>';
                    infotekst += '<div class="row">';
                        infotekst += '<span class="fat"><b>Bestandstype:</b> '+$(this).attr('data-type')+'</span>';
                    infotekst += '</div>';
                    infotekst += '<div class="row">';
                        infotekst += '<span class="fat"><b>Bestandsgrootte:</b> '+$(this).attr('data-filesize')+'</span>';
                    infotekst += '</div>';
                    infotekst += '<div class="row">';
                        infotekst += '<span class="fat" style="word-break: break-all;"><b>Url:</b> <span id="copy-url" data-url="<?=$url;?><?php echo ($mediaSoort == 'afbeelding') ? '/img/' : '/doc/'; ?>'+$(this).attr('data-naam')+'" style="text-decoration:underline;cursor:pointer;"><?=$url;?><?php echo ($mediaSoort == 'afbeelding') ? '/img/' : '/doc/'; ?>'+$(this).attr('data-naam')+'</span></span>';
                    infotekst += '</div>';
                    infotekst += '<span id="gekopieerd" style="position:absolute;bottom:10px;left:10px;color:green;"></span>';
                infotekst += '</div>';

            return $('<div class="tooltip-content">' + infotekst + '</div>');
        },
        open: function(event, ui) {
            ui.tooltip.on('click', '#copy-url', function() {
                var textToCopy = $(this).attr('data-url');
                navigator.clipboard.writeText(textToCopy)
                    .then(function() {
                        $('#gekopieerd').text('URL gekopieërd!'); // Show success message
                    })
                    .catch(function(err) {
                        console.error("Failed to copy text:", err);
                    });
            });

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
};

$(document).ready(function () {
    initializeTooltips();
});
</script>

<div class="fancybox-wrap" style="width: 100%;">
    <div class="box-container grid-container">
        <div id="error_area">
            <?php if ($error) { ?><span class="alert error"><?php echo $error; ?></span><?php } ?>
        </div>
        <form action="media_upload.php?id=<?php echo $_GET['id'] ?>&block_id=<?php echo $_GET['block_id'];?>&taal=<?=$_GET['taal'];?>&media=<?=$_GET['media'];?>&upload_from=<?=$_GET['upload_from'];?>" method="POST" enctype="multipart/form-data" style="width:100%;">
            <div class="box box-1-2 md-box-full left">
                <h3><span class="icon fas fa-image"></span>Huidige afbeeldingen</h3>
                <a href="" class="clickme btn fl-right nieuw">Upload mediabestand</a>
                <div id="contentLeft">
                    <div class="content-container toggle-box">
                        <div class="drag-and-drop">
                            <div class="">
                                <button type="button" class="btn upload button-input" onclick="document.getElementById('inputFile').click()">Bestand uploaden</button>
                                <i>Of</i>
                                <input type="file" name="mediabiebFiles" class="form-control-file text-success font-weight-bold" id="inputFile" multiple accept="<?=$accept;?>" onchange="readUrl(this)" data-title="Een bestand slepen en neerzetten">
                            </div>
                        </div>
                    </div>
                    <div class="content-container">  
                        <ul id="update_records">
                            <?php 
                            if($_GET['media'] == 'afbeelding') {
                                if($_GET['block_id']){
                                    $querydrag = $mysqli->query("SELECT * FROM sitework_img WHERE block_id = '".$_GET['block_id']."' AND img_taal = '".$_GET['taal']."' order by volgorde") or die($mysqli->error.__LINE__);
                                }else{
                                    $querydrag = $mysqli->query("SELECT * FROM sitework_img WHERE cms_id = '".$_GET['id']."' AND block_id = '0' AND img_taal = '".$_GET['taal']."' order by volgorde") or die($mysqli->error.__LINE__);
                                }
                            } else {
                                if($_GET['block_id']){
                                    $querydrag = $mysqli->query("SELECT * FROM sitework_doc WHERE block_id = '".$_GET['block_id']."' AND doc_taal = '".$_GET['taal']."' order by volgorde") or die($mysqli->error.__LINE__);
                                }else{
                                    $querydrag = $mysqli->query("SELECT * FROM sitework_doc WHERE cms_id = '".$_GET['id']."' AND doc_taal = '".$_GET['taal']."' order by volgorde") or die($mysqli->error.__LINE__);
                                }
                            }
                            
                            if ($querydrag->num_rows == 0) {
                                echo "<div class=\"middentekst\">Er zijn nog geen ".$_GET['media']."en geplaatst ...</div>";
                            }
                            
                            while ($rowdrag = $querydrag->fetch_assoc()) {
                                if($_GET['media'] == 'afbeelding') {
                                    $mediaID = $rowdrag['naam'];
                                } else {
                                    $mediaID = $rowdrag['url'];
                                }
                                ?>

                                <li id="recordsArray_<?php echo $rowdrag['id']; ?>">
                                    <input class="huidige-media" type="checkbox" value="<?=$mediaSoort;?>-<?=$rowdrag['id'];?>" data-cms-id="<?=$_GET['id'];?>" data-block-id="<?php echo ($_GET['block_id']) ? $_GET['block_id'] : '0'; ?>" data-media-id="<?=$mediaID;?>-<?=$rowdrag['id'];?>" data-media-soort="<?=$mediaSoort;?>" name="huidige-media" id="<?=$mediaSoort;?>-<?=$rowdrag['id'];?>">
                                    <label for="<?=$mediaSoort;?>-<?=$rowdrag['id'];?>" class="sort-wrap <?php echo ($mediaSoort == 'afbeelding') ? 'img' : 'doc';?>">
                                        <div>    
                                            <?php 
                                                $querydragMedia = $mysqli->query("SELECT * FROM sitework_mediabibliotheek WHERE id = '".$mediaID."' ORDER BY id DESC") or die($mysqli->error.__LINE__);
                                                $rowdragMedia = $querydragMedia->fetch_assoc();
                                            
                                            if($_GET['media'] == 'afbeelding'):
                                                img($url, $rowdragMedia['naam'], $rowdragMedia['ext'], '', '500', '500', '500');
                                            else: ?>
                                                <div class="geupload-doc">
                                                    <i class="fas fa-file"></i>
                                                    <p><?=$rowdrag['naam'];?></p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <span class="soort-image"><?php echo ($_GET['media'] == 'afbeelding') ? $rowdrag['afbsoort'] : $rowdragMedia['ext'] ?></span>
                                        <span 
                                            class="showImgInfo" 
                                            title=""
                                            data-geupload="<?php $geupload = strftime("%d %B %Y", strtotime($rowdragMedia['datum_geupload'])); echo $geupload; ?>"
                                            data-afmetingen="
                                            <?php 
                                                list($width, $height, $type, $attr) = getimagesize("../../img/".$rowdragMedia['naam'].'.'.$rowdragMedia['ext']); 
                                                echo $width." x ".$height." pixels<br>";  
                                            ?>"
                                            data-naam="<?=$rowdragMedia['naam'].'.'.$rowdragMedia['ext'];?>"
                                            data-type="
                                            <?php $finfo = finfo_open(FILEINFO_MIME_TYPE); 
                                                if($mediaSoort == 'document') {
                                                    foreach (glob("../../doc/".$rowdragMedia['naam'].'.'.$rowdragMedia['ext']) as $filename) {
                                                        echo finfo_file($finfo, $filename) . "\n";
                                                    } finfo_close($finfo);
                                                } else {
                                                    foreach (glob("../../img/".$rowdragMedia['naam'].'.'.$rowdragMedia['ext']) as $filename) {
                                                        echo finfo_file($finfo, $filename) . "\n";
                                                    } finfo_close($finfo);
                                                }
                                                ?>"
                                            data-filesize="
                                            <?php 
                                                if($mediaSoort == 'document') {
                                                    $filename = '../../doc/'.$rowdragMedia['naam'].'.'.$rowdragMedia['ext']; 
                                                } else {
                                                    $filename = '../../img/'.$rowdragMedia['naam'].'.'.$rowdragMedia['ext']; 
                                                }
                                                
                                                echo round(filesize($filename)/1024, 2) . ' Kb'; 
                                            ?>"
                                        >
                                        <i class="far fa-info-circle"></i></span>
                                        <a class="delete-image" href="?id=<?php echo $_GET['id']; ?>&block_id=<?php echo $_GET['block_id'];?>&taal=<?=$_GET['taal'];?>&media=<?=$_GET['media'];?>&upload_from=<?=$_GET['upload_from'];?>&delete_id=<?php echo $rowdrag['id']; ?>"
                                            onclick='return ConfirmDelete();'>
                                            <span class="far fa-trash-alt"></span>
                                        </a>
                                    </label>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="content-container">    	
                    <div class="info full !mt-20">
                        <span class="far fa-info-circle"></span>&nbsp;&nbsp;U kunt de volgorde van de <?=$_GET['media'];?>en zelf bepalen door ze
                        te verslepen.<br><br> U kunt als u met uw muis over de <span class="far fa-info-circle"></span> gaat de afbeelding informatie bekijken en ook de afbeelding-url kopieëren door er op te klikken
                    </div>
                </div>
            </div>

            <div class="right">
                <div id="media_tvg" class="box box-1-2 md-box-full hidden-box">
                    <h3><span class="icon fas fa-images"></span><?php echo ucfirst($_GET['media']);?>(en) toevoegen</h3>
                        <?php if($_GET['media'] == 'afbeelding'): ?>
                            <?php if($_GET['block_id']){
                                echo '<input type="hidden" name="afbsoort_new" value="block">';
                            } else { ?>
                                <div class="form-group">
                                    <label for="afbsoort_new">Soort</label>
                                    <select name="afbsoort_new" class="inputveld invoer dropdown<?php if ($error_afb) {
                                            echo ' foutveld';
                                        } ?>" id="afbsoort_new">
                                        <option value="">Selecteer afbeeldingstype</option>
                                        <option value="hoofdfoto">hoofdfoto</option>
                                        <option value="zijkant">zijkant</option>
                                        <option value="galerij">galerij</option>
                                        <option value="uitgelicht">uitgelicht</option>
                                        <option value="logo">logo</option>
                                    </select>
                                </div>
                            <?php } ?>
                            <?php if ($rowinstellingen['afbeeldingopties'] == 'ja' && $rowinstellingen['hoofdtitelveld'] == 'ja') {?>
                                <div id="hoofdtitel" class="form-group">
                                    <label for="hoofdtitel_new">Hoofdtitel</label><input type="text" name="hoofdtitel_new"
                                        class="inputveld invoer" placeholder="Hoofdtitel" maxlength="200" />
                                </div>
                            <?php } ?>
                            <?php if ($rowinstellingen['afbeeldingopties'] == 'ja' && $rowinstellingen['subtitelveld'] == 'ja') {?>
                                <div id="subtitel" class="form-group">
                                    <label for="subtitel_new">Subtitel</label><input type="text" name="subtitel_new" class="inputveld invoer"
                                        placeholder="Subtitel" maxlength="200" />
                                </div>
                            <?php } ?>
                            <?php if ($rowinstellingen['afbeeldingopties'] == 'ja' && $rowinstellingen['linkveld'] == 'ja') {?>
                                <div id="link" class="form-group">
                                    <label for="link_new">Link</label><input type="text" name="link_new" class="inputveld invoer"
                                        placeholder="Link (incl https://)" maxlength="200" />
                                </div>
                                <div id="linknaam" class="form-group">
                                    <label for="linknaam_new">Link naam</label><input type="text" name="linknaam_new" class="inputveld invoer"
                                        placeholder="Link (bijvoorbeeld 'lees meer')" maxlength="300" />
                                </div> 
                            <?php } ?>
                        <?php else: ?>
                            <div class="form-group">
                                <label for="doc_naam_new">Naam</label><input type="text" name="doc_naam_new" class="inputveld invoer"
                                    placeholder="Naam van de document" maxlength="50" />
                            </div>
                        <?php endif; ?>

                        <input type="hidden" name="media" value="<?=$_GET['media'];?>">
                        <input type="hidden" name="afb" value="<?php echo $_GET['id'] ?>">
                        <input type="hidden" name="taal" value="<?=$_GET['taal'];?>">
                        <input type="hidden" name="toevoegen" value="1">

                        <button name="opslaan_toevoegen" class="btn fl-left save" type="submit">Opslaan</button>
                </div>
                
                <div id="media_bewerk" class="box box-1-2 md-box-full hidden-box">
                    <h3><span class="icon fas fa-images"></span><?php echo ucfirst($_GET['media']);?>(en) bewerken</h3>
                        <?php if($_GET['media'] == 'afbeelding'): ?>
                            <?php if($_GET['block_id']) {
                                echo '<input type="hidden" name="afbsoort" value="block">';
                            } else { ?>
                                <div class="form-group">
                                    <label for="afbsoort">Soort</label>
                                    <select name="afbsoort" class="inputveld invoer dropdown<?php if ($error_afb) {
                                            echo ' foutveld';
                                        } ?>" id="afbsoort">
                                        <optgroup label="Huidige soort" id="afbsoort_bewerk">
                                            
                                        </optgroup>
                                        <option value="">Selecteer afbeeldingstype</option>
                                        <option value="hoofdfoto">hoofdfoto</option>
                                        <option value="zijkant">zijkant</option>
                                        <option value="galerij">galerij</option>
                                        <option value="uitgelicht">uitgelicht</option>
                                        <option value="logo">logo</option>
                                    </select>
                                </div>
                            <?php } ?>
                            <?php if ($rowinstellingen['afbeeldingopties'] == 'ja' && $rowinstellingen['hoofdtitelveld'] == 'ja') {?>
                                <div id="hoofdtitel" class="form-group">
                                    <label for="hoofdtitel">Hoofdtitel</label><input type="text" name="hoofdtitel"
                                        class="inputveld invoer" placeholder="Hoofdtitel" maxlength="200" />
                                </div>
                            <?php } ?>
                            <?php if ($rowinstellingen['afbeeldingopties'] == 'ja' && $rowinstellingen['subtitelveld'] == 'ja') {?>
                                <div id="subtitel" class="form-group">
                                    <label for="subtitel">Subtitel</label><input type="text" name="subtitel" class="inputveld invoer"
                                        placeholder="Subtitel" maxlength="200" />
                                </div>
                            <?php } ?>
                            <?php if ($rowinstellingen['afbeeldingopties'] == 'ja' && $rowinstellingen['linkveld'] == 'ja') {?>
                                <div id="link" class="form-group">
                                    <label for="link">Link</label><input type="text" name="link" class="inputveld invoer"
                                        placeholder="Link (incl https://)" maxlength="200" />
                                </div>
                                <div id="linknaam" class="form-group">
                                    <label for="linknaam">Link naam</label><input type="text" name="linknaam" class="inputveld invoer"
                                        placeholder="Link (bijvoorbeeld 'lees meer')" maxlength="300" />
                                </div> 
                            <?php } ?>
                        <?php else: ?>
                            <div class="form-group">
                                <label for="doc_naam">Naam</label><input type="text" name="doc_naam" class="inputveld invoer"
                                    placeholder="Naam van de document" maxlength="50" />
                            </div>
                        <?php endif; ?>

                        <input type="hidden" name="media" value="<?=$_GET['media'];?>">
                        <input type="hidden" name="afb" value="<?php echo $_GET['id'] ?>">
                        <input type="hidden" name="taal" value="<?=$_GET['taal'];?>">
                        <input type="hidden" name="toevoegen" value="1">

                        <button name="opslaan_bewerken" class="btn fl-left save" type="submit">Opslaan</button>
                </div>

                <div class="box box-1-2 md-box-full">
                    <h3><span class="icon fas fa-photo-video"></span>Mediabibliotheek</h3>
                    <div class="content-container">    	
                        <div class="row mediabieb kies-media">
                            <?php if($rows > 0): ?>
                                <?php while ($row = $sql->fetch_assoc()){
                                    if(!in_array($row['ext'], $valid_formats)) {
                                        $sqlMediaUse = $mysqli->query("SELECT * FROM sitework_doc WHERE url = '".$row['id']."'") or die($mysqli->error.__LINE__);
                                        $rowMediaUse = $sqlMediaUse->fetch_assoc();
                                    } else {
                                        $sqlMediaUse = $mysqli->query("SELECT * FROM sitework_img WHERE naam = '".$row['id']."'") or die($mysqli->error.__LINE__);
                                        $rowMediaUse = $sqlMediaUse->fetch_assoc();
                                    }
                                    
                                    ?>
                                        <?php if(!in_array($row['ext'], $valid_formats)): ?>
                                            <input class="media-keuze" type="checkbox" value="<?=$mediaSoort;?>-<?=$row['id'];?>" name="media-keuze[]" id="<?=$mediaSoort;?>-<?=$row['id'];?>">
                                            <label for="<?=$mediaSoort;?>-<?=$row['id'];?>" class="doc ">
                                                <i class="fas fa-file"></i>  
                                                <p><?=$row['naam'];?>.<?=$row['ext'];?></p>                                      
                                            </label>
                                        <?php else: ?>
                                            <input class="media-keuze" type="checkbox" value="<?=$mediaSoort;?>-<?=$row['id'];?>" name="media-keuze[]" id="<?=$mediaSoort;?>-<?=$row['id'];?>">
                                            <label for="<?=$mediaSoort;?>-<?=$row['id'];?>" class="img ">
                                                <img src="/img/<?=$row['naam'];?>_tn.<?=$row['ext'];?>" alt="<?=$row['naam'];?>" onerror="handleImageError(this, <?=$row['id'];?>);">
                                            </label>
                                        <?php endif; ?>
                                <? } ?>  
                            <?php else: ?>
                                <p id="geen-media"><strong>Geen afbeeldingen gevonden</strong></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <span id="scroll-to-media-top"><i class="fas fa-arrow-up"></i></span>
</div>

<script>
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function() {
            $(this).remove();
        }); 
    }, 4000);

    $(".alert").on("click", function() {
        $(this).fadeTo("slow", 0);
    });

    function getMimeType(filePath) {
        const extension = filePath.split('.').pop().toLowerCase();
        const mimeTypes = {
            'jpg': 'image/jpeg',
            'jpeg': 'image/jpeg',
            'png': 'image/png',
            'gif': 'image/gif',
            'pdf': 'application/pdf',
            'doc': 'application/msword',
            'docx': 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls': 'application/vnd.ms-excel',
            'xlsx': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        };
        return mimeTypes[extension] || 'Kan geen juist type weergeven';
    }

    // Example for File Size
    function getFileSize(filePath) {
        return fetch(filePath, { method: 'HEAD' })
            .then(response => {
                const size = response.headers.get('content-length');
                return size ? (size / 1024).toFixed(2) : 'Unknown'; // Size in KB
            })
            .catch(error => {
                console.error('Error fetching file size:', error);
                return 'Unknown';
            });
    }

    function readUrl(input) {
        if (input.files && input.files.length > 0) {
            let reader = new FileReader();
            reader.onload = (e) => {
                let imgData = e.target.result;
                let imgNames = [];
                for (let i = 0; i < input.files.length; i++) {
                    imgNames.push(input.files[i].name);
                }
                let filesCountText = input.files.length === 1 ? 'bestand' : 'bestanden';
                let displayText = `${input.files.length} ${filesCountText}`;
                input.setAttribute("data-title", displayText);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).ready(function () {
    
        $("input[name=mediabiebFiles]").on('change', function (event) {
            event.preventDefault();

            $('.fancybox-wrap').append("<div class=\"alert alert-info fancybox\">Media word geupload</div>");

            // Get all selected files
            var files = event.target.files;

            if (files.length > 0) {
                // Create a FormData object
                var formData = new FormData();

                // Loop through all selected files
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    // Append each file to the FormData object
                    formData.append('mediabiebFiles[]', file); 
                }
                formData.append('cms_id', '<?=$_GET['id'];?>');
                formData.append('block_id', '<?=$_GET['block_id'];?>');
                formData.append('taal', '<?=$_GET['taal'];?>');
                formData.append('mediaSoort', '<?=$_GET['media'];?>');
                formData.append('upload_from', '<?=$_GET['upload_from'];?>');
                
                // Configure the AJAX request
                $.ajax({
                    type: "POST",
                    url: "/cms/php/upload_mediabestand.php", // Replace with your actual URL
                    data: formData,
                    contentType: false, // Set to avoid default form data processing
                    processData: false, // Prevent jQuery from pre-processing data
                    success: function (data) {

                        let mediaData;
                        try {
                            mediaData = JSON.parse(data);
                        } catch (e) {
                            console.error("Invalid JSON response:", e);
                            $(".mediabieb").html(data);
                        }

                        // If JSON parsing was successful, proceed to use the data
                        if (mediaData && mediaData.mediabieb && mediaData.selected) {
                            let mediabiebContent = '';
                            mediaData.mediabieb.forEach(function(item) {
                                if(item.type === 'doc') {
                                    mediabiebContent += `
                                        <input class="media-keuze" type="checkbox" value="${item.id}" name="media-keuze[]" id="${item.id}">
                                        <label for="${item.id}" class="${item.type}">
                                            <i class="fas fa-file"></i> <p>${item.naam}.${item.ext}</p>
                                        </label>
                                    `;
                                } else {
                                    mediabiebContent += `
                                        <input class="media-keuze" type="checkbox" value="${item.id}" name="media-keuze[]" id="${item.id}">
                                        <label for="${item.id}" class="${item.type}">
                                            <img src="/img/${item.naam}_tn.${item.ext}" alt="${item.naam}" onerror="handleImageError(this, ${item.id});">
                                        </label>
                                    `;
                                }
                            });
                            $(".mediabieb").html(mediabiebContent);

                            let selectedContent = '';
                            mediaData.selected.forEach(function(item) {
                                let content = '';
                                let soortImg = '';

                                if(item.mediaSoort === 'afbeelding') {
                                    content = `
                                        <div class="picture-container" style="padding-top: 100.00%">
                                            <picture>
                                                <source media="(max-width: 700px)" data-srcset="${item.afbeelding.url}/img/${item.afbeelding.naam}_tn.${item.afbeelding.ext}" type="image/jpeg">
                                                <source media="(max-width: 900px)" data-srcset="${item.afbeelding.url}/img/${item.afbeelding.naam}_mid.${item.afbeelding.ext}" type="image/jpeg">
                                                <source media="(min-width: 900px)" data-srcset="${item.afbeelding.url}/img/${item.afbeelding.naam}.${item.afbeelding.ext}" type="image/jpeg">
                                                <img src="${item.afbeelding.url}/img/${item.afbeelding.naam}.${item.afbeelding.ext}" alt="" class="lazy" width="500" height="500">
                                            </picture>
                                        </div>
                                    `;
                                    soortImg = item.afbeelding.afbsoort;
                                } else {
                                    content = `
                                        <div class="geupload-doc">
                                            <i class="fas fa-file"></i>
                                            <p>${item.naam}</p>
                                        </div>
                                    `;
                                    soortImg = item.afbeelding.ext;
                                }

                                selectedContent += `
                                    <li id="recordsArray_${item.id}">
                                        <input class="huidige-media" 
                                            type="checkbox" 
                                            value="${item.mediaSoort}-${item.id}" 
                                            data-cms-id="${item.cms_id}" 
                                            data-block-id="${item.block_id ? item.block_id : '0'}" 
                                            data-media-id="${item.naam}-${item.id}" 
                                            data-media-soort="${item.mediaSoort}" 
                                            name="huidige-media" 
                                            id="${item.mediaSoort}-${item.id}">
                                        <label for="${item.mediaSoort}-${item.id}" class="sort-wrap ${item.mediaSoort == 'afbeelding' ? 'img' : 'doc'}">
                                            <div>    
                                                ${content}
                                            </div>
                                            <span class="soort-image">${soortImg}</span>
                                            <span class="showImgInfo" 
                                                title=""
                                                data-geupload="${new Date(item.uploadDate).toLocaleDateString('nl-NL', { day: '2-digit', month: 'long', year: 'numeric' })}"
                                                data-afmetingen="${item.afbeelding.width} x ${item.afbeelding.height} pixels"
                                                data-naam="${item.afbeelding.naam}.${item.afbeelding.ext}"
                                                data-type="${item.mediaSoort === 'document' 
                                                            ? getMimeType(item.afbeelding.url+'/doc/'+item.afbeelding.naam+'.'+item.afbeelding.ext) 
                                                            : getMimeType(item.afbeelding.url+'/img/'+item.afbeelding.naam+'.'+item.afbeelding.ext)}"
                                                data-filesize="Fetching size...">
                                                <i class="far fa-info-circle"></i>
                                            </span>
                                            <a class="delete-image" 
                                            href="?id=${item.cms_id}&block_id=${item.block_id}&taal=${item.taal}&media=${item.mediaSoort}&upload_from=${item.uploadFrom}&delete_id=${item.id}"
                                            onclick='return ConfirmDelete();'>
                                            <span class="far fa-trash-alt"></span>
                                            </a>
                                        </label>
                                    </li>
                                `;

                                let filePath = item.mediaSoort === 'document' 
                                    ? `${item.afbeelding.url}/doc/${item.afbeelding.naam}.${item.afbeelding.ext}` 
                                    : `${item.afbeelding.url}/img/${item.afbeelding.naam}.${item.afbeelding.ext}`;

                                getFileSize(filePath).then(size => {
                                    // Find the element and update the data-filesize attribute
                                    document.querySelector(`#recordsArray_${item.id} .showImgInfo`).setAttribute('data-filesize', `${size} KB`);
                                }).catch(error => {
                                    console.error('Error fetching file size:', error);
                                    document.querySelector(`#recordsArray_${item.id} .showImgInfo`).setAttribute('data-filesize', 'Unknown');
                                });
                            });
                            $("#update_records").html(selectedContent);
                        } else {
                            $(".mediabieb").html(data);
                        }
                        
                        $('#inputFile').val("");
                        $('#inputFile').attr("data-title", "Een bestand slepen en neerzetten");
                        $('.fancybox-wrap').append('<div class="alert alert-success fancybox" style="z-index:11;">Media is succesvol geupload</div>');
                        initializeTooltips();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error("Upload failed:", textStatus, errorThrown);
                        // Handle upload errors gracefully (optional)
                    }
                });
            }
        });

        // $(".media-keuze").on('change', function (event) {
        //     if ($(this).is(':checked')) {
        //         $(".media-keuze").not(this).prop('checked', false);
        //     }
        // });

        $(".kies-media").on('change', '.media-keuze', function (event) {
            var checkedCount = $(".media-keuze:checked").length;
            var checkedCountHuidig = $(".huidige-media:checked").length;

            if(checkedCountHuidig > 0) {
                $('#media_bewerk').addClass('hidden-box');
                $(".huidige-media").not(this).prop('checked', false);
            }

            if(checkedCount > 0) {
                $('#media_tvg').removeClass('hidden-box');
                if ($('#media_toevoegen').length === 0) {
                    $('#media_tvg').append('<input type="hidden" name="media_toevoegen" id="media_toevoegen" value="ja" />');
                }
            } else { $('#media_tvg').addClass('hidden-box'); $('#media_toevoegen').remove(); }
            
            if(checkedCount > 1) {
                if ($('#meerdere_media_toevoegen').length === 0) {
                    $('#media_tvg').append('<input type="hidden" name="meerdere_media_toevoegen" id="meerdere_media_toevoegen" value="ja" />');
                }
                $('#hoofdtitel').addClass('hidden');
                $('#subtitel').addClass('hidden');
                $('#link').addClass('hidden');
                $('#linknaam').addClass('hidden');
            } else {
                $('#meerdere_media_toevoegen').remove();
                $('#hoofdtitel').removeClass('hidden');
                $('#subtitel').removeClass('hidden');
                $('#link').removeClass('hidden');
                $('#linknaam').removeClass('hidden');
            }
        });

        $("#update_records").on('change', '.huidige-media', function (event) {
            if ($(this).is(':checked')) {
                $(".huidige-media").not(this).prop('checked', false);
            }
        });

        $("#update_records").on('change', '.huidige-media', function (event) {
            var checkedCount = $(".huidige-media:checked").length;
            var checkedCountMedaBieb = $(".media-keuze:checked").length;

            if(checkedCountMedaBieb > 0) {
                $('#media_tvg').addClass('hidden-box');
                $(".media-keuze").not(this).prop('checked', false);
            }

            if(checkedCount > 0) {
                $('#media_bewerk').removeClass('hidden-box');
                if ($('#media_bewerken').length === 0) {
                    $('#media_bewerk').append('<input type="hidden" name="media_bewerken" id="media_bewerken" value="ja" />');
                }

                const media_id = $(this).data('media-id');
                const media_soort = $(this).data('media-soort');
                const media_cms_id = $(this).data('cms-id');
                const media_block_id = $(this).data('block-id');

                $.ajax({
                    url: "/cms/php/verkrijg_afbeelding.php",
                    type: "POST",
                    data: { 
                        media_id: media_id, 
                        media_soort: media_soort,
                        media_cms_id: media_cms_id,
                        media_block_id: media_block_id
                    },
                    dataType: 'json',
                    success: function (data) {
                        if(data.status == 'success') {
                        var media_bestand = data.media;
                            var details = data.details;

                            var kopier_link = '<?php echo $url;?>/img/'+media_bestand.naam+'.'+media_bestand.ext;

                            if(media_bestand.media == 'afbeelding') {
                                var afbsoort = details.afbsoort;
                                var hoofdtitel = details.hoofdtitel;
                                var subtitel = details.subtitel;
                                var link = details.link;
                                var linknaam = details.linknaam;

                                $('#afbsoort_bewerk').html('<option value="'+afbsoort+'" selected>'+afbsoort+'</option>');

                                $('[name="hoofdtitel"]').val(hoofdtitel);
                                $('[name="subtitel"]').val(subtitel);
                                $('[name="link"]').val(link);
                                $('[name="linknaam"]').val(linknaam);
                            } else if(media_bestand.media == 'document') {
                                var doc_naam = details.naam;

                                $('[name="doc_naam"]').val(doc_naam);
                            } 
                        } else {
                            $('#error_area').html('<span class="error">Er zijn geen gegevens gevonden, probeer het nogmaals</span>');
                        }
                    }
                });
            } else {
                $('#media_bewerk').addClass('hidden-box');
                $('#media_bewerken').remove();

                // $('#afbsoort_bewerk').val('');
                $('#afbsoort_bewerk').html('');

                $('[name="hoofdtitel"]').val('');
                $('[name="subtitel"]').val('');
                $('[name="link"]').val('');
                $('[name="linknaam"]').val('');

                $('[name="doc_naam"]').val('');
            }
        });
    });
</script>