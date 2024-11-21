<?
// checken of men wel is ingelogd
// ==============================
login_check_v2();

require_once '../vendor/autoload.php';

$geentoegang = '<div class="box-container">
<div class="box box-2-3 lg-box-full">
<h3 style="width:100%;"><span class="icon far fa-hand-paper"></span>Geen toegang!</h3>
<p>U bent niet gemachtigd om deze gebruiker te bekijken/bewerken!</p>
</div></div>
';

// eerst checken of deze gebruiker het account mag openen/bewerken. het niveau administrator mag wel alle accounts zien en bewerken
// ================================================================================================================================
if ($_GET['id'] <> $_SESSION['id'] && $rowuser['niveau'] <> "administrator") {
	echo $geentoegang; }

// uitsluiten dat een klant met een administrator account het sitework account kan aanpassen
// =========================================================================================
elseif ($_GET['id'] == '1' && $_SESSION['id'] <> "1") {
	echo $geentoegang; }

else {

	if($_POST['opslaan'] == 1){

		// verplichte velden checken
		// =========================
		if (!$_POST['email'] or !$_POST['username'] or !$_POST['voorletters'] or !$_POST['niveau'] or !$_POST['achternaam']) {  $error = "U heeft nog niet alle verplichte velden goed ingevuld!";
			if (!$_POST['email']) 		{ $error_email = 'id="foutveld"'; }
			if (!$_POST['username']) 	{ $error_username = 'id="foutveld"'; }
			if (!$_POST['voorletters']) { $error_voorletters = 'id="foutveld"'; }
			if (!$_POST['niveau']) 		{ $error_niveau = 'id="foutveld"'; }
			if (!$_POST['achternaam']) 	{ $error_achternaam = 'id="foutveld"'; }

		} else {	
			
			if($_POST['verificatie'] == '1') {
				if($_POST['verificatie_secretkey'] == "") {
					$secretFactory = new \Sitework\GoogleAuthenticator\SecretFactory();
					$sr_name = "Sitework B.V. " . date('Y') . " - " . $_POST['username'] ."";
					$sr_issuer = $sitenaam . " CMS - " . $_POST['username'];
					$secret = $secretFactory->create($sr_name, $sr_issuer);

					$secretKey = $secret->getSecretKey();
					$auth_secretkey = $secretKey;
				} else {
					$auth_secretkey = $_POST['verificatie_secretkey'];
				}
			}
			
			$sql = $mysqli->query("UPDATE siteworkcms_gebruikers SET 	voorletters = '".$_POST['voorletters']."', 
																		achternaam = '".$_POST['achternaam']."',
																		bedrijfsnaam = '".$_POST['bedrijfsnaam']."',
																		niveau = '".$_POST['niveau']."',
																		username = '".$_POST['username']."',
																		email = '".$_POST['email']."', 
																		ipadres = '".$_SERVER['REMOTE_ADDR']."',
																		meldingen = '".$_POST['meldingen']."',
																		twee_stap_verificatie = '".$_POST['verificatie']."',
																		verificatie_secretkey = '".$auth_secretkey."',										
																		actief = '".$_POST['actief']."' WHERE id = '".$_GET['id']."'") or die($mysqli->error.__LINE__);
				
					//wachtwoord wijzigen?
			if ($_POST['wwwijzigen'] == "ja") { 
				//wachtwoorden checken
				if (!$_POST['password']) { $error = "U heeft geen geldig wachtwoord ingevoerd";  $gelukt = 1;}
				else { 
						
					//hash script + salt
					// Het hashed wachtwoord vanuit het formulier
					// (dit wordt geregeld door de 2 javascriptjes en de submitknop op het formulier)
					$password = $_POST['password'];

					//password hasj
					$password = password_hash($password, PASSWORD_DEFAULT);

					// $password = $_POST['p'];
					// // Willekeurige salt aanmaken
					// $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
					// // De 'salt' toepassen op het wachtwoord
					// $password = hash('sha512', $password.$random_salt);
					
					// Wachtwoord en salt wegschrijven met 'prepared statements!'
					if ($insert_stmt = $mysqli->prepare("UPDATE siteworkcms_gebruikers SET password = ? WHERE id = '".$_GET['id']."'")) {    
						$insert_stmt->bind_param('s', $password); 
						// Execute the prepared query.
						$insert_stmt->execute();
					}
																			
					
				} 	
			} 
			// pagina redirecten om deze te kunnen bewerken
			// ============================================
			header('Location: ?page=gebruiker_bewerken&id='.$_GET['id'].'&opgeslagen=ja');	
		}
		
	}			
			
$sql = $mysqli->query("SELECT * FROM siteworkcms_gebruikers WHERE id = '".$_GET['id']."'") or die($mysqli->error.__LINE__);
$row = $sql->fetch_assoc();	?>

<script type="text/javascript">
//het wachtwoord (lengte + match) checken met javascript
 function chkpassword() {

        var p1 = document.getElementById("pass1").value;
        var p2 = document.getElementById("pass2").value;

        if(p1.length>5) {
            document.getElementById("passwordAlert").style.display = 'none';

            if(p1===p2){
                document.getElementById("passwordAlert").style.display = 'none';
                validpass="yes";
            } else {
                validpass="no";
                document.getElementById("passwordAlert").style.display = 'block';
                document.getElementById("passwordAlert").innerHTML = "Beide wachtwoorden moeten hetzelfde zijn";
            }

        } else{
            document.getElementById("passwordAlert").style.display = 'block';
            document.getElementById("passwordAlert").innerHTML = "Het wachtwoord moet minimaal 6 karakters lang zijn.";
        }

} 
//einde wachtwoord checken
function showMe (it, box) {
  var vis = (box.checked) ? "block" : "none";
  document.getElementById(it).style.display = vis;
  }
</script>

<? if ($_GET['opgeslagen'] <> 'ja') {  // formulier verbergen na verzending ?>
<div class="box-container">
  	<div class="box box-2-3 lg-box-full">
	<h3><span class="icon far fa-user"></span>Account bewerken</h3>
	<? if ($error){ ?><span class="error"><? echo $error; ?></span><? } ?>
	<form action="<? echo $PHP_SELF ?>?page=gebruiker_bewerken&id=<? echo $_GET['id']; ?>" method="post" enctype="multipart/form-data" name="form1" onSubmit="syncTextarea();">
	<div class="form-box">
		<div class="form-group">
			<label for="email">Emailadres</label>
			<input type="text" class="inputveld invoer<? if ($error_email){ echo ' foutveld'; } ?>" tabindex="1" name="email" value="<? if ($row['email']) { echo $row['email']; } ?>">
		</div>
		<div class="form-group">
			<label for="username">Gebruikersnaam</label>
			<input type="text" class="inputveld invoer<? if ($error_username){ echo ' foutveld'; } ?>" tabindex="2" name="username" value="<? if ($row['username']) { echo $row['username']; } ?>">
		</div>
		<? if ($rowuser['niveau'] == 'administrator') { ?>
		<div class="form-group">
			<label for="niveau">Rechten</label>
			<select name="niveau" class="inputveld invoer dropdown<? if ($error_niveau){ echo ' foutveld'; } ?>" id="niveau">
				<option value="administrator" <?php echo ($row['niveau'] == 'administrator') ? 'selected' : ''; ?>>administrator</option>
				<option value="agency" <?php echo ($row['niveau'] == 'agency') ? 'selected' : ''; ?>>agency</option>
				<option value="gebruiker" <?php echo ($row['niveau'] == 'gebruiker') ? 'selected' : ''; ?>>gebruiker</option>
			</select>
		</div>
		<? } ?>
		<div class="form-group">
			<label for="meldingen" class="langer">Update meldingen uitschakelen</label>
			<div class="inputveld invoer radio">
				<input name="meldingen" type="radio" id="meldingen_nonactief" class="radio-button" value="1" <? if($row['meldingen'] == "1") { echo "checked"; }  ?>><label for="meldingen_nonactief">uit</label>
				<input name="meldingen" type="radio" id="meldingen_actief" class="radio-button" value="0" <? if($row['meldingen'] == "0") { echo "checked"; }  ?>><label for="meldingen_actief">aan</label>
			</div>
		</div>		
		<div class="form-group">
			<label for="wwwijzigen" class="langer rounded">Wachtwoord wijzigen? <input id="wwwijzigen" name="wwwijzigen" type="checkbox" value="ja" onclick="showMe('ww', this)"></label>			
		</div>			  
		<div id="ww" style="display:none;">
			<div class="form-group">
				<label for="password">Wachtwoord</label>
				<input type="password" class="inputveld invoer" tabindex="3" name="password" id="pass1" onkeyup="chkpassword()">
			</div>
			<div class="form-group">
				<label for="password2">Wachtwoord herhalen</label>
				<input type="password" class="inputveld invoer" tabindex="4" name="password2" id="pass2" onkeyup="chkpassword()">
				<div id="passwordAlert"></div>
			</div>	
		</div>
	</div>

	<h3><span class="icon far fa-id-card"></span>Persoonlijke gegevens</h3>
	<div class="form-box">
		<div class="form-group">
			<label>Bedrijfsnaam</label><input type="text" class="inputveld invoer" tabindex="5" name="bedrijfsnaam" value="<? if ($row['bedrijfsnaam']) { echo $row['bedrijfsnaam']; } ?>">
		</div>
		<div class="form-group">
			<label>Voorletters</label><input type="text" class="inputveld invoer<? if ($error_voorletters){ echo ' foutveld'; } ?>" tabindex="6" name="voorletters" value="<? if ($row['voorletters']) { echo $row['voorletters']; } ?>">
		</div>
		<div class="form-group">
			<label>Achternaam</label><input type="text" class="inputveld invoer<? if ($error_achternaam){ echo ' foutveld'; } ?>" tabindex="7" name="achternaam" value="<? if ($row['achternaam']) { echo $row['achternaam']; } ?>">
		</div>
	</div>
	<h3><span class="icon far fa-cog"></span>Actief</h3>
	<div class="form-box">
		<div class="form-group">   
		   	<label for="actief">Gebruiker actief</label>
			<div class="inputveld invoer radio">
				<input name="actief" type="radio" id="gebruikeractief_nee" class="radio-button" value="nee" <? if($row['actief'] == "nee") { echo "checked"; }  ?>><label for="gebruikeractief_nee">nee</label>
				<input name="actief" type="radio" id="gebruikeractief_ja" class="radio-button" value="ja" <? if($row['actief'] == "ja") { echo "checked"; }  ?>><label for="gebruikeractief_ja">ja</label>
			</div>
		</div>
		<div class="form-group">   
		   	<label for="verificatie">2 Stap verificatie</label>
			<div class="inputveld invoer radio">
				<input name="verificatie" type="radio" id="verificatie_nonactief" class="radio-button" value="0" <? if($row['twee_stap_verificatie'] == "0") { echo "checked"; }  ?>><label for="verificatie_nonactief">niet actief</label>
				<input name="verificatie" type="radio" id="verificatie_actief" class="radio-button" value="1" <? if($row['twee_stap_verificatie'] == "1") { echo "checked"; }  ?>><label for="verificatie_actief">actief</label>
				<?php if($row['twee_stap_verificatie'] == "1"): ?>
					<a class="show-qr" data-fancybox data-small-btn="true" data-type="inline" href="#qr-code" href="javascript:;"><i class="fas fa-qrcode"></i></a>
					<div id="qr-code" class="qr-code" style="display:none">
						<?php
							$qrNaam = $row['username'];
							$qrCMS = "Sitework B.V. API" . date('Y');
							$qrFullUrl = "otpauth://totp/".$qrCMS." - ".$qrNaam."?secret=".$row['verificatie_secretkey']."&issuer=".$sitenaam." API - " . $qrNaam;
							$qrImage = urlencode($qrFullUrl);
						?>
						<img height="200px" width="200px" src="https://qrcode.tec-it.com/API/QRCode?data=<?=$qrImage;?>" alt="">
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php if($row['twee_stap_verificatie'] == "1"): ?>
			<div class="info full">
				<span class="far fa-info-circle"></span>&nbsp;&nbsp;Als de 2 stap verificatie word uitgeschakeld is uw QR code niet meer te gebruiken.<br>
				Als u de 2 stap verificatie weer besluit aan te zetten zult u met de QR code uw account weer opnieuw moeten koppelen.<br><br>
				<a href="https://apps.apple.com/nl/app/google-authenticator/id388497605" target="_blank" rel="noopener" rel="noreferrer" class="download-auth"><img src="<?=$url;?>/cms/images/app_store.svg" alt="App Store">App Store</a>
				<a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=nl" target="_blank" rel="noopener" rel="noreferrer" class="download-auth"><img src="<?=$url;?>/cms/images/play_store.svg" alt="Play Store">Play Store</a>
			</div>
		<?php endif ?>
	</div>
	<input type="hidden" name="opslaan" value="1">
	<input type="hidden" name="id" value="<? echo $_GET['id'];?>">
	<input type="hidden" name="verificatie_secretkey" value="<?=$row['verificatie_secretkey'];?>">
    <button name="button" class="btn fl-left save" type="submit" value="Opslaan">Opslaan</button>
</form>
<? } else { ?>
	<div class="box-container">
		<div class="box box-2-3 lg-box-full">
			<h3 class="!full"><span class="icon fas fa-thumbs-up"></span>Gebruiker gewijzigd!</h3>
			<p>Gebruiker is succesvol gewijzigd.<br><br>
			<a class="btn fl-left edit mr-10" href="?page=gebruiker_bewerken&id=<? echo $_GET['id']; ?>">Gebruiker bewerken</a>
			<a class="btn fl-left arrow" href="?page=gebruikers">Overzicht</a>
		</div>
	</div>
<? } } ?>