<?php
// Tekst
$teksttweekol = the_field('tekst', 'block', $blockId);

if($teksttweekol){
    if (strpos($teksttweekol, '<hr />') !== false) {
        $startplode = explode("<hr />", $teksttweekol);
        $kol1 = $startplode[0];
        $kol2 = $startplode[1];

        echo <<<HTML
            <div id="block-$blockId" class="relative container mx-auto tekst-twee-kolom mb-32 text-black">
                <div class="grid grid-cols-1 md:grid-cols-2 ga-12">
                    <div>
                        $kol1
                    </div>
                    <div>
                        $kol2
                    </div>
                </div>
            </div>
        HTML;
    }else{
        echo <<<HTML
            <div id="block-$blockId" class="relative container mx-auto tekst-twee-kolom mb-32 text-black">
                $teksttweekol
            </div>
        HTML;
    }
}
?>
