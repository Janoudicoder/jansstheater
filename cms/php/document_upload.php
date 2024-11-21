<? ob_start();
// database connectie en inlogfuncties
// ======================================
include ("../login/config.php");
include ("../login/functions.php");
session_start();

// checken of men wel is ingelogd
// ==============================
login_check_v2();

$vervang = array(" ", "\'");
$door   = array("-", "");

str_replace(array('\'', '"'), '', $UserInput); 
$bestandsnaams = str_replace($vervang, $door, $_FILES['doc1']['name']);

$doc_1_naam = $_POST['doc_1_naam'];
$doc_1_doc = rand(10000,50000) . "-" .$bestandsnaams;

echo $_FILES['doc1']['size'][0];

// document toevoegen
// ==================
if($_POST['toevoegen'] == 1){

	if (!$_POST['doc_1_naam'] OR $_FILES['doc1']['size'] == 0) {
		$error = "Selecteer een document en geef een naam";
		if (!$_POST['doc_1_naam']) { 
			$error_docnaam = 'foutveld'; 
		}
		if ($_FILES['doc1']['size'] == 0) { 
			$error_docfile = 'foutveld'; 
		}
	} else {
	// mappen open zetten
	// ==================
	ftp_site($ftpstream,$pad_doc_open);
	// documenten toevoegen
	// ====================
	if(move_uploaded_file($_FILES['doc1']['tmp_name'], "../../doc/". $doc_1_doc)){
							
		// dan de foto daadwerken wegschrijven
		// ===================================
		if(!$volgorde){
			$volgorde = '0';
		}
		$sql_insert = $mysqli->query("INSERT 	sitework_doc set naam = '$doc_1_naam',
												cms_id = '".$_GET['id']."',
												volgorde = '$volgorde',
												url = '$doc_1_doc' ") 
		or die($mysqli->error.__LINE__);
			
		$melding = "Het bestand is toegevoegd"; 
		echo "
		<div class=\"alert alert-success fancybox\">
			Het bestand is toegevoegd
		</div>
	";
	}
	else{ 
		$melding = "Het bestand is NIET toegevoegd"; 
		echo "
		<div class=\"alert alert-error fancybox\">
		Het bestand is NIET toegevoegd
		</div>
	";
	}

ftp_site($ftpstream,$pad_doc_dicht);
} }

// document verwijderen
// ====================
	if($_GET['delete_id'] <> ""){

	$sql2 = $mysqli->query("SELECT * FROM sitework_doc WHERE id = '".$_GET['delete_id']."' ") or die($mysqli->error.__LINE__);
	$row2 = $sql2->fetch_assoc();	
		
	ftp_site($ftpstream,$pad_doc_open);
	unlink('../../doc/'.$row2['url']);
	ftp_site($ftpstream,$pad_doc_dicht);
		
	$sql_del = $mysqli->query("DELETE FROM sitework_doc WHERE id = '".$_GET['delete_id']."' ") or die($mysqli->error.__LINE__);
	header('Location: document_upload.php?id='.$_GET['id'].'');

} ?>

<TITLE>SiteWork CMS document upload</TITLE>
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
<script type="text/javascript">
function ConfirmDelete() { 
	return confirm('Weet u zeker dat u dit document wilt verwijderen?'); 
} 
// volgorde van documenten verslepen en opslaan
// ============================================
    $(function() {
        $("#content-doc-Left ul").sortable({ opacity: 0.6, cursor: 'move', update: function() {
            var order = $(this).sortable("serialize") + '&action=updateRecordsListings'; 
            $.post("../dragdrop/update_documenten_DB.php", order, function(theResponse){
                $("#contentRight").html(theResponse);
            }); 															 
        }								  
        });
    });
</script>	

<div class="fancybox-wrap">
	<div class="box-container">
  		<div class="box box-1-2 md-box-full kaal">
		  	<div class="info full fancy">
				<span class="far fa-info-circle"></span>&nbsp;&nbsp;U kunt de volgorde van de documenten zelf bepalen door ze te verslepen.
			</div>
    	
			<div id="content-doc-Left">
				<ul>
					<? $querydrag = $mysqli->query("SELECT * FROM sitework_doc WHERE cms_id = '".$_GET['id']."' order by volgorde") or die($mysqli->error.__LINE__);
					if ($querydrag->num_rows < 1) { echo "<div class=\"middentekst\">Er zijn nog geen documenten geplaatst ...</div>";}
					
					while($rowdrag = $querydrag->fetch_assoc()){ ?>
						
					<li id="recordsArray_<?php echo $rowdrag['id']; ?>">
						<div class="sort-wrap">
							<a href="document_bewerken.php?id=<?=$_GET['id']; ?>&doc_id=<?=$rowdrag['id']; ?>">
								<img src="../images/pdf-logo.png" border="0" width="100%"  />
							</a>    
							<span class="soort-image"><? echo substr($rowdrag['naam'],0,20); ?>...</span>
							<a class="delete-image" href="?id=<?=$_GET['id']; ?>&delete_id=<?=$rowdrag['id']; ?>" onclick='return ConfirmDelete();'>
								<span class="ti-trash"></span>
							</a>
						</div>
					</li>
					
					<?  } ?>		      
				</ul>
			</div>	
		</div>
		<div class="box box-1-2 md-box-full">
		<h3><span class="icon ti-files"></span>Documenten uploaden</h3>
		<? if ($error){ ?><span class="error"><? echo $error; ?></span><? } ?>
		<form action="<? echo $PHP_SELF ?>?id=<? echo $_GET['id'] ?>" method="POST" enctype="multipart/form-data">      
			<div class="form-group">	
				<label for="doc1">Bestand</label><input type="file" name="doc1" class="inputveld invoer media<? if ($error_docfile){ echo ' foutveld'; } ?>" id="files">  
			</div>
			<div class="form-group">
				<label for="doc_1_naam">Naam</label><input name="doc_1_naam" value="<? echo $_POST['doc_1_naam']; ?>" type="text" id="doc_1_naam"  class="inputveld invoer<? if ($error_docnaam){ echo ' foutveld'; } ?>" placeholder="Naam of beschrijving van het document op de website" maxlength="60"  />     
			</div>
			
			<input type="hidden" name="afb" value="<? echo $_GET['id'] ?>" >
			<input type="hidden" name="toevoegen" value="1">
			<button name="opslaan" class="btn fl-left upload" type="submit">Uploaden</button>
		</form>
	</div>
</div>