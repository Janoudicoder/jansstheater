<?php
$textGallery = the_field('tekst', 'block', $blockId);
$catGallery = the_field('categorie', 'block', $blockId);
// Functie om een willekeurige afmeting te kiezen
function getRandomImageSize() {
    $sizes = [
        ['width' => 370, 'height' => 270],
        ['width' => 270, 'height' => 300]
    ];
    return $sizes[array_rand($sizes)];
    
}

$imageIds = [1, 2, 3, 4]; // Dit moet dynamisch de daadwerkelijke beschikbare afbeelding IDs bevatten
$totalImages = count($imageIds); // Totaal aantal afbeeldingen die er zijn
?>
<div id="block-<?=$blockId;?>" class="blocken tekst-galerij mb-[13rem] relative container mx-auto galerij h-[700px] <?= ($catGallery == '1') ? 'tekst-galerij-yallow' : ''; ?> sm:my-28 flex flex-col md:flex-row items-center justify-center">
    <div class="<?= ($catGallery == '1') ? 'tekstgalerij-background' : ''; ?> w-full h-full">
        <div class="flex flex-wrap md:flex-nowrap gap-6 md:gap-12 h-full justify-center items-center">
            <!-- Text Section -->
            <div class="w-full md:w-[470px] lg:h-full h-auto overflow-y-auto flex items-center p-6 lg:justify-center self-center">
                <?php if ($textGallery): ?>
                    <div class="mt-8 md:mt-0"><?= $textGallery; ?></div>
                <?php endif; ?>
            </div>

            <!-- Gallery Section -->
            <div class="">
                <div id="galerij-container" class="galerij-slider grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php 
                        getImg(get_url(), get_id(), $blockId, get_taal(), 'block', $size['width'], $size['height'], '1440', 4); 
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
