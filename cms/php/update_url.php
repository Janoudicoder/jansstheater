<?php // checken of men wel is ingelogd
// ====================================
login_check_v2();

// bijwerken instellingen
// ======================

// eerst nog even de oude URL ophalen
// ==================================
$sqlurl = $mysqli->query("SELECT * FROM sitework_settings WHERE id = '1' ") or die($mysqli->error.__LINE__);
$rowurl = $sqlurl->fetch_assoc();

$oudeurl 	= $rowurl['weburl'];
$nieuweurl 	= $_POST['weburl'];

if($_POST['bijwerken'] == 1){
	$sql_update = $mysqli->query("UPDATE sitework_settings SET 	weburl = '".$nieuweurl."' WHERE id = '1'") or die($mysqli->error.__LINE__);
	
	
	// daarna ook de hele database doorzoeken en de url vervangen
	// ==========================================================
	$sql_update_url = $mysqli->query("UPDATE siteworkcms	SET 	externeurl = REPLACE(externeurl, '".$oudeurl."',  '".$nieuweurl."'),
																	tekst = REPLACE(tekst, '".$oudeurl."',  '".$nieuweurl."')
																				  
															WHERE 	externeurl LIKE '%".$oudeurl."%' or
																	tekst 	LIKE '%".$oudeurl."%'") ;

	$melding = "De wijzigingen zijn opgeslagen";
	$opgeslagen = "ja";
	header('Location: ?page=instellingen');
}

// ophalen instellingen
// ====================
$sql = $mysqli->query("SELECT * FROM sitework_settings WHERE id = '1' ") or die($mysqli->error.__LINE__);
$row = $sql->fetch_assoc(); ?>

<div class="box-container">
	<div class="box box-2-3 lg-box-full">
		<? if ($rowuser['id'] == "1") { ?>
			<h3><span class="icon fas fa-cog"></span>URL update</h3>
			<div class="content-container mt-0 mb-20" >
		<? } ?>
		<form action="<?=$PHP_SELF; ?>" method="POST" >	


				<div class="form-group">
					<label for="weburl">Website url</label><input type="text" name="weburl" class="inputveld invoer" placeholder="Voer hier de gehele url van de website in" value="<? echo $row['weburl']; ?>" />
				</div>


				<input type="hidden" name="bijwerken" value="1">
				<button name="opslaan" class="btn fl-left save" type="submit">Opslaan</button>
			</form>
		</div>
	</div>
</div>
