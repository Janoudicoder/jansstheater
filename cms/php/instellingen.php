<?php // checken of men wel is ingelogd
// ====================================
include ('ftp/config.php');

login_check_v2();

// bijwerken instellingen
// ======================
if($_GET['success'] == '1') {
	echo "<div class=\"alert alert-success\">";
         echo "Gegevens opgeslagen";
     echo "</div>";
}

if($_POST['bijwerken'] == 0){
	// echo '<pre>' . var_dump($_POST) . '</pre>';
	// echo '<pre>' . var_dump($_FILES) . '</pre>';
}

if($_POST['bijwerken'] == 1){
	if ($rowuser['id'] == "1") { 
		if ($rowinstellingen['livegezet'] == "nee") {
		    if($_POST['livegezet_datum'] != "" && $_POST['livegezet_datum'] != '0000-00-00'){
                $sitelive = "livegezet = 'ja',";
                $sitelivedatum = "livegezet_datum = '".$_POST['livegezet_datum']."',";
            } else {
                $sitelive = "livegezet = 'nee',";
                $sitelivedatum = "livegezet_datum = '0000-00-00',";
            }

		} else {
			$sitelive = "livegezet = 'ja',";
			$sitelivedatum = "livegezet_datum = '".$rowinstellingen['livegezet_datum']."',";
		}
		$afgeschermde_instellingen = "
		weburl = '".$_POST['weburl']."',
		domeinnaam = '".$_POST['domeinnaam']."',
		cmspakket = '".$_POST['cmspakket']."',
		meertaligheid = '".$_POST['meertaligheid']."',
		branding = '".$_POST['branding']."',
		afbeeldingopties = '".$_POST['afbeeldingopties']."',
		hoofdtitelveld = '".$_POST['hoofdtitelveld']."',
		subtitelveld = '".$_POST['subtitelveld']."',
		linkveld = '".$_POST['linkveld']."',
		export = '".$_POST['exportmogelijkheid']."',
		formuliermodule = '".$_POST['formuliermodule']."',
		blockbuilder = '".$_POST['blockbuilder']."',
		makelaar = '".$_POST['makelaar']."',
		realworksnieuwbouw = '".$_POST['realworksnieuwbouw']."',
		realworksbog = '".$_POST['realworksbog']."',
		realworkswonen = '".$_POST['realworkswonen']."',
		hcaptcha_secretkey = '".$_POST['hcaptcha_secretkey']."',
		api_key_verificatie = '".$_POST['api_key']."',
		";

		if($_POST['makelaar'] == 'nee') {
			$sql_kenmerken_update = $mysqli->query("UPDATE sitework_kenmerken SET actief = '0' WHERE (kenmerk = 'woningoverzicht' OR kenmerk = 'bedrijfspandenoverzicht' OR kenmerk = 'nieuwbouwoverzicht')") or die($mysqli->error.__LINE__);
		} elseif($_POST['makelaar'] == 'ja') {

			if($_POST['realworkswonen'] == 'ja') {
				$sql_kenmerken_update = $mysqli->query("UPDATE sitework_kenmerken SET actief = '1' WHERE kenmerk = 'woningoverzicht'") or die($mysqli->error.__LINE__);
			} elseif($_POST['realworkswonen'] == 'nee') {
				$sql_kenmerken_update = $mysqli->query("UPDATE sitework_kenmerken SET actief = '0' WHERE kenmerk = 'woningoverzicht'") or die($mysqli->error.__LINE__);
			}
			
			if($_POST['realworksbog'] == 'ja') {
				$sql_kenmerken_update = $mysqli->query("UPDATE sitework_kenmerken SET actief = '1' WHERE kenmerk = 'bedrijfspandenoverzicht'") or die($mysqli->error.__LINE__);
			} elseif($_POST['realworksbog'] == 'nee') {
				$sql_kenmerken_update = $mysqli->query("UPDATE sitework_kenmerken SET actief = '0' WHERE kenmerk = 'bedrijfspandenoverzicht'") or die($mysqli->error.__LINE__);
			}
			
			if($_POST['realworksnieuwbouw'] == 'ja') {
				$sql_kenmerken_update = $mysqli->query("UPDATE sitework_kenmerken SET actief = '1' WHERE kenmerk = 'nieuwbouwoverzicht'") or die($mysqli->error.__LINE__);
			} elseif($_POST['realworksnieuwbouw'] == 'nee') {
				$sql_kenmerken_update = $mysqli->query("UPDATE sitework_kenmerken SET actief = '0' WHERE kenmerk = 'nieuwbouwoverzicht'") or die($mysqli->error.__LINE__);
			}
		}
	} else {
		$afgeschermde_instellingen = "";
	}

	if($_POST['favicon_upload'] == 'true' && isset($_FILES['favicon']) && $_FILES['favicon']['name'] <> "") {
		$favicon_path = $pad_favicon; // Adjust this path if needed

		ftp_site($ftpstream, $pad_favicon_open);
		// Get uploaded file information
		$favicon_tmp_name = $_FILES['favicon']['tmp_name'];
		$original_name = $_FILES['favicon']['name'];

		// Validate file extension
		$extension = pathinfo($original_name, PATHINFO_EXTENSION);
		if (strtoupper($extension) !== 'ICO') {
			$melding =  'Invalid file format. '. var_dump($_FILES['favicon']) .' Please upload a .ico file.';
			exit;
		}

		// Create a new filename if needed (prevents overwriting)
		$newFilename = "favicon.ico";

		// Rename uploaded file to sanitized filename
		if (!move_uploaded_file($favicon_tmp_name, $newFilename)) {
			$melding = "Error renaming uploaded file.";
			exit;
		}

		// Navigate to the favicon directory on the FTP server
		if (!ftp_chdir($ftpstream, $favicon_path)) {
			$melding = "Failed to navigate to favicon directory.";
			exit;
		}

		// Upload the renamed favicon in binary mode
		if (!ftp_put($ftpstream, $newFilename, $newFilename, FTP_BINARY)) {
			$melding = "Favicon upload failed.";
		} else {
			$melding = "Favicon uploaded successfully!";
		}

		// Close the FTP connection when finished
		ftp_site($ftpstream, $pad_favicon_dicht);
		ftp_close($ftpstream);
	}

	if($_POST['logo_upload'] == 'true' && isset($_FILES['logo']) && $_FILES['logo']['name'] <> "") {
		$logo_path = $pad_logo; // Adjust this path if needed

		ftp_site($ftpstream, $pad_logo_open);
		// Get uploaded file information
		$logo_tmp_name = $_FILES['logo']['tmp_name'];
		$original_logo_name = $_FILES['logo']['name'];

		// Validate file extension
		$extension_logo = pathinfo($original_logo_name, PATHINFO_EXTENSION);
		if (strtoupper($extension_logo) !== 'PNG') {
			$melding =  'Invalid file format. '. var_dump($_FILES['logo']) .' Please upload a .png file.';
			exit;
		}

		// Create a new filename if needed (prevents overwriting)
		$newFilename_logo = "logo.png";

		// Rename uploaded file to sanitized filename
		if (!move_uploaded_file($logo_tmp_name, $newFilename_logo)) {
			$melding = "Error renaming uploaded file.";
			exit;
		}

		// Navigate to the logo directory on the FTP server
		if (!ftp_chdir($ftpstream, $logo_path)) {
			$melding = "Failed to navigate to logo directory.";
			exit;
		}

		// Upload the renamed logo in binary mode
		if (!ftp_put($ftpstream, $newFilename_logo, $newFilename_logo, FTP_BINARY)) {
			$melding = "logo upload failed.";
		} else {
			$melding = "logo uploaded successfully!";
		}

		// Close the FTP connection when finished
		ftp_site($ftpstream, $pad_logo_dicht);
		ftp_close($ftpstream);
	}

	if($_POST['rw_afdelingscode']):
		$sql_rw_update = $mysqli->query("UPDATE sitework_realworks SET rw_afdelingscode = '".$_POST['rw_afdelingscode']."' WHERE id = '1'") or die($mysqli->error.__LINE__);
	endif;

	$sql_update = $mysqli->query("UPDATE 	sitework_settings SET offline = '".$_POST['offline']."', 
											$afgeschermde_instellingen
											$sitelive
											$sitelivedatum
											naamwebsite = '".$_POST['naamwebsite']."',
											websiteemail = '".$_POST['websiteemail']."',
											websitetelnr = '".$_POST['websitetelnr']."',
											uanummer = '".$_POST['uanummer']."',
											gmapskey = '".$_POST['gmapskey']."',
											website_beveiliging = '".$_POST['website_beveiliging']."',
											hcaptcha_clientkey = '".$_POST['hcaptcha_clientkey']."',
											recaptcha_clientkey = '".$_POST['recaptcha_clientkey']."',
											recaptcha_secretkey = '".$_POST['recaptcha_secretkey']."',
											offline_tekst = '".$_POST['offline_tekst']."' WHERE id = '1'") or die($mysqli->error.__LINE__);
									  
	$melding = "De wijzigingen zijn opgeslagen";
	$opgeslagen = "ja";
	header('Location: ?page=instellingen&success=1');

}

if (isset($_POST['taal_setting']) && !empty($_POST['taal_setting']) && $_POST['taal_soort'] == 'keuze') {
    $nieuwe_taal = $_POST['taal_setting'];

    foreach ($nieuwe_taal as $languageCode) { // Treat the single element as an array
		$query = "UPDATE sitework_taal SET actief = '1' WHERE taalkort = '$languageCode'";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);  
	}

    header('Location: ?page=instellingen&taal_nieuw=1#talen');
}

if (isset($_POST['taal_verwijder']) && !empty($_POST['taal_verwijder']) && $_POST['taal_soort'] == 'huidig') {
    $verwijder_taal = $_POST['taal_verwijder'];

    foreach ($verwijder_taal as $languageCode) { // Treat the single element as an array
		$query = "UPDATE sitework_taal SET actief = '0' WHERE taalkort = '$languageCode'";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);  
	}

    header('Location: ?page=instellingen&taal_nieuw=0#talen');
}

// ophalen instellingen
// ====================
$sql = $mysqli->query("SELECT * FROM sitework_settings WHERE id = '1' ") or die($mysqli->error.__LINE__);
$row = $sql->fetch_assoc(); 

// ophalen Realworks
// ====================
$sqlRW = $mysqli->query("SELECT * FROM sitework_realworks WHERE id = '1' ") or die($mysqli->error.__LINE__);
$rowRW = $sqlRW->fetch_assoc(); 
?>

<script>
	$(function() {
		$("#huidige_talen .huidig").sortable({
			opacity: 0.6,
			cursor: 'move',
			update: function(event, ui) {
				var order = $(this).sortable("serialize") + '&action=taalPositie';
				$.post("./dragdrop/update_taal_positie.php", order, function(theResponse) {
				    $("#talen").append(theResponse);
				});
			}
		});
	});
</script>

<div class="box-container" data-sticky_parent>
	<div class="box box-2-3 lg-box-full">
		<? if ($rowuser['id'] == "1") { ?>
			<h3><span class="icon fas fa-cog"></span>Afgeschermde instellingen</h3>
			<div class="content-container mt-0 mb-20">
		<? } ?>
				<form action="<?=$PHP_SELF; ?>" method="POST" enctype="multipart/form-data">	

					<? if ($rowuser['id'] == "1") { ?>
						<div class="form-group">
							<label for="weburl">Website url</label><input type="text" name="weburl" class="inputveld invoer<? if ($row['weburl']) { echo ' small-80';} ?>" placeholder="Voer hier de gehele (tijdelijke) url van de website in" value="<? echo $row['weburl']; ?>" <? if ($row['weburl']) { echo 'readonly'; } ?> />
							<? if ($row['weburl']) { ?> <a class="btn fl-left arrow browse size-20" href="?page=update_url">Update url</a><? } ?>
						</div>
						<div class="form-group">
							<label for="domeinnaam">Domeinnaam</label><input type="text" name="domeinnaam" class="inputveld invoer" placeholder="Voer de domeinnaam in" value="<? echo $row['domeinnaam']; ?>" />
						</div>
						<? if ($rowinstellingen['livegezet'] == "nee") { ?>
							<div class="form-group">
								<label for="livegezet_datum">Website livegezet</label>
								<div class="inputveld invoer radio">
									<input name="livegezet_datum" type="radio" id="livegezet_nee" class="radio-button" value="0000-00-00" <? if ($row['livegezet_datum'] == "0000-00-00") { echo "checked"; } ?>><label for="livegezet_nee">nee</label>
									<input name="livegezet_datum" type="radio" id="livegezet_ja" class="radio-button" value="<? echo date("Y-m-d");?>"><label for="livegezet_ja">ja</label>
								</div>
							</div>
						<? } ?>

						<div class="form-group">
							<label for="cmspakket">CMS pakket</label>
							<select name="cmspakket" class="inputveld invoer dropdown">
								<option value="standaard" <?php echo ($rowinstellingen['cmspakket'] == 'standaard') ? 'selected' : '' ?>>Standaard</option>
								<option value="deluxe" <?php echo ($rowinstellingen['cmspakket'] == 'deluxe') ? 'selected' : '' ?>>Deluxe</option>
								<option value="premium" <?php echo ($rowinstellingen['cmspakket'] == 'premium') ? 'selected' : '' ?>>Premium</option>
							</select>
						</div>

						<div class="form-group api_key">
							<label for="api_key">API beveiliging sleutel</label>
							<input id="api_key" class="inputveld invoer" autocomplete="off" placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx" <?php echo ($rowinstellingen['api_key_verificatie'] != "") ? 'type="password"' : 'type="text"' ?> value="<?=$rowinstellingen['api_key_verificatie'];?>" name="api_key">
							<?php if($rowinstellingen['api_key_verificatie'] != ""){ echo '<span id="checkPass" onclick="showPass()"><i id="eye" class="fa fa-eye"></i></span>'; } ?>
						</div>

						<div class="form-group">
							<label for="branding">Branding</label>
							<select name="branding" class="inputveld invoer dropdown">
								<option value="sitework" <?php echo ($rowinstellingen['branding'] == 'sitework') ? 'selected' : '' ?>>sitework</option>
								<option value="reclamemakers" <?php echo ($rowinstellingen['branding'] == 'reclamemakers') ? 'selected' : '' ?>>reclamemakers</option>
								<option value="puremotion" <?php echo ($rowinstellingen['branding'] == 'puremotion') ? 'selected' : '' ?>>puremotion</option>
								<option value="oviiontwerp" <?php echo ($rowinstellingen['branding'] == 'oviiontwerp') ? 'selected' : '' ?>>oviiontwerp</option>			
							</select>
						</div>

						<div class="form-group">    
								<label for="meertaligheid">Website meertalig</label>
							<div class="inputveld invoer radio">
							<input name="meertaligheid" type="radio" id="meertalig_nee" class="radio-button" value="nee" <? if ($row['meertaligheid'] == "nee") { echo "checked"; } ?>><label for="meertalig_nee">nee</label>
							<input name="meertaligheid" type="radio" id="meertalig_ja" class="radio-button" value="ja" <? if ($row['meertaligheid'] == "ja") { echo "checked"; } ?>><label for="meertalig_ja">ja</label>
							</div>
						</div>

						<div class="form-group">    
							<label for="formuliermodule">Formulier module</label>
							<div class="inputveld invoer radio">
							<input name="formuliermodule" type="radio" id="formuliermodule_nee" class="radio-button" value="nee" <? if ($row['formuliermodule'] == "nee") { echo "checked"; } ?>><label for="formuliermodule_nee">nee</label>
							<input name="formuliermodule" type="radio" id="formuliermodule_ja" class="radio-button" value="ja" <? if ($row['formuliermodule'] == "ja") { echo "checked"; } ?>><label for="formuliermodule_ja">ja</label>
							</div>
						</div>

						<div class="form-group">
							<label for="blockbuilder">Block builder</label>
							<div class="inputveld invoer radio">
								<input name="blockbuilder" type="radio" id="blockbuilder_nee" class="radio-button" value="nee" <? if ($row['blockbuilder'] == "nee") { echo "checked"; } ?>><label for="blockbuilder_nee">nee</label>
								<input name="blockbuilder" type="radio" id="blockbuilder_ja" class="radio-button" value="ja" <? if ($row['blockbuilder'] == "ja") { echo "checked"; } ?>><label for="blockbuilder_ja">ja</label>
							</div>
						</div>
						
						<div class="form-group">    
							<label for="makelaar">Makelaar</label>
							<div class="inputveld invoer radio">
								<input name="makelaar" type="radio" id="makelaar_nee" class="radio-button" value="nee" <? if ($row['makelaar'] == "nee") { echo "checked"; } ?>><label for="makelaar_nee">nee</label>
								<input name="makelaar" type="radio" id="makelaar_ja" class="radio-button" value="ja" <? if ($row['makelaar'] == "ja") { echo "checked"; } ?>><label for="makelaar_ja">ja</label>
							</div>
						</div>

						<div class="form-group rw" style="<? if ($row['makelaar'] == "nee"){ echo 'display:none;';} else { echo 'display:block';} ?>">    
							<label class="suboptie" for="rw_api">RW wonen API-Sleutel</label>
							<input type="text" name="rw_api" class="inputveld invoer small-80" placeholder="Uw Realworks wonen API sleutel" readonly value="<? if ($rowRW['rw_api'] != "") { echo $rowRW['rw_api']; } ?>">
							<a class="btn fl-left arrow browse size-20" data-fancybox="" data-small-btn="true" data-type="iframe" href="/cms/php/realworks_api.php">Voer API-sleutel in</a>
						</div>

						<div class="form-group rw" style="<? if ($row['makelaar'] == "nee"){ echo 'display:none;';} else { echo 'display:block';} ?>">
							<label class="suboptie" for="rw_afdelingscode">RW afdelingscode</label>
							<input type="text" name="rw_afdelingscode" class="inputveld invoer" placeholder="Voer hier uw afdelingscode in" value="<? echo $rowRW['rw_afdelingscode']; ?>" />
						</div>

						<div class="form-group rw" style="<? if ($row['makelaar'] == "nee"){ echo 'display:none;';} else { echo 'display:block';} ?>">    
							<label class="suboptie" for="realworkswonen">RW koppeling wonen</label>
							<div class="inputveld invoer suboptie radio">
								<input name="realworkswonen" type="radio" id="realworkswonen_nee" class="radio-button" value="nee" <? if ($row['realworkswonen'] == "nee") { echo "checked"; } ?>><label for="realworkswonen_nee">nee</label>
								<input name="realworkswonen" type="radio" id="realworkswonen_ja" class="radio-button" value="ja" <? if ($row['realworkswonen'] == "ja") { echo "checked"; } ?>><label for="realworkswonen_ja">ja</label>
							</div>
						</div>

						<div class="form-group rw" style="<? if ($row['makelaar'] == "nee"){ echo 'display:none;';} else { echo 'display:block';} ?>">    
							<label class="suboptie" for="realworksbog">RW koppeling bog</label>
							<div class="inputveld invoer suboptie radio">
								<input name="realworksbog" type="radio" id="realworksbog_nee" class="radio-button" value="nee" <? if ($row['realworksbog'] == "nee") { echo "checked"; } ?>><label for="realworksbog_nee">nee</label>
								<input name="realworksbog" type="radio" id="realworksbog_ja" class="radio-button" value="ja" <? if ($row['realworksbog'] == "ja") { echo "checked"; } ?>><label for="realworksbog_ja">ja</label>
							</div>
						</div>

						<div class="form-group rw" style="<? if ($row['makelaar'] == "nee"){ echo 'display:none;';} else { echo 'display:block';} ?>">    
							<label class="suboptie" for="realworksnieuwbouw">RW nieuwbouw</label>
							<div class="inputveld invoer suboptie radio">
								<input name="realworksnieuwbouw" type="radio" id="realworksnieuwbouw_nee" class="radio-button" value="nee" <? if ($row['realworksnieuwbouw'] == "nee") { echo "checked"; } ?>><label for="realworksnieuwbouw_nee">nee</label>
								<input name="realworksnieuwbouw" type="radio" id="realworksnieuwbouw_ja" class="radio-button" value="ja" <? if ($row['realworksnieuwbouw'] == "ja") { echo "checked"; } ?>><label for="realworksnieuwbouw_ja">ja</label>
							</div>
						</div>
						
						<div class="form-group">    
							<label for="afbeeldingopties">Afbeelding opties</label>
							<div class="inputveld invoer radio">
							<input name="afbeeldingopties" type="radio" id="afbeeldingopties_nee" class="radio-button" value="nee" <? if ($row['afbeeldingopties'] == "nee") { echo "checked"; } ?>><label for="afbeeldingopties_nee">nee</label>
							<input name="afbeeldingopties" type="radio" id="afbeeldingopties_ja" class="radio-button" value="ja" <? if ($row['afbeeldingopties'] == "ja") { echo "checked"; } ?>><label for="afbeeldingopties_ja">ja</label>
							</div>
						</div>

						<div class="form-group afb" style="<? if ($row['afbeeldingopties'] == "nee"){ echo 'display:none;';} else { echo 'display:block';} ?>">    
							<label class="suboptie" for="hoofdtitelveld">Hoofdtitelveld</label>
							<div class="inputveld invoer suboptie radio">
								<input name="hoofdtitelveld" type="radio" id="hoofdtitelveld_nee" class="radio-button" value="nee" <? if ($row['hoofdtitelveld'] == "nee") { echo "checked"; } ?>><label for="hoofdtitelveld_nee">nee</label>
								<input name="hoofdtitelveld" type="radio" id="hoofdtitelveld_ja" class="radio-button" value="ja" <? if ($row['hoofdtitelveld'] == "ja") { echo "checked"; } ?>><label for="hoofdtitelveld_ja">ja</label>
							</div>
						</div>

						<div class="form-group afb" style="<? if ($row['afbeeldingopties'] == "nee"){ echo 'display:none;';} else { echo 'display:block';} ?>">    
							<label class="suboptie" for="subtitelveld">Subtitelveld</label>
							<div class="inputveld invoer suboptie radio">
								<input name="subtitelveld" type="radio" id="subtitelveld_nee" class="radio-button" value="nee" <? if ($row['subtitelveld'] == "nee") { echo "checked"; } ?>><label for="subtitelveld_nee">nee</label>
								<input name="subtitelveld" type="radio" id="subtitelveld_ja" class="radio-button" value="ja" <? if ($row['subtitelveld'] == "ja") { echo "checked"; } ?>><label for="subtitelveld_ja">ja</label>
							</div>
						</div>

						<div class="form-group afb" style="<? if ($row['afbeeldingopties'] == "nee"){ echo 'display:none;';} else { echo 'display:block';} ?>">    
							<label class="suboptie" for="linkveld">Linkveld</label>
							<div class="inputveld invoer suboptie radio">
								<input name="linkveld" type="radio" id="linkveld_nee" class="radio-button" value="nee" <? if ($row['linkveld'] == "nee") { echo "checked"; } ?>><label for="linkveld_nee">nee</label>
								<input name="linkveld" type="radio" id="linkveld_ja" class="radio-button" value="ja" <? if ($row['linkveld'] == "ja") { echo "checked"; } ?>><label for="linkveld_ja">ja</label>
							</div>
						</div>

						<div class="form-group last">    
							<label for="exportmogelijkheid">Exporteer mogelijkheden</label>
							<div class="inputveld invoer radio">
								<input name="exportmogelijkheid" type="radio" id="exportmogelijkheid_nee" class="radio-button" value="nee" <? if ($row['export'] == "nee") { echo "checked"; } ?>><label for="exportmogelijkheid_nee">nee</label>
								<input name="exportmogelijkheid" type="radio" id="exportmogelijkheid_ja" class="radio-button" value="ja" <? if ($row['export'] == "ja") { echo "checked"; } ?>><label for="exportmogelijkheid_ja">ja</label>
							</div>
						</div>
				
					<!-- </div> -->
				<? } ?>
				<h3><span class="icon fas fa-cog"></span>Instellingen</h3>
				<div class="content-container mt-0">   
						
					<div class="form-group">
						<label>Achtergrond inlogscherm</label>
						<a class="btn fl-left arrow browse" data-fancybox="" data-small-btn="true" data-type="iframe" href="php/cms_background.php">Stel achtergrond in</a>
					</div>
						
					<div class="form-group">
						<label for="favicon">Favicon</label>
						<div class="inputveld invoer small-70">
							<img src="<? echo $url; ?>/favicon.ico" width="28" height="28" border="0">
							<input type="file" name="favicon" accept="image/vnd.microsoft.icon" id="files">
							<input type="hidden" name="favicon_upload" value="true">
						</div>
					</div>
					

					<div class="form-group">
						<label>Logo</label>
						<div class="inputveld invoer small-70 darker">
							<img src="<? echo $url; ?>/images/logo.png" height="28" border="0">
							<input type="file" name="logo" accept=".png,.PNG" id="files">
							<input type="hidden" name="logo_upload" value="true">
						</div>
					</div>
					
					<div class="form-group">
						<label for="naamwebsite">Naam website</label><input type="text" name="naamwebsite" class="inputveld invoer" placeholder="Voer hier de naam van de website in (bijv. bedrijfsnaam, naam vereniging)" value="<? echo $row['naamwebsite']; ?>" />
					</div>

					<div class="form-group">
						<label for="websiteemail">Bedrijf E-mail</label><input type="email" name="websiteemail" class="inputveld invoer" placeholder="Voer hier uw bedrijfs E-mail in" value="<? echo $row['websiteemail']; ?>" />
					</div>

					<div class="form-group">
						<label for="websitetelnr">Bedrijf Telefoonnummer</label><input type="tel" name="websitetelnr" class="inputveld invoer" placeholder="Voer hier uw bedrijfs Telefoonnummer in" value="<? echo $row['websitetelnr']; ?>" />
					</div>

					<div class="form-group">
						<label for="gmapskey">Google Analytics</label><input type="text" name="uanummer" class="inputveld invoer" placeholder="Voer hier uw tracking-ID in" value="<? echo $row['uanummer']; ?>" />
					</div>

					<div class="form-group">
						<label for="gmapskey">Google Maps key</label><input type="text" name="gmapskey" class="inputveld invoer" placeholder="Voer hier uw Google Maps API key in" value="<? echo  $row['gmapskey']; ?>" />
					</div>

					<div class="form-group divide-up">    
						<label for="website_beveiliging">Website beveiliging</label>
						<div class="inputveld invoer radio">
							<input name="website_beveiliging" type="radio" id="beveiliging_hcaptcha" class="radio-button" value="hcaptcha" <? if ($row['website_beveiliging'] == "hcaptcha") { echo "checked"; } ?>><label for="beveiliging_hcaptcha">hCaptcha</label>
							<input name="website_beveiliging" type="radio" id="beveiliging_recaptcha" class="radio-button" value="recaptcha" <? if ($row['website_beveiliging'] == "recaptcha") { echo "checked"; } ?>><label for="beveiliging_recaptcha">Recaptcha</label>
						</div>
					</div>

					<div class="form-group hcaptcha">
						<label for="hcaptcha_clientkey">Client key</label><input type="text" name="hcaptcha_clientkey" class="inputveld invoer" placeholder="Voer hier uw hCaptcha client key in" value="<? echo $row['hcaptcha_clientkey']; ?>" />
					</div>

					<?php if($rowuser['id'] == '1'): ?>
						<div class="form-group hcaptcha">
							<label for="hcaptcha_secretkey">Secret key</label><input type="text" name="hcaptcha_secretkey" class="inputveld invoer" placeholder="Voer hier uw hCaptcha secret key in" value="<? echo $row['hcaptcha_secretkey']; ?>" />
						</div>
					<?php endif; ?>

					<div class="form-group recaptcha">
						<label for="recaptcha_clientkey">Client key</label><input type="text" name="recaptcha_clientkey" class="inputveld invoer" placeholder="Voer hier uw Recaptcha client key in" value="<? echo $row['recaptcha_clientkey']; ?>" />
					</div>

					<div class="form-group recaptcha">
						<label for="recaptcha_secretkey">Secret key</label><input type="text" name="recaptcha_secretkey" class="inputveld invoer" placeholder="Voer hier uw Recaptcha secret key in" value="<? echo $row['recaptcha_secretkey']; ?>" />
					</div>

					<div class="form-group divide-down">
					</div>

					<div class="form-group">
						<label>XML sitemap</label>
						<a class="btn fl-left arrow browse" href="<?php echo $row['weburl'];?>/create_sitemap.php">Genereer/update sitemap</a>
					</div>

					<div class="form-group">    
						<label for="offline">Website offline</label>
						<div class="inputveld invoer radio">
							<input name="offline" type="radio" id="status_online" class="radio-button" value="nee" <? if ($row['offline'] == "nee") { echo "checked"; } ?>><label for="status_online">nee</label>
							<input name="offline" type="radio" id="status_offline" class="radio-button" value="ja" <? if ($row['offline'] == "ja") { echo "checked"; } ?>><label for="status_offline">ja</label>
						</div>
					</div>

					<div class="form-group">    
						<label for="offline_tekst">Website offline</label>
						<textarea name="offline_tekst" cols="60" class="textveld invoer" placeholder="Tekst voor op de offline pagina" style="min-height:150px;" rows="6" ><? echo  $row['offline_tekst'];  ?></textarea>
					</div>
					
					<input type="hidden" name="bijwerken" value="1">
					<button name="opslaan" id="settingsave" class="btn fl-left save" type="submit">Opslaan</button>
			</form>
		<? if ($rowuser['id'] == "1") { ?>
			</div>
		<? } ?>
	</div>
</div>
<?php if($rowuser['id'] == '1'): ?>
	<div class="box box-1-3 lg-box-full title sticky_files">
		<div class="sidebar-box sticky-bestanden">
			<h3><span class="icon fas fa-book-open"></span>Handige bestanden</h3>
			<a data-fancybox data-small-btn="true" data-type="iframe" href="/cms/php/checklist_livegang.php" 
				href="javascript:;" class="btn fl-left checklist">Checklist livegang</a>
		</div>
	</div>
<?php endif; ?>
<?php if($rowinstellingen['meertaligheid'] == 'ja'): ?>
	<div id="talen" class="box box-full lg-box-full" style="position:relative;">
	<?php
		if($_GET['taal_nieuw'] == '1') {
			echo "<div class=\"alert alert-success\">";
				 echo "Taal(en) toegevoegd";
			 echo "</div>";
		}
		
		if($_GET['taal_nieuw'] == '0') {
			echo "<div class=\"alert alert-error\">";
				 echo "Taal(en) verwijderd";
			 echo "</div>";
		}
	?>
		<h3><span class="icon fas fa-language"></span>Talen</h3>
		<div class="content-container">
			<div class="row talen">
				<div id="huidige_talen">
					<h3><span class="icon fad fa-language"></span>Huidige talen</h3>
					<form action="<?=$PHP_SELF; ?>" method="POST" class="grid huidig">
						<?php  
						$sqlKeuzeTlactief = $mysqli->query("SELECT * FROM sitework_taal WHERE actief = '1' ORDER BY volgorde ASC") or die($mysqli->error.__LINE__);
						while($rowKeuzeTlactief = $sqlKeuzeTlactief->fetch_assoc()):
						?>
							<div id="taal_<?=$rowKeuzeTlactief['taalkort'];?>" class="taal">
								<img src="/flags/<?=$rowKeuzeTlactief['taalkort'];?>.svg" alt="<?=$rowKeuzeTlactief['taalkort'];?>" width="30px">
								<input type="checkbox" name="taal_verwijder[]" style="display:none;" id="taal_<?=$rowKeuzeTlactief['taalkort'];?>" value="<?=$rowKeuzeTlactief['taalkort'];?>">
								<label for="taal_<?=$rowKeuzeTlactief['taalkort'];?>"><h2><?=$rowKeuzeTlactief['taallang'];?></h2></label>
							</div>
						<?php endwhile; ?>
						<input type="hidden" name="taal_soort" value="huidig">
						<button class="btn delete fl-right" type="submit">Verwijder taal(en)</button>
					</form>
				</div>
				<div id="keuze_talen">
					<h3><span class="icon fal fa-language"></span>Mogelijke talen</h3>
					<form action="<?=$PHP_SELF; ?>" method="POST" class="grid keuze">
						<?php  
						$sqlKeuzeTl = $mysqli->query("SELECT * FROM sitework_taal WHERE actief = '0' ") or die($mysqli->error.__LINE__);
						while($rowKeuzeTl = $sqlKeuzeTl->fetch_assoc()):
						?>
							<div class="taal">
								<img src="/flags/<?=$rowKeuzeTl['taalkort'];?>.svg" alt="<?=$rowKeuzeTl['taalkort'];?>" width="30px">
								<input type="checkbox" name="taal_setting[]" style="display:none;" id="taal_<?=$rowKeuzeTl['taalkort'];?>" value="<?=$rowKeuzeTl['taalkort'];?>">
								<label for="taal_<?=$rowKeuzeTl['taalkort'];?>"><h2><?=$rowKeuzeTl['taallang'];?></h2></label>
							</div>
						<?php endwhile; ?>
						<input type="hidden" name="taal_soort" value="keuze">
						<button class="btn nieuw fl-right" type="submit">Voeg taal(en) toe</button>
					</form>
				</div>
			</div>
		</div>
		<div class="info !mt-20">
			<span class="far fa-info-circle"></span>&nbsp;&nbsp;U kunt de volgorde van de huidige talen wijzigen hoe het u past.
		</div>
	</div>
<?php endif; ?>
<script>
	var save = document.getElementById('settingsave');
	var toggle = document.getElementById('settingsform');
	save.addEventListener( "click", function(e) { 
        toggle.preventDefault();
    });

	$(document).ready(function() {
		var beveiliging = $('input[name=website_beveiliging]:checked').val();
		if(beveiliging == 'hcaptcha') {
			$('.recaptcha').hide();
			$('.hcaptcha').show();
		} else if(beveiliging == 'recaptcha') {
			$('.hcaptcha').hide();
			$('.recaptcha').show();
		}

		var cmspakket = $('select[name=cmspakket]').val();
		if(cmspakket == 'deluxe' || cmspakket == 'premium') {
			$('.api_key').show();
		} else if(cmspakket == 'standaard') {
			$('.api_key').hide();
		}
	});

	$('input[name=website_beveiliging]').on('change', function() {
		var beveiliging = $(this).val();
		if(beveiliging == 'hcaptcha') {
			$('.recaptcha').hide();
			$('.hcaptcha').show();
		} else if(beveiliging == 'recaptcha') {
			$('.hcaptcha').hide();
			$('.recaptcha').show();
		}
	})

	$('select[name=cmspakket]').on('change', function() {
		var cmspakket = $(this).val();
		if(cmspakket == 'deluxe' || cmspakket == 'premium') {
			$('.api_key').show();
		} else if(cmspakket == 'standaard') {
			$('.api_key').hide();
		}
	})

	function showPass() {
      var x = document.getElementById("api_key");
      var icon = document.getElementById("eye");
      if (x.type === "password") {
        icon.classList.add("fa-eye-slash");
        x.type = "text";
      } else {
        x.type = "password";
        icon.classList.remove("fa-eye-slash");
      }
    }
</script>