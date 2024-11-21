<?php // checken of men wel is ingelogd
// ====================================
login_check_v2(); ?>

<script>
// inline editen url van formulier velden
// ===========================
	function showEdit(editableObj) {
		$(editableObj).css("background","#FFF");
	}

	function saveToDatabase(editableObj,column,id) {
		$(editableObj).css("background","#FFF url(editinplace/loaderIcon.gif) no-repeat right");
		$.ajax({
			url: "./editinplace/saveedit_form.php",
			type: "POST",
			data:'column='+column+'&editval='+editableObj.innerHTML+'&id='+id,
			success: function(data){
				$(editableObj).css("background","#FDFDFD");
				//alert(editableObj.innerHTML);
			}
		});
	}
	function saveToDatabaseAfhankelijk(editableObj,column,id) {
		$(editableObj).css("background","#FFF url(editinplace/loaderIcon.gif) no-repeat right");
		var afhankelijkvalue = $('#afhankelijk'+id+' option:selected').val();
		$.ajax({
			url: "./editinplace/saveedit_form.php",
			type: "POST",
			data:'column='+column+'&editval='+afhankelijkvalue+'&id='+id,
			success: function(data){
				$(editableObj).css("background","#FDFDFD");
			}
		});
	}
	function saveToDatabaseVerplicht(editableObj,column,id) {
		$(editableObj).css("background","#FFF url(editinplace/loaderIcon.gif) no-repeat right");
		var verplichtvalue = $('#verplicht-'+id+':checked').val();
		if(verplichtvalue == "undefined"){verplichtvalue = "nee"};
		$.ajax({
			url: "./editinplace/saveedit_form.php",
			type: "POST",
			data:'column='+column+'&editval='+verplichtvalue+'&id='+id,
			success: function(data){
				$(editableObj).css("background","#FDFDFD");
			}
		});
	}
	function saveToDatabaseOption(editableObj,column,id) {
		$(editableObj).css("background","#FFF url(editinplace/loaderIcon.gif) no-repeat right");
		$.ajax({
			url: "./editinplace/saveedit_form_option.php",
			type: "POST",
			data:'column='+column+'&editval='+editableObj.innerHTML+'&id='+id,
			success: function(data){
				$(editableObj).css("background","#FDFDFD");
			}
		});
	}

	// veld verwijderen melding
	// ===========================
    function ConfirmDeleteSoc() {
    	return confirm('Weet u zeker dat u dit veld wilt verwijderen?');
    }

</script>

<?php
// formulier toevoegen
// ======================
if($_POST['toevoegen'] == 1 /*veld toevoegen*/){
	$sql_ins = $mysqli->query("INSERT INTO sitework_formuliervelden
                                    SET formid = '".$_GET['id']."',
										naam = '',
										label = '".$mysqli->real_escape_string($_POST['label'])."',
										type = '".$_POST['type']."',
										verplicht = '".$_POST['verplicht']."'
                            ") or die($mysqli->error.__LINE__);
	echo "
    <div class=\"alert alert-success\">
      Veld toegevoegd
    </div>
  ";
}

if($_POST['toevoegen'] == 2 /*option toevoegen*/){
	$sql_ins = $mysqli->query("INSERT INTO sitework_formuliervelden_opties
                                    SET fieldid = '".$_POST['fieldid']."',
										naam = '".$mysqli->real_escape_string($_POST['naam'])."'
                            ") or die($mysqli->error.__LINE__);
	echo "
    <div class=\"alert alert-success\">
      Optie toegevoegd
    </div>
  ";
}

if($_POST['instellingen'] == "ja" /*Formulier instellingen opslaan*/){
	if($_POST['privacyURL'] <> "") {
		$privacy = str_replace(" ", "-", $mysqli->real_escape_string($_POST['privacyURL']));
	} else { $privacy = ""; }
	$sql_insert = $mysqli->query("UPDATE sitework_formulieren SET
													naam = '".$_POST['naam']."',
													ontvanger = '".$_POST['ontvanger']."',
													email = '".$_POST['email']."',
													emailcc = '".$_POST['emailcc']."',
													afzender = '".$_POST['afzender']."',
													emailafzender = '".$_POST['emailafzender']."',
													onderwerp = '".$mysqli->real_escape_string($_POST['onderwerp'])."',
													bericht = '".$mysqli->real_escape_string($_POST['bericht'])."',
													bedankttekst = '".$mysqli->real_escape_string($_POST['bedankttekst'])."',
													bedanktURL = '".$mysqli->real_escape_string($_POST['bedanktURL'])."',
													privacyURL = '".$privacy."',
													kopienaarklant = '".$_POST['kopienaarklant']."',
													url_meesturen = '".$mysqli->real_escape_string($_POST['url_meesturen'])."'

					WHERE id = '".$_GET['id']."'") or die($mysqli->error.__LINE__);

	$rowid = $mysqli->insert_id;
	echo "
		<div class=\"alert alert-success\">
			Formulier instellingen opgeslagen
		</div>
	";

}

// item verwijderen
// ================
if ($_GET['delid']) {
	$mysqli->query("DELETE FROM sitework_formuliervelden WHERE id = '".$_GET['delid']."' ") or die($mysqli->error.__LINE__);
	$mysqli->query("DELETE FROM sitework_formuliervelden_opties WHERE id = '".$_GET['delid']."' ") or die($mysqli->error.__LINE__);
	header('Location: ?page=formulier_bewerken&id='.$_GET['id'].'');
	die;
}

// Type velden
// ===========
$typeLabels = array(
	"name" => "Naam veld",
	"text" => "Tekst veld",
	"date" => "Datum veld",
	"email" => "Email veld",
	"tel" => "Telefoon veld",
	"number" => "Nummer veld",
	"file" => "Bestand veld",
	"checkbox" => "Checkbox",
	"radio" => "Radio buttons",
	"select" => "Keuze selectie",
	"textarea" => "Lang tekst veld",
	"tekstopvulling" => "Tekst opvulling"
);

//haal pagina's die huidige formulier gekoppeld hebben
$sqlGebruikt = $mysqli->query("SELECT * FROM siteworkcms WHERE formulieren = '".$_GET['id']."' ") or die($mysqli->error.__LINE__);

$sql = $mysqli->query("SELECT * FROM sitework_formulieren WHERE id = '".$_GET['id']."' ") or die($mysqli->error.__LINE__);
$row = $sql->fetch_assoc(); ?>

<div class="box-container">
	<div class="box box-2-3 md-box-full">
		<h3><span class="icon far fa-envelope"></span>Formulier instellingen: <?php echo $row['naam'];?></h3>
		<form action="<?=$PHP_SELF; ?>" method="post" enctype="multipart/form-data" class="fl-right">
			<?php if($_POST['sorteren'] == "ja"){
					$disableEdit = "toggleSort";
					$disableOrder = "";
					$disableClass = "";
					$disableClass2 = "disable";
					$infomelding = "U kunt de volgorde wijzigen door op een veld te klikken en deze te verslepen";
				} else {
					$disableEdit = "";
					$disableOrder = "toggleSort";
					$disableClass = "disable";
					$disableClass2 = "";
					$infomelding = "U kunt de tekst van de velden wijzigen door met de muis in het veld te klikken. De wijzigingen worden automatisch opgeslagen";
				}
			?>
			<input type="hidden" name="sorteren" value="ja">
			<button class="btn fl-left right-toggle <?=$disableOrder;?> <?=$disableClass2;?>"><i class="fas fa-arrows-alt-v"></i> Velden sorteren</button>
		</form>
		<form action="<?=$PHP_SELF; ?>" method="post" enctype="multipart/form-data" class="fl-right">
			<input type="hidden" name="sorteren" value="nee">
			<button class="btn fl-left left-toggle <?=$disableEdit;?> <?=$disableClass;?>"><i class="fas fa-pencil"></i> Velden bewerken</button>
		</form>
		<a class="clickme btn fl-right nieuw mr-10 " href="">nieuw veld</a>
		<div class="info">
				<span class="far fa-info-circle"></span>&nbsp;&nbsp;<?php echo $infomelding;?>
			</div>
		<div class="toggle-box">
			<form action="<?=$PHP_SELF; ?>" method="post" enctype="multipart/form-data" name="form1">
				<input type="text" tabindex="1" name="label"  class="inputveld invoer small-50 first" id="label"  placeholder="Vul hier de vraag in" />

				<select name="type" class="inputveld invoer small-20 dropdown noborderradius" placeholder="Selecteer een type veld" >
					<option value="">selecteer een type veld</option>
					<?php
						foreach ($typeLabels as $value => $label) {
							echo '<option value="' . htmlspecialchars($value) . '">' . htmlspecialchars($label) . '</option>';
						}
					?>
				</select>

				<div class="inputveld invoer small-20 checkbox noborderradius">
					<input name="verplicht" id="verplicht" type="checkbox" class="inputveld checkbox" value="ja" title="Aanvinken als het veld 'verplicht' moet zijn."/>
					<label for="verplicht">verplicht</label>
				</div>
				<input type="hidden" name="toevoegen" value="1">
				<input name="button3" type="submit" class="btn fl-left add-field" id="button" value="toevoegen">
			</form>
		</div>
		<!--VELDEN TONEN-->
		<div id="structuurform" <?php if($_POST['sorteren'] == "ja"){echo "class=\"volgorde\"";}?>>
			<div class="row formulier_bewerken type">
				<div class="col">type veld</div>
				<div class="col">Naam veld</div>
				<div class="col">Veld is afhankelijk van</div>
				<div class="col">Verplicht</div>
				<div class="col center"></div>
			</div>
			<ul>
			<?php
				// hoofdmenu ophalen
				// ====================
				$querydrag = $mysqli->query("SELECT * FROM sitework_formuliervelden WHERE formid = '".$_GET['id']."' ORDER BY volgorde,id ASC") or die($mysqli->error.__LINE__);
				while($rowdrag = $querydrag->fetch_assoc()){ ?>

					<li id="recordsArrayForm_<?php echo $rowdrag['id']; ?>">
						<div class="hoofditemlabel"><?php echo $rowdrag['type']; ?></div>
						<div class="hoofditemtitel" contenteditable="true" onBlur="saveToDatabase(this,'label','<?php echo $rowdrag["id"]; ?>')" onClick="showEdit(this);"><?php echo rtrim($rowdrag['label']);  ?></div>
						<div class="afhankelijk">
							<select id="afhankelijk<?php echo $rowdrag['id'];?>" class="inputveld dropdown extra-pad afhankelijk" name="afhankelijk_van" contenteditable="true" onBlur="saveToDatabaseAfhankelijk(this,'afhankelijk_van','<?php echo $rowdrag["id"]; ?>')" onClick="showEdit(this);">
								<option value="">niet afhankelijk</option>
								<option value=""></option>
								<<?php
									$currentType = '';
									$afhankelijksql = $mysqli->query("SELECT * FROM sitework_formuliervelden WHERE formid = '".$_GET['id']."' AND volgorde < '".$rowdrag['volgorde']."' AND id != '".$rowdrag['id']."' ORDER BY volgorde,type,id ASC") or die($mysqli->error.__LINE__);
									while($rowafhankelijk = $afhankelijksql->fetch_assoc()){

										$checked = "";
										if ($rowafhankelijk['type'] !== $currentType) {
											if ($currentType !== '') {
												echo '</optgroup>';
											}
											$currentType = $rowafhankelijk['type'];

											$label = isset($typeLabels[$currentType]) ? $typeLabels[$currentType] : $currentType;
											echo '<optgroup label="' . htmlspecialchars($label) . '">';
										}

										if($rowdrag['afhankelijk_van'] != 0){
											if($rowdrag['afhankelijk_van'] == $rowafhankelijk['id']){
												$checked = "selected";
											}else{
												$checked = "";
											}
										}
										?>
										<option value="<?php echo $rowafhankelijk['id'];?>" <?php echo $checked;?>><?php echo $rowafhankelijk['label'];?></option>
								<?php 
									} 
									if ($currentType !== '') {
										echo '</optgroup>';
									}
								?>
							</select>
						</div>
						<div class="inputveld invoer center verplicht-edit noborderradius">
							<input name="verplicht" id="verplicht-<?=$rowdrag['id'];?>" type="checkbox" class="checkbox moderncheck" value="ja" <?php if($rowdrag['verplicht'] == "ja"){echo "checked";}?> title="Aanvinken als het veld 'verplicht' moet zijn." contenteditable="true" onBlur="saveToDatabaseVerplicht(this,'verplicht','<?php echo $rowdrag["id"]; ?>')" onClick="showEdit(this);"/>
						</div>
						<a class="structure-edit edit-form" href="<?=$PHP_SELF; ?>?page=formulier_bewerken&id=<?=$row['id'];?>&delid=<?=$rowdrag['id']; ?>" onclick='return ConfirmDeleteSoc();'><span class="far fa-trash"></span></a>

						<?php if($rowdrag['type'] == "select" OR $rowdrag['type'] == "radio" OR $rowdrag['type'] == "checkbox" /*ALS TYPE SELECT RADIO OF CHECKBOX IS HALEN WE OPTIONS OP*/){
							$querydragoption = $mysqli->query("SELECT * FROM sitework_formuliervelden_opties WHERE fieldid = '".$rowdrag['id']."' ORDER BY volgorde,id ASC") or die($mysqli->error.__LINE__);
							if ($querydragoption -> num_rows > 0) { ?>
								<div id="structuursuboption" <?php if($_POST['sorteren'] == "ja"){echo "class=\"volgorde-option\"";}?>>
									<ul>
									<?php
									while($rowdragoption = $querydragoption->fetch_assoc()){?>
										<li id="recordsArrayFormOption_<?php echo $rowdragoption['id']; ?>">
											<div class="subitemlabel">Keuze <?php echo $rowdrag['type']; ?></div>
											<div class="subitemtitel" contenteditable="true" onBlur="saveToDatabaseOption(this,'naam','<?php echo $rowdragoption["id"]; ?>')" onClick="showEdit(this);"><?php echo rtrim($rowdragoption['naam']); ?></div>
											<a class="structure-edit edit-form" href="<?=$PHP_SELF; ?>?page=formulier_bewerken&id=<?=$row['id'];?>&delid=<?=$rowdragoption['id']; ?>"  onclick='return ConfirmDeleteSoc();'><span class="far fa-trash"></span></a>
										</li>
									<?php } ?>
									</ul>
								</div>
							<?php } ?>
							<div id="structuursuboption">
								<div class="subitemlabel">Keuze toevoegen</div>
								<div class="subitemtitel option no-padding">
									<form action="<?=$PHP_SELF; ?>" method="post" enctype="multipart/form-data" name="form1">
										<input type="text" tabindex="1" name="naam"  class="inputveld invoer-option" id="naam"  placeholder="keuze" />
										<input type="hidden" name="fieldid" value="<?php echo $rowdrag['id'];?>">
										<input type="hidden" name="toevoegen" value="2">
										<button name="button3" type="submit" class="btn fl-left" id="button">
											<span class="fas fa-plus"></span>
										</button>
									</form>
								</div>
							</div>
							<hr>
						<?php } ?>

					</li>

				<? } ?>
			</ul>
		</div>
	</div>
	<div class="box box-1-3 md-box-full">
		<form action="<? echo $PHP_SELF ?>" method="post" enctype="multipart/form-data">
		<div class="sidebar-box">
			<h3><span class="icon fab fa-wpforms"></span>Invoegen in website</h3>
			<div class="info">
				<span class="far fa-info-circle"></span>&nbsp;&nbsp; Dit formulier is momenteel gekoppeld aan de volgede pagina's:
				<?php
					while($rowGebruikt = $sqlGebruikt->fetch_assoc()){
						echo $rowGebruikt['item2'];
						echo $sqlGebruikt->num_rows > 1 ? ' | ' : '';
					}
				?>
			</div>
		</div>
		<div class="sidebar-box">
			<h3><span class="icon fab fa-wpforms"></span>Verzendgegevens</h3>

			<span class="dropdown-title">Naam formulier</span>
			<input name="naam" type="text" class="inputveld full sidebar" id="naam" value="<? echo $row['naam']; ?>" maxlength="200" />

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

			<span class="dropdown-title">Privacy verklaring pagina</span>
			<input name="privacyURL" type="text" class="inputveld full sidebar" id="privacyURL" value="<? echo  $row['privacyURL'];  ?>" maxlength="200" placeholder="Vul deze link in als u een privacy verklaring pagina heeft" />

			<span class="dropdown-title">Kopie van formulier naar klant</span>
			<div class="inputveld full sidebar">
				<input name="kopienaarklant" id="kopienaarklant" type="checkbox" class="checkbox moderncheck" value="ja" title="Aanvinken als er een kopie moet worden verstuurd naar de afzender" <?php if($row['kopienaarklant'] == "ja"){echo "checked";}?>/>
				<label for="kopienaarklant">Ja <em>(let op: er mag maar 1 veld met het type email aanwezig zijn)</em></label>
			</div>

			<span class="dropdown-title">Pagina van afkomst meesturen</span>
			<div class="inputveld full sidebar">
				<input name="url_meesturen" id="url_meesturen" type="checkbox" class="checkbox moderncheck" value="ja" title="Aanvinken als de url van de formulier pagina moet worden meegestuurd." <?php if($row['url_meesturen'] == "ja"){echo "checked";}?>/>
				<label for="verplichtveld14">URL Meesturen</label>
			</div>
			<input type="hidden" name="instellingen" value="ja">
			<button name="opslaan" class="btn fl-left save mt-20" type="submit" value="Opslaan">Opslaan</button>
		</div>
		</form>
	</div>
</div>
