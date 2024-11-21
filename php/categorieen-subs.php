<?php
$sqlCat = $mysqli->prepare("SELECT id,item2,tekst,paginaurl FROM siteworkcms WHERE hoofdid = ? and status = 'actief' ORDER BY volgorde ASC") or die($mysqli->error . __LINE__);
$sqlCat->bind_param('s', get_id()); 
$sqlCat->execute();
$sqlCat->store_result();
$sqlCat->bind_result($idCat, $titelCat, $tekstCat, $urlCat);

if($sqlCat->num_rows > 0){ ?>
    <section class="bg-LichtGrijs">
        <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-16 py-16 categorieen-grid-3">
            <?php
            while ($sqlCat->fetch()) {
                echo "<a class=\"relative cat-item bg-white pb-16\" href=\"".get_link($idCat)."\">";
                    echo "<div class=\"img-container overflow-hidden\">";
                        getImg(get_url(), get_id(), '', get_taal(), 'uitgelicht', '500','300','500', 1);
                    echo "</div>";
                    echo "
                        <div class=\"catContent p-6\">
                            <h3>{$titelCat}</h3>".
                            strip_tags(limit_text($tekstCat,100),'')."[...]
                        </div>
                        <span class=\"absolute left-6 btn\">Lees verder</span>
                    ";
                echo "</a>";
            }
            ?>
        </div>
    </section>
<?php } ?>