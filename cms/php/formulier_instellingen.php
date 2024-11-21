<?php // checken of men wel is ingelogd
// ====================================
login_check_v2();

// pagina wijzigen
// ===============
if($_POST['opslaan'] == 1){

	// verplichte velden
	// =================
	if (!$_POST['email'])   {  $error = "U heeft nog niet alle verplichte velden goed ingevuld!";	 }
	else
			 {	// verplichte velden omzetten 
				// ==========================
				$verplichte_velden = $_POST['verplicht1'].
				$_POST['verplicht2'].
				$_POST['verplicht3'].
				$_POST['verplicht4']. 
				$_POST['verplicht5'].
				$_POST['verplicht6'].
				$_POST['verplicht7'].
				$_POST['verplicht8'].
				$_POST['verplicht9'].
				$_POST['verplicht10'].
				$_POST['verplicht11'].
				$_POST['verplicht12'].
				$_POST['verplicht13'].
				$_POST['verplicht14'].
				$_POST['verplichtemail'];

				// eerste komma er af halen
				// ========================
				$verplichte_velden = substr($verplichte_velden,3,400);

				$sql_insert = $mysqli->query("UPDATE sitework_emailsettings SET
																ontvanger = '".$_POST['ontvanger']."',
																email = '".$_POST['email']."',
																emailcc = '".$_POST['emailcc']."',
																afzender = '".$_POST['afzender']."',
																emailafzender = '".$_POST['emailafzender']."',
																onderwerp = '".$mysqli->real_escape_string($_POST['onderwerp'])."',
																bericht = '".$mysqli->real_escape_string($_POST['bericht'])."',
																bedankttekst = '".$mysqli->real_escape_string($_POST['bedankttekst'])."',
																bevestigingstekst = '".$mysqli->real_escape_string($_POST['bevestigingstekst'])."',
																bedanktURL = '".$_POST['bedanktURL']."',

																formVeld1 = '".$mysqli->real_escape_string($_POST['formVeld1'])."',
																formVeld2 = '".$mysqli->real_escape_string($_POST['formVeld2'])."',
																formVeld3 = '".$mysqli->real_escape_string($_POST['formVeld3'])."',
																formVeld4 = '".$mysqli->real_escape_string($_POST['formVeld4'])."',
																formVeld5 = '".$mysqli->real_escape_string($_POST['formVeld5'])."',
																formVeld6 = '".$mysqli->real_escape_string($_POST['formVeld6'])."',
																formVeld7 = '".$mysqli->real_escape_string($_POST['formVeld7'])."',
																formVeld8 = '".$mysqli->real_escape_string($_POST['formVeld8'])."',
																formVeld9 = '".$mysqli->real_escape_string($_POST['formVeld9'])."',
																formVeld10 = '".$mysqli->real_escape_string($_POST['formVeld10'])."',
																formVeld11 = '".$mysqli->real_escape_string($_POST['formVeld11'])."',
																formVeld12 = '".$mysqli->real_escape_string($_POST['formVeld12'])."',
																formVeld13 = '".$mysqli->real_escape_string($_POST['formVeld13'])."',
																formVeld14 = '".$mysqli->real_escape_string($_POST['formVeld14'])."',
																formEmail = '".$_POST['formEmail']."',

																beveiliging = '".$_POST['beveiliging']."',

																verplichtevelden = '".$verplichte_velden."'

								WHERE formulier = 'standaard'") or die($mysqli->error.__LINE__);

				$rowid = $mysqli->insert_id;
				echo "
					<div class=\"alert alert-success\">
						Formulier toegevoegd
					</div>
				";

	}
}

$sql = $mysqli->query("SELECT * FROM sitework_emailsettings WHERE formulier = 'standaard' ") or die($mysqli->error.__LINE__);
$row = $sql->fetch_assoc(); ?>

<div class="box-container">
  <div class="box box-2-3 md-box-full">
    <h3><span class="icon ti-email"></span>Contactformulier instellingen</h3>
	<form action="<? echo $PHP_SELF ?>" method="post" enctype="multipart/form-data">

	<div class="form-group">
		<label>Veld 1</label>
		<input type="text" name="formVeld1" class="inputveld invoer small-80" placeholder="Vul hier de omschrijving van dit veld in. Leeg laten indien niet van toepassing" value="<?=$row['formVeld1']; ?>" />
		<div class="inputveld invoer small-20 checkbox">
			<input name="verplicht1" id="verplichtveld1" type="checkbox" class="inputveld checkbox" value="or !$formVeld1 " title="Aanvinken als het veld 'verplicht' moet zijn." <? if (preg_match("/formVeld1 /i", $row['verplichtevelden'])) {  echo "checked"; } ?> />
			<label for="verplichtveld1">verplicht</label>
		</div>
	</div>

	<div class="form-group">
		<label>Veld 2</label>
		<input type="text" name="formVeld2" class="inputveld invoer small-80" placeholder="Vul hier de omschrijving van dit veld in. Leeg laten indien niet van toepassing" value="<?=$row['formVeld2']; ?>" />
		<div class="inputveld invoer small-20 checkbox">
			<input name="verplicht2" id="verplichtveld2" type="checkbox" class="inputveld checkbox" value="or !$formVeld2 " title="Aanvinken als het veld 'verplicht' moet zijn." <? if (preg_match("/formVeld2 /i", $row['verplichtevelden'])) {  echo "checked"; } ?> />
			<label for="verplichtveld2">verplicht</label>
		</div>
	</div>

	<div class="form-group">
		<label>Veld 3</label>
		<input type="text" name="formVeld3" class="inputveld invoer small-80" placeholder="Vul hier de omschrijving van dit veld in. Leeg laten indien niet van toepassing" value="<?=$row['formVeld3']; ?>" />
		<div class="inputveld invoer small-20 checkbox">
			<input name="verplicht3" id="verplichtveld3" type="checkbox" class="inputveld checkbox" value="or !$formVeld3 " title="Aanvinken als het veld 'verplicht' moet zijn." <? if (preg_match("/formVeld3 /i", $row['verplichtevelden'])) {  echo "checked"; } ?> />
			<label for="verplichtveld3">verplicht</label>
		</div>
	</div>
	<div class="form-group">
		<label>Veld 4</label>
		<input type="text" name="formVeld4" class="inputveld invoer small-80" placeholder="Vul hier de omschrijving van dit veld in. Leeg laten indien niet van toepassing" value="<?=$row['formVeld4']; ?>" />
		<div class="inputveld invoer small-20 checkbox">
			<input name="verplicht4" id="verplichtveld4" type="checkbox" class="inputveld checkbox" value="or !$formVeld4 " title="Aanvinken als het veld 'verplicht' moet zijn." <? if (preg_match("/formVeld4 /i", $row['verplichtevelden'])) {  echo "checked"; } ?> />
			<label for="verplichtveld4">verplicht</label>
		</div>
	</div>
	<div class="form-group">
		<label>Veld 5</label>
		<input type="text" name="formVeld5" class="inputveld invoer small-80" placeholder="Vul hier de omschrijving van dit veld in. Leeg laten indien niet van toepassing" value="<?=$row['formVeld5']; ?>" />
		<div class="inputveld invoer small-20 checkbox">
			<input name="verplicht5" id="verplichtveld5" type="checkbox" class="inputveld checkbox" value="or !$formVeld5 " title="Aanvinken als het veld 'verplicht' moet zijn." <? if (preg_match("/formVeld5 /i", $row['verplichtevelden'])) {  echo "checked"; } ?> />
			<label for="verplichtveld5">verplicht</label>
		</div>
	</div>
	<div class="form-group">
		<label>Veld 6</label>
		<input type="text" name="formVeld6" class="inputveld invoer small-80" placeholder="Vul hier de omschrijving van dit veld in. Leeg laten indien niet van toepassing" value="<?=$row['formVeld6']; ?>" />
		<div class="inputveld invoer small-20 checkbox">
			<input name="verplicht6" id="verplichtveld6" type="checkbox" class="inputveld checkbox" value="or !$formVeld6 " title="Aanvinken als het veld 'verplicht' moet zijn." <? if (preg_match("/formVeld6 /i", $row['verplichtevelden'])) {  echo "checked"; } ?> />
			<label for="verplichtveld6">verplicht</label>
		</div>
	</div>
	<div class="form-group">
		<label>Veld 7</label>
		<input type="text" name="formVeld7" class="inputveld invoer small-80" placeholder="Vul hier de omschrijving van dit veld in. Leeg laten indien niet van toepassing" value="<?=$row['formVeld7']; ?>" />
		<div class="inputveld invoer small-20 checkbox">
			<input name="verplicht7" id="verplichtveld7" type="checkbox" class="inputveld checkbox" value="or !$formVeld7 " title="Aanvinken als het veld 'verplicht' moet zijn." <? if (preg_match("/formVeld7 /i", $row['verplichtevelden'])) {  echo "checked"; } ?> />
			<label for="verplichtveld7">verplicht</label>
		</div>
	</div>
	<div class="form-group">
		<label>Veld 8</label>
		<input type="text" name="formVeld8" class="inputveld invoer small-80" placeholder="Vul hier de omschrijving van dit veld in. Leeg laten indien niet van toepassing" value="<?=$row['formVeld8']; ?>" />
		<div class="inputveld invoer small-20 checkbox">
			<input name="verplicht8" id="verplichtveld8" type="checkbox" class="inputveld checkbox" value="or !$formVeld8 " title="Aanvinken als het veld 'verplicht' moet zijn." <? if (preg_match("/formVeld8 /i", $row['verplichtevelden'])) {  echo "checked"; } ?> />
			<label for="verplichtveld8">verplicht</label>
		</div>
	</div>
	<div class="form-group">
		<label>Veld 9</label>
		<input type="text" name="formVeld9" class="inputveld invoer small-80" placeholder="Vul hier de omschrijving van dit veld in. Leeg laten indien niet van toepassing" value="<?=$row['formVeld9']; ?>" />
		<div class="inputveld invoer small-20 checkbox">
			<input name="verplicht9" id="verplichtveld9" type="checkbox" class="inputveld checkbox" value="or !$formVeld9 " title="Aanvinken als het veld 'verplicht' moet zijn." <? if (preg_match("/formVeld9 /i", $row['verplichtevelden'])) {  echo "checked"; } ?> />
			<label for="verplichtveld9">verplicht</label>
		</div>
	</div>
	<div class="form-group">
		<label>Veld 10</label>
		<input type="text" name="formVeld10" class="inputveld invoer small-80" placeholder="Vul hier de omschrijving van dit veld in. Leeg laten indien niet van toepassing" value="<?=$row['formVeld10']; ?>" />
		<div class="inputveld invoer small-20 checkbox">
			<input name="verplicht10" id="verplichtveld10" type="checkbox" class="inputveld checkbox" value="or !$formVeld10 " title="Aanvinken als het veld 'verplicht' moet zijn." <? if (preg_match("/formVeld10 /i", $row['verplichtevelden'])) {  echo "checked"; } ?> />
			<label for="verplichtveld10">verplicht</label>
		</div>
	</div>
	<div class="form-group">
		<label>Veld 11</label>
		<input type="text" name="formVeld11" class="inputveld invoer small-80" placeholder="Vul hier de omschrijving van dit veld in. Leeg laten indien niet van toepassing" value="<?=$row['formVeld11']; ?>" />
		<div class="inputveld invoer small-20 checkbox">
			<input name="verplicht11" id="verplichtveld11" type="checkbox" class="inputveld checkbox" value="or !$formVeld11 " title="Aanvinken als het veld 'verplicht' moet zijn." <? if (preg_match("/formVeld11 /i", $row['verplichtevelden'])) {  echo "checked"; } ?> />
			<label for="verplichtveld11">verplicht</label>
		</div>
	</div>
	<div class="form-group">
		<label>Veld 12</label>
		<input type="text" name="formVeld12" class="inputveld invoer small-80" placeholder="Vul hier de omschrijving van dit veld in. Leeg laten indien niet van toepassing" value="<?=$row['formVeld12']; ?>" />
		<div class="inputveld invoer small-20 checkbox">
			<input name="verplicht12" id="verplichtveld12" type="checkbox" class="inputveld checkbox" value="or !$formVeld12 " title="Aanvinken als het veld 'verplicht' moet zijn." <? if (preg_match("/formVeld12 /i", $row['verplichtevelden'])) {  echo "checked"; } ?> />
			<label for="verplichtveld12">verplicht</label>
		</div>
	</div>
	<div class="form-group">
		<label>Veld 13</label>
		<input type="text" name="formVeld13" class="inputveld invoer small-80" placeholder="Vul hier de omschrijving van dit veld in. Leeg laten indien niet van toepassing" value="<?=$row['formVeld13']; ?>" />
		<div class="inputveld invoer small-20 checkbox">
			<input name="verplicht13" id="verplichtveld13" type="checkbox" class="inputveld checkbox" value="or !$formVeld13 " title="Aanvinken als het veld 'verplicht' moet zijn." <? if (preg_match("/formVeld13 /i", $row['verplichtevelden'])) {  echo "checked"; } ?> />
			<label for="verplichtveld13">verplicht</label>
		</div>
	</div>
	<div class="form-group">
		<label>Veld email</label>
		<input type="text" name="formEmail" class="inputveld invoer small-80" placeholder="Emailadres" value="<?=$row['formEmail']; ?>"  />
		<div class="inputveld invoer small-20 checkbox">
			<input name="verplichtemail" id="verplichtveldmail" type="checkbox" class="vink" value="or !$formEmail " title="Aanvinken als het veld 'verplicht' moet zijn." <? if (preg_match("/formEmail /i", $row['verplichtevelden'])) {  echo "checked"; } ?> />
			<label for="verplichtveldmail">verplicht</label>
		</div>
	</div>
	<div class="form-group">
		<label>Veld 14</label>
		<input type="text" name="formVeld14" class="inputveld invoer small-80" placeholder="Vul hier de omschrijving van dit veld in. Leeg laten indien niet van toepassing" value="<?=$row['formVeld14']; ?>" />
		<div class="inputveld invoer small-20 checkbox">
			<input name="verplicht14" id="verplichtveld14" type="checkbox" class="inputveld checkbox" value="or !$formVeld14 " title="Aanvinken als het veld 'verplicht' moet zijn." <? if (preg_match("/formVeld14 /i", $row['verplichtevelden'])) {  echo "checked"; } ?> />
			<label for="verplichtveld14">verplicht</label>
		</div>
	</div>

	<button name="opslaan" class="btn fl-left save" type="submit" value="Opslaan">Opslaan</button>
	<input type="hidden" name="opslaan" value="1" >
</div>

<div class="box box-1-3 md-box-full">
	<div class="sidebar-box">
		<h3><span class="icon ti-receipt"></span>Toelichting</h3>
		<div class="info">
			<span class="far fa-info-circle"></span>&nbsp;&nbsp;Stel hier uw emailformulier in. Zodra u de omschrijving van het veld invult, komt dat betreffende veld op het formulier te staan. Door het 'vinkje' aan te zetten kunt u een veld verplicht maken. Ook kunt u hieronder de emailadressen en teksten aanpassen op het formulier.
		</div>
	</div>
	<div class="sidebar-box">
		<h3><span class="icon ti-receipt"></span>Verzendgegevens</h3>

		<span class="dropdown-title">Naam ontvanger</span>
		<input name="ontvanger" type="text" class="inputveld full sidebar" id="ontvanger" value="<? echo $row['ontvanger']; ?>" maxlength="200" />

		<span class="dropdown-title">Emailadres ontvanger</span>
		<input name="email" type="text" class="inputveld full sidebar" id="email" value="<? echo $row['email']; ?>" maxlength="200" />

		<span class="dropdown-title">CC emailadres</span>
		<input name="emailcc" type="text" class="inputveld full sidebar" id="emailcc" value="<? echo  $row['emailcc'];  ?>" maxlength="200" />

		<span class="dropdown-title">Naam afzender</span>
		<input name="afzender" type="text" class="inputveld full sidebar" id="afzender" value="<? echo $row['afzender']; ?>" maxlength="200" />

		<span class="dropdown-title">Email afzender</span>
		<input name="emailafzender" type="text" class="inputveld full sidebar" id="emailafzender" value="<? echo  $row['emailafzender'];  ?>" maxlength="200" />

		<span class="dropdown-title">Onderwerp</span>
		<input name="onderwerp" type="text" class="inputveld full sidebar" id="onderwerp" value="<? echo  $row['onderwerp'];  ?>" maxlength="200" />

		<span class="dropdown-title">1e deel bericht</span>
		<textarea name="bericht" class="textveld full sidebar" cols="" rows="4"><? echo  $row['bericht'];  ?></textarea>

		<span class="dropdown-title">Tekst op bedanktpagina</span>
		<input name="bedankttekst" type="text" class="inputveld full sidebar" id="bedankttekst" value="<? echo  $row['bedankttekst'];  ?>" maxlength="200" />

		<span class="dropdown-title">Bericht bevestigings email</span>
		<textarea name="bevestigingstekst" class="textveld full sidebar" cols="" rows="4"><? echo  $row['bevestigingstekst'];  ?> </textarea>

		<span class="dropdown-title">Bedankt pagina</span>
		<input name="bedanktURL" type="text" class="inputveld full sidebar" id="bedanktURL" value="<? echo  $row['bedanktURL'];  ?>" maxlength="200" placeholder="Vul deze link in als u een aparte bedankt pagina wilt gebruiken" />
	</div>
</form>
</div>
