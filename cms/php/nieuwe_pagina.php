<?php
// checken of men wel is ingelogd
// ==============================
login_check_v2();

// pagina toevoegen
// ================
if($_POST['opslaan'] == 1){
	
	// verplichte velden
	// ================
	if (!$_POST['item2'] or !$_POST['keuze1'])   {  $error = "U heeft nog niet alle verplichte velden goed ingevuld!";
		if (!$_POST['keuze1']) 	{ $error_keuze1 = 'ja'; }
		if (!$_POST['item2']) 	{ $error_item2 = 'ja'; }		
	}
	
	else {	
		// check of paginaurl al bestaat
		// =============================
		$paginaurlerror = false;
		$paginaurl = slugify($_POST['item2']);
		$paginaUrlCheck = $mysqli->query(" SELECT * FROM siteworkcms WHERE paginaurl = '".$paginaurl."'") or die ($mysqli->error.__LINE__);
		$rowpaginaurl = $paginaUrlCheck->num_rows;             
		if ($rowpaginaurl > 0) {
			$error2 = "Deze menu titel bestaat al. Voer een unieke menu titel in.";
			$error_item1_2 = 'ja';
			$paginaurlerror = true;
		}

	
		// pagina wegschrijven naar database
		// =================================
		if($paginaurlerror == false){
			$sql_insert = $mysqli->query("INSERT siteworkcms SET gebruikersid = '".$_SESSION['id']."',
																 item1 	= '".$mysqli->real_escape_string($_POST['item2'])."',
																 item2	= '".$mysqli->real_escape_string($_POST['item2'])."',
																 keuze1	= '".$_POST['keuze1']."',
																 paginaurl = '".slugify($_POST['item2'])."',
																 datum 	= NOW() ") or die($mysqli->error.__LINE__);											
			$rowid = $mysqli->insert_id;  
			$melding = "Gegevens zijn opgeslagen";
					
			// pagina redirecten om deze te kunnen bewerken
			// ============================================
			header('Location: ?page=pagina_bewerken&id='.$rowid.'');
		}
	}
}

if($_GET['cat']) { $cat = $_GET['cat']; }
else { $cat = ""; }

if($cat == ""){ 
	$catNieuw = "pagina";
} else {
	$catNieuw = $cat . " pagina";
}

?>

<div class="box-container">
  <div class="box box-2-3 md-box-full">

  <h3><span class="icon fas fa-copy"></span>Nieuwe <?=$catNieuw;?> toevoegen</h3>
	  <? if ($error){ ?><span class="error"><? echo $error; ?></span><? } ?>
	  <? if ($error2){ ?><span class="error"><? echo $error2; ?></span><? } ?>
	<form action="<? echo $PHP_SELF ?>" method="post" >
		<div class="form-group">
			<label for="keuze1">Categorie</label>
			<select name="keuze1" class="inputveld invoer dropdown<? if ($error_keuze1){ echo ' foutveld'; } ?>" placeholder="Selecteer een categorie" >	
			<? if ($_POST['keuze1']) { echo '<option value="'.$_POST['keuze1'].'">'.$_POST['keuze1'].'</option>'; } else { ?><option value="">selecteer een categorie</option><? } ?>	
				<? //categorien ophalen
				foreach (getCategorie() as $categorien) {
					if($cat == ""){ 
						$selected = "";
					} else {
						if(htmlspecialchars($categorien['categorie']) == $cat) {
							$selected = "selected";
						} else {
							$selected = "";
						}
					}
					echo '<option value="'.htmlspecialchars($categorien['categorie']).'" '.$selected.'>'.htmlspecialchars($categorien['categorie']).'</option>';		
				} //functie categorien ?>
			</select>
		</div>
		<div class="form-group">
			<label for="item2">Pagina titel</label><input type="text" name="item2" class="inputveld invoer<? if ($error_item2){ echo ' foutveld'; } ?>" placeholder="Paginatitel" value="<? echo stripslashes($_POST['item2']); ?>" />
		</div>
		<input type="hidden" name="opslaan" value="1" >
		<button name="save" class="btn fl-left save" type="submit">Opslaan</button>

</div>
<div class="box box-1-3 md-box-full">
	<h3><span class="icon far fa-info-circle"></span>Toelichting</h3>
	<div class="info">
	Klik op 'opslaan' om de pagina toe te voegen.
	Nadat de pagina is opgeslagen, kunt u afbeeldingen en andere informatie toevoegen.
	</div>
</div>
</form>
