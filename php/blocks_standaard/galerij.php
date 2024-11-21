<?php
$textGallery = the_field('tekst', 'block', $blockId);
?>
<div  id="block-<?=$blockId;?>" class="blocken relative container mx-auto galerij my-16">
    <div class="img relative galerij-blok">
            <div class="galerij-block relative"> 
                <?php getImg(get_url(), get_id(), $blockId, get_taal(), 'block', '1440','750','1440', 0); ?>
            </div>          
    </div>
</div>  
<div class="container mx-auto galerij-nav  mb-32">
    <?php
    if($textGallery){
        echo '<div class="mt-8">'.$textGallery.'</div>';   
    }
    ?>
    <div class="w-full block arrows overflow-hidden relative">
        <span class="prev-galerij"><i class="fal fa-arrow-left"></i></span> 
        <span class="next-galerij"><i class="fal fa-arrow-right"></i></span>
        <div class="slick-slider-dots"></div>
    </div>
</div>
