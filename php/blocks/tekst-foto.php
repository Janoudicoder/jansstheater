<?php
// Grid 
$TextGridLinks = "";
$TextGridRechts = "";

$textBlock = the_field('tekst', 'block', $blockId);
$verhoudingBlock = the_field('categorie', 'block', $blockId);
$layoutBlock = the_field('titel', 'block', $blockId);

if($textBlock){

    if($verhoudingBlock == "30-70") {
        $TextGridLinks = "w-full lg:w-1/3";
        $TextGridRechts = "w-full lg:w-2/3";
    } elseif($verhoudingBlock == "70-30") {
        $TextGridLinks = "w-full lg:w-2/3";
        $TextGridRechts = "w-full lg:w-1/3";
    } else {
        $TextGridLinks = "w-full lg:w-[37%]";
        $TextGridRechts = "w-full lg:w-[37%]";
    }

?>
<div id="block-<?=$blockId;?>" class="blocken relative container mx-auto flex flex-col lg:flex-row justify-center gap-8 lg:gap-8 xl:gap-16 tekst-foto my-16" 
    style="background-image: url('https://jansstheater.sitework.link/img/_8851_1732022177.jpg'); background-size: cover; background-position: center;">
    
    <?php if($layoutBlock == "foto-links-tekst-rechts"): ?>
        <div class="relative <?=$TextGridLinks;?> z-10">
            <div class="absolute inset-0 z-0">
                <svg width="553" height="377" viewBox="0 0 568 388" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 0H568L547.714 364.038L0 388V0Z" fill="#D03184"/>
                </svg>
            </div>
            <div class="galery">
                <div class="image-slider image-slider-2">
                    <?php getImg(get_url(), get_id(), $blockId, get_taal(), 'block', '','','', 0); ?>
                </div>
            </div>            
        </div>
        
        <div class="content pb-8 pt-8 flex justify-start flex-col <?=$TextGridRechts;?> z-10">
            <?php echo $textBlock; ?>
        </div>
    <?php else: ?>
        <div class="content pb-8 pt-8 flex justify-start flex-col <?=$TextGridLinks;?> z-10">
            <?php echo $textBlock; ?>
        </div>
        
        <div class="relative <?=$TextGridRechts;?> z-10">
            <div class="absolute inset-0 z-0">
                <svg width="471" height="315" viewBox="0 0 568 388" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 0H568L547.714 364.038L0 388V0Z" fill="#D03184"/>
                </svg>
            </div>
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
