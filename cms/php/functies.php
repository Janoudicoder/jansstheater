<?php // checken of men wel is ingelogd
// ====================================

if (!isset($_SESSION['loggedin'])) {
	header('Location: '.$url.'/cms/index.php');
	exit;
}

//functies voor categrorie / kenmerk vertalingen
function getTranlationMenu($veld, $soort, $taal, $id)
    //bij categorie / kenmerk is $veld de waarde. Bij een normale tekst is het het id van de pagina
{
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    mysqli_set_charset($mysqli, 'utf8');
    //kenmerken
    if ($soort == 'kenmerk') {
        $sqlkop = $mysqli->query("SELECT * FROM sitework_kenmerken WHERE kenmerk = '" . $veld . "'") or die($mysqli->error . __LINE__);
        $rowkop = $sqlkop->fetch_assoc();
        if ($taal == 'de') {
            return $rowkop['kenmerk_de'];
        } else if ($taal == 'en') {
            return $rowkop['kenmerk_en'];
        }else{
            return $rowkop['kenmerk'];
        }
    }
    //categorieen
    if ($soort == 'categorie') {
        $sqlcat = $mysqli->query("SELECT * FROM sitework_categorie WHERE categorie = '" . $veld . "'") or die($mysqli->error . __LINE__);
        $rowcat = $sqlcat->fetch_assoc();
        if ($taal == 'de') {
            return $rowcat['categorie_de'];
        } else if ($taal == 'en') {
            return $rowcat['categorie_en'];
        }else{
            return $rowcat['categorie'];
        }
    }
    //siteworkcms velden
    if($soort == 'veld'){
        //bij een vertaling de gegevens uit de sitework_vertaling db halen
        if($taal != 'nl' AND $taal != ''){
            $sqlveld = $mysqli->query("SELECT waarde FROM sitework_vertaling WHERE cms_id = '" . $id . "' AND taal = '" . $taal . "' AND veld = '".$veld."'") or die($mysqli->error . __LINE__);
            $rowveld = $sqlveld->fetch_assoc();
            return $rowveld['waarde'];
        }else{
            //geen taal meegegeven. Dus gewoon nederlands tonen uit siteworkcms tabel
            $sqlveld = $mysqli->query("SELECT $veld FROM siteworkcms WHERE id = '" . $id . "'") or die($mysqli->error . __LINE__);
            $rowveld = $sqlveld->fetch_assoc();
            return $rowveld[$veld];
        }

    }

}

//functies voor categrorie / kenmerk vertalingen
function getTranslation($veld, $soort, $taal, $id)
    //bij categorie / kenmerk is $veld de waarde. Bij een normale tekst is het het id van de pagina
{
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    mysqli_set_charset($mysqli, 'utf8');
    //kenmerken
    if ($soort == 'kenmerk') {
        $sqlkop = $mysqli->query("SELECT * FROM sitework_kenmerken WHERE kenmerk = '" . $veld . "'") or die($mysqli->error . __LINE__);
        $rowkop = $sqlkop->fetch_assoc();

        if ($taal == 'nl') {
            return $rowkop['kenmerk'];
        } else{
            return $rowkop['kenmerk_' . $taal];
        }
    }
    //categorieen
    if ($soort == 'categorie') {
        $sqlcat = $mysqli->query("SELECT * FROM sitework_categorie WHERE categorie = '" . $veld . "'") or die($mysqli->error . __LINE__);
        $rowcat = $sqlcat->fetch_assoc();

        if ($taal == 'nl') {
            return $rowcat['categorie'];
        } else{
            return $rowcat['categorie_' . $taal];
        }
    }
    //siteworkcms velden
    if($soort == 'veld'){
        //bij een vertaling de gegevens uit de sitework_vertaling db halen
        if($taal != 'nl' AND $taal != ''){
            $sqlveld = $mysqli->query("SELECT waarde FROM sitework_vertaling WHERE cms_id = '" . $id . "' AND taal = '" . $taal . "' AND veld = '".$veld."'") or die($mysqli->error . __LINE__);
            $rowveld = $sqlveld->fetch_assoc();
            return $rowveld['waarde'];
        }else{
            //geen taal meegegeven. Dus gewoon nederlands tonen uit siteworkcms tabel
            $sqlveld = $mysqli->query("SELECT $veld FROM siteworkcms WHERE id = '" . $id . "'") or die($mysqli->error . __LINE__);
            $rowveld = $sqlveld->fetch_assoc();
            return $rowveld[$veld];
        }

    }

}

//functies voor categrorie / kenmerk vertalingen
function getBlockTranslation($veld, $taal, $block_id, $hoofd_id, $id) {
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    mysqli_set_charset($mysqli, 'utf8');
    
    //sitework_vertaling_blocks velden
    //bij een vertaling de gegevens uit de sitework_vertaling db halen
    if($taal != 'nl' AND $taal != ''){
        if($hoofd_id <> "" && $hoofd_id != null) {
            $sqlveld = $mysqli->query("SELECT waarde FROM sitework_vertaling_blocks WHERE cms_id = '" . $id . "' AND hoofdid = '".$hoofd_id."' AND taal = '" . $taal . "' AND veld = '".$veld."'") or die($mysqli->error . __LINE__);
            $rowveld = $sqlveld->fetch_assoc();
            return $rowveld['waarde'];
        } else {
            $sqlveld = $mysqli->query("SELECT waarde FROM sitework_vertaling_blocks WHERE cms_id = '" . $id . "' AND id = '".$block_id."' AND taal = '" . $taal . "' AND veld = '".$veld."'") or die($mysqli->error . __LINE__);
            $rowveld = $sqlveld->fetch_assoc();
            return $rowveld['waarde'];
        }
    }else{
        //geen taal meegegeven. Dus gewoon nederlands tonen uit siteworkcms tabel
        $sqlveld = $mysqli->query("SELECT * FROM sitework_blocks WHERE cms_id = '" . $id . "' AND id = '".$block_id."'") or die($mysqli->error . __LINE__);
        $rowveld = $sqlveld->fetch_assoc();
        return $rowveld[$veld];
    }
}

//functie voor opmaken taalswitcher
function taalmenu($id, $taal)
{
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    mysqli_set_charset($mysqli, 'utf8');
    $sqltaal = $mysqli->query("SELECT * FROM sitework_taal WHERE actief = '1' ORDER BY taalkort DESC") or die($mysqli->error . __LINE__);

    $sqlpaginas = $mysqli->query("SELECT id FROM siteworkcms where id = '" . $id . "'") or die($mysqli->error . __LINE__);
    $rowpaginas = $sqlpaginas->fetch_assoc();

    while ($rowtaal = $sqltaal->fetch_assoc()) {
        $sqlvertalingPaginas = $mysqli->query("SELECT * FROM sitework_vertaling WHERE cms_id = '" . $id . "' AND taal = '".$rowtaal['taalkort']."'") or die($mysqli->error . __LINE__);
        $numberOfRows = $sqlvertalingPaginas->num_rows;

        //knop op actief zetten als taal van pagina gelijk is met de taal in de loop
        switch ($rowtaal['taalkort']) {
            //op basis van de taal de url's en classes op de knoppen aanpassen
            case 'nl':
                if ($rowpaginas['id']) {
                    $statusClass = 'available';
                    $paginaUrl = 'maincms.php?page=pagina_bewerken&id=' . $rowpaginas['id'] . '&taal=nl';
                    $iframeCode = '';
                } else {
                    $statusClass = 'notavailable';
                    $paginaUrl = 'php/nieuwe_taalpagina.php?cmsid=' . $id . '&taal=nl';
                    $iframeCode = 'data-fancybox data-small-btn="true" data-fancybox-trigger="taaltoevoegen" data-type="iframe"';
                }
                if ($taal == 'nl') {
                    $activeClass = "active";
                } else {
                    $activeClass = "";
                }
                break;
            case $rowtaal['taalkort']:
                if ($numberOfRows > 0) {
                    $statusClass = 'available';
                    $paginaUrl = 'maincms.php?page=pagina_bewerken&id=' . $id . '&taal=' . $rowtaal['taalkort'];
                    $iframeCode = '';
                } else {
                    $statusClass = 'notavailable';
                    $paginaUrl = 'php/nieuwe_taalpagina.php?cmsid=' . $id . '&taal=' . $rowtaal['taalkort'];
                    $iframeCode = 'data-fancybox data-small-btn="true" data-fancybox-trigger="taaltoevoegen" data-type="iframe"';
                }
                if ($taal == $rowtaal['taalkort']) {
                    $activeClass = "active";
                } else {
                    $activeClass = "";
                }
                break;
            // case 'de':
            //     if ($numberOfRows > 0) {
            //         $statusClass = 'available';
            //         $paginaUrl = 'maincms.php?page=pagina_bewerken&id=' . $id . '&taal=de';
            //         $iframeCode = '';
            //     } else {
            //         $statusClass = 'notavailable';
            //         $paginaUrl = 'php/nieuwe_taalpagina.php?cmsid=' . $id . '&taal=de';
            //         $iframeCode = 'data-fancybox data-small-btn="true" data-fancybox-trigger="taaltoevoegen" data-type="iframe"';
            //     }
            //     if ($taal == 'de') {
            //         $activeClass = "active";
            //     } else {
            //         $activeClass = "";
            //     }
            //     break;

        }

        if(!$taal AND $rowtaal['taalkort'] == 'nl'){
            $statusClass = 'available';
            $paginaUrl = 'maincms.php?page=pagina_bewerken&id=' . $rowpaginas['id'] . '&taal=nl';
            $iframeCode = '';
            $activeClass = "active";
        }

        echo '
            <a href="' . $paginaUrl . '" '.$iframeCode. 'class="tab status ' . $statusClass . ' ' . $activeClass . '"><img src="/flags/'.$rowtaal['taalkort'].'.svg" alt="'.$rowtaal['taalkort'].'">' . $rowtaal['taalkort'] . '</a>
        ';
    }

}

//functie voor opmaken taalswitcher footer
function footermenu($taal)
{
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    mysqli_set_charset($mysqli, 'utf8');
    $sqltaal = $mysqli->query("SELECT * FROM sitework_taal WHERE actief = '1' ORDER BY taalkort DESC") or die($mysqli->error . __LINE__);

    while ($rowtaal = $sqltaal->fetch_assoc()) {
        $sqlWebsiteFooter = $mysqli->query("SELECT * FROM sitework_website_settings WHERE taal = '" . $rowtaal['taalkort'] . "'") or die($mysqli->error . __LINE__);
        $rowWebsiteFooter = $sqlWebsiteFooter->fetch_assoc();
        $numberOfRows = $sqlWebsiteFooter->num_rows;

        //knop op actief zetten als taal van pagina gelijk is met de taal in de loop
        switch ($rowtaal['taalkort']) {
            //op basis van de taal de url's en classes op de knoppen aanpassen
            case 'nl':
                if ($numberOfRows > 0) {
                    $statusClass = 'available';
                    $paginaUrl = 'maincms.php?page=website-instellingen&footertaal=nl';
                    $iframeCode = '';
                } else {
                    $statusClass = 'notavailable';
                    $paginaUrl = 'php/nieuwe_footertekst.php?taal=nl';
                    $iframeCode = 'data-fancybox data-small-btn="true" data-fancybox-trigger="footerteksttoevoegen" data-type="iframe"';
                }
                if ($taal == 'nl') {
                    $activeClass = "active";
                } else {
                    $activeClass = "";
                }
                break;
            case $rowtaal['taalkort']:
                    if ($numberOfRows > 0) {
                        $statusClass = 'available';
                        $paginaUrl = 'maincms.php?page=website-instellingen&footertaal=' . $rowtaal['taalkort'];
                        $iframeCode = '';
                    } else {
                        $statusClass = 'notavailable';
                        $paginaUrl = 'php/nieuwe_footertekst.php?taal=' . $rowtaal['taalkort'];
                        $iframeCode = 'data-fancybox data-small-btn="true" data-fancybox-trigger="taaltoevoegen" data-type="iframe"';
                    }
                    if ($taal == $rowtaal['taalkort']) {
                        $activeClass = "active";
                    } else {
                        $activeClass = "";
                    }
                    break;

        }

        if(!$taal AND $rowtaal['taalkort'] == 'nl'){
            $statusClass = 'available';
            $paginaUrl = 'maincms.php?page=website-instellingen&footertaal=nl';
            $iframeCode = '';
            $activeClass = "active";
        }

        echo '
            <a href="' . $paginaUrl . '" '.$iframeCode. 'class="tab status ' . $statusClass . ' ' . $activeClass . '"><img src="/flags/'.$rowtaal['taalkort'].'.svg" alt="'.$rowtaal['taalkort'].'">' . $rowtaal['taalkort'] . '</a>
        ';
    }

}

function getCustomFields($cms_id = 0, $cat = "", $kenmerk = "", $template_id = 0, $taal = '') {
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    mysqli_set_charset($mysqli, 'utf8');

    $cmsCheck = "(cms_id = '".$cms_id."' OR cms_id = '0' OR";
    $catCheck = "cat = '".$cat."' OR";
    $taalCheck = "taal = '".$taal."' OR";

    if($kenmerk != "") {
        $extraCheck = "kenmerk LIKE '%".$kenmerk."%')";
    } elseif($kenmerk != "" && $template_id != 0) {
        $extraCheck = "kenmerk LIKE '%".$kenmerk."%' OR template_id = '".$template_id."')";
    } elseif($template_id != 0) {
        $extraCheck = "template_id = '".$template_id."')";
    } else { $extraCheck = "kenmerk LIKE '%%')"; }

    $sqlcf = $mysqli->query("SELECT * FROM sitework_customfields WHERE status = 'actief' AND ".$cmsCheck." ".$catCheck." ".$taalCheck." ".$extraCheck." ORDER BY id ASC") or die($mysqli->error . __LINE__);

    $result = [];
    $result['count'] = $sqlcf->num_rows;
    while ($rowcf = $sqlcf->fetch_assoc()) {
        $result['data'][] = $rowcf;
    }

    return $result;
}

function getSubCustomFields($koppel_id = 0) {
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    mysqli_set_charset($mysqli, 'utf8');

    $sqlcf_opties = $mysqli->query("SELECT * FROM sitework_customfields_opties WHERE koppel_id = '".$koppel_id."' ORDER BY id ASC") or die($mysqli->error . __LINE__);

    while ($rowcf_opties = $sqlcf_opties->fetch_assoc()) {
        $customFields_opties[] = $rowcf_opties;
    }
    return $customFields_opties;
}

function getCustomFieldWaarde($veld_id = 0, $cms_id = 0, $taal = '') {
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    mysqli_set_charset($mysqli, 'utf8');

    $sqlcf_opties = $mysqli->query("SELECT waarde FROM sitework_customfields_waardes WHERE veld_id = '".$veld_id."' AND cms_id = '".$cms_id."' AND taal = '".$taal."' ORDER BY id ASC") or die($mysqli->error . __LINE__);
    $rowcf_opties = $sqlcf_opties->fetch_assoc();
    
    $customField_waarde = $rowcf_opties['waarde'];
    
    return $customField_waarde;
}

function getCustomFieldNonce($veld_id = 0, $cms_id = 0, $taal = '') {
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    mysqli_set_charset($mysqli, 'utf8');

    $sqlcf_nonce = $mysqli->query("SELECT sw_nonce FROM sitework_customfields_waardes WHERE veld_id = '".$veld_id."' AND cms_id = '".$cms_id."' AND taal = '".$taal."' ORDER BY id ASC") or die($mysqli->error . __LINE__);
    $rowcf_nonce = $sqlcf_nonce->fetch_assoc();
    
    $customField_nonce = $rowcf_nonce['sw_nonce'];
    
    return $customField_nonce;
}

// Maak nonce, voor waarde opslaan. Hij word eerst gecheckt dat deze nonce niet al bestaat 
// ============================
function checkNonce($nonce) {
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    mysqli_set_charset($mysqli, 'utf8');

    $stmt = $mysqli->prepare("SELECT id FROM sitework_customfields_waardes WHERE sw_nonce = ?");
    $stmt->bind_param('s', $nonce);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return false;
    } else {
        return true;
    }
}
function generateNonce($length = 10) {
    $chars = '1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
    $char_len = strlen($chars) - 1;
    $output = '';

    do {
        $output = '';
        while (strlen($output) < $length) {
            $output .= $chars[rand(0, $char_len)];
        }
        $output = str_replace(" ", "", $output);
    } while (!checkNonce($output));

    return $output;
}

// functie categorie ophalen
// =========================
function getCategorie()
{
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    mysqli_set_charset($mysqli, 'utf8');
    $sqlcat = $mysqli->query("SELECT * FROM sitework_categorie") or die($mysqli->error . __LINE__);

    while ($rowcat = $sqlcat->fetch_assoc()) {
        $categorien[] = $rowcat;
    }
    return $categorien;
}

function getKenmerken($taal = 'nl') {

	$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
	mysqli_set_charset($mysqli, 'utf8');

    if($taal != 'nl') {
        $getTaalKen = "kenmerk_".$taal;        
    } else { $getTaalKen = "kenmerk"; }

	$sqlKen = $mysqli->query("SELECT * FROM sitework_kenmerken WHERE actief = '1'") or die($mysqli->error.__LINE__);

    while($rowKen = $sqlKen->fetch_assoc()){
        $kenmerken[] = $rowKen[$getTaalKen];
    }
    return $kenmerken;
}

// functie hoofdmenu/submenu ophalen
// =================================
function getKoppelingen() {

	$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
	mysqli_set_charset($mysqli, 'utf8');
	$sqlkop = $mysqli->query("SELECT id,keuze1,item1 FROM siteworkcms WHERE keuze1 = 'hoofdmenu' AND status = 'actief'") or die($mysqli->error.__LINE__);

    //$icensers = array();
    while($rowkop = $sqlkop->fetch_assoc()){
        $koppelingen[] = $rowkop;
    }
    return $koppelingen;
}

// functie hoofdmenu/submenu ophalen
// =================================
function getKoppelingenAll() {
 
	$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
	mysqli_set_charset($mysqli, 'utf8');
	$sqlkopall = $mysqli->query("SELECT id,keuze1,item1 FROM siteworkcms WHERE id != '1' AND status = 'actief'") or die($mysqli->error.__LINE__);

    //$icensers = array();
    while($rowkopall = $sqlkopall->fetch_assoc()){
        $koppelingenall[] = $rowkopall;
    }
    return $koppelingenall;
}

// functie paginas ophalen die nog niet in het menu staan
// =========================
function getPagesNotMenu() {

	$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
	mysqli_set_charset($mysqli, 'utf8');
	$sqlPageNotMenu = $mysqli->query("SELECT * FROM siteworkcms WHERE in_menu = 0 AND status <> 'prullenbak' ORDER BY id DESC") or die($mysqli->error.__LINE__);

    while($rowPageNotMenu = $sqlPageNotMenu->fetch_assoc()){
        if($rowPageNotMenu['id'] != "2" && $rowPageNotMenu['id'] != "69") {
            $pagesNotMenu[] = $rowPageNotMenu;
        }
    }
    return $pagesNotMenu;
}

// functie paginas ophalen die in het menu staan
// =========================
function getPagesMenu() {

	$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
	mysqli_set_charset($mysqli, 'utf8');
	$sqlPageMenu = $mysqli->query("SELECT * FROM siteworkcms WHERE in_menu = 1 AND hoofdid = '0' ORDER BY menu_volgorde ASC") or die($mysqli->error.__LINE__);

    while($rowPageMenu = $sqlPageMenu->fetch_assoc()){
        $pageMenu[] = $rowPageMenu;
    }
    return $pageMenu;
}

function getSubPagesMenu($hoofdid) {

	$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
	mysqli_set_charset($mysqli, 'utf8');
	$sqlSubPageMenu = $mysqli->query("SELECT * FROM siteworkcms WHERE in_menu = 1 AND hoofdid = ".$hoofdid." ORDER BY menu_volgorde ASC") or die($mysqli->error.__LINE__);

    while($rowSubPageMenu = $sqlSubPageMenu->fetch_assoc()){
        $subPageMenu[] = $rowSubPageMenu;
    }
    return $subPageMenu;
}

// schone url met toevoegen pagina
// ===============================
function slugify ($slug) {
    $slug = utf8_encode($slug);
    $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $slug);
    $slug = preg_replace('/[^a-z0-9- ]/i', '', $slug);
    $slug = str_replace(' ', '-', $slug);
    $slug = trim($slug, '-');
    $slug = strtolower($slug);

    if (empty($slug)) {
        return 'n-a';
    }

    return $slug;
}

function ip_echo_start() {
    ob_start();
}

function ip_echo_end() {
    $content = ob_get_clean();
    // Specify the allowed IP address
    $allowed_ip = '88.159.229.158';

    // Get the visitor's IP address
    $visitor_ip = $_SERVER['REMOTE_ADDR'];

    // Check if the visitor's IP address matches the allowed IP address
    if ($visitor_ip === $allowed_ip) {
        echo $content;
    }
}

// debug console - javascript
function debug_to_console($data)
{
    $output = $data;
    if (is_array($output)) {
        $output = implode(',', $output);
    }

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}


//debug dump functie
function dump($d){
    echo "<pre>";
        print_r(var_dump($d));
    echo "</pre>";
}


//debug functie
function d($d){
    echo "<pre>";
    print_r($d);
    echo "</pre>";
}
?>
