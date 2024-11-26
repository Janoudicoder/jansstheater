<?php // function voor het inkorten van tekst op hele woorden
// ==========================================================

// get_field uitleg
// ===============
/*

Als de functie leeggelaten word haalt hij alle waardes van de huidige pagina in de huidige taal op.
Deze worden weergeven in een array formaat en je krijgt het in dit format:

    [slug] => [waarde] (Voorbeeld = [team_tel_nr] => ["06 12345678"])

    Je kan ook waardes meegeven aan de functie, dat kunnen de volgende waardes zijn.
    - pagina id: 1 (voorbeeld = get_field('1'); )
    - categorie id/naam: categorie_pagina of categorie_11 (voorbeeld = get_field('categorie_1'); )
    - kenmerk id/naam: kenmerk_uitgelicht of kenmerk_22 (voorbeeld = get_field('kenmerk_uitgelicht'); )
    - template id: 1 - (voorbeeld = get_field('template_1'); )
    - slug: als er een slug word meegegeven zul je de id leeg moeten laten dan ziet je get_field er dus zo uit:
        $slugwaarde = get_field('', 'veld_slug'); 
        Met een slug krijg je dus altijd 1 waarde omdat je 1 specifiek veld opvraagd.

*/
// ======================
// Einde get_field uitleg

function get_field($get_post_id = '', $slug = '') {
    // MySql instellen
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    mysqli_set_charset($mysqli, 'utf8');

    // Hier word gecontroleerd of er een post_id word meegegeven
    global $post_taal;
    global $post_id;

    if($slug != '') {
        $sqlSlug = $mysqli->query("SELECT id FROM sitework_customfields WHERE slug = '".$slug."'") or die($mysqli->error . __LINE__);
        $rowSlug = $sqlSlug->fetch_assoc();

        if($get_post_id){
            $sqlSlugWaarde = $mysqli->query("SELECT waarde FROM sitework_customfields_waardes WHERE veld_id = '".$rowSlug['id']."' AND cms_id = '".$get_post_id."' AND taal = '".$post_taal."'") or die($mysqli->error . __LINE__);
            $rowSlugWaarde = $sqlSlugWaarde->fetch_assoc();
        }else{
            $sqlSlugWaarde = $mysqli->query("SELECT waarde FROM sitework_customfields_waardes WHERE veld_id = '".$rowSlug['id']."' AND cms_id = '".$post_id."' AND taal = '".$post_taal."'") or die($mysqli->error . __LINE__);
            $rowSlugWaarde = $sqlSlugWaarde->fetch_assoc();
        }        
        return $rowSlugWaarde['waarde'];
    } elseif($get_post_id != '') {
        // Nu bekijken we met wat voor id we werken,
        // Dat zijn de volgende mogelijkheden: id = 1, categorie = cat_1, kenmerk = kenmerk_1 or template = template_id
        if(str_contains($get_post_id, '_')) {
            $posttype = explode('_', $get_post_id);

            switch ($posttype[0]) {
                case "categorie":
                    if(isNumbersOnly($posttype[1])):
                        $sqlCat = $mysqli->query("SELECT categorie FROM sitework_categorie WHERE id = '" . $posttype[1] . "'") or die($mysqli->error . __LINE__);
                        $rowCat = $sqlCat->fetch_assoc();

                        $query = "SELECT * FROM sitework_customfields WHERE cat = '".strtolower($rowCat['categorie'])."'";
                    elseif(isLettersOnly($posttype[1])):
                        $query = "SELECT * FROM sitework_customfields WHERE cat = '".strtolower($posttype[1])."'";
                    else:
                        return "voer een geldig ID in. <strong>" . $get_post_id ."</strong> is geen geldig ID.";
                    endif;

                    break;
                case "kenmerk":
                    if(isNumbersOnly($posttype[1])):
                        $sqlKenmerk = $mysqli->query("SELECT kenmerk FROM sitework_kenmerken WHERE id = '" . $posttype[1] . "'") or die($mysqli->error . __LINE__);
                        $rowKenmerk = $sqlKenmerk->fetch_assoc();

                        $query = "SELECT * FROM sitework_customfields WHERE kenmerk = '".strtolower($rowKenmerk['kenmerk'])."'";
                    elseif(isLettersOnly($posttype[1])):
                        $query = "SELECT * FROM sitework_customfields WHERE kenmerk = '".strtolower($posttype[1])."'";
                    else:
                        return "voer een geldig ID in. <strong>" . $get_post_id ."</strong> is geen geldig ID.";
                    endif;

                    break;
                case "template":
                    if(isNumbersOnly($posttype[1])):
                        $query = "SELECT * FROM sitework_customfields WHERE template_id = '".$posttype[1]."'";
                    elseif(isLettersOnly($posttype[1])):
                        $sqlTemplate = $mysqli->query("SELECT id FROM sitework_templates WHERE naam = '" . $posttype[1] . "'") or die($mysqli->error . __LINE__);
                        $rowTemplate = $sqlTemplate->fetch_assoc();

                        $query = "SELECT * FROM sitework_customfields WHERE template_id = '".$rowTemplate['id']."'";
                    else:
                        return "voer een geldig ID in. <strong>" . $get_post_id ."</strong> is geen geldig ID.";
                    endif;

                    break;
                default:
                    return "voer een geldig ID in. <strong>" . $get_post_id ."</strong> is geen geldig ID.";
            }

            $sqlPakVelden = $mysqli->query($query) or die($mysqli->error . __LINE__);
            while($rowPakVelden = $sqlPakVelden->fetch_assoc()) {
                $sqlPakVeldenWaarde = $mysqli->query("SELECT waarde FROM sitework_customfields_waardes WHERE veld_id = '".$rowPakVelden['id']."' AND cms_id = '".$post_id."' AND taal = '".$post_taal."' ") or die($mysqli->error . __LINE__);
                $rowPakVeldenWaarde = $sqlPakVeldenWaarde->fetch_assoc();

                $customVelden[] = $rowPakVeldenWaarde['waarde'];
            }
            
            return $customVelden;

        }
    } else {
        $sqlPakAlleVelden = $mysqli->query("SELECT id,veld,slug FROM sitework_customfields WHERE cms_id = '".$post_id."'") or die($mysqli->error . __LINE__);
            while($rowPakAlleVelden = $sqlPakAlleVelden->fetch_assoc()) {
                $sqlPakAlleVeldenWaarde = $mysqli->query("SELECT waarde FROM sitework_customfields_waardes WHERE veld_id = '".$rowPakAlleVelden['id']."' AND cms_id = '".$post_id."' AND taal = '".$post_taal."' ") or die($mysqli->error . __LINE__);
                $rowPakAlleVeldenWaarde = $sqlPakAlleVeldenWaarde->fetch_assoc();

                $alleCustomVelden[$rowPakAlleVelden['slug']] = $rowPakAlleVeldenWaarde['waarde'];
            }
            
            return $alleCustomVelden;
    }
}

// Checkt of er alleen maar nummers in een string staan
function isNumbersOnly($string) {
    return ctype_digit($string);
}

// Checkt of er alleen maar letters in een string staan
function isLettersOnly($string) {
    return ctype_alpha($string);
}

function limit_text($text, $maxchar){
    $split=explode(" ", $text);
    $newtext = "";
    $length=0;
    foreach ($split as $word) {
        $word = " ".$word;
        $length+=strlen($word);
        if ($length>$maxchar) {
            break;
        }
        $newtext .= $word;
    }
    return $newtext;
}

function zoekWoordLatenZien($text, $word, $numWordsBefore = 5, $numWordsAfter = 5) {
    // Vind de positie van het woord
    $pos = stripos($text, $word);
  
    if ($pos === false) {
      echo limit_text($text, 75);
    } else {
        // Pak woorden voor en na het zoekwoord
        $before = explode(" ", substr($text, 0, $pos));
        $after = explode(" ", substr($text, $pos + strlen($word)));
    
        // Pak nu het aantal woorden dat je voor en na wilt
        $contextBefore = array_slice($before, -$numWordsBefore);
        $contextAfter = array_slice($after, 0, $numWordsAfter);
    
        // Voeg woorden toe aan het zoekwoord
        $context = implode(" ", array_filter($contextBefore)) . (empty($contextBefore) ? "" : " ") . "<span class=\"zoekHighlight\">$word</span>" . (empty($contextAfter) ? "" : " ") . implode(" ", array_filter($contextAfter));
    
        // $context = implode(" ", array_filter($context));
        
        echo $context;
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
        $sqlveld = $mysqli->query("SELECT waarde FROM sitework_vertaling_blocks WHERE cms_id = '" . $id . "' AND id = '".$block_id."' AND taal = '" . $taal . "' AND veld = '".$veld."'") or die($mysqli->error . __LINE__);
        
        if($sqlveld->num_rows > 0){
            $rowveld = $sqlveld->fetch_assoc();
            return $rowveld['waarde'];
        } else {
            $sqlveld = $mysqli->query("SELECT waarde FROM sitework_vertaling_blocks WHERE cms_id = '" . $id . "' AND hoofdid = '".$hoofd_id."' AND taal = '" . $taal . "' AND veld = '".$veld."'") or die($mysqli->error . __LINE__);
            $rowveld = $sqlveld->fetch_assoc();
            return $rowveld['waarde'];
        }
    } else {
        //geen taal meegegeven. Dus gewoon nederlands tonen uit siteworkcms tabel
        $sqlveld = $mysqli->query("SELECT * FROM sitework_blocks WHERE cms_id = '" . $id . "' AND id = '".$block_id."'") or die($mysqli->error . __LINE__);
        $rowveld = $sqlveld->fetch_assoc();
        return $rowveld[$veld];
    }
}

function the_field($slug = '', $id_or_other = '', $extra_id = '') {
    // MySql instellen
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    mysqli_set_charset($mysqli, 'utf8');

    // Hier word gecontroleerd of er een post_id word meegegeven
    global $post_taal;
    global $post_id;

    if($slug == 'categorie' && $extra_id == "") 
    {
        if($id_or_other <> "") {
            return getTranslation($id_or_other, $slug, $post_taal, '');
        } else {
            return "Vergeet niet om een categorie ID mee te geven.";
        }
    } 
    elseif($slug == 'kenmerk') 
    {
        if($id_or_other <> "") {
            return getTranslation($id_or_other, $slug, $post_taal, '');
        } else {
            return "Vergeet niet om een kenmerk ID mee te geven.";
        }
    } 
    elseif($slug == "pagina") 
    {
        $sqlVolledigePagina = $mysqli->query("SELECT * FROM siteworkcms WHERE id = '" . $post_id . "' AND taal = 'nl'") or die($mysqli->error . __LINE__);
        while($rowVolledigePagina = $sqlVolledigePagina->fetch_assoc()) {
            foreach($rowVolledigePagina as $key => $value) {
                $value = getTranslation($key, 'veld', $post_taal, $post_id);

                if (!is_null($value) && $value !== '') {
                    $paginaWaardes[$key] = $value;
                }
            }
        }
        
        return $paginaWaardes;
    } 
    elseif($id_or_other == 'cf') 
    {
        if($slug <> "") {
            $sqlSlug = $mysqli->query("SELECT id FROM sitework_customfields WHERE slug = '".$slug."'") or die($mysqli->error . __LINE__);
            $rowSlug = $sqlSlug->fetch_assoc();

            if($extra_id <> "") {
                $sqlSlugWaarde = $mysqli->query("SELECT waarde FROM sitework_customfields_waardes WHERE veld_id = '".$rowSlug['id']."' AND cms_id = '".$extra_id."' AND taal = '".$post_taal."'") or die($mysqli->error . __LINE__);
                $rowSlugWaarde = $sqlSlugWaarde->fetch_assoc();
                return $rowSlugWaarde['waarde'];
            } else {
                $sqlSlugWaarde = $mysqli->query("SELECT waarde FROM sitework_customfields_waardes WHERE veld_id = '".$rowSlug['id']."' AND cms_id = '".$post_id."' AND taal = '".$post_taal."'") or die($mysqli->error . __LINE__);
                $rowSlugWaarde = $sqlSlugWaarde->fetch_assoc();
                return $rowSlugWaarde['waarde'];
            }            
        } else {
            return "Vergeet niet om een geldig veldnaam mee te geven.";
        }
    } 
    elseif($id_or_other == 'block') 
    {
        if($extra_id <> "") {
            return getBlockTranslation($slug, $post_taal, $extra_id, $extra_id, $post_id);
        } else {
            return "Vergeet niet het block_id mee te geven.";
        }
    } 
    elseif($slug <> "") 
    {
        if($id_or_other <> "") {
            return getTranslation($slug, 'veld', $post_taal, $id_or_other);
        } else {
            return getTranslation($slug, 'veld', $post_taal, $post_id);
        }
    } else {
        return "Er is geen bestaande waarde, probeer het opnieuw.";
    }

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

//console log functie
function debug_to_console($data)
{
    $output = $data;
    if (is_array($output)) {
        $output = implode(',', $output);
    }

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

function findPropertyByObjectcode($properties, $objectcodeToSearch) {
    foreach ($properties as $property) {
        // Check if the 'algemeen' array has the matching objectcode
        if (isset($property['diversen']['diversen']['objectcode']) && $property['diversen']['diversen']['objectcode'] == $objectcodeToSearch) {
            return $property; // Return the matching property
        }
    }
    return null; // Return null if no match is found
}

function limited_text($text = '', $limit = 0) {
    $split=explode(" ", $text);
    $newtext = "";
    $length=0;
    foreach ($split as $word) {
        $word = " ".$word;
        $length+=strlen($word);
        if ($length>$limit) {
            break;
        }
        $newtext .= $word;
    }
    return $newtext;
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

//create img
function img($url, $src, $alt, $ext, $width, $height, $maxdivwidth) 
{
    $nosize = "";
    if (empty($width) and empty($height)) {
        $aspectratioDiv = number_format(0);
        $nosize = true;
    } else {
        $aspectratioCalc = $height / $width;
        $aspectratio = $aspectratioCalc * 100; 
        $aspectratioDiv = number_format($aspectratio, 2, '.', '');
    }

    if ($maxdivwidth < '700') {
        $defaultsrc = $url."/img/webp/".$src."_tn.webp";
        $sourcemediathumb = "<source media=\"(max-width: 700px)\" srcset=\"{$url}/img/webp/{$src}_tn.webp\" type=\"image/webp\">";
        $sourcemediamid = "";
        $sourcemediafull = "";
    } elseif ($maxdivwidth < '900') {
        $defaultsrc = $url."/img/webp/".$src."_mid.webp";
        $sourcemediathumb = "<source media=\"(max-width: 700px)\" srcset=\"{$url}/img/webp/{$src}_tn.webp\" type=\"image/webp\">";
        $sourcemediamid = "<source media=\"(max-width: 900px)\" srcset=\"{$url}/img/webp/{$src}_mid.webp\" type=\"image/webp\">";
        $sourcemediafull = "";
    } else {
        $defaultsrc = $url."/img/webp/".$src.".webp";
        $sourcemediathumb = "<source media=\"(max-width: 700px)\" srcset=\"{$url}/img/webp/{$src}_tn.webp\" type=\"image/webp\">";
        $sourcemediamid = "<source media=\"(max-width: 900px)\" srcset=\"{$url}/img/webp/{$src}_mid.webp\" type=\"image/webp\">";
        $sourcemediafull = "<source media=\"(min-width: 1200px)\" srcset=\"{$url}/img/webp/{$src}_full.webp\" type=\"image/webp\">";
    }

    if($nosize == true){
        echo "
            <picture>
                {$sourcemediathumb}
                {$sourcemediamid}
                {$sourcemediafull}
                <img src=\"{$defaultsrc}\" alt=\"{$alt}\" class=\"lazy\" width=\"{$width}\" height=\"{$height}\" onerror=\"handleImageError(this);\">
            </picture>
    ";
    }else{
        echo "
        <div class=\"picture-container\" >
            <picture>
                {$sourcemediathumb}
                {$sourcemediamid}
                {$sourcemediafull}
                <img src=\"{$defaultsrc}\" alt=\"{$alt}\" class=\"lazy\" width=\"{$width}\" height=\"{$height}\" onerror=\"handleImageError(this);\">
            </picture>
        </div>
    ";
    }
}

function imgRet($url, $src, $alt, $ext, $width, $height, $maxdivwidth) 
{
    $nosize = "";
    if (empty($width) and empty($height)) {
        $aspectratioDiv = number_format(0);
        $nosize = true;
    } else {
        $aspectratioCalc = $height / $width;
        $aspectratio = $aspectratioCalc * 100; 
        $aspectratioDiv = number_format($aspectratio, 2, '.', '');
    }

    if ($maxdivwidth < '700') {
        $defaultsrc = $url."/img/webp/".$src."_tn.webp";
        $sourcemediathumb = "<source media=\"(max-width: 700px)\" srcset=\"{$url}/img/webp/{$src}_tn.webp\" type=\"image/webp\">";
        $sourcemediamid = "";
        $sourcemediafull = "";
    } elseif ($maxdivwidth < '900') {
        $defaultsrc = $url."/img/webp/".$src."_mid.webp";
        $sourcemediathumb = "<source media=\"(max-width: 700px)\" srcset=\"{$url}/img/webp/{$src}_tn.webp\" type=\"image/webp\">";
        $sourcemediamid = "<source media=\"(max-width: 900px)\" srcset=\"{$url}/img/webp/{$src}_mid.webp\" type=\"image/webp\">";
        $sourcemediafull = "";
    } else {
        $defaultsrc = $url."/img/webp/".$src.".webp";
        $sourcemediathumb = "<source media=\"(max-width: 700px)\" srcset=\"{$url}/img/webp/{$src}_tn.webp\" type=\"image/webp\">";
        $sourcemediamid = "<source media=\"(max-width: 900px)\" srcset=\"{$url}/img/webp/{$src}_mid.webp\" type=\"image/webp\">";
        $sourcemediafull = "<source media=\"(min-width: 1200px)\" srcset=\"{$url}/img/webp/{$src}_full.webp\" type=\"image/webp\">";
    }

    if($nosize == true){
        return "
            <picture>
                {$sourcemediathumb}
                {$sourcemediamid}
                {$sourcemediafull}
                <img src=\"{$defaultsrc}\" alt=\"{$alt}\" class=\"lazy\" width=\"{$width}\" height=\"{$height}\" onerror=\"handleImageError(this);\">
            </picture>
    ";
    }else{
        return "
        <div class=\"picture-container\" style=\"padding-top: {$aspectratioDiv}%\">
            <picture>
                {$sourcemediathumb}
                {$sourcemediamid}
                {$sourcemediafull}
                <img src=\"{$defaultsrc}\" alt=\"{$alt}\" class=\"lazy\" width=\"{$width}\" height=\"{$height}\" onerror=\"handleImageError(this);\">
            </picture>
        </div>
    ";
    }
}

function getImg($url, $cms_id, $block_id, $taal, $afbsoort, $width, $height, $maxdivwidth, $aantal)
{
    // Database connectie opzetten
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    mysqli_set_charset($mysqli, 'utf8');

    // Custom query's
    // hier word gekeken of een taal is gezet.
    if($taal != ""){
        $imgTaalQuery = "AND img_taal LIKE '%".$taal."%'";
    } else { $imgTaalQuery = ""; }
    
    // hier word gekeken of een afbeelding soort is gezet.
    if($afbsoort == "team" OR $afbsoort == "categorie"){
        $imgAfbSoortQuery = "AND afbsoort LIKE '%uitgelicht%'";
    } elseif($afbsoort != "") {
        $imgAfbSoortQuery = "AND afbsoort LIKE '%".$afbsoort."%'";
    } else { $imgAfbSoortQuery = ""; }

    // hier word gekeken of een afbeeldingen aantal  is ingesteld. zo ja dan word het limit er op gezet,
    // zo niet dan zit er geen limit op de query
    if($aantal == 0 OR $aantal == '') {
        $imgLimit = "";
    } else { $imgLimit = "LIMIT " . $aantal; }
    
    // Block id check of hij wel 0 is en niet leeg
    $fotoFromBlockID = 0;
    if($block_id == "" OR $block_id == 0) {
        $block_id = 0;
    } else {
        if($taal == 'nl') {
            $sqlGetBlock = $mysqli->query("SELECT block_id FROM sitework_blocks WHERE id = '".$block_id."'") or die($mysqli->error . __LINE__);
            $rowBlock = $sqlGetBlock->fetch_assoc();
            $fotoFromBlockID = $rowBlock['block_id'];
        } else {
            $sqlGetBlock = $mysqli->query("SELECT block_id FROM sitework_vertaling_blocks WHERE id = '".$block_id."'") or die($mysqli->error . __LINE__);
            $rowBlock = $sqlGetBlock->fetch_assoc();
            $fotoFromBlockID = $rowBlock['block_id'];
        }
    }

    // afbeelding ophalen van de database
    // Hier word geprobeerd een afbeelding op te halen met alle parameters mogelijk
    // Zoals in dit geval het megegeven: cms_id, block_id, taal, afbeeldingsoort en aantal afbeeldingen.
    $sqlImg = $mysqli->prepare("SELECT id, cms_id, block_id, naam, hoofdtitel, subtitel, link, linknaam, uitlijning, afbsoort, volgorde, img_taal 
                                FROM sitework_img WHERE cms_id = ? AND block_id = ? $imgTaalQuery $imgAfbSoortQuery order by volgorde ASC $imgLimit") or die($mysqli->error.__LINE__);
    
    $sqlImg -> bind_param('ii', $cms_id, $block_id);
    $sqlImg -> execute(); 
    $sqlImg -> store_result();

    // Hier word gekeken of  er minimaal één afbeelding is gevonden die voldoet aan de criteria.
    // Zo niet, dan word de query hieronder uitgevoerd in het if statement.
    // Deze checkt of er wel een afbeelding met de zelfde criteria is maar dan in het Nederlands.
    if($sqlImg -> num_rows <= 0) {
        if($taal != 'nl') {
            $sqlImg = $mysqli->prepare("SELECT id, cms_id, block_id, naam, hoofdtitel, subtitel, link, linknaam, uitlijning, afbsoort, volgorde, img_taal 
                        FROM sitework_img WHERE cms_id = '1' AND block_id = '0' AND img_taal LIKE '%".$taal."%' AND afbsoort = 'hoofdfoto' order by volgorde ASC LIMIT 1") or die($mysqli->error.__LINE__);

            $sqlImg -> execute(); 
            $sqlImg -> store_result();
        } elseif($afbsoort == 'hoofdfoto') {
            $sqlImg = $mysqli->prepare("SELECT id, cms_id, block_id, naam, hoofdtitel, subtitel, link, linknaam, uitlijning, afbsoort, volgorde, img_taal 
                                FROM sitework_img WHERE cms_id = '1' AND block_id = '0' AND img_taal LIKE '%nl%' AND afbsoort = 'hoofdfoto' order by volgorde ASC LIMIT 1") or die($mysqli->error.__LINE__);
        
            $sqlImg -> execute(); 
            $sqlImg -> store_result();
        } else {
            $sqlImg = $mysqli->prepare("SELECT id, cms_id, block_id, naam, hoofdtitel, subtitel, link, linknaam, uitlijning, afbsoort, volgorde, img_taal 
                                FROM sitework_img WHERE cms_id = ? AND block_id = ? AND img_taal LIKE '%nl%' $imgAfbSoortQuery order by volgorde ASC $imgLimit") or die($mysqli->error.__LINE__);

            $sqlImg -> bind_param('ii', $cms_id, $block_id);
            $sqlImg -> execute(); 
            $sqlImg -> store_result();
        }
    }

    // Als er een afbeelding gevonden is word hier aan elk veld een variable gezet, daarmee kun je dan de afbeeldingen
    // mee ophalen en in de img() functie zetten.
    $sqlImg -> bind_result($imgID, $imgCmsId, $imgBlockId, $imgNaam, $imgHoofdtitel, $imgSubtitel, 
                            $imgLink, $imgLinkNaam, $imgUitlijning, $imgAfbSoort, $imgVolgorde, $imgTaal);

    // Als er dus afbeelding(en) gevonden zijn word hier een while loop uitgevoerd om ze allemaal te tonen.
    // Als er geen afbeeldingen zijn vangt dit if statement dit op en voert een enkele afbeeling uit, dit is dan de noimg.jpg.
    if($sqlImg -> num_rows > 0) {
        while($sqlImg -> fetch()) {
            $sqlMediaImg = $mysqli->query("SELECT * FROM sitework_mediabibliotheek WHERE id = '" . $imgNaam . "'") or die($mysqli->error . __LINE__);
            $rowMediaImg = $sqlMediaImg->fetch_assoc();
            // Hier word de afbeelding bijschrift opgebouwd uit of het bijschrift of afbeelding hoofdtitel.
            if ($rowMediaImg['bijschrift']) { 
                $bijschrift = $rowMediaImg['bijschrift'];
            } else if($imgHoofdtitel) {
                $bijschrift = $imgHoofdtitel;
            } else {
                $bijschrift = getTranslation('item2', 'veld', $taal, $cms_id);
            }
            
            // Afbeelding actie bepaald: open afbeelding link of vergroot in fancybox
            if($afbsoort == "team" OR $afbsoort == "categorie" OR $afbsoort == "uitgelicht") {
                // Hier word de afbeelding gemaakt in een a element zodat er een link geopend kan worden als de afbeelding dit heeft.
                // Of de afbeelding vergroot worden als er geen link aanwezig is. ook word de afbeelding gemaakt door de img functie.
                // De img() functie word ook gemaakt in dit bestand dus deze vind je hier boven.
                echo '<div class="block-img">';
                    img($url, $rowMediaImg['naam'], $bijschrift, $rowMediaImg['ext'], $width, $height, $maxdivwidth);
                echo '</div>';
            } elseif($afbsoort == "hoofdfoto") {
                
                $imgCaption = '<div class="hoofd_caption">';
                    $imgCaption .= '<span class="text-2xl sm:text-{} md:text-2xl lg:text-4xl ">'.$imgHoofdtitel.'</span>';
                    $imgCaption .= '<span class="text-2xl sm:text-1xl md:text-2xl lg:text-4xl ">'.$imgSubtitel.'</span>';
                    $imgCaption .= '<button class="btn mt-6">Lees verder</button>';

                $imgCaption .= '</div>';

                echo '<div class="block-img">';
                    echo $imgCaption;
                    img($url, $rowMediaImg['naam'], $bijschrift, $rowMediaImg['ext'], $width, $height, $maxdivwidth);
                echo '</div>';
            } elseif($afbsoort == "zijkant") {
                if($imgLink){
                    $imgActie = 'href="'.$imgLink.'" target="_blank" "rel="noopener" rel="noreferrer"';
                }else{
                    $imgActie = 'data-fancybox="images-'.$cms_id.'-'.$taal.'" href="'.$url.'/img/'.$rowMediaImg['naam'].'.'.$rowMediaImg['ext'].'" data-caption="'.$imgHoofdtitel.'"';
                }

                echo '<div class="block-img afbeelding-zijkant overflow-hidden">';
                    echo '<a '.$imgActie.' aria-label="'.$bijschrift.'">';
                        img($url, $rowMediaImg['naam'], $bijschrift, $rowMediaImg['ext'], $width, $height, $maxdivwidth);
                    echo '</a>';
                echo '</div>';
            } elseif($fotoFromBlockID == "12") {
                if($imgLink){
                    $imgActie = 'href="'.$imgLink.'" target="_blank" "rel="noopener" rel="noreferrer"';
                }else{
                    $imgActie = 'data-fancybox="images-'.$block_id.'-'.$taal.'" href="'.$url.'/img/'.$rowMediaImg['naam'].'.'.$rowMediaImg['ext'].'" data-caption="'.$imgHoofdtitel.'"';
                }

                $imgCaption = '<div class="grid-caption">';
                    $imgCaption .= '<span class="text-xl sm:text-1xl md:text-2xl lg:text-4xl font-semibold">'.$imgHoofdtitel.'</span>';
                    $imgCaption .= '<span class="text-lg sm:text-xl md:text-1xl lg:text-2xl font-semibold">'.$imgSubtitel.'</span>';
                $imgCaption .= '</div>';

                // Hier word de afbeelding gemaakt in een a element zodat er een link geopend kan worden als de afbeelding dit heeft.
                // Of de afbeelding vergroot worden als er geen link aanwezig is. ook word de afbeelding gemaakt door de img functie.
                // De img() functie word ook gemaakt in dit bestand dus deze vind je hier boven.
                echo '<a class="block-img" '.$imgActie.' aria-label="'.$bijschrift.'">';
                    echo $imgCaption;
                    img($url, $rowMediaImg['naam'], $bijschrift, $rowMediaImg['ext'], $width, $height, $maxdivwidth);
                echo '</a>';
            } elseif($fotoFromBlockID == "11") {
                if($imgLink){
                    $imgActie = 'href="'.$imgLink.'" target="_blank" "rel="noopener" rel="noreferrer"';
                }else{
                    $imgActie = 'data-fancybox="images-'.$block_id.'-'.$taal.'" href="'.$url.'/img/'.$rowMediaImg['naam'].'.'.$rowMediaImg['ext'].'" data-caption="'.$imgHoofdtitel.'"';
                }

                $imgCaption = '<div class="grid-caption">';
                    $imgCaption .= '<span class="text-xl sm:text-1xl md:text-2xl lg:text-4xl font-semibold">'.$imgHoofdtitel.'</span>';
                    $imgCaption .= '<span class="text-lg sm:text-xl md:text-1xl lg:text-2xl font-semibold">'.$imgSubtitel.'</span>';
                $imgCaption .= '</div>';

                // Hier word de afbeelding gemaakt in een a element zodat er een link geopend kan worden als de afbeelding dit heeft.
                // Of de afbeelding vergroot worden als er geen link aanwezig is. ook word de afbeelding gemaakt door de img functie.
                // De img() functie word ook gemaakt in dit bestand dus deze vind je hier boven.
                echo '<a class="block-img image !flex items-center justify-center" '.$imgActie.' aria-label="'.$bijschrift.'">';
                    echo $imgCaption;
                    img($url, $rowMediaImg['naam'], $bijschrift, $rowMediaImg['ext'], $width, $height, $maxdivwidth);
                echo '</a>';
            } elseif($fotoFromBlockID == "19") {
                if($imgLink){
                    $imgActie = 'href="'.$imgLink.'" target="_blank" "rel="noopener" rel="noreferrer"';
                }else{
                    $imgActie = 'data-fancybox="images-'.$block_id.'-'.$taal.'" href="'.$url.'/img/'.$rowMediaImg['naam'].'.'.$rowMediaImg['ext'].'" data-caption="'.$imgHoofdtitel.'"';
                }

                // Hier word de afbeelding gemaakt in een a element zodat er een link geopend kan worden als de afbeelding dit heeft.
                // Of de afbeelding vergroot worden als er geen link aanwezig is. ook word de afbeelding gemaakt door de img functie.
                // De img() functie word ook gemaakt in dit bestand dus deze vind je hier boven.
                echo '<a class="tekst-galerij-block" '.$imgActie.' aria-label="'.$bijschrift.'">';
                    img($url, $rowMediaImg['naam'], $bijschrift, $rowMediaImg['ext'], $width, $height, $maxdivwidth);
                echo '</a>';
            } else {             
                if($imgLink){
                    $imgActie = 'href="'.$imgLink.'" target="_blank" "rel="noopener" rel="noreferrer"';
                }else{
                    $imgActie = 'data-fancybox="images-'.$block_id.'-'.$taal.'" href="'.$url.'/img/'.$rowMediaImg['naam'].'.'.$rowMediaImg['ext'].'" data-caption="'.$imgHoofdtitel.'"';
                }

                // Hier word de afbeelding gemaakt in een a element zodat er een link geopend kan worden als de afbeelding dit heeft.
                // Of de afbeelding vergroot worden als er geen link aanwezig is. ook word de afbeelding gemaakt door de img functie.
                // De img() functie word ook gemaakt in dit bestand dus deze vind je hier boven.
                echo '<a class="block-img" '.$imgActie.' aria-label="'.$bijschrift.'">';
                    img($url, $rowMediaImg['naam'], $bijschrift, $rowMediaImg['ext'], $width, $height, $maxdivwidth);
                echo '</a>';
            }
        }
    } else {
        if($afbsoort != 'galerij') {
            // hier word dus een default afbeelding ingeladen als er geen afbeelding uit de database word gehaald.
            $afbeelding = "noimg";
            $imgExt = "jpg";   

            echo '<div class="block-img">';
                img($url, $afbeelding, $afbeelding, $imgExt, $width, $height, $maxdivwidth);
            echo '</div>';
        }
    }
}

function getImgReturn($url, $cms_id, $block_id, $taal, $afbsoort, $width, $height, $maxdivwidth, $aantal)
{
    // Database connectie opzetten
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    mysqli_set_charset($mysqli, 'utf8');

    // Custom query's
    // hier word gekeken of een taal is gezet.
    if($taal != ""){
        $imgTaalQuery = "AND img_taal LIKE '%".$taal."%'";
    } else { $imgTaalQuery = ""; }
    
    // hier word gekeken of een afbeelding soort is gezet.
    if($afbsoort == "team" OR $afbsoort == "categorie"){
        $imgAfbSoortQuery = "AND afbsoort LIKE '%uitgelicht%'";
    } elseif($afbsoort != "") {
        $imgAfbSoortQuery = "AND afbsoort LIKE '%".$afbsoort."%'";
    } else { $imgAfbSoortQuery = ""; }

    // hier word gekeken of een afbeeldingen aantal  is ingesteld. zo ja dan word het limit er op gezet,
    // zo niet dan zit er geen limit op de query
    if($aantal == 0 OR $aantal == '') {
        $imgLimit = "";
    } else { $imgLimit = "LIMIT " . $aantal; }
    
    // Block id check of hij wel 0 is en niet leeg
    $fotoFromBlockID = 0;
    if($block_id == "" OR $block_id == 0) {
        $block_id = 0;
    } else {
        if($taal == 'nl') {
            $sqlGetBlock = $mysqli->query("SELECT block_id FROM sitework_blocks WHERE id = '".$block_id."'") or die($mysqli->error . __LINE__);
            $rowBlock = $sqlGetBlock->fetch_assoc();
            $fotoFromBlockID = $rowBlock['block_id'];
        } else {
            $sqlGetBlock = $mysqli->query("SELECT block_id FROM sitework_vertaling_blocks WHERE id = '".$block_id."'") or die($mysqli->error . __LINE__);
            $rowBlock = $sqlGetBlock->fetch_assoc();
            $fotoFromBlockID = $rowBlock['block_id'];
        }
    }

    // afbeelding ophalen van de database
    // Hier word geprobeerd een afbeelding op te halen met alle parameters mogelijk
    // Zoals in dit geval het megegeven: cms_id, block_id, taal, afbeeldingsoort en aantal afbeeldingen.
    $sqlImg = $mysqli->prepare("SELECT id, cms_id, block_id, naam, hoofdtitel, subtitel, link, linknaam, uitlijning, afbsoort, volgorde, img_taal 
                                FROM sitework_img WHERE cms_id = ? AND block_id = ? $imgTaalQuery $imgAfbSoortQuery order by volgorde ASC $imgLimit") or die($mysqli->error.__LINE__);
    
    $sqlImg -> bind_param('ii', $cms_id, $block_id);
    $sqlImg -> execute(); 
    $sqlImg -> store_result();

    // Hier word gekeken of  er minimaal één afbeelding is gevonden die voldoet aan de criteria.
    // Zo niet, dan word de query hieronder uitgevoerd in het if statement.
    // Deze checkt of er wel een afbeelding met de zelfde criteria is maar dan in het Nederlands.
    if($sqlImg -> num_rows <= 0) {
        if($taal != 'nl') {
            $sqlImg = $mysqli->prepare("SELECT id, cms_id, block_id, naam, hoofdtitel, subtitel, link, linknaam, uitlijning, afbsoort, volgorde, img_taal 
                        FROM sitework_img WHERE cms_id = '1' AND block_id = '0' AND img_taal LIKE '%".$taal."%' AND afbsoort = 'hoofdfoto' order by volgorde ASC LIMIT 1") or die($mysqli->error.__LINE__);

            $sqlImg -> execute(); 
            $sqlImg -> store_result();
        } elseif($afbsoort == 'hoofdfoto') {
            $sqlImg = $mysqli->prepare("SELECT id, cms_id, block_id, naam, hoofdtitel, subtitel, link, linknaam, uitlijning, afbsoort, volgorde, img_taal 
                                FROM sitework_img WHERE cms_id = '1' AND block_id = '0' AND img_taal LIKE '%nl%' AND afbsoort = 'hoofdfoto' order by volgorde ASC LIMIT 1") or die($mysqli->error.__LINE__);
        
            $sqlImg -> execute(); 
            $sqlImg -> store_result();
        } else {
            $sqlImg = $mysqli->prepare("SELECT id, cms_id, block_id, naam, hoofdtitel, subtitel, link, linknaam, uitlijning, afbsoort, volgorde, img_taal 
                                FROM sitework_img WHERE cms_id = ? AND block_id = ? AND img_taal LIKE '%nl%' $imgAfbSoortQuery order by volgorde ASC $imgLimit") or die($mysqli->error.__LINE__);

            $sqlImg -> bind_param('ii', $cms_id, $block_id);
            $sqlImg -> execute(); 
            $sqlImg -> store_result();
        }
    }

    // Als er een afbeelding gevonden is word hier aan elk veld een variable gezet, daarmee kun je dan de afbeeldingen
    // mee ophalen en in de img() functie zetten.
    $sqlImg -> bind_result($imgID, $imgCmsId, $imgBlockId, $imgNaam, $imgHoofdtitel, $imgSubtitel, 
                            $imgLink, $imgLinkNaam, $imgUitlijning, $imgAfbSoort, $imgVolgorde, $imgTaal);

    // Als er dus afbeelding(en) gevonden zijn word hier een while loop uitgevoerd om ze allemaal te tonen.
    // Als er geen afbeeldingen zijn vangt dit if statement dit op en voert een enkele afbeeling uit, dit is dan de noimg.jpg.
    if($sqlImg -> num_rows > 0) {
        while($sqlImg -> fetch()) {
            $sqlMediaImg = $mysqli->query("SELECT * FROM sitework_mediabibliotheek WHERE id = '" . $imgNaam . "'") or die($mysqli->error . __LINE__);
            $rowMediaImg = $sqlMediaImg->fetch_assoc();
            // Hier word de afbeelding bijschrift opgebouwd uit of het bijschrift of afbeelding hoofdtitel.
            if ($rowMediaImg['bijschrift']) { 
                $bijschrift = $rowMediaImg['bijschrift'];
            } else if($imgHoofdtitel) {
                $bijschrift = $imgHoofdtitel;
            } else {
                $bijschrift = getTranslation('item2', 'veld', $taal, $cms_id);
            }
            
            // Afbeelding actie bepaald: open afbeelding link of vergroot in fancybox
            if($afbsoort == "team" OR $afbsoort == "categorie" OR $afbsoort == "uitgelicht") {
                // Hier word de afbeelding gemaakt in een a element zodat er een link geopend kan worden als de afbeelding dit heeft.
                // Of de afbeelding vergroot worden als er geen link aanwezig is. ook word de afbeelding gemaakt door de img functie.
                // De img() functie word ook gemaakt in dit bestand dus deze vind je hier boven.
                return "<div class=\"block-img\">".imgRet($url, $rowMediaImg['naam'], $bijschrift, $rowMediaImg['ext'], $width, $height, $maxdivwidth)."</div>";
            } elseif($afbsoort == "hoofdfoto") {
                $imgCaption = '<div class="absolute top-0 left-0 h-full w-full flex justify-center items-center">';
                $imgCaption .= '<div class="hoofd_caption text-center rellax">';
                    $imgCaption .= '<span class="text-2xl sm:text-3xl md:text-4xl lg:text-4xl xxl:text-5xl amatic">'.$imgHoofdtitel.'</span>';
                    $imgCaption .= '<span class="text-xl sm:text-1xl md:text-2xl lg:text-4xl font-semibold">'.$imgSubtitel.'</span>';
                $imgCaption .= '</div>';
                $imgCaption .= '</div>';

                return '<div class="block-img">'.$imgCaption . imgRet($url, $rowMediaImg['naam'], $bijschrift, $rowMediaImg['ext'], $width, $height, $maxdivwidth).'</div>';
            } elseif($afbsoort == "zijkant") {
                if($imgLink){
                    $imgActie = 'href="'.$imgLink.'" target="_blank" "rel="noopener" rel="noreferrer"';
                }else{
                    $imgActie = 'data-fancybox="images-'.$cms_id.'-'.$taal.'" href="'.$url.'/img/'.$rowMediaImg['naam'].'.'.$rowMediaImg['ext'].'" data-caption="'.$imgHoofdtitel.'"';
                }

                return '<div class="block-img afbeelding-zijkant overflow-hidden"><a '.$imgActie.'>'.imgRet($url, $rowMediaImg['naam'], $bijschrift, $rowMediaImg['ext'], $width, $height, $maxdivwidth).'</a></div>';
            } elseif($fotoFromBlockID == "12") {
                if($imgLink){
                    $imgActie = 'href="'.$imgLink.'" target="_blank" "rel="noopener" rel="noreferrer"';
                }else{
                    $imgActie = 'data-fancybox="images-'.$block_id.'-'.$taal.'" href="'.$url.'/img/'.$rowMediaImg['naam'].'.'.$rowMediaImg['ext'].'" data-caption="'.$imgHoofdtitel.'"';
                }

                $imgCaption = '<div class="grid-caption">';
                    $imgCaption .= '<span class="text-xl sm:text-1xl md:text-2xl lg:text-4xl font-semibold">'.$imgHoofdtitel.'</span>';
                    $imgCaption .= '<span class="text-lg sm:text-xl md:text-1xl lg:text-2xl font-semibold">'.$imgSubtitel.'</span>';
                $imgCaption .= '</div>';

                // Hier word de afbeelding gemaakt in een a element zodat er een link geopend kan worden als de afbeelding dit heeft.
                // Of de afbeelding vergroot worden als er geen link aanwezig is. ook word de afbeelding gemaakt door de img functie.
                // De img() functie word ook gemaakt in dit bestand dus deze vind je hier boven.
                return '<a class="block-img" '.$imgActie.'>'.$imgCaption.imgRet($url, $rowMediaImg['naam'], $bijschrift, $rowMediaImg['ext'], $width, $height, $maxdivwidth). '</a>';
            } else {             
                if($imgLink){
                    $imgActie = 'href="'.$imgLink.'" target="_blank" "rel="noopener" rel="noreferrer"';
                }else{
                    $imgActie = 'data-fancybox="images-'.$block_id.'-'.$taal.'" href="'.$url.'/img/'.$rowMediaImg['naam'].'.'.$rowMediaImg['ext'].'" data-caption="'.$imgHoofdtitel.'"';
                }

                // Hier word de afbeelding gemaakt in een a element zodat er een link geopend kan worden als de afbeelding dit heeft.
                // Of de afbeelding vergroot worden als er geen link aanwezig is. ook word de afbeelding gemaakt door de img functie.
                // De img() functie word ook gemaakt in dit bestand dus deze vind je hier boven.
                return '<a class="block-img" '.$imgActie.'><div>'.imgRet($url, $rowMediaImg['naam'], $bijschrift, $rowMediaImg['ext'], $width, $height, $maxdivwidth).'</div></a>';
            }
        }
    } else {
        if($afbsoort != 'galerij') {
            // hier word dus een default afbeelding ingeladen als er geen afbeelding uit de database word gehaald.
            $afbeelding = "noimg";
            $imgExt = "jpg";   

            return "<div class=\"block-img 2\">".imgRet($url, $afbeelding, $afbeelding, $imgExt, $width, $height, $maxdivwidth)."</div>";
        }
    }
}


function get_id() {
    global $post_id;
    return $post_id;
}

function get_taal() {
    global $post_taal;
    return $post_taal;
}

function use_query($select = "", $from = "", $where = "", $order = "", $limit = "") {
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    mysqli_set_charset($mysqli, 'utf8');

    if($select <> "" && $from <> "") {

        if($where <> "") {
            $where = "AND " . $where;
        }
        if($order <> "") {
            $order = "ORDER BY " . $order;
        }
        if($limit <> "") {
            $limit = "LIMIT " . $limit;
        }
        if($from == 'sitework_img') {
            $taal = 'img_taal = ?';
        } elseif($from == 'sitework_doc') {
            $taal = 'doc_taal = ?';
        } elseif ($from == 'sitework_taal') {
            $taal = 'taalkort != ?';
        } elseif(
            $from == 'siteworkcms' OR
            $from == 'sitework_vertaling' OR
            $from == 'sitework_blocks' OR
            $from == 'sitework_vertaling_blocks'
        ) {
            $taal = 'taal = ?';
        } else {
            $taal = 'id != ?';
        }

        $sql = $mysqli -> prepare("SELECT ".$select." FROM ".$from." WHERE ".$taal." ".$where." ".$order." ".$limit."") or die ($mysqli->error.__LINE__);
        $sql->bind_param('s', get_taal());
        $sql->execute();
        $result = $sql->get_result();
        $data = $result->fetch_assoc();
        return $data;
    } else {
        return "Geen waardes mee die je wilt selecteren en van waar (SELECT ... FROM ...)";
    }
}

function use_query_loop($select = "", $from = "", $where = "", $order = "", $limit = "") {
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    mysqli_set_charset($mysqli, 'utf8');

    if ($select !== "" && $from !== "") {

        if ($where !== "") {
            $where = "AND " . $where;
        }
        if ($order !== "") {
            $order = "ORDER BY " . $order;
        }
        if ($limit !== "") {
            $limit = "LIMIT " . $limit;
        }

        if ($from == 'sitework_img') {
            $taal = 'img_taal = ?';
        } elseif ($from == 'sitework_doc') {
            $taal = 'doc_taal = ?';
        } elseif ($from == 'sitework_taal') {
            $taal = 'taalkort != ?';
        } elseif(
            $from == 'siteworkcms' OR
            $from == 'sitework_vertaling' OR
            $from == 'sitework_blocks' OR
            $from == 'sitework_vertaling_blocks'
        ) {
            $taal = 'taal = ?';
        } else {
            $taal = 'id != ?';
        }

        $sql = $mysqli->prepare("SELECT " . $select . " FROM " . $from . " WHERE " . $taal . " " . $where . " " . $order . " " . $limit) or die($mysqli->error . __LINE__);
        $sql->bind_param('s', get_taal());
        $sql->execute();
        $result = $sql->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    } else {
        return "Geen waardes mee die je wilt selecteren en van waar (SELECT ... FROM ...)";
    }
}

function get_title() {
    return the_field('item2');
}

function get_content($limit = 0) {
    $text = the_field('tekst');

    if($limit <> 0) {
        $split=explode(" ", $text);
        $newtext = "";
        $length=0;
        foreach ($split as $word) {
            $word = " ".$word;
            $length+=strlen($word);
            if ($length>$limit) {
                break;
            }
            $newtext .= $word;
        }
        return $newtext;
    } else {
        return $text;
    }
}

function get_category() {
    return the_field('keuze1');
}

function get_kenmerk() {
    return the_field('kenmerken');
}

function get_setting($setting = '') {
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    mysqli_set_charset($mysqli, 'utf8');

    $sqlSettings = $mysqli->query("SELECT ".$setting." FROM sitework_settings WHERE id = '1'") or die($mysqli->error . __LINE__);
    $rowSettings = $sqlSettings->fetch_assoc();

    return $rowSettings[$setting];
}

function get_meertaligheid() {
    if(get_setting('meertaligheid') == 'ja') {
        return true;
    } else {
        return false;
    }
}

function get_url() {
    return get_setting('weburl');
}

function home_url() {
    $homeUrl = the_field('paginaurl', 1);

    if(get_meertaligheid() == true) {
        return get_url() . '/' . get_taal() . '/' . $homeUrl;
    } else {
        return get_url() . '/' . $homeUrl;
    }
}

function get_link($id = '', $extraParameter = '') {
    if(get_meertaligheid() == true) {
        $taalURL = '/' . get_taal();
    } else {
        $taalURL = "";
    }

    if($id <> "") {
        $paginaUrl = the_field('paginaurl', $id);
        $externeUrl = the_field('externeurl', $id);
        $hoofdid = the_field('hoofdid', $id);
    } else {
        $paginaUrl = the_field('paginaurl');
        $externeUrl = the_field('externeurl');
        $hoofdid = the_field('hoofdid');
    }

    if($hoofdid <> '0') {
        $hoofdUrl = the_field('paginaurl', $hoofdid);

        if($externeUrl <> "") {
            return $externeUrl;
        } elseif($extraParameter <> "") {
            return get_url() . $taalURL . '/' . $extraParameter . '/' . $hoofdUrl . '/' . $paginaUrl.'/';
        } else {
            return get_url() . $taalURL . '/' . $hoofdUrl . '/' . $paginaUrl.'/';
        }
    } else {
        if($externeUrl <> "") {
            return $externeUrl;
        } elseif($extraParameter <> "") {
            return get_url() . $taalURL . '/' . $extraParameter . '/' . $paginaUrl.'/';
        } else {
            return get_url() . $taalURL . '/' . $paginaUrl.'/';
        }
    }
}

// Meta Twitter/X
function get_twitterUser() {
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    mysqli_set_charset($mysqli, 'utf8');

    $sqlTwitter = $mysqli->query("SELECT * FROM sitework_socialmedia WHERE naam = 'Twitter'") or die($mysqli->error . __LINE__);
    $rowTwitter = $sqlTwitter->fetch_assoc();

    $path = parse_url($rowTwitter['url'], PHP_URL_PATH);

    // Split the path by slashes
    $pathParts = explode('/', trim($path, '/'));

    // Assuming the username is the last part of the path
    $username = end($pathParts);

    return $username;
}

// Meta pagina canonical
function canonical_link($http_host) {
    $can_url = '';
    $params = explode('.', $http_host);

    if($params[0] == 'www') {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $can_url = 'https://' . $http_host;
        } else {
            $can_url = 'http://' . $http_host;
        }
    } else {
        $can_url = get_url();
    }

    return $can_url . '/' . 
    (get_meertaligheid() == true && isset($_GET["taal"]) && $_GET["taal"] ? $_GET["taal"] . '/' : '') . 
    (isset($_GET["page"]) && $_GET["page"] ? $_GET["page"] . '/' : '') . 
    (isset($_GET["title"]) && $_GET["title"] ? $_GET["title"] . '/' : '') . 
    (isset($_GET["beginnenbij"]) && $_GET["beginnenbij"] ? $_GET["beginnenbij"] . '/' : '');
}

// Meta pagina language
function getCountryFullName($countryCode) {
    $countryCodes = array(
        'nl' => 'nederlands',
        'en' => 'english',
        'de' => 'deutsch',
        'fr' => 'french',
        'pl' => 'polish',
        'es' => 'spanish',
        'pt' => 'portuguese',
        'da' => 'danish',
        'cs' => 'czech'
    );

    // Convert the country code to lowercase to ensure case insensitivity
    $countryCode = strtolower($countryCode);

    // Check if the country code exists in the array
    if (array_key_exists($countryCode, $countryCodes)) {
        return $countryCodes[$countryCode];
    } else {
        // If the country code doesn't exist, return null or any other default value as per your requirement
        return null;
    }
}

// Meta pagina locale
function getLocaleCode($countryCode) {
    $countryCodes = array(
        'nl' => 'nl_NL',
        'en' => 'en_US',
        'de' => 'de_DE',
        'fr' => 'fr_FR',
        'pl' => 'pl_PL',
        'es' => 'es_ES',
        'pt' => 'pt_PT',
        'da' => 'da_DK',
        'cs' => 'cs_CZ'
    );

    // Convert the country code to lowercase to ensure case insensitivity
    $countryCode = strtolower($countryCode);

    // Check if the country code exists in the array
    if (array_key_exists($countryCode, $countryCodes)) {
        return $countryCodes[$countryCode];
    } else {
        // If the country code doesn't exist, return null or any other default value as per your requirement
        return null;
    }
}