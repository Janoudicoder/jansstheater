<?php ob_start();
// database connectie en inlogfuncties
// ===================================
require("../login/config.php");
include '../login/functions.php';
session_start();

// checken of men wel is ingelogd
// ==============================
login_check_v2();

$realworks = $mysqli->query("SELECT * FROM sitework_realworks WHERE id = '1'") or die($mysqli->error.__LINE__);
$rowrealworks = $realworks->fetch_assoc();

if(isset($_POST['apikey'])) {
    $id = '1';
    $apikey = $_POST['apikey'];

    $query = "UPDATE sitework_realworks SET rw_api = '".$mysqli->real_escape_string($apikey)."' WHERE id = " . $id;
    $resultdrag = $mysqli->query($query) or die($mysqli->error.__LINE__);
    
    echo "
    <div class=\"alert alert-success fancybox\">
        Realworks API-Sleutel is opgeslagen
    </div>";

    $realworks = $mysqli->query("SELECT * FROM sitework_realworks WHERE id = '1'") or die($mysqli->error.__LINE__);
    $rowrealworks = $realworks->fetch_assoc();
}
?>

<TITLE>SiteWork CMS afbeelding upload</TITLE>
<meta charset="UTF-8" />
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/css/stylesheet.css">
<link rel='stylesheet' type='text/css' href='<?php echo $url; ?>/cms/css/branding-stylesheet.php' />
<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/cms/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/font-awesome/css/all.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/cms/css/themify-icons.css">

<script type="text/javascript" src="<?php echo $url; ?>/cms/jquery/files/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery-ui-1-12-1.min.js"></script>
<script>
	// Alert voor opgeslagen
    // ======================
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove(); 
        });
    }, 4000);

    $(".alert").on("click", function () {
        $( this ).fadeTo( "slow", 0 );
	});
</script>

<div class="fancybox-wrap" style="height:100vh;width:100vw">
    <div class="box box-2-3 md-box-full in-het-midden">
        <h3 class="!full"><span class="icon fas fa-house-user"></span> Verbind hier met Realworks</h3>
        <form action="<?=$PHP_SELF;?>" method="post">
            <div class="form-group">
                <label>Realworks API-sleutel:</label>
                <input 
                    type="text" 
                    name="apikey"
                    id="apikey"
                    class="inputveld invoer"
                    placeholder="rwauth ..."
                    value="<?=$rowrealworks['rw_api'];?>"
                />
            </div>
            <button type="submit" class="btn save">Opslaan</button>
        </form>
    </div>
</div>