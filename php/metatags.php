<?php 

if ($_GET['page'] == "woning") {
	// meta titel woningen
	// ===================
	$pandtitle = $mysqli->query("SELECT * FROM sitework_woonhuizen WHERE paginaurl = '" . $_GET['title'] . "'") or die($mysqli->error . __LINE__);
    $rowpandtitle = $pandtitle->fetch_assoc();
    $titel = $rowpandtitle['ADDRESS_STREETNAME'] . " " . $rowpandtitle['ADDRESS_STREETNR'] . " " . $rowpandtitle['ADDRESS_STREETNREXTENSION'] . " - " . ucfirst(strtolower($rowpandtitle['ADDRESS_CITY']));
} else {
	// meta titel gewone cms pagina's
	// ==============================
	if (the_field('meta_titel') <> "") { $titel =  the_field('meta_titel'); }
	else { 
		$titel = strip_tags(substr(ucfirst(the_field('item2')), 0, 100));  
	}
}
// meta description
// ================
if (the_field('meta_beschrijving') <> "") { $description = the_field('meta_beschrijving'); }
else { 	$strip = strip_tags(get_content());
		$description_ruw = strtolower(substr($strip, 0, 170));
		$description = preg_replace('/[ ]{2,}|[\t]/', ' ', trim($description_ruw));
} 

// meta keywords (maximaal 20 keywords)
// ====================================
if (the_field('meta_keywords') <> "") { $keywords = the_field('meta_keywords'); }
else { 	$keywords_ruw =  implode(", ", array_slice(preg_split("/\s+/", $strip), 0, 30));
		$keywords = strtolower(str_replace(",,", ",", $keywords_ruw)); 
} ?>