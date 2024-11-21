<?
// checken of men wel is ingelogd
// ==============================
login_check_v2();

// eerst checken of deze gebruiker dit item wel mag openen/bewerken. het niveau Administrator mag wel alles zien en bewerken
// =========================================================================================================================
if ($row['gebruikersid'] <> $_SESSION['user'] && $rowuser['id'] <> "1") {
	echo "<span class=\"fout\">U bent niet gemachtigd om deze pagina te bekijken!</span>"; }

else {

	// verplichte velden checken
	// =========================
	if($_POST['opslaan'] == 1 ){

		// emailadres checken
		// ==================
		if (!$_POST['email'] or !$_POST['username'] or !$_POST['voorletters'] or !$_POST['niveau'] or !$_POST['achternaam'] or !$_POST['password'] or !$_POST['password2'])   {  $error = "U heeft nog niet alle verplichte velden goed ingevuld!";
			if (!$_POST['email']) 		{ $error_email = 'id="foutveld"'; }
			if (!$_POST['username']) 	{ $error_username = 'id="foutveld"'; }
			if (!$_POST['voorletters']) { $error_voorletters = 'id="foutveld"'; }
			if (!$_POST['niveau']) 		{ $error_niveau = 'id="foutveld"'; }
			if (!$_POST['achternaam']) 	{ $error_achternaam = 'id="foutveld"'; }
			if (!$_POST['password']) 	{ $error_ww1 = 'id="foutveld"'; }
			if (!$_POST['password2']) 	{ $error_ww2 = 'id="foutveld"'; }
		}
		
		// wachtwoorden checken
		// ====================
		else {						
			
			// bepalen of de username al voorkomt in de database
			// =================================================
			$sqluser = $mysqli->query("SELECT username,email FROM siteworkcms_gebruikers WHERE username = '".$_POST['username']."' or email = '".$_POST['email']."' ") or die($mysqli->error.__LINE__);
			$rowsuser = $sqluser->fetch_assoc();
					
			if($rowsuser >= 1){ $error = "Deze gebruikersnaam of emailadres komt reeds voor in de database."; }
			else {
						
				// hash script + salt
				// het hashed wachtwoord vanuit het formulier
				// (dit wordt geregeld door de 2 javascriptjes en de submitknop op het formulier)
				// ==============================================================================
										$password = $_POST['password'];

										//password hasj
										$password = password_hash($password, PASSWORD_DEFAULT);

										// $password = $_POST['p'];
										// // willekeurige salt aanmaken
										// // ==========================
										// $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
										// // de 'salt' toepassen op het wachtwoord
										// // =====================================
										// $password = hash('sha512', $password.$random_salt);
										
										$sql = "INSERT INTO siteworkcms_gebruikers SET voorletters = '".$_POST['voorletters']."', 
																			achternaam = '".$_POST['achternaam']."',
																			bedrijfsnaam = '".$_POST['bedrijfsnaam']."',
																			niveau = '".$_POST['niveau']."',
																			username = '".$_POST['username']."',
																			email = '".$_POST['email']."', 
																			password = '$password', 
																			datum_invoer = now(),
																			ipadres = '".$_SERVER['REMOTE_ADDR']."',										
																			actief = '".$_POST['actief']."' ";
								
								$result = $mysqli->query($sql) or die($mysqli->error.__LINE__);
								$rowid = $mysqli->insert_id;
				// pagina redirecten om deze te kunnen bewerken
				// ============================================
				header('Location: ?page=nieuwe_gebruiker&id='.$rowid.'&opgeslagen=ja');	
			}
		}			
	}			
	?>

	<script type="text/javascript">	
	// het wachtwoord (lengte + match) checken met javascript
	// ======================================================
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
	</script>
<? if ($_GET['opgeslagen'] <> 'ja') {  //formulier verbergen na verzending ?>
<div class="box-container">
  	<div class="box box-2-3 lg-box-full">
	<h3><span class="icon fas fa-user"></span>Aanmaken gebruiker</h3>
	<? if ($error){ ?><span class="error"><? echo $error; ?></span><? } ?>
	<form action="<? echo $PHP_SELF ?>?page=nieuwe_gebruiker" method="post" enctype="multipart/form-data" autocomplete="off" name="form1" onSubmit="syncTextarea();">
		<div class="form-box">
			<div class="form-group">	
				<label for="email">Emailadres</label>
				<input type="email" class="inputveld invoer<? if ($error_email){ echo ' foutveld'; } ?>" tabindex="1" name="email" placeholder="voer hier uw emailadres in" value="<? if ($_POST['email']) { echo $_POST['email']; } ?>" >
			</div>
			<div class="form-group">	
				<label for="username">Gebruikersnaam</label>
				<input type="text" class="inputveld invoer<? if ($error_username){ echo ' foutveld'; } ?>" tabindex="2" name="username" placeholder="voer hier uw gebruikersnaam in" value="<? if ($_POST['username']) { echo $_POST['username']; } ?>">
			</div>
			<div class="form-group">
				<label for="niveau">Rechten</label>
				<select name="niveau" class="inputveld invoer dropdown<? if ($error_niveau){ echo ' foutveld'; } ?>" id="niveau">
					<? if ($_POST['niveau']) { echo '<option value="'.$_POST['niveau'].'">'.$_POST['niveau'].'</option>'; } else { ?><option value="">selecteer een niveau</option><? } ?>
					<option value="administrator">administrator</option>
					<option value="agency">agency</option>
					<option value="gebruiker">gebruiker</option>
				</select>
			</div>
			<div class="form-group">	
				<label for="email">Wachtwoord</label>
				<input type="password" class="inputveld invoer<? if ($error_ww1){ echo ' foutveld'; } ?>" tabindex="3" placeholder="voer hier uw wachtwoord in" name="password" id="pass1" onkeyup="chkpassword()">
			</div>
			<div class="form-group">
				<label for="email">Wachtwoord herhalen</label>	
				<input type="password" class="inputveld invoer<? if ($error_ww2){ echo ' foutveld'; } ?>" tabindex="4" placeholder="voer hier nogmaals uw wachtwoord in" name="password2" id="pass2" onkeyup="chkpassword()">
			</div>
			<div class="form-group">
				<span id="generateBtn" class="btn fl-left mr-10">Random genereren</span>
				<span id="showPass" class="btn fl-left">Wachtwoorden weergeven</span>
				<div id="passwordAlert" class="!fl-right w-fit"></div>
			</div>
		</div>
		<h3><span class="icon fas fa-id-card"></span>Persoonlijke gegevens</h3>
		<div class="form-box">
			<div class="form-group">
				<label for="bedrijfsnaam">Bedrijfsnaam</label><input type="text" class="inputveld invoer" tabindex="5" placeholder="voer hier uw bedrijfsnaam in" name="bedrijfsnaam" value="<? if ($_POST['bedrijfsnaam']) { echo $_POST['bedrijfsnaam']; } ?>">
			</div>
			<div class="form-group">
				<label for="voorletters">Voorletters</label>
				<input type="text" class="inputveld invoer<? if ($error_voorletters){ echo ' foutveld'; } ?>" tabindex="6" placeholder="voer hier uw voorletters in" name="voorletters" value="<? if ($_POST['voorletters']) { echo $_POST['voorletters']; } ?>">
			</div>
			<div class="form-group">
				<label for="achternaam">Achternaam</label>
				<input type="text" class="inputveld invoer<? if ($error_achternaam){ echo ' foutveld'; } ?>" tabindex="7" placeholder="voer hier uw achternaam in" name="achternaam" value="<? if ($_POST['achternaam']) { echo $_POST['achternaam']; } ?>">
			</div>
		</div>
		<h3><span class="icon fas fa-cog"></span>Actief</h3>
		<div class="form-box">
			<div class="form-group">    
				<label for="actief">Gebruiker actief</label>
				<div class="inputveld invoer radio">
					<input name="actief" type="radio" id="gebruikeractief_nee" class="radio-button" value="nee" checked><label for="gebruikeractief_nee">nee</label>
					<input name="actief" type="radio" id="gebruikeractief_ja" class="radio-button" value="ja"><label for="gebruikeractief_ja">ja</label>
				</div>
			</div>
		</div>
		<input type="hidden" name="opslaan" value="1">
		<button name="button" class="btn fl-left arrow" type="submit" onclick="formhash(this.form, this.form.password);">Aanmaken</button>
	</form>

	<? } else { ?>
	<div class="box-container">
		<div class="box box-2-3 lg-box-full">
			<h3 class="!full"><span class="icon fas fa-thumbs-up"></span>Gebruiker toegevoegd!</h3>
			<p>Uw zojuist aangemaakte gebruiker is succesvol toegevoegd.<br><br>
			<a class="btn fl-left edit mr-10" href="?page=gebruiker_bewerken&id=<?=$_GET['id']; ?>">Gebruiker bewerken</a>
			<a class="btn fl-left arrow" href="?page=gebruikers">Overzicht</a>
		</div>
	</div>
<? } } ?>

<script>
	// Random wachtwoord genereren
	// ===========================
	function generateStrongPassword(length = 9, addDashes = false, availableSets = 'luds') {
		const sets = [];

		if (availableSets.includes('l'))
			sets.push('abcdefghjkmnpqrstuvwxyz');
		if (availableSets.includes('u'))
			sets.push('ABCDEFGHJKMNPQRSTUVWXYZ');
		if (availableSets.includes('d'))
			sets.push('23456789');
		if (availableSets.includes('s'))
			sets.push('!@#$%&*?');

		let all = '';
		let password = '';

		sets.forEach(set => {
			password += set.charAt(Math.floor(Math.random() * set.length));
			all += set;
		});

		all = all.split('');
		for (let i = 0; i < length - sets.length; i++) {
			password += all[Math.floor(Math.random() * all.length)];
		}

		password = password.split('').sort(() => 0.5 - Math.random()).join('');

		if (addDashes) {
			const dashLen = Math.floor(Math.sqrt(length));
			let dashStr = '';
			while (password.length > dashLen) {
				dashStr += password.substring(0, dashLen) + '-';
				password = password.substring(dashLen);
			}
			password = dashStr + password;
		}

		// Set the password value to the inputs with IDs 'pass1' and 'pass2'
		document.getElementById('pass1').value = password;
		document.getElementById('pass2').value = password;
	}

	document.getElementById('generateBtn').onclick = function() {
		generateStrongPassword(12, true, 'luds'); // Adjust parameters as needed
	};

	// Wachtwoord weergeven
	// ====================
	function showPass() {
		var p1 = document.getElementById("pass1");
		var p2 = document.getElementById("pass2");
		var showPass =document.getElementById("showPass");

		if (p1.type === "password" && p2.type === "password") {
			p1.type = "text";
			p2.type = "text";
			showPass.innerHTML = 'Verberg wachtwoorden';
		} else {
			p1.type = "password";
			p2.type = "password";
			showPass.innerHTML = 'Wachtwoorden weergeven';
		}
	}

	document.getElementById('showPass').onclick = function() {
		showPass(); // Adjust parameters as needed
	};
</script>