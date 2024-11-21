<?php
if($_POST['q']){ 
    $zoekterm = htmlspecialchars($_POST['q']);
    if ($zoekterm == "") { $zoekterm = $rowinstellingen['naamwebsite'];} 

    $urlCheck = str_replace(' ', '%', $zoekterm);

    if(get_taal() == 'nl'){
        $sqlzoek = "SELECT * FROM siteworkcms WHERE 
                (item1 LIKE '%$zoekterm%' or 
                item2 LIKE '%$zoekterm%' or
                item3 LIKE '%$zoekterm%' or
                item4 LIKE '%$zoekterm%' or
                item5 LIKE '%$zoekterm%' or
                paginaurl LIKE '%$urlCheck%' or  
                tekst LIKE '%$zoekterm%')
                AND inXML = 'ja' and status = 'actief'";

        $sqlzoekBlocks = "SELECT * FROM sitework_blocks WHERE titel LIKE '%$zoekterm%' or tekst LIKE '%$zoekterm%'";
    } else {
        $taal = get_taal();
        $vertaalCheck = str_replace(' ', '%', $zoekterm);
        $sqlzoek = "SELECT * FROM sitework_vertaling WHERE waarde LIKE '%$vertaalCheck%' AND taal = '$taal'";

        $sqlzoekBlocks = "SELECT * FROM sitework_vertaling_blocks WHERE waarde LIKE '%$zoekterm%' AND taal = '$taal'";
    }

    $resultzoek = $mysqli->query($sqlzoek) or die($mysqli->error.__LINE__);
    $rowszoek = mysqli_num_rows($resultzoek);

    $resultszoekBlocks = $mysqli->query($sqlzoekBlocks) or die($mysqli->error.__LINE__);
    $rowsBlocks = mysqli_num_rows( $resultszoekBlocks );

    $processedIDs = []; // Array to store processed IDs
}

?>  

<? include ('php/breadcrumbs.php'); ?>
<section id="content-vervolg-main">
	<div class="container mx-auto grid grid-cols-1 pt-6">
        <div id="zoekblok">
            <h1><?php echo the_field('item2'); ?></h1>
            <form id='zoekform' class="relative" method="POST" action="<?php echo 'https://' . $_SERVER['SERVER_NAME'] . '' . $_SERVER['REDIRECT_URL']; ?>">
                <input type="search" name='q' id='q' placeholder='<?=$zoekwoord;?>'>
                <input type="hidden" name="taal" value="<?php if(get_taal()){ echo get_taal(); } else { echo $_POST['taal']; };?>">
                <button class='btn search absolute right-0 top-1/2 translate-y-[-50%]' type="submit"><i class="far fa-search"></i></button>
            </form>
            
            <br><br>

            <div id="zoekresultaten">
                <?php
                if(isset($_POST['q']) && !empty($_POST['q'])){ ?> 
                    <div class="text-2xl">
                        <?php if($rowszoek > 0): ?>
                            <strong id="totalresult">(<?=$rowszoek;?>)</strong> <?=$resultatenGevondenVoor;?>: <b><?php echo $zoekterm; ?></b>
                            <hr class="my-2">
                        <?php endif; ?>
                        <?php if($rowsBlocks > 0): ?>
                            <?php echo ($rowszoek > 0) ? $metNog : '' ?><strong>(<?=$rowsBlocks;?>)</strong> <?php echo ($rowsBlocks > 1) ? $resultaten : $resultaat ?> <?=$tekstInBlock;?>. 
                        <?php endif; ?>
                        <br>
                    </div>
                    <div class="zoek-inner grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                        <?php
                            $zoekteller = 0;

                            while($rowzoek = $resultzoek->fetch_assoc()){ 
                                if(get_taal() == "nl") {
                                    $currentID = $rowzoek['id'];
                                } else {
                                    $currentID = $rowzoek['cms_id'];
                                }

                                if (in_array($currentID, $processedIDs)) {
                                    // The ID is already in the array, so skip the rest of the loop.
                                    continue;
                                } else {
                                    $zoekteller++;
                                }

                                $processedIDs[] = $currentID;

                                if(get_taal() != 'nl') {
                                    $searchHitCmsId = $rowzoek['cms_id'];
                                    $searchHitTaal = $rowzoek['taal'];
                                    $searchHit = $rowzoek['waarde'];

                                    $vertaalZoek = $mysqli->query("SELECT * FROM sitework_vertaling WHERE cms_id = '$searchHitCmsId' AND taal = '$searchHitTaal'") or die($mysqli->error.__LINE__);

                                    $rowzoek = [];

                                    while ($rowVertaalZoek = $vertaalZoek->fetch_assoc()) {
                                        $rowzoek[$rowVertaalZoek['veld']] = $rowVertaalZoek['waarde'];
                                    }

                                }                                

                                $value = "";
                                $paginaurlCheck = strToLower(str_replace(' ', '-', $zoekterm));

                                if (strpos($rowzoek['item1'], $zoekterm) !== false) { $value = "item1";
                                } elseif (strpos($rowzoek['item2'], $zoekterm) !== false) { $value = "item2";
                                } elseif (strpos($rowzoek['item3'], $zoekterm) !== false) { $value = "item3";
                                } elseif (strpos($rowzoek['item4'], $zoekterm) !== false) { $value = "item4";
                                } elseif (strpos($rowzoek['item5'], $zoekterm) !== false) { $value = "item5"; 
                                } elseif (strpos($rowzoek['paginaurl'], $paginaurlCheck) !== false) { $value = "paginaurl"; 
                                } elseif (strpos($rowzoek['tekst'], $zoekterm) !== false) { $value = "tekst"; 
                                }

                                if ($rowzoek['meta_titel'] <> '') { $sublink = str_replace (" ", "-", $rowzoek['meta_titel']); } else { $sublink = str_replace (" ", "-", $rowzoek['item1']); } //checken voor metaURL
                                
                                if(get_meertaligheid() == true) {
                                    $zoekVertaalUrl = get_taal() . '/';
                                } else {
                                    $zoekVertaalUrl = '';
                                }
                        ?>

                                <div class="zoekitem">
                                    <div class="zoek-img relative aspect-[4/3]">
                                        <?php getImg(get_url(), $rowzoek['id'], 0, get_taal(), '', '200','100','500', 1); ?>
                                        <div class="absolute left-0 bottom-0 bg-white rounded-tr-md">
                                            <?php if($value != 'tekst' && $value <> "" && $value != ''): ?>
                                                <strong class="zoekHighlight flex px-4 py-2">
                                                    <?php
                                                        if($value <> 'paginaurl'){
                                                            echo ucfirst($rowzoek[$value]);
                                                        } else { 
                                                            if(get_meertaligheid() == true) {
                                                                echo get_url() . '/' . get_taal() . '/' . $rowzoek[$value]; 
                                                            } else {
                                                                echo get_url() . '/' . $rowzoek[$value]; 
                                                            }
                                                        }
                                                    ?>
                                                </strong>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="zoekitem-tekst">
                                        <div class='titel-en-tekst'>
                                            <div class="zoekitem_titel"><b><? echo $rowzoek['item2']; ?></b></div>
                                            <?php 
                                                $stripzoek = strip_tags($rowzoek['tekst']);
                                                if(!empty($stripzoek)): 
                                                    $contextBlock = zoekWoordLatenZien($stripzoek, $_POST['q']);
                                                    echo $contextBlock;
                                                    // echo substr(htmlentities(html_entity_decode($stripzoek)), 0, 75) . '...'; 
                                                endif;
                                            ?>
                                        </div>
                                        <?php if($rowzoek['keuze1'] == 'nieuws' || $rowzoek['keuze1'] == 'team' || $rowzoek['keuze1'] == 'agenda'){?>
                                            <a class='btn' href="<?php echo get_url();?>/<?=$zoekVertaalUrl;?><?=$rowzoek['keuze1']?>/<?= $rowzoek['paginaurl'];?>/"><?=$leesmeer;?></a>
                                        <?}else{?>
                                            <a class='btn' href="<?php echo get_url();?>/<?=$zoekVertaalUrl;?><?= $rowzoek['paginaurl'];?>/"><?=$leesmeer;?></a>
                                        <?}?>
                                    
                                    </div>
                                </div>

                                <script>
                                    document.getElementById('totalresult').innerHTML = '(<?=$zoekteller;?>)';
                                </script>
                        <?php } ?>
                        <?php 
                            while($rowBlocks = $resultszoekBlocks->fetch_assoc())
                            { 
                                if(get_taal() == 'nl') {
                                    $rowszoek3 = "SELECT * FROM siteworkcms WHERE id = '".$rowBlocks['cms_id']."' and status = 'actief'";
                                    $resultzoek3 = $mysqli->query($rowszoek3) or die($mysqli->error.__LINE__);
                                } else {
                                    $rowszoek3 = "SELECT * FROM sitework_vertaling WHERE cms_id = '".$rowBlocks['cms_id']."' and taal = '".$_GET['taal']."'";
                                    $resultzoek3 = $mysqli->query($rowszoek3) or die($mysqli->error.__LINE__);
                                }

                                $resultBlockTekst = $mysqli->query("SELECT * FROM sitework_block WHERE id = '".$rowBlocks['block_id']."' ") or die($mysqli->error.__LINE__);
                                $rowBlockTekst = $resultBlockTekst->fetch_assoc();
                            
                                while($rowBlockWeb = $resultzoek3->fetch_assoc())
                                {
                                    if(get_taal() != 'nl') {
                                        $rowBlockWeb = [];
                                        $rowBlockWeb['id'] = $rowBlocks['cms_id'];
                                        while ($row = $resultzoek3->fetch_assoc()) {
                                            $rowBlockWeb[$row['veld']] = $row['waarde'];
                                        }
                                    }
                                    if(get_taal() == 'nl') {
                                        if ($rowBlockWeb['meta_titel'] <> '') { $sublink = str_replace (" ", "-", $rowBlockWeb['meta_titel']); } else { $sublink = str_replace (" ", "-", $rowBlockWeb['item1']); } //checken voor metaURL
                                    }
                                    if(get_meertaligheid() == true) {
                                        $zoekBlockVertaalUrl = get_taal() . '/';
                                    } else {
                                        $zoekBlockVertaalUrl = '';
                                    }
                                    ?>

                                    <div class="zoekitem">
                                        <div class="zoek-img relative aspect-[4/3]">
                                            <?php getImg(get_url(), $rowBlockWeb['id'], 0, get_taal(), '', '200','100','500', 1); ?>
                                            <div class="absolute left-0 bottom-0 px-4 py-2 bg-white rounded-tr-md">
                                                <?=$InBlock;?>: <strong><?=$rowBlockTekst['block_naam'];?></strong>
                                            </div>
                                        </div>
                                        <div class="zoekitem-tekst">
                                            <div class='titel-en-tekst'>
                                                <div class="zoekitem_titel"><b><? echo ($rowBlockWeb['item2'] <> "") ? $rowBlockWeb['item2'] : $rowBlockWeb['item1']; ?></b></div>
                                                <?php 
                                                    $stripzoek2 = strip_tags($rowBlockWeb['tekst']);
                                                    if(!empty($stripzoek2)): 
                                                        echo substr(htmlentities(html_entity_decode($stripzoek2)), 0, 75) . '...'; 
                                                    endif;
                                                ?>
                                            </div>
                                            <?php if($rowBlockWeb['keuze1'] == 'nieuws' || $rowBlockWeb['keuze1'] == 'team' || $rowBlockWeb['keuze1'] == 'agenda'){?>
                                                <a class='btn' href="<?php echo get_url();?>/<?=$zoekBlockVertaalUrl;?><?=$rowBlockWeb['keuze1']?>/<?= $rowBlockWeb['paginaurl'];?>/#block-<?=$rowBlocks['id'];?>"><?=$leesmeer;?></a>
                                            <?}else{?>
                                                <a class='btn' href="<?php echo get_url();?>/<?=$zoekBlockVertaalUrl;?><?= $rowBlockWeb['paginaurl'];?>/#block-<?=$rowBlocks['id'];?>"><?=$leesmeer;?></a>
                                            <?}?>
                                        
                                        </div>
                                    </div>
                                <?php
                                }
                            }
                        ?>
                    </div>
                <?php } else {
                    echo $geenResultaten;
                } ?>

            </div>

        </div>
	</div>
	<?php 
    include('php/blocks/blocks.php');  
    ?>
</section>