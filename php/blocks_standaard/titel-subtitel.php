<?php
//Titel / subtitel gegevens
$subtitleTitles = the_field('tekst', 'block', $blockId);
$titleTitles = the_field('titel', 'block', $blockId);
?>
<div id="block-<?=$blockId;?>" class="blocken relative my-16">
    <div class="titel-blok container mx-auto my-16 flex flex-col gap-4 relative text-center">
        <span class="text-secondary text-xl xxl:text-2xl"><?=$subtitleTitles;?></span>
        <h2 class="text-3xl xxl:text-5xl"><?=$titleTitles;?></h2>
    </div>
</div> 