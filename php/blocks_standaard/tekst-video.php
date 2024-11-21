<?php
$videoTekst = the_field('tekst', 'block', $blockId);
$videoCode = the_field('titel', 'block', $blockId);
$videoSoort = the_field('categorie', 'block', $blockId);
?>

<div id="block-<?=$blockId;?>" class="blocken video">
    <div class="container mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 my-16">
        <div>
            <?=$videoTekst;?>
        </div>
        <?php if($videoSoort == "youtube"){
            echo ' 
            <div class="video-container mb-8 tekst-video">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/'.$videoCode.'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen autoplay></iframe>
            </div>
            ';
        }else{
            echo '
            <div class="video-container tekst-video">
                <div style="padding:52.73% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/'.$videoCode.'?h=009870c6c7&color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe></div><script src="https://player.vimeo.com/api/player.js"></script>
            </div>
            ';
        }
        ?>
    </div>
</div>