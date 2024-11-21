<?php
//Titel / subtitel gegevens
$subtitleTitles = the_field('tekst', 'block', $blockId);
$titleTitles = the_field('titel', 'block', $blockId);
?>
<div id="block-<?=$blockId;?>" class="blocken relative my-16">
    <div class="container mx-auto">
        <span><?=$subtitleTitles;?></span>
        <h2><?=$titleTitles;?></h2>
    </div>
</div> 