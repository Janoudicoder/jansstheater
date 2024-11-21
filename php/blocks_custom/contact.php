<?php
$titleBlock = the_field('titel', 'block', $blockId);
$textBlock = the_field('tekst', 'block', $blockId);
$catBlock = the_field('categorie', 'block', $blockId);
?>

<div id="block-<?=$blockId;?>" class="blocken contact bg-primaryLight">
    <div class="container mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-32 py-16">
        <div class="info flex justify-center flex-col">
            <?php
            if($titleBlock){
                echo '<h1 class="mb-8">'.$titleBlock.'</h1>';
            }
            if($textBlock){
                echo '<span>'.$textBlock.'</span>';
            }
            ?>
        </div>
        <div class="form">
            <?php
                $_GET['formIdBlock'] = $catBlock;
                include('php/formulieren.php');
            ?>
        </div>
    </div>
</div>
