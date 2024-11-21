<? // database connectie en inlogfuncties
// ===================================
include ("../../login/config.php");
include ('../../login/functions.php'); 

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
        <div class=\"picture-container\" style=\"padding-top: {$aspectratioDiv}%\">
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

$querydrag = $mysqli->query("SELECT * FROM sitework_img WHERE block_id = '".$_GET['id']."' AND img_taal = '".$_GET['img_taal']."' order by volgorde LIMIT 6") or die($mysqli->error.__LINE__);

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

        <script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery-3.5.1.min.js"></script>
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
        <div id="block-thums-container" class="<?=$_GET['overzicht'];?>">
            <?php
            while ($rowdrag = $querydrag->fetch_assoc()) { 
                $querydragImg = $mysqli->query("SELECT * FROM sitework_mediabibliotheek WHERE id = '".$rowdrag['naam']."'") or die($mysqli->error.__LINE__);
                $rowdragImg = $querydragImg->fetch_assoc()
            ?>
                <div>
                <?php img($url, $rowdragImg['naam'], $rowdragImg['ext'], '', '750', '750', '750');?>
                </div>
            <?php  } ?>
        </div>
    </body>
</html>
