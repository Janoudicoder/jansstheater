<?php
$titleBlock = the_field('titel', 'block', $blockId);
$textBlock = the_field('tekst', 'block', $blockId);

$sqlTeamItem = $mysqli->prepare("SELECT id FROM siteworkcms WHERE keuze1 = 'team' and status = 'actief' ORDER BY volgorde ASC") or die($mysqli->error . __LINE__);
$sqlTeamItem->execute();
$sqlTeamItem->store_result();
$sqlTeamItem->bind_result($idTeamItem);

?>

<div id="block-<?=$blockId;?>" class="blocken container mx-auto relative team-lijst my-16">
    <div class="w-full md:w-2/3">
        <?php
        if ($titleBlock) {
            echo '<h2>' . $titleBlock . "</h2>";
        }
        if ($textBlock) {
            echo '<span class="block">' . $textBlock . "</span>";
        }
        ?>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-16 py-16">
        <?php while ($sqlTeamItem->fetch()) {
            $titleTeamItem = the_field('item2', $idTeamItem);
            $urlTeamItem = the_field('paginaurl', $idTeamItem);
            $functionTeamItem = the_field('item3', $idTeamItem);

            echo '<a class="relative team overflow-hidden" href="' .get_link($idTeamItem, 'team').'/">';
            echo '<div class="absolute bottom-0 z-10 flex w-full flex-col gradient justify-between pt-32 p-6 text-white">
                    <span class="font-bold text-xl mb-4">' .
                        $titleTeamItem .
                    '</span>
                    <span class="">
                        <span class="block font-bold text-sm">'.$functie.':</span>
                        <span class="text-sm">'.$functionTeamItem.'</span>
                    </span>
                </div>';
                getImg(get_url(), $idTeamItem, 0, get_taal(), 'team', '500','550','500', 0);
            echo "</a>";
        } ?>
    </div>
</div>