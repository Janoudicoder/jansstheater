<?php
// Grid links/rechts
$TextGridLinks = "";
$TextGridRechts = "";

// Tekst, Verhouding, Layout
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

    // Dit is de layout van de links en rechts
    if($layoutBlock == "foto-links-tekst-rechts") {
        $flexLayout = 'lg:flex-row';
    } else {
        $flexLayout = 'lg:flex-row-reverse';
        if($verhoudingBlock == "30-70") {
            $TextGridLinks = "w-full lg:w-2/3";
            $TextGridRechts = "w-full lg:w-1/3";
        } elseif($verhoudingBlock == "70-30") {
            $TextGridLinks = "w-full lg:w-1/3";
            $TextGridRechts = "w-full lg:w-2/3";
        } else {
            $TextGridLinks = "w-full lg:w-1/2";
            $TextGridRechts = "w-full lg:w-1/2";
        }
    }

    // Image
    $getImg = getImgReturn(get_url(), get_id(), $blockId, get_taal(), 'block', '','','', 0);

?>
    <div id="block-<?=$blockId;?>" class="blocken relative container mx-auto flex flex-col <?=$flexLayout;?> justify-center gap-8 lg:gap-16 xl:gap-24 tekst-foto my-16">
        <div class="relative <?=$TextGridLinks;?>">
            <div class="image-slider image-slider-2">
                <?php 
                    // Image
                    if($getImg){
                        echo $getImg;
                    }
                ?>
            </div>
        </div>
        <div class="content pb-8 pt-8 flex justify-start flex-col <?=$TextGridRechts;?>">
            <?php echo $textBlock; ?>
        </div>
    </div>  
<?php
}
?>