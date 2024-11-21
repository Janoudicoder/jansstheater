<? // checken of men wel is ingelogd
// =================================
login_check_v2();
?>

<script>
// inline editen url van kanaal
// ===========================
	function showEdit(editableObj) {
		$(editableObj).css("background","#FFF");
	}

	function saveToDatabase(editableObj,column,id) {
		$(editableObj).css("background","#FFF url(editinplace/loaderIcon.gif) no-repeat right");
		$.ajax({
			url: "./editinplace/saveedit_social.php",
			type: "POST",
			data:'column='+column+'&editval='+editableObj.innerHTML+'&id='+id,
			success: function(data){
				$(editableObj).css("background","#FDFDFD");
			}
		});
	}

// kanaal verwijderen melding
// ===========================
    function ConfirmDeleteSoc() {
    return confirm('Weet u zeker dat u dit kanaal wilt verwijderen?'); 
    } 

</script>

<?php
// Social media
if($_POST['toevoegen'] == 1 && $_POST['naam']){

	$sql_ins = $mysqli->query("INSERT INTO sitework_socialmedia SET icon = '".$_POST['icon']."', url = '".$_POST['url']."',naam = '".$_POST['naam']."'") or die($mysqli->error.__LINE__);
	$melding = "Gegevens zijn opgeslagen"; 
	
	echo "
	<div class=\"alert alert-success\">
		Kanaal toegevoegd
	</div>
	";
}

// foutmelding
// ===========
if($_POST['toevoegen'] == 1 && !$_POST['naam']){ 
	echo "
		<div class=\"alert alert-error\">
			Voor een naam in
		</div>
	";
}

// item verwijderen
// ================
if ($_GET['delid']) {
	$mysqli->query("DELETE FROM sitework_socialmedia WHERE id = '".$_GET['delid']."' ") or die($mysqli->error.__LINE__);
	header('Location: maincms.php?page=website-instellingen');
}
?>

<?php
if(isset($_GET['footertaal']) && $_GET['footertaal'] != "") {
	$footerTekstTaal = $_GET['footertaal'];
} else {
	$footerTekstTaal = "nl";
}

// Footer teksten wijzigen
// ========================
if($_POST['opslaanFooter'] == 1) {

	$sql_updatecookie = $mysqli->query("UPDATE  sitework_website_settings SET 
												kolom_1     = '".str_replace('\r\n', '', $mysqli->real_escape_string($_POST['footer-kol-1'])) . "',
												kolom_2     = '".str_replace('\r\n', '', $mysqli->real_escape_string($_POST['footer-kol-2'])) . "',
												kolom_3     = '".str_replace('\r\n', '', $mysqli->real_escape_string($_POST['footer-kol-3'])) . "',
                                                kolom_4     = '".str_replace('\r\n', '', $mysqli->real_escape_string($_POST['footer-kol-4'])) . "'
                                                WHERE taal = '".$footerTekstTaal."'") or die($mysqli->error.__LINE__);													  
	
	$rowidcookie = $mysqli->insert_id;  
    $melding = "Wijzigingen zijn opgeslagen";
    header('Location: ?page=website-instellingen&opgeslagen=ja');	
}
if($_GET['opgeslagen'] == "ja"){
    echo "
    <div class=\"alert alert-success\">
        Wijzigingen zijn opgeslagen
    </div>
    ";
};

// footer tekst ophalen
// ==========================
$sqlWebsiteSettings = $mysqli->query("SELECT * FROM sitework_website_settings WHERE taal = '$footerTekstTaal'") or die($mysqli->error.__LINE__);
$rowWebsiteSettings = $sqlWebsiteSettings->fetch_assoc(); ?>

<div class="box-container">
    <div class="box box-1-3 title lg-box-full">
        <h3><span class="icon fab fa-elementor"></span>Website instellingen</h3>
		<div class="info full mt-25"><span class="far fa-info-circle"></span>&nbsp;&nbsp;</i>
			Op deze pagina kunt u de social media kanalen toevoegen die u op uw website beschikbaar wilt hebben.
			Bestaande links/url`s kunt u aanpassen door de link aan te klikken. De link wordt dan automatisch opgeslagen zodra u buiten het vakje klikt.<br><br>
			Als u uw socialmedia link van uw twitterprofiel de naam: <strong>Twitter</strong> geeft. wordt dit ook weergeven als de website op Twitter/X gedeeld word.<br>
			Dan moet de link er wel zo uitzien: https://twitter.com/gebruikersnaam
		</div>
		<div class="info full mt-25"><span class="far fa-info-circle"></span>&nbsp;&nbsp;</i>
			De footerkolommen die leeg worden gelaten zijn daarmee ook niet zichtbaar op de website.
		</div>
    </div>
    <div class="box box-2-3 lg-box-full">
		<h3><span class="icon fas fa-globe"></span>Social media</h3>
		<a href="" class="clickme btn fl-right nieuw">Nieuw kanaal</a>
		<div class="toggle-box">
			<form action="<?=$PHP_SELF; ?>" method="post" enctype="multipart/form-data" name="form1">
				<select class="inputveld mr-10 dropdown extra-pad" name="icon">
					<option value="">Social media</option>
					<option value="fa-facebook-f">Facebook</option>
					<option value="fa-twitter">Twitter</option>
					<option value="fa-google-plus">Google+</option>
					<option value="fa-pinterest-p">Pinterest</option>
					<option value="fa-youtube">Youtube</option>
					<option value="fa-linkedin">LinkedIn</option>
					<option value="fa-instagram">Instagram</option>
					<option value="fa-whatsapp">Whatsapp</option>
					<option value="fa-rss">Rss</option>
				</select>
				<input type="text" tabindex="1" name="url"  class="inputveld mr-10 breed-40" id="url"  placeholder="Link naar het kanaal" />
				<input type="text" tabindex="1" name="naam"  class="inputveld mr-10" id="naam"  placeholder="Naam/omschrijving" />
				<input type="hidden" name="toevoegen" value="1">
				<button name="button3" type="submit" class="btn fl-left nieuw" id="button">Toevoegen</button>
			</form>
		</div>
		<div class="content-container">

			<div class="row socialmedia type">
				<div class="col">icon</div>
				<div class="col">link naar kanaal</div>
				<div class="col sm-mob-hide">naam</div>
				<div class="col center">verwijderen</div>
			</div>

			<?php
				$sql = $mysqli->query("SELECT * FROM sitework_socialmedia LIMIT 20") or die($mysqli->error.__LINE__);
				$rows = $sql->num_rows;
				while ($row = $sql->fetch_assoc()){ ?>
				
				<div class="row socialmedia">
					<div class="col"><i class="fab <?=$row['icon'];  ?> fa-lg"></i></div>
					<div class="col" contenteditable="true" onBlur="saveToDatabase(this,'url','<?php echo $row["id"]; ?>')" onClick="showEdit(this);"><? echo substr($row['url'],0,100); ?></div>
					<div class="col sm-mob-hide" contenteditable="true" onBlur="saveToDatabase(this,'naam','<?php echo $row["id"]; ?>')" onClick="showEdit(this);"><? echo $row['naam']; ?></div>
					<div class="col center"><a class="delete" href="<?=$PHP_SELF; ?>?page=website-instellingen&delid=<?=$row['id']; ?>" onclick='return ConfirmDeleteSoc();' title="Verwijderen"><span class="far fa-trash"></span></a></div>
				</div>

			<? } ?>

		</div>

	</div>
	<?php if($rowinstellingen['meertaligheid'] == 'ja'): ?>
		<div id="taalswitch">
			<?php footermenu($footerTekstTaal); ?>
		</div>
	<?php endif; ?>
	<div class="box box-full lg-box-full">
	    <h3><span class="icon fas fa-edit"></span>Footer</h3>
		<!-- replacemend -->

		<div class="content-container mt-0">   
		<div style="display: flex; align-items: center; gap: 8px;">
			<p>icon-phone</p>
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<g clip-path="url(#clip0_18_284)">
				<path d="M21 16.42V19.956C21.0001 20.2092 20.9042 20.453 20.7316 20.6382C20.559 20.8234 20.3226 20.9363 20.07 20.954C19.633 20.984 19.276 21 19 21C10.163 21 3 13.837 3 5C3 4.724 3.015 4.367 3.046 3.93C3.06372 3.67744 3.17658 3.44101 3.3618 3.26841C3.54703 3.09581 3.79082 2.99989 4.044 3H7.58C7.70404 2.99987 7.8237 3.04586 7.91573 3.12902C8.00776 3.21218 8.0656 3.32658 8.078 3.45C8.101 3.68 8.122 3.863 8.142 4.002C8.34073 5.38892 8.748 6.73783 9.35 8.003C9.445 8.203 9.383 8.442 9.203 8.57L7.045 10.112C8.36445 13.1865 10.8145 15.6365 13.889 16.956L15.429 14.802C15.4919 14.714 15.5838 14.6509 15.6885 14.6237C15.7932 14.5964 15.9042 14.6068 16.002 14.653C17.267 15.2539 18.6156 15.6601 20.002 15.858C20.141 15.878 20.324 15.9 20.552 15.922C20.6752 15.9346 20.7894 15.9926 20.8724 16.0846C20.9553 16.1766 21.0012 16.2961 21.001 16.42H21Z" fill="#FFA3A3"/>
				</g>
				<defs>
				<clipPath id="clip0_18_284">
					<rect width="24" height="24" fill="white"/>
				</clipPath>
				</defs>
			</svg>
			<p>icon-mail</p>
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<g clip-path="url(#clip0_18_287)">
				<path d="M3 3H21C21.2652 3 21.5196 3.10536 21.7071 3.29289C21.8946 3.48043 22 3.73478 22 4V20C22 20.2652 21.8946 20.5196 21.7071 20.7071C21.5196 20.8946 21.2652 21 21 21H3C2.73478 21 2.48043 20.8946 2.29289 20.7071C2.10536 20.5196 2 20.2652 2 20V4C2 3.73478 2.10536 3.48043 2.29289 3.29289C2.48043 3.10536 2.73478 3 3 3ZM12.06 11.683L5.648 6.238L4.353 7.762L12.073 14.317L19.654 7.757L18.346 6.244L12.061 11.683H12.06Z" fill="#FFA3A3"/>
				</g>
				<defs>
				<clipPath id="clip0_18_287">
				<rect width="24" height="24" fill="white"/>
				</clipPath>
				</defs>
				</svg>

			<p>icon-location</p>
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<g clip-path="url(#clip0_18_290)">
				<path d="M18.364 17.3639L12 23.7279L5.636 17.3639C4.37734 16.1052 3.52019 14.5016 3.17293 12.7558C2.82567 11.0099 3.00391 9.20035 3.6851 7.55582C4.36629 5.91129 5.51984 4.50569 6.99988 3.51677C8.47992 2.52784 10.22 2 12 2C13.78 2 15.5201 2.52784 17.0001 3.51677C18.4802 4.50569 19.6337 5.91129 20.3149 7.55582C20.9961 9.20035 21.1743 11.0099 20.8271 12.7558C20.4798 14.5016 19.6227 16.1052 18.364 17.3639ZM12 12.9999C12.5304 12.9999 13.0391 12.7892 13.4142 12.4141C13.7893 12.0391 14 11.5304 14 10.9999C14 10.4695 13.7893 9.96078 13.4142 9.58571C13.0391 9.21064 12.5304 8.99992 12 8.99992C11.4696 8.99992 10.9609 9.21064 10.5858 9.58571C10.2107 9.96078 10 10.4695 10 10.9999C10 11.5304 10.2107 12.0391 10.5858 12.4141C10.9609 12.7892 11.4696 12.9999 12 12.9999Z" fill="#FFA3A3"/>
				</g>
				<defs>
				<clipPath id="clip0_18_290">
				<rect width="24" height="24" fill="white"/>
				</clipPath>
				</defs>
				</svg>

			</div>

            <form id="footer-cols" action="<? echo $PHP_SELF ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">    
                    <label for="kolommen">Tekst</label>
					<div class="footer-kolommen">

						<div id="footer-kol-editor-1" style="min-height:400px;max-height:800px" class="inputveld invoer sitework-editor">
							<?php echo $rowWebsiteSettings['kolom_1']; ?>
						</div>

						<div id="footer-kol-editor-2" style="min-height:400px;max-height:800px" class="inputveld invoer sitework-editor">
							<?php echo $rowWebsiteSettings['kolom_2']; ?>
						</div>

						<div id="footer-kol-editor-3" style="min-height:400px;max-height:800px" class="inputveld invoer sitework-editor">
							<?php echo $rowWebsiteSettings['kolom_3']; ?>
						</div>

						<div id="footer-kol-editor-4" style="min-height:400px;max-height:800px" class="inputveld invoer sitework-editor">
							<?php echo $rowWebsiteSettings['kolom_4']; ?>
						</div>

					</div>
                </div>

				<input type="hidden" id="footer-kol-1" name="footer-kol-1">
				<input type="hidden" id="footer-kol-2" name="footer-kol-2">
				<input type="hidden" id="footer-kol-3" name="footer-kol-3">
				<input type="hidden" id="footer-kol-4" name="footer-kol-4">

                <button class="btn fl-left save" name="opslaan" type="submit">Opslaan</button>
				<input type="hidden" name="footer_taal" value="<?=$footerTekstTaal;?>">
                <input type="hidden" name="opslaanFooter" value="1" >
            </form>
        </div>
    </div>
</div>
<?php

?>
<?php 
    include './richtexteditor/footer-editor.php';
?>
<script>
    document.getElementById('footer-cols').addEventListener('submit', function(event) {
		save_and_strip('footer-kol-1', editorFooter1);
		save_and_strip('footer-kol-2', editorFooter2);
		save_and_strip('footer-kol-3', editorFooter3);
		save_and_strip('footer-kol-4', editorFooter4);
    });
</script>