<? ob_start();
// database connectie en inlogfuncties
// ===================================
include ("../login/config.php");
include ('../login/functions.php');
session_start();

// checken of men wel is ingelogd
// ==============================
login_check_v2();

// document wijzigen
// =================
if($_POST['opslaan'] == 1){
		
				$sql_insert = $mysqli->query("UPDATE sitework_doc SET naam = '".$mysqli->real_escape_string($_POST['naam'])."'				  
				WHERE cms_id = '".$_GET['id']."' and id = '".$_GET['doc_id']."' ") or die($mysqli->error.__LINE__);
														  
				$rowid = $mysqli->insert_id;  
				$melding = "Wijzigingen zijn opgeslagen";
				echo "
				<div class=\"alert alert-success fancybox\">
				Wijzigingen zijn opgeslagen
				</div>";

}

// document ophalen
// ================
$sqldoc = $mysqli->query("SELECT * FROM sitework_doc WHERE cms_id = '".$_GET['id']."' and id = '".$_GET['doc_id']."' ") or die($mysqli->error.__LINE__);
$rowdoc = $sqldoc->fetch_assoc();
?>

<TITLE>SiteWork CMS document bewerken</TITLE>
<meta charset="UTF-8"/>
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/css/stylesheet.min.css">
<link rel='stylesheet' type='text/css' href='<? echo $url; ?>/cms/css/branding-stylesheet.php' />
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/font-awesome/css/all.min.css">
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/css/themify-icons.css">

<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery-3.5.1.min.js"></script>
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
<div class="fancybox-wrap">
	<div class="box-container">
  		<div class="box box-1-2 md-box-full">
    	<h3><span class="icon ti-files"></span>Document(en)</h3>

		<div class="afbeelding">
			<iframe src="../../doc/<? echo $rowdoc['url']; ?>" width="100%" height="300" frameborder="0"></iframe>
		</div>

		<div class="afbeelding-info">
			<div class="row">
				<span class="fat">Bestandsnaam: </span><? $bestandje = $rowdoc['url']; echo $bestandje; ?>
			</div>
			<div class="row">
				<span class="fat">Omschrijving op website: </span><? echo $rowdoc['naam']; ?>
			</div>
			<div class="row">
				<span class="fat">Type: </span><? $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
					foreach (glob("../../doc/".$bestandje) as $filename) { echo finfo_file($finfo, $filename) . "\n"; } finfo_close($finfo); ?>
			</div>
			<div class="row">
				<span class="fat">Bestandsgrootte: </span><? $filename = '../../doc/'.$bestandje; echo round(filesize($filename)/1024,2) . ' Kb';?>
			</div>
		</div>
	</div>
	<div class="box box-1-2 md-box-full">
		<h3><span class="icon ti-files"></span>Eigenschappen</h3>
		<form action="<? echo $PHP_SELF ?>?id=<?=$_GET['id']; ?>&doc_id=<?=$_GET['doc_id'];   ?>" method="POST" >
			<div class="form-group">	
				<label for="naam">Naam</label><input type="text" name="naam" class="inputveld invoer" placeholder="Naam of beschrijving van het document op de website" value="<? echo $rowdoc['naam']; ?>" maxlength="150" />
			</div>
			<input type="hidden" name="opslaan" value="1">
			<a class="btn fl-left back mr-10" href="document_upload.php?id=<?=$_GET['id']; ?>">Terug</a>
			<button name="opslaanbut" class="btn fl-left save" type="submit">Wijzigingen opslaan</button>
		</form>

	</div>
</div>
