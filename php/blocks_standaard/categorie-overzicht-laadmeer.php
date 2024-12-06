<?php
$titleBlock = the_field('titel', 'block', $blockId);
$textBlock = the_field('tekst', 'block', $blockId);
$catBlock = the_field('categorie', 'block', $blockId);

$sqlCatLoadMore = $mysqli->prepare("SELECT id FROM siteworkcms WHERE keuze1 = ? and status = 'actief' ORDER BY volgorde ASC") or die($mysqli->error . __LINE__);
$sqlCatLoadMore->bind_param('s', $catBlock);
$sqlCatLoadMore->execute();
$sqlCatLoadMore->store_result();
$sqlCatLoadMore->bind_result($idCatItem);

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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 py-16">
        <?php
        while ($sqlCatLoadMore->fetch()) {
            $titleCatItem = the_field('item2', $idCatItem);
            $urlCatItem = the_field('paginaurl', $idCatItem);
            $textCatItem = the_field('tekst', $idCatItem);
            $dateCatItem = the_field('datum', $idCatItem);

            if($urlCatItem <> "") {
                echo '<a class="relative nieuws-laadmeer-blok" href="'.get_link($idCatItem, $categorie.'-'.$berichten).'">';
                    echo '<div class="absolute bottom-0 z-10 flex w-full flex-col gradient justify-between pt-32 p-2 text-white">
                            <span class="font-bold text-[16px] mb-4">'.$titleCatItem.'</span>

                            <span class="mb-4 text-[16px]">'.strip_tags(limit_text($textCatItem, 90),'').'</span>
                        </div>';
                    getImg(get_url(), $idCatItem, 0, get_taal(), 'uitgelicht', '500','550','500', 1);
                echo '</a>';
            }
        }
        ?>
    </div>
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