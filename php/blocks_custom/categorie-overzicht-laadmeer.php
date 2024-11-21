<?php
// Titel, Tekst en Categorie
$titleBlock = the_field('titel', 'block', $blockId);
$textBlock = the_field('tekst', 'block', $blockId);
$catBlock = the_field('categorie', 'block', $blockId);

// Categorie pagina's
$sqlCatLoadMore = $mysqli->prepare("SELECT id FROM siteworkcms WHERE keuze1 = ? and status = 'actief' ORDER BY volgorde ASC") or die($mysqli->error . __LINE__);
$sqlCatLoadMore->bind_param('s', $catBlock);
$sqlCatLoadMore->execute();
$sqlCatLoadMore->store_result();
$sqlCatLoadMore->bind_result($idCatItem);

// Categorie met vertaling
$categorie = the_field('categorie', $catBlock);
?>

<div id="block-<?=$blockId;?>" class="blocken container mx-auto relative nieuws-laadmeer my-16">
    <div class="w-full md:w-2/3">
        <?php 
        if($titleBlock){
            echo '<h2 class="inline-block w-full">'.$titleBlock.'</h2>';
        }
        if($textBlock){
            echo '<span class="block">'.$textBlock.'</span>';
        }
        ?>
    </div>
    <?php
        while ($sqlCatLoadMore->fetch()) {
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
    <div class="container text-center mt-4">
        <a href="#" class="btn ghost" id="loadMore"><?=$laadmeer;?> <?=$categorie;?></a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to show items
        function showItems(start, count) {
            const items = document.querySelectorAll('.nieuws-laadmeer-blok');
            for (let i = start; i < start + count && i < items.length; i++) {
                items[i].classList.add('show');
            }
        }

        showItems(0, 3);

        document.getElementById('loadMore').addEventListener('click', function(e) {
            e.preventDefault();
            const hiddenItems = Array.from(document.querySelectorAll('.nieuws-laadmeer-blok:not(.show)'));
            const countToShow = 3;
            const itemsToShow = hiddenItems.slice(0, countToShow);

            itemsToShow.forEach(item => item.classList.add('show'));

            if (hiddenItems.length <= countToShow) {
                this.textContent = '<?=$geenItems;?>';
                this.classList.add('noContent');
            }
        });
    });
</script>