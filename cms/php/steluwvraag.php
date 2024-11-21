<? // checken of men wel is ingelogd
// =================================
login_check_v2();

// bericht content
// ===============
$subject			 = "Vraag via CMS formulier - via: " . $rowinstellingen['domeinnaam'];
$bericht 			.= "<br>GEGEVENS: <br>";
$bericht 			.= $_POST['naam']."<br>";
$bericht 			.= $_POST['bedrijfsnaam']."<br>";
$bericht 			.= $_POST['email']."<br><br>";
$bericht			.= "<br>Gestelde vraag<br>";
$bericht 			.= $_POST['onderwerp']."<br>";
$bericht 			.= $_POST['vraag']."<br>";

// headers html mail
// =================
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// aanvullende headers
// ===================
$headers .= 'From: '.$_POST['naam'].' <'.$_POST['email'].'>' . "\r\n";

if($_POST['opslaan'] == 1){  // check of er op versturen is gedrukt

	if (!$_POST['onderwerp'] or !$_POST['vraag']) { $foutmelding = "U heeft nog niet alle velden juist ingevuld."; }

	else {

		// hier wordt de e-mail verstuurd
		// ==============================
		mail('support@sitework.nl', $subject, $bericht, $headers);
		
		// na versturen pagina vernieuwen en melding weergeven
		// ===================================================
		header('Location: ?page=dashboard&verzonden=ja');
	}
}
?>

<?php if ($_GET['verzonden'] <> "ja") { ?>
<form action="<?php echo $PHP_SELF ?>" method="post">
	<?php if ($foutmelding) { echo "<div class=\"vraagfout\">".$foutmelding."</div>"; } ?>
	<select name="onderwerp" class="inputveld full mb-10 dropdown">
		<option>Selecteer onderwerp</option>
	    <option value="Gebruik website">Gebruik website</option>
	    <option value="Gebruik CMS">Gebruik CMS</option>
	    <option value="Technische vraag">Technische vraag</option>
	    <option value="Administratief">Administratief</option>
		<option value="Overig">Overig</option>
	</select>
	<textarea name="vraag" rows="7" class="textveld full mb-10" placeholder="Stel hier uw vraag..."><?php echo $vraag; ?></textarea>
	<input type="hidden" name="email" value="<?php echo $rowuser['email']; ?>">
	<input type="hidden" name="bedrijfsnaam" value="<?php echo $rowuser['bedrijfsnaam']; ?>">
	<input type="hidden" name="naam" value="<?php echo "".$rowuser['voorletters']." ".$rowuser['achternaam']."" ?>">

	<input type="hidden" name="opslaan" value="1" >
	<button name="verzenden" class="btn fl-left arrow" type="submit">Vraag stellen</button>
</form>

<?php } else { ?>
	<span class="vraagsucces">Hartelijk dank voor uw bericht. <br>Wij zullen zo spoedig mogelijk reageren.</span>
<?php } ?>
