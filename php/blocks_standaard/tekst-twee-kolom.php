<?php
$teksttweekol = the_field('tekst', 'block', $blockId);

if($teksttweekol){
    if (strpos($teksttweekol, '<hr />') !== false) {
        $startplode = explode("<hr />", $teksttweekol);
        echo '
        <div id="block-'.$blockId.'" class="relative container mx-auto tekst-twee-kolom mb-32 text-black">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>'.$startplode[0].'</div>
                <div>'.$startplode[1].'</div>
            </div>
        </div>
        ';
    }else{
        echo '
        <div id="block-'.$blockId.'" class="relative container mx-auto tekst-twee-kolom mb-32 text-black">
            <div class="content">
                '.$teksttweekol.'
            </div>
        </div>
        ';
    }
}
?>
