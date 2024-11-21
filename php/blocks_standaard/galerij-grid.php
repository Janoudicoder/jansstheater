<?php
$gridTitel = the_field('titel', 'block', $blockId);
?>

<div id="block-<?=$blockId;?>" class="blocken container mx-auto">
    <?php if($gridTitel <> ""): ?>
        <div>
            <h2><?=$gridTitel;?></h2>
        </div>
    <?php endif; ?>
    <div class="grid-galerij mb-16 mt-6"> 
        <?php getImg(get_url(), get_id(), $blockId, get_taal(), 'block', '','','', 0); ?>
    </div>
</div>