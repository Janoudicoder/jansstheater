<?php
//settings
$categorie = "nieuws";  
$categorieUrl = "nieuwsbericht";
//eind settings
?>

<div class="container mx-auto">
    <h1>Nieuws</h1>
</div>
<div class="container mx-auto grid autoGrid gap-16 py-16 categorieen-grid-3">
    <?php // voor paginanummering en navigatie
    // =======================================
    $beginnenbij                                        = $_GET['beginnenbij']-1;
    if (empty($max)) $max                               = 9; // $max is max aantal per pagina
    if (empty($_GET['beginnenbij'])) $beginnenbij       = 0;
    $start = ($beginnenbij*$max);

    // hier de gewenste items ophalen
    // ==============================
    $sqlNieuws = $mysqli -> prepare("SELECT SQL_CALC_FOUND_ROWS id,item2,tekst,paginaurl, DATE_FORMAT(datum, '%e %M %Y') as datum_pub,DATE_FORMAT(datum2, '%d-%m-%Y') AS datum2 FROM siteworkcms WHERE keuze1 = ? $taalquery and datum2 > CURDATE() - INTERVAL 1 DAY and status = 'actief' ORDER BY datum DESC LIMIT ".$start.",".$max) or die($mysqli->error.__LINE__);
    $keuzenieuws = "nieuws";
    $sqlNieuws -> bind_param('s', $keuzenieuws);
    $sqlNieuws -> execute();
    $sqlNieuws -> store_result();
    $sqlNieuws -> bind_result($idNieuws, $titelNieuws, $tekstNieuws, $paginaurlNieuws, $datum_pub, $datum2);

    // nodig voor pagina nummering:
    // ============================
    $aantal_limiet      = $sqlNieuws->num_rows; // het aantal gevonden rijen met limiet (kan dus minder zijn dan limiet!)
    $totaalrijen_tellen = $mysqli->query("Select FOUND_ROWS()");
    $totaalrijen        = $totaalrijen_tellen->fetch_assoc();

    $totaal             = $totaalrijen["FOUND_ROWS()"]; // het totaal aantal gevonden rijen!)
    $aantalPaginas      = ceil($totaal/$max);
    $welkePagina        = ceil($start/$max)+1;

    // einde paginanummering
    // =====================
    while($sqlNieuws->fetch()) {
        $titelNieuws = the_field('item2', $idNieuws);
        $datum_pub = the_field('datum', $idNieuws);
        $tekstNieuws = the_field('tekst', $idNieuws);

        echo "<a class=\"relative cat-item bg-LichtGrijs pb-16\" href=\"".get_link($idNieuws, 'nieuws')."\">";
            echo "<div class=\"img-container overflow-hidden\">";
                getImg(get_url(), get_id(), '', get_taal(), 'uitgelicht', '500','300','500', 1);
            echo "</div>";
            echo "
                <div class=\"catContent p-6\">
                    <h4>{$titelNieuws}</h4>
                    <span class=\"block font-strong mb-6\">{$datum_pub}</span> 
                    ".
                    strip_tags(limit_text($tekstNieuws,200),'')."[...]
                </div>
                <span class=\"absolute left-6 bottom-6 btn\">Lees verder</span>
            ";
        echo "</a>";
    } ?>
</div>
<? // paginanummering
// ================== ?>
<div class="paginatie">
    <? if($beginnenbij <> 0) { ?>
        <a href="<?php echo get_url(); ?>/<? if ($taalquery) { echo get_taal().'/'; } ?>nieuws/overzicht/<? echo $beginnenbij; ?>" class="vorige-pijl"><input id="vorige" type="submit" name="vorige" onClick="location.href='<? echo $url; ?>/<? if ($taalquery) { echo get_taal().'/'; } ?>nieuws/overzicht/<? echo $beginnenbij; ?>'"/></a>
    <? } ?>
    <? for($i=0; $i<$aantalPaginas; $i++){ ?>
        <a href="<?php echo get_url(); ?>/<? if ($taalquery) { echo get_taal().'/'; } ?>nieuws/overzicht/<?=$i+1; ?>"><input class="paginatie_inactief" type="submit" name="beginnenbij" value="<? echo $i+1; ?>" <? if($welkePagina == $i+1){ echo "id=\"paginatie_actief\""; } ?> onClick="location.href='<? echo $url; ?>/<? if ($taalquery) { echo get_taal().'/'; } ?>nieuws/overzicht/<?=$i+1; ?>'"/></a>
    <? } ?>
    <? if($welkePagina <> $aantalPaginas) { ?>
        <a href="<?php echo get_url(); ?>/<? if ($taalquery) { echo get_taal().'/'; } ?>nieuws/overzicht/<? echo $beginnenbij+2; ?>" class="volgende-pijl"><input id="volgende" type="submit" name="volgende"  onClick="location.href='<? echo $url; ?>/<? if ($taalquery) { echo get_taal().'/'; } ?>nieuws/overzicht/<? echo $beginnenbij+2; ?>'"/></a>
    <? } ?>
</div>
