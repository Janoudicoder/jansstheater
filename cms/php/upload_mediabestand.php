<?php
$valid_formats = array("jpg", "JPG", "JPEG", "jpeg", "png", "PNG", "svg", "SVG", "gif"); // "jpg", "png", "gif", "zip", "bmp"
$max_file_size = 6 * 1024 * 1024; // 6 MB in bytes
$pad = "../../img/"; // upload directory
$doc_pad = "../../doc/"; // upload directory
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
    private $thumbnail;


    function __construct($image,$width,$height,$type) {
        if($type == 'thumb'){
            $pref = "_tn";
        }else if($type == 'mid'){
            $pref = "_mid";
        }else if($type == 'full'){
            $pref = "_full";
        }else{
            $pref = "";
        }
        parent::set_img($image);
        parent::set_quality(95);
        parent::set_size($width,$height);
        $this->thumbnail = "../../img/".pathinfo($image, PATHINFO_FILENAME).$pref.".".pathinfo($image, PATHINFO_EXTENSION);
        parent::save_img($this->thumbnail);
        convertImageToWebP($this->thumbnail, "../../img/webp/".pathinfo($image, PATHINFO_FILENAME).$pref.".webp", 95);
        parent::clear_cache();
        }
    function __toString() {
            return $this->thumbnail;
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
require("../ftp/config.php");
include '../login/functions.php';

session_start();

// checken of men wel is ingelogd
// ==============================
login_check_v2();

if (isset($_FILES['mediabiebFiles'])) {
    if ($_FILES['mediabiebFiles']['size'][0] == 0) {
        echo 'post afbsoort gezet<br>';
        $error = "kies eerst een afbeelding en een afbeelding soort";
        if ($_FILES['mediabiebFiles']['size'][0] == 0) {
            $error_afbfile = 'foutveld';
        }
    } else {
        // eerst de mappen open zetten
        // ===========================
        
        // loop $_FILES to execute all files
        // =================================
        foreach ($_FILES['mediabiebFiles']['name'] as $f => $name) {
            // unieke bestandsnaam aanmaken
            // ============================
            if ($_FILES['mediabiebFiles']['error'][$f] == 4) {
                continue; // skip file bij een error
            }
            if ($_FILES['mediabiebFiles']['error'][$f] == 0) {
                list($width, $height, $type, $attr) = getimagesize($_FILES["mediabiebFiles"]["tmp_name"][$f]);

                if(!in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats)) {
                    $vervang = array(" ", "\'");
                    $door   = array("-", "");
                    $fileName = pathinfo($name, PATHINFO_FILENAME);
                    $bestandsnaams = str_replace($vervang, $door, $fileName);

                    $bestandsnaam = rand(10000,50000) . "-" .$bestandsnaams;
                    $extentie = pathinfo($name, PATHINFO_EXTENSION);

                    ftp_site($ftpstream,$pad_doc_open);
                    // no error found! move uploaded files
                    if (move_uploaded_file($_FILES["mediabiebFiles"]["tmp_name"][$f], $doc_pad.$bestandsnaam.".".$extentie)) {
                        // afbeeldingen in database wegschrijven
                        // =====================================
                    
                        $sql_insert = $mysqli->query("INSERT sitework_mediabibliotheek set 
                                                        media='document',
                                                        naam='".$bestandsnaam."',
                                                        ext='".$extentie."'")
                        or die($mysqli->error.__LINE__);

                        $mediaFile = $mysqli->insert_id;  

                        if($_POST['mediaSoort'] == 'document') {
                            $insert_cms_id = $_POST['cms_id'];
                            $insert_taal = $_POST['taal'];

                            $sql_insert = $mysqli->query("INSERT sitework_doc set 
                                                        cms_id='".$insert_cms_id."',
                                                        naam='".$bestandsnaam."',
                                                        url='".$mediaFile."',
                                                        doc_taal='".$insert_taal."'")
                            or die($mysqli->error.__LINE__);
                        }
                    }
                    $count++; // number of successfully uploaded file

                    ftp_site($ftpstream,$pad_doc_dicht);
                } else {
                    $bestandsnaam = "_".rand(1, 9999)."_".time();
                    $extentie = pathinfo($name, PATHINFO_EXTENSION);

                    if ($_FILES['mediabiebFiles']['size'][$f] > $max_file_size) {
                        $message[] = "$name is te groot (maximaal 4MB)!.";
                        continue; // skip te grote bestanden
                    } elseif (! in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats)) {
                        $message[] = "$name is geen toegestaan bestandsformaat";
                        continue; // skip verkeerde bestandsformaten
                    } elseif($width > ($max_afbeelding + 1) || $height > ($max_afbeelding + 1)) {
                        $message[] = "$name overschrijdt de maximale toegestane afmetingen (maximaal $max_afbeelding x $max_afbeelding pixels).";
                        continue; // Skip te grootte afbeeldingen
                    } else { 
                        ftp_site($ftpstream, $pad_img_open);
                        ftp_site($ftpstream, $pad_temp_open);
                        ftp_site($ftpstream, $pad_thumbs_open);

                        // no error found! move uploaded files
                        if (move_uploaded_file($_FILES["mediabiebFiles"]["tmp_name"][$f], $pad.$bestandsnaam.".".$extentie)) {
                            $photo = $pad.$bestandsnaam.".".$extentie;
                            list($normal_width, $normal_height) = getimagesize($photo);

                            $thumb = new thumbnail($photo, $max_thumb, $max_thumb, 'thumb');
                            $mid = new thumbnail($photo, $max_mid, $max_mid, 'mid');
                            $normal = new thumbnail($photo, $normal_width, $normal_height, '');
                            $full = new thumbnail($photo, $max_afbeelding, $max_afbeelding, 'full');
                            // afbeeldingen in database wegschrijven
                            // =====================================
                        
                            $sql_insert = $mysqli->query("INSERT sitework_mediabibliotheek set 
                                                            media='afbeelding',
                                                            naam='".$bestandsnaam."',
                                                            ext='".$extentie."'")
                            or die($mysqli->error.__LINE__);

                            $mediaFile = $mysqli->insert_id;  

                            if($_POST['mediaSoort'] == 'afbeelding') {
                                $insert_cms_id = $_POST['cms_id'];
                                $insert_block_id = $_POST['block_id'];
                                $insert_taal = $_POST['taal'];

                                if($insert_block_id != 0 && $insert_block_id != '0') {
                                    $afbsoortUpload = "block";
                                } else { $afbsoortUpload = "uitgelicht"; }

                                $sql_insert = $mysqli->query("INSERT sitework_img set 
                                                            cms_id='".$insert_cms_id."',
                                                            block_id='".$insert_block_id."',
                                                            naam='".$mediaFile."',
                                                            afbsoort='".$afbsoortUpload."',
                                                            img_taal='".$insert_taal."'")
                                or die($mysqli->error.__LINE__);
                            }
                        }
                        $count++; // number of successfully uploaded file
    
                        ftp_site($ftpstream, $pad_img_dicht);
                        ftp_site($ftpstream, $pad_temp_dicht);
                        ftp_site($ftpstream, $pad_thumbs_dicht);
                    }
                }
            } 
        }
        // mappen weer dicht zetten
        // ========================
        ftp_close($ftpstream);
    }
}

if($_POST['upload_from'] == 'page' OR $_POST['upload_from'] == 'block') {
    $data = [
        'mediabieb' => [],
        'selected' => []
    ];
    $mediaSoort = $_POST['mediaSoort'];
    $sql = $mysqli->query("SELECT * FROM sitework_mediabibliotheek WHERE media = '".$mediaSoort."' ORDER BY id DESC LIMIT 100") or die ($mysqli->error.__LINE__);
    while ($row = $sql->fetch_assoc()){

        $element = [];
        if(!in_array($row['ext'], $valid_formats)) {
            $sqlMediaUse = $mysqli->query("SELECT * FROM sitework_doc WHERE url = '".$row['id']."'") or die($mysqli->error.__LINE__);
            $rowMediaUse = $sqlMediaUse->fetch_assoc();

            $element = [
                'type' => 'doc',
                'id' => $mediaSoort . '-' . $row['id'],
                'naam' => $row['naam'],
                'ext' => $row['ext']
            ];
        } else {
            $sqlMediaUse = $mysqli->query("SELECT * FROM sitework_img WHERE naam = '".$row['id']."'") or die($mysqli->error.__LINE__);
            $rowMediaUse = $sqlMediaUse->fetch_assoc();

            $element = [
                'type' => 'img',
                'id' => $mediaSoort . '-' . $row['id'],
                'naam' => $row['naam'],
                'ext' => $row['ext']
            ];
        }
        // Append the element to the mediabieb array
        $data['mediabieb'][] = $element;
    }

    if($mediaSoort == 'afbeelding') {
        if($_POST['block_id']){
            $querydrag = $mysqli->query("SELECT * FROM sitework_img WHERE block_id = '".$_POST['block_id']."' AND img_taal = '".$_POST['taal']."' order by volgorde") or die($mysqli->error.__LINE__);
        }else{
            $querydrag = $mysqli->query("SELECT * FROM sitework_img WHERE cms_id = '".$_POST['cms_id']."' AND block_id = '0' AND img_taal = '".$_POST['taal']."' order by volgorde") or die($mysqli->error.__LINE__);
        }
    } else {
        $querydrag = $mysqli->query("SELECT * FROM sitework_doc WHERE cms_id = '".$_POST['cms_id']."' AND doc_taal = '".$_POST['taal']."' order by volgorde") or die($mysqli->error.__LINE__);
    }
    
    if ($querydrag->num_rows == 0) {
        $data['selected'][] = "Er zijn nog geen ".$mediaSoort."en geplaatst ...";
    }
    
    while ($rowdrag = $querydrag->fetch_assoc()) {
        $recordArray = [];
        if($mediaSoort == 'afbeelding') {
            $mediaID = $rowdrag['naam'];
            $folder = 'img';
        } else {
            $mediaID = $rowdrag['url'];
            $folder = 'doc';
        }

        $querydragMedia = $mysqli->query("SELECT * FROM sitework_mediabibliotheek WHERE id = '".$mediaID."' ORDER BY id DESC") or die($mysqli->error.__LINE__);
        $rowdragMedia = $querydragMedia->fetch_assoc();

        list($width, $height, $type, $attr) = getimagesize($url . '/' . $folder . '/' . $rowdragMedia['naam'] . '.' . $rowdragMedia['ext']);

        $recordArray = [
            'id' => $rowdrag['id'],
            'naam' => $rowdrag['naam'],
            'cms_id' => $_POST['cms_id'],
            'block_id' => $_POST['block_id'],
            'taal' => $_POST['taal'],
            'mediaSoort' => $mediaSoort,
            'uploadFrom' => 'page',
            'uploadDate' => $rowdragMedia['datum_geupload'],
            'afbeelding' => [
                'url' => $url,
                'naam' => $rowdragMedia['naam'],
                'ext' => $rowdragMedia['ext'],
                'afbsoort' => $rowdrag['afbsoort'],
                'width' => $width,
                'height' => $height,
            ],
        ];

        $data['selected'][] = $recordArray;
    }

    echo json_encode($data);
} else {
    $sql = $mysqli->query("SELECT * FROM sitework_mediabibliotheek ORDER BY id DESC LIMIT 100") or die ($mysqli->error.__LINE__);
    while ($row = $sql->fetch_assoc()){

        if(!in_array($row['ext'], $valid_formats)) {
            $sqlMediaUse = $mysqli->query("SELECT * FROM sitework_doc WHERE url = '".$row['id']."'") or die($mysqli->error.__LINE__);
            $rowMediaUse = $sqlMediaUse->fetch_assoc();
        } else {
            $sqlMediaUse = $mysqli->query("SELECT * FROM sitework_img WHERE naam = '".$row['id']."'") or die($mysqli->error.__LINE__);
            $rowMediaUse = $sqlMediaUse->fetch_assoc();
        }
        if($sqlMediaUse->num_rows <= 0) {
            $noUse = '&imgUse=no';
        } else { $noUse = ''; }
        
        ?>
        <?php if(!in_array($row['ext'], $valid_formats)): ?>
            <?php if($date <> ""): ?>
                <?php if(getMonthsFromImageNames($row['naam']) == $date): ?>
                    <a class="doc <?php echo ($sqlMediaUse->num_rows <= 0) ? 'no-use' : '' ?> <?php echo (isset($imgActief) && $sqlMediaUse->num_rows > 0) ? 'in-gebruik-' . $imgActief : 'niet-gebruik-' . $imgActief ?>" data-fancybox data-small-btn="true" data-type="iframe" href="/cms/php/media_bewerken.php?media_id=<?=$row['id'];?><?=$noUse;?>" href="javascript:;">
                        <i class="fas fa-file"></i>  
                        <p><?=$row['naam'];?>.<?=$row['ext'];?></p>  
                    </a>
                <?php endif; ?>
            <?php else: ?>
                <a class="doc <?php echo ($sqlMediaUse->num_rows <= 0) ? 'no-use' : '' ?> <?php echo (isset($imgActief) && $sqlMediaUse->num_rows > 0) ? 'in-gebruik-' . $imgActief : 'niet-gebruik-' . $imgActief ?>" data-fancybox data-small-btn="true" data-type="iframe" href="/cms/php/media_bewerken.php?media_id=<?=$row['id'];?><?=$noUse;?>" href="javascript:;">
                    <i class="fas fa-file"></i>  
                    <p><?=$row['naam'];?>.<?=$row['ext'];?></p>                                      
                </a>
            <?php endif; ?>
        <?php else: ?>
            <?php if($date <> ""): ?>
                <?php if(getMonthsFromImageNames($row['naam']) == $date): ?>
                    <a class="img <?php echo ($sqlMediaUse->num_rows <= 0) ? 'no-use' : '' ?> <?php echo (isset($imgActief) && $sqlMediaUse->num_rows > 0) ? 'in-gebruik-' . $imgActief : 'niet-gebruik-' . $imgActief ?>" data-fancybox data-small-btn="true" data-type="iframe" href="/cms/php/media_bewerken.php?media_id=<?=$row['id'];?><?=$noUse;?>" href="javascript:;">
                        <img src="/img/<?=$row['naam'];?>_tn.<?=$row['ext'];?>" alt="<?=$row['naam'];?>" onerror="handleImageError(this, <?=$row['id'];?>);">
                    </a>
                <?php endif; ?>
            <?php else: ?>
                <a class="img <?php echo ($sqlMediaUse->num_rows <= 0) ? 'no-use' : '' ?> <?php echo (isset($imgActief) && $sqlMediaUse->num_rows > 0) ? 'in-gebruik-' . $imgActief : 'niet-gebruik-' . $imgActief ?>" data-fancybox data-small-btn="true" data-type="iframe" href="/cms/php/media_bewerken.php?media_id=<?=$row['id'];?><?=$noUse;?>" href="javascript:;">
                    <img src="/img/<?=$row['naam'];?>_tn.<?=$row['ext'];?>" alt="<?=$row['naam'];?>" onerror="handleImageError(this, <?=$row['id'];?>);">
                </a>
            <?php endif; ?>
        <?php endif;
    }
}

if (isset($message)): ?>
    <div class="alert alert-error fancybox">
        <?php if (isset($message)) {
            foreach ($message as $msg) {
                printf("<p>%s", $msg);
            }
        } ?>
    </div>
<?php endif;

?>