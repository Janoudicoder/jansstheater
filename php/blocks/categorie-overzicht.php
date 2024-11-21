<?php
$titleBlock = the_field('titel', 'block', $blockId);
$textBlock = the_field('tekst', 'block', $blockId);
$catBlock = the_field('categorie', 'block', $blockId);

$sqlCatCols = $mysqli->prepare("SELECT id FROM siteworkcms WHERE keuze1 = ? and status = 'actief' ORDER BY volgorde ASC") or die($mysqli->error . __LINE__);
$sqlCatCols->bind_param('s', $catBlock);
$sqlCatCols->execute();
$sqlCatCols->store_result();
$sqlCatCols->bind_result($idCatItem);

$categorie = the_field('categorie', $catBlock);

?>
<div id="block-<?=$blockId;?>" class="blocken nieuws-driekolommen relative my-16">
    <div class="container mx-auto relative">
        <div class="w-full md:w-2/3">
            <?php if($titleBlock){ ?>
                <h1 class="inline-block w-full"><?= $titleBlock; ?></h1>
            <?php } ?>
            <?php if($textBlock){ ?>
                <span class="inline-block w-full"><?php echo limit_text(strip_tags($textBlock, ''), 350); ?></span>
            <?php } ?>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 py-16">
            <?php
            while ($sqlCatCols->fetch()) {
                $titleCatItem = the_field('item2', $idCatItem);
                $urlCatItem = the_field('paginaurl', $idCatItem);
                $textCatItem = the_field('tekst', $idCatItem);
                $dateCatItem = the_field('datum', $idCatItem);
                //hier komt het blok met img functie
                if($urlCatItem <> "") {
                    echo '<a class="relative cat-item border-2 shadow-lg overflow-hidden rounded-xl pb-16" href="'.get_link($idCatItem, $categorie . '-'.$berichten).'">';
                        echo "<div class=\"img-container overflow-hidden\">";
                            getImg(get_url(), $idCatItem, 0, get_taal(), 'uitgelicht', '500','300','500', 1);
                        echo "</div>";
                        echo "    
                                <div class=\"catContent p-6\">
                                    <p class=\"h3\">{$titleCatItem}</p>".
                                    strip_tags(limit_text($textCatItem,100),'')." [...]
                                </div>
                                <span class=\"absolute left-6 btn\">".$leesverder."</span>
                            ";
                    echo '</a>';
                }
            }
            ?>
        </div>
    </div>
</div>