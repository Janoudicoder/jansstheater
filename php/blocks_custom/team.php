<?php
// Titel & Tekst
$titleBlock = the_field('titel', 'block', $blockId);
$textBlock = the_field('tekst', 'block', $blockId);

// Teamleden ophalen
$sqlTeamItem = $mysqli->prepare("SELECT id FROM siteworkcms WHERE keuze1 = 'team' and status = 'actief' ORDER BY volgorde ASC") or die($mysqli->error . __LINE__);
$sqlTeamItem->execute();
$sqlTeamItem->store_result();
$sqlTeamItem->bind_result($idTeamItem);

?>

<div id="block-<?=$blockId;?>" class="blocken container mx-auto relative team-lijst my-16">
    <div class="container mx-auto">
        <h1>Team overzicht</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 mt-16 gap-8 lg:gap-16"> 
        <?php 
            while ($sqlTeamItem->fetch()) {
                $titleTeamItem = the_field('item2', $idTeamItem);
                $urlTeamItem = the_field('paginaurl', $idTeamItem);
                $functionTeamItem = the_field('item3', $idTeamItem);
                $emailTeamItem = the_field('item4', $idTeamItem);

                if($urlTeamItem <> "") {
                    $getImg = getImgReturn(get_url(), $idTeamItem, 0, get_taal(), 'team', '500','550','500', 1);
                    $blockLink = get_link($idTeamItem, 'team');

                    echo <<<HTML
                        <div class="relative">
                            <div class="relative mb-8">
                                <div class="absolute z-10 rounded-xl py-2 px-4 left-6 bottom-6 right-6 lg:right-auto">{$titleTeamItem}</div>
                                <div class="rounded-br-3xl overflow-hidden">
                                    {$getImg}
                                </div>
                            </div>
                            <div class="grid grid-auto-1 gap-x-8">
                                <div class="left font-bold">Functie:</div>
                                <div class="right">{$functionTeamItem}</div>
                                <div class="left font-bold">E-mail:</div>
                                <div class="right">{$emailTeamItem}</div>
                            </div>
                        </div>
                    HTML;
                }
            } 
        ?>
        </div>
    </div> 
</div>