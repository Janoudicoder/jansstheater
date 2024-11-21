<?php
$stickerID = '69';
$titelSticker = the_field('item2', $stickerID);
$tekstSticker = the_field('tekst', $stickerID);
$urlSticker = the_field('paginaurl', $stickerID);
$extUrlSticker = the_field('externeurl', $stickerID);
$targetSticker = the_field('targetlink', $stickerID);
$statusSticker = the_field('status', $stickerID);

//url sticker bepalen
if($extUrlSticker){
    $stickerUrl = $extUrlSticker;
}else{
    $stickerUrl = get_url() .'/'.$urlSticker;
}
//url target bepalen
if($targetSticker){
    $stickerTarget = "target=\"_blank\"";
}else{
    $stickerTarget = "target=\"_self\"";
}

if($statusSticker == "Actief"){
    echo "<a id=\"sticker\" href=\"{$stickerUrl}\" {$stickerTarget}>
            <span class=\"font-bold text-2xl\">{$titelSticker}</span>
            <span class=\"\">".limit_text(strip_tags($tekstSticker,''),50)."</span> 
        </a>
    ";
}
?>
