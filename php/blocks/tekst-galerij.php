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
<div id="block-<?=$blockId;?>" class="blocken tekst-galerij relative container mx-auto galerij my-28 h-[700px] <?= ($catGallery == '1') ? 'tekst-galerij-yallow' : ''; ?> ">
    <div class="<?= ($catGallery == '1') ? 'tekstgalerij-background' : ''; ?>">
        <div class="flex flex-wrap lg:flex-nowrap gap-8 h-full">
            <div class="w-full lg:w-1/2 h-full overflow-y-auto flex items-center p-[112px] justify-center">
                <?php
                if ($textGallery) {
                    echo '<div class="mt-8">' . $textGallery . '</div>';
                }
                ?>
            </div>
            

            <div id='galerij-container' class="w-full lg:w-1/2 grid grid-cols-2 gap-4" style="align-content: center;">
                <?php 
                    getImg(get_url(), get_id(), $blockId, get_taal(), 'block', $size['width'], $size['height'], '1440', 4); 
                ?>
            </div>
        </div>
    </div>
</div>
