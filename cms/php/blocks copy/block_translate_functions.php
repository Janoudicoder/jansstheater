<?php
// customfields opties in een blok kunnen weergeven
// ================================================
function get_field_option($get_post_id = '', $slug = '') {

    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    mysqli_set_charset($mysqli, 'utf8');
    
    global $post_taal;
    global $post_id;

    

    $sqlSlug = $mysqli->query("SELECT id FROM sitework_customfields WHERE slug = '".$slug."'") or die($mysqli->error . __LINE__);
    $rowSlug = $sqlSlug->fetch_assoc();
    //return $rowSlug['id'];
    if($get_post_id){
        $sqlSlugWaarde = $mysqli->query("SELECT waarde FROM sitework_customfields_opties WHERE koppel_id = '".$rowSlug['id']."' AND cms_id = '".$get_post_id."' AND taal = '".$post_taal."'") or die($mysqli->error . __LINE__);
        while($rowSlugWaarde = $sqlSlugWaarde->fetch_assoc()) {
            $customVelden[] = $rowSlugWaarde['waarde'];
        }
        
    }else{
        $sqlSlugWaarde = $mysqli->query("SELECT waarde FROM sitework_customfields_opties WHERE koppel_id = '".$rowSlug['id']."' ") or die($mysqli->error . __LINE__);
        while($rowSlugWaarde = $sqlSlugWaarde->fetch_assoc()) {
            $customVelden[] = $rowSlugWaarde['waarde'];
        }
    }
    return $customVelden;
}
//functies voor block teksten / waardes vertalingen
// ================================================
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

//functies voor categrorie / kenmerk vertalingen
// =============================================
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

// debug_var_dump
// =============
function debug_var_dump($value) {
    echo '<pre>' . var_dump($value) . '</pre>';
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
?>