<?php
$textFull = the_field('tekst', 'block', $blockId);
?>
 
<div id="block-<?=$blockId;?>" class="blocken tekst-volledige-breedte my-16">
    <div class="container mx-auto"> 
        <div class="block mx-auto content w-full md:w-2/3">
            <span><?= $textFull ?></span>
        </div>
    </div>
</div> 