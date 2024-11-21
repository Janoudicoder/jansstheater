<?php
// Grid 
$TextGridLinks = "";
$TextGridRechts = "";

$textBlock = the_field('tekst', 'block', $blockId);
$verhoudingBlock = the_field('categorie', 'block', $blockId);
$layoutBlock = the_field('titel', 'block', $blockId);

if($textBlock){

    // Hier word gekeken welke verhouding het blok moet aanhouden
    if($verhoudingBlock == "30-70") {
        $TextGridLinks = "w-full lg:w-1/3";
        $TextGridRechts = "w-full lg:w-2/3";
    } elseif($verhoudingBlock == "70-30") {
        $TextGridLinks = "w-full lg:w-2/3";
        $TextGridRechts = "w-full lg:w-1/3";
    } else {
        $TextGridLinks = "w-full lg:w-1/2";
        $TextGridRechts = "w-full lg:w-1/2";
    }

?>
    <div id="block-<?=$blockId;?>" class="blocken relative container mx-auto flex flex-col lg:flex-row justify-center gap-8 lg:gap-16 xl:gap-24 tekst-foto my-16">
        <?php // Hier word gekeken welke layout het blok moet aanhouden
        if($layoutBlock == "foto-links-tekst-rechts"): ?>
            <div class="relative <?=$TextGridLinks;?>">
                <div class="galery">
                    <div class="image-slider image-slider-2">
                        <?php getImg(get_url(), get_id(), $blockId, get_taal(), 'block', '','','', 0); ?>
                    </div>
                </div>            
            </div>
            <div class="content pb-8 pt-8 flex justify-start flex-col <?=$TextGridRechts;?>">
                <?php echo $textBlock; ?>
            </div>
        <?php else: ?>
            <div class="content pb-8 pt-8 flex justify-start flex-col <?=$TextGridLinks;?>">
                <?php echo $textBlock; ?>
            </div>
            <div class="relative <?=$TextGridRechts;?>">
                <div class="galery">
                    <div class="image-slider image-slider-2">
                        <?php getImg(get_url(), get_id(), $blockId, get_taal(), 'block', '','','', 0); ?>
                    </div>
                </div>            
            </div>
        <?php endif; ?>
    </div>  
<?php
}
?>