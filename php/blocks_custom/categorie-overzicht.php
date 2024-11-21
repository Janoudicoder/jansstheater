<?php
// Titel, Tekst en Categorie
$titleBlock = the_field('titel', 'block', $blockId);
$textBlock = the_field('tekst', 'block', $blockId);
$catBlock = the_field('categorie', 'block', $blockId);

// Categorie pagina's
$sqlCatCols = $mysqli->prepare("SELECT id FROM siteworkcms WHERE keuze1 = ? and status = 'actief' ORDER BY volgorde ASC") or die($mysqli->error . __LINE__);
$sqlCatCols->bind_param('s', $catBlock);
$sqlCatCols->execute();
$sqlCatCols->store_result();
$sqlCatCols->bind_result($idCatItem);

// Categorie met vertaling
$categorie = the_field('categorie', $catBlock);
?>

<div id="block-<?=$blockId;?>" class="blocken nieuws-driekolommen relative my-16">
    <div class="container mx-auto relative">
        <h1><?=$titleBlock;?></h1>
        <div class="grid grid-cols-1 md:grid-cols-3 mt-16 gap-8 lg:gap-16"></div>
        <?php
            while ($sqlCatCols->fetch()) {
                $titleCatItem = the_field('item2', $idCatItem);
                $urlCatItem = the_field('paginaurl', $idCatItem);
                $textCatItem = the_field('tekst', $idCatItem);
                $dateCatItem = the_field('datum', $idCatItem);

                if($urlCatItem <> "") {
                    $getImg = getImgReturn(get_url(), $idCatItem, 0, get_taal(), 'uitgelicht', '500','550','500', 1);

                    echo <<<HTML
                    
                    HTML;
                }
            }
        ?>
        </div>
    </div>
    <div class="container mx-auto text-center mt-16">
        <a href="<?=$siteUrl;?>/<?=$catBlock;?>/" class="btn ghost">Alle items</a>
    </div>
</div>