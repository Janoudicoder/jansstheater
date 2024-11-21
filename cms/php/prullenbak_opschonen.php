<?php
// checken of men wel is ingelogd
// ==============================
include ('ftp/config.php');

// met deze functie worden alle pagina's definitief verwijderd. Inclusief alle documenten en afbeeldingen
// ======================================================================================================	
	$sql = $mysqli->query("SELECT * FROM siteworkcms WHERE status = 'prullenbak' AND id <> '1' /* startpagina */ AND id <> '2' /* pagina niet gevonden */") or die($mysqli->error.__LINE__);			
	$rows = $sql->num_rows;
	
	$tel = 0;
	while ($row = $sql->fetch_assoc()){  
		$tel++;
		
		// eerst de bijbehorende foto`s verwijderen
		// ========================================
		$sql_delimg = $mysqli->query("DELETE FROM sitework_img WHERE cms_id = '".$row['id']."' ") or die($mysqli->error.__LINE__);  // afbeelding uit db verwijderen
	
		// dan de bijbehorende documenten verwijderen
		// ==========================================
		$sql_deldoc = $mysqli->query("DELETE FROM sitework_doc WHERE cms_id = '".$row['id']."' ") or die($mysqli->error.__LINE__);  // document uit db verwijderen

		// Verwijder blokken
		// =================
		$sql_blocksdel = $mysqli->query("DELETE from sitework_blocks WHERE cms_id = '".$row['id']."' ") or die($mysqli->error.__LINE__);

		// Verwijder blok vertalingen
		// =================
		$sql_blocks_vertaal_del = $mysqli->query("DELETE from sitework_vertaling_blocks WHERE cms_id = '".$row['id']."' ") or die($mysqli->error.__LINE__);

		// Verwijder pagina vertalingen
		// =================
		$sql_pagina_vertaal_del = $mysqli->query("DELETE from sitework_vertaling WHERE cms_id = '".$row['id']."' ") or die($mysqli->error.__LINE__);
	
		// het item zelf verwijderen
		// =========================
		$sql_del = $mysqli->query("DELETE from siteworkcms WHERE id = '".$row['id']."' ") or die($mysqli->error.__LINE__);
	}

// redirecten zodat delid uit de url is
// ====================================
	if ($rows === $tel) {
		// Redirect with JavaScript
		echo "<script type='text/javascript'>
				window.location.href = '$url/cms/maincms.php?page=prullenbak';
			</script>";
	}
?>