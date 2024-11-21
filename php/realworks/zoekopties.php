<?php

// ################# zoekopties wonen ###################

if ($_POST['woningzoeken'] == '1') {
    $_SESSION['zoek_plaats'] = $_POST['zoek_plaats'];
    $_SESSION['zoek_type'] = $_POST['zoek_type'];
    $_SESSION['zoek_trefwoord'] = $_POST['zoek_trefwoord'];
    //prijsrange bepalen vanaf
	if ($_POST['van_prijs'] == "150000") {		$_SESSION['zoek_prijsvan'] = "10";  }
	if ($_POST['van_prijs'] == "200000") { 		$_SESSION['zoek_prijsvan'] = "150000"; }
	if ($_POST['van_prijs'] == "250000") { 		$_SESSION['zoek_prijsvan'] = "200000"; }
	if ($_POST['van_prijs'] == "350000") { 		$_SESSION['zoek_prijsvan'] = "250000"; }
	if ($_POST['van_prijs'] == "500000") { 		$_SESSION['zoek_prijsvan'] = "350000"; }
	if ($_POST['van_prijs'] == "600000") { 		$_SESSION['zoek_prijsvan'] = "500000"; }
	if ($_POST['van_prijs'] == "750000") { 		$_SESSION['zoek_prijsvan'] = "600000"; }
	if ($_POST['van_prijs'] == "1000000") {  	$_SESSION['zoek_prijsvan'] = "750000"; }
	if ($_POST['van_prijs'] == "90000000") { 	$_SESSION['zoek_prijsvan'] = "1000000";}
	if ($_POST['van_prijs'] == "") { 			$_SESSION['zoek_prijsvan'] = "0"; }

	//prijsrange bepalen tot
	if ($_POST['tot_prijs'] == "150000") {		$_SESSION['zoek_prijstot'] = "10"; }
	if ($_POST['tot_prijs'] == "200000") { 		$_SESSION['zoek_prijstot'] = "150000"; }
	if ($_POST['tot_prijs'] == "250000") { 		$_SESSION['zoek_prijstot'] = "200000"; }
	if ($_POST['tot_prijs'] == "350000") { 		$_SESSION['zoek_prijstot'] = "250000"; }
	if ($_POST['tot_prijs'] == "500000") { 		$_SESSION['zoek_prijstot'] = "350000"; }
	if ($_POST['tot_prijs'] == "600000") { 		$_SESSION['zoek_prijstot'] = "500000"; }
	if ($_POST['tot_prijs'] == "750000") { 		$_SESSION['zoek_prijstot'] = "600000"; }
	if ($_POST['tot_prijs'] == "1000000") {  	$_SESSION['zoek_prijstot'] = "750000"; }
	if ($_POST['tot_prijs'] == "90000000") { 	$_SESSION['zoek_prijstot'] = "1000000"; }
	if ($_POST['tot_prijs'] == "") { 			$_SESSION['zoek_prijstot'] = "10000000"; }

    // koop of huur
    if ($_POST['zoek_koop'] == 'ja') {
        $_SESSION['zoek_sale'] = 'ja';
    }
    if ($_POST['zoek_huur'] == 'ja') {
        $_SESSION['zoek_rent'] = 'ja';
    }
    if ($_POST['zoek_huur'] == 'ja' && $_POST['zoek_koop'] == '') {
        $_SESSION['zoek_sale'] = '';
    }
    if ($_POST['zoek_huur'] == '' && $_POST['zoek_koop'] == 'ja') {
        $_SESSION['zoek_rent'] = ''; 
        $_SESSION['zoek_sale'] = 'ja';
    }
    if ($_POST['zoek_huur'] == '' && $_POST['zoek_koop'] == '') {
        $_SESSION['zoek_rent'] = '';
        $_SESSION['zoek_sale'] = '';
    }

    header('Location: '.$_SERVER['REQUEST_URI']); // POST OMZETTEN NAAR SESSIE!!!!
}
if ($_POST['zoek_prijs'] == '' && $_SESSION['zoek_prijstot'] == '') {
    $_SESSION['zoek_prijsvan'] = '0';
    $_SESSION['zoek_prijstot'] = '90000000';
}

    if ($_GET['page'] == 'openhuis') {
        $openhuis = 'ja';
    }
    // nieuw
    if ($_GET['page'] == 'nieuw') {
        $nieuwquery = '(STATUS_DATEIN BETWEEN DATE_SUB(CURDATE() ,INTERVAL 14 day) AND CURDATE()) AND';
    } // deze query wordt dan bij-in de aanbod query gezet
    // recent verkocht
    if ($_GET['page'] == 'verkocht') {
        $verkochtquery = "STATUS_STATUS = 'VERKOCHT' or STATUS_STATUS = 'VERKOCHT_ONDER_VOORBEHOUD' or STATUS_STATUS = 'VERHUURD'  AND";
    } // deze query wordt dan bij-in de aanbod query gezet
    if ($_GET['page'] == 'huur') {
        $verhuurdquery = "RENT = 'ja' AND";
    } // deze query wordt dan bij-in de aanbod query gezet
    if ($_GET['page'] == 'koop') {
        $verhuurdquery = "SALE = 'ja' AND";
    } // deze query wordt dan bij-in de aanbod query gezet
    // nieuwbouw
    if ($_GET['page'] == 'nieuwbouw') {
        $nieuwbouwquery = "(kenmerken LIKE '%nieuwbouw%' OR bouwvorm = 'nieuwbouw') AND";
    } // "bouwvorm LIKE 'nieuwbouw' AND" deze query wordt dan bij-in de aanbod query gezet

    // queries per plaats ( voor linkjes in footer )
    if ($_GET['page'] == 'goor') {
        $plaatsquery = "ADDRESS_CITY = 'GOOR' AND";
    } // deze query wordt dan bij-in de aanbod query gezet
    if ($_GET['page'] == 'markelo') {
        $plaatsquery = "ADDRESS_CITY = 'MARKELO' AND";
    } // deze query wordt dan bij-in de aanbod query gezet
    if ($_GET['page'] == 'hengevelde') {
        $plaatsquery = "ADDRESS_CITY = 'HENGEVELDE' AND";
    } // deze query wordt dan bij-in de aanbod query gezet
    if ($_GET['page'] == 'diepenheim') {
        $plaatsquery = "ADDRESS_CITY = 'DIEPENHEIM' AND";
    } // deze query wordt dan bij-in de aanbod query gezet

// ################# zoekopties bog ###################

if ($_POST['bogzoeken'] == '1') {
    $_SESSION['zoek_type'] = $_POST['zoek_type'];
    $_SESSION['zoek_trefwoord'] = $_POST['zoek_trefwoord'];
    $_SESSION['zoek_metrage'] = $_POST['zoek_metrage'];
    $_SESSION['zoek_plaats'] = $_POST['zoek_plaats'];

    // metrage bepalen
    if ($_POST['zoek_metrage'] == '150') {
        $_SESSION['zoek_metragevan'] = '10';
        $_SESSION['zoek_metragetot'] = '150';
    }
    if ($_POST['zoek_metrage'] == '300') {
        $_SESSION['zoek_metragevan'] = '150';
        $_SESSION['zoek_metragetot'] = '300';
    }
    if ($_POST['zoek_metrage'] == '1000') {
        $_SESSION['zoek_metragevan'] = '300';
        $_SESSION['zoek_metragetot'] = '1000';
    }
    if ($_POST['zoek_metrage'] == '2000') {
        $_SESSION['zoek_metragevan'] = '1000';
        $_SESSION['zoek_metragetot'] = '2000';
    }
    if ($_POST['zoek_metrage'] == '5000') {
        $_SESSION['zoek_metragevan'] = '2000';
        $_SESSION['zoek_metragetot'] = '5000';
    }
    if ($_POST['zoek_metrage'] == '100000') {
        $_SESSION['zoek_metragevan'] = '5000';
        $_SESSION['zoek_metragetot'] = '100000';
    }
    if ($_POST['zoek_metrage'] == '') {
        $_SESSION['zoek_metragevan'] = '0';
        $_SESSION['zoek_metragetot'] = '1000000';
    }

    // koop of huur
    if ($_POST['zoek_koop'] == 'ja') {
        $_SESSION['zoek_sale'] = 'ja';
    }
    if ($_POST['zoek_huur'] == 'ja') {
        $_SESSION['zoek_rent'] = 'ja';
    }
    if ($_POST['zoek_huur'] == 'ja' && $_POST['zoek_koop'] == '') {
        $_SESSION['zoek_sale'] = '';
    }
    if ($_POST['zoek_huur'] == '' && $_POST['zoek_koop'] == 'ja') {
        $_SESSION['zoek_rent'] = '';
        $_SESSION['zoek_sale'] = 'ja';
    }
    if ($_POST['zoek_huur'] == '' && $_POST['zoek_koop'] == '') {
        $_SESSION['zoek_rent'] = 'ja';
        $_SESSION['zoek_sale'] = 'ja';
    }

    header('Location: '.$_SERVER['REQUEST_URI']); // POST OMZETTEN NAAR SESSIE!!!!
}

if ($_SESSION['zoek_rent'] == '' && $_SESSION['zoek_sale'] == '') {
    $_SESSION['zoek_rent'] = 'ja';
    $_SESSION['zoek_sale'] = 'ja';
}

// kenmerken
    if ($_GET['page'] == 'bedrijfsruimte' or
    $_GET['page'] == 'kantoorruimte' or
    $_GET['page'] == 'agrarischeenbospercelen' or
    $_GET['page'] == 'winkelruimte') {
        $_SESSION['zoek_cat'] = $_GET['page'];
        $kenmerk = $_GET['page'];
    } elseif ($_POST['zoek_cat']) {
        $kenmerk = $_POST['zoek_cat'];
    }
