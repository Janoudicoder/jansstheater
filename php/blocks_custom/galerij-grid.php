<?php
// Titel
$gridTitel = the_field('titel', 'block', $blockId);
?>

<div id="block-<?=$blockId;?>" class="blocken">
    <?php if($gridTitel):?>
        <div class="container mx-auto text-center">
            <h1 class="text-primary"><?=$gridTitel;?></h1>
        </div>
    <?php endif;?>
    <div class="container mx-auto">
        <div class="container mx-auto grid grid-cols-2 md:grid-cols-4 mt-16 gap-8 lg:gap-16"> 
            <?php
                // grid afbeeldingen loop 
                getImg(get_url(), get_id(), $blockId, get_taal(), 'block', '','','', 0); 
            ?>
        </div>
    </div>
</div>