<?php
$titleBlock = the_field('titel', 'block', $blockId);
$textBlock = the_field('tekst', 'block', $blockId);
$catBlock = the_field('categorie', 'block', $blockId);
$dateCatItem = the_field('datum', 'block', $blockId);

if ($titleBlock === "1") { 
    $sqlCatLoadMore = $mysqli->prepare("SELECT id FROM siteworkcms WHERE keuze1 = ? and status = 'actief' ORDER BY volgorde ASC") or die($mysqli->error . __LINE__);
    $sqlCatLoadMore->bind_param('s', $catBlock);
    $sqlCatLoadMore->execute();
    $sqlCatLoadMore->store_result();
    $sqlCatLoadMore->bind_result($idCatItem);

    $categorie = the_field('categorie', $catBlock);
    ?>

    <div id="block-<?=$blockId;?>" class="blocken programma w-full mx-auto relative nieuws-laadmeer my-16">
        <div class="w-full  md:w-[33.666667%] text-center md:ml-0 ">
            <?php 
          
            if ($textBlock) {
                echo '<span class="block">'.$textBlock.'</span>';
            }
            ?>
        </div>
        <div class="programma-slider pt-4">
            <?php
            while ($sqlCatLoadMore->fetch()) {
                $titleCatItem = the_field('item2', $idCatItem);
                $urlCatItem = the_field('paginaurl', $idCatItem);
                $textCatItem = the_field('tekst', $idCatItem);
                $dateCatItem = the_field('datum', $idCatItem);

                $itemDate = strtotime($dateCatItem);
                $currentDate = time();

                if ($itemDate >= $currentDate && $urlCatItem != "") {
                    $daysLeft = ceil(($itemDate - $currentDate) / (60 * 60 * 24)); 

                    echo '<a class="relative nieuws-laadmeer-blok" href="'.get_link($idCatItem, $categorie.'-'.$berichten).'">';
                    echo '<div class="absolute bottom-0 z-10 flex flex-col w-full sm:w-[370px] gradient justify-between pt-32 p-2 text-white">
                    <span class="font-bold text-[24px] mb-4"><i class="fas fa-calendar-day"></i> '.$dateCatItem.'</span>
                                <span class="text-[16px] mb-4">'.$titleCatItem.'</span>
                                <span class="mb-4 text-gray-400">'.strip_tags(limit_text($textCatItem, 90), '').'</span>';
                    
                    if ($daysLeft > 0) {
                        echo '<span class="text-sm text-yellow-400">Over ' .$daysLeft.' dagen </span>';
                    }
                    
                    echo '</div>';
                    getImg(get_url(), $idCatItem, 0, get_taal(), 'uitgelicht', '500', '550', '500', 1);
                    echo '</a>';
                }
            }
            ?>
        </div>
    </div>
    <?php
} 
else{
    $sqlCatLoadMore = $mysqli->prepare("SELECT id FROM siteworkcms WHERE keuze1 = ? and status = 'actief' ORDER BY volgorde ASC") or die($mysqli->error . __LINE__);
    $sqlCatLoadMore->bind_param('s', $catBlock);
    $sqlCatLoadMore->execute();
    $sqlCatLoadMore->store_result();
    $sqlCatLoadMore->bind_result($idCatItem);
    
    $categorie = the_field('categorie', $catBlock);
    ?>
    
    <div id="block-<?=$blockId;?>" class="blocken programma w-full mx-auto relative nieuws-laadmeer my-16">
        <div class="w-full md:w-[33.666667%] text-center md:ml-0 ">
            <?php 
     
                echo '<span class="block">'.$textBlock.'</span>';
            
            ?>
        </div>
        <div class="programma-table p-14 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 justify-items-center">
    <?php
    while ($sqlCatLoadMore->fetch()) {
        $titleCatItem = the_field('item2', $idCatItem);
        $urlCatItem = the_field('paginaurl', $idCatItem);
        $textCatItem = the_field('tekst', $idCatItem);
        $dateCatItem = the_field('datum', $idCatItem);

        $itemDate = strtotime($dateCatItem);
        $currentDate = time();

        if ($itemDate >= $currentDate && $urlCatItem != "") {
            $daysLeft = ceil(($itemDate - $currentDate) / (60 * 60 * 24)); 

            echo '<a class="relative nieuws-laadmeer-blok" href="'.get_link($idCatItem, $categorie.'-'.$berichten).'">';
            echo '<div class="absolute bottom-0 z-10 flex flex-col w-full sm:w-[370px] gradient justify-between pt-32 p-2 text-white">
                    <span class="font-bold text-[24px] mb-4"><i class="fas fa-calendar-day"></i> '.$dateCatItem.'</span>
                    <span class="text-[16px] mb-4">'.$titleCatItem.'</span>
                    <span class="mb-4 text-gray-400">'.strip_tags(limit_text($textCatItem, 90), '').'</span>';

            if ($daysLeft > 0) {
                echo '<span class="text-sm text-yellow-400">Over ' .$daysLeft.' dagen </span>';
            }

            echo '</div>';
            getImg(get_url(), $idCatItem, 0, get_taal(), 'uitgelicht', '500', '550', '500', 1);
            echo '</a>';
        }
    }
    ?>
</div>

    </div>
    <?php

}
