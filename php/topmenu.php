<div id="topmenu" class="flex justify-end">
    <ul class="nav-menu">
       <?php 
            $sqlTopmenu = use_query_loop('id', 'siteworkcms', 'keuze1 = "topmenu" AND status = "Actief"', 'volgorde ASC');
            foreach( $sqlTopmenu as $topmenu ):
                $topmenuId = $topmenu['id'];
                $topmenuTitle = the_field('item2', $topmenuId);
                $topmenuUrl = the_field('paginaurl', $topmenuId);
                $siteUrl = get_url();
                $currentId = get_id();
                if($currentId == $topmenuId):
                    $activeClass = "active";
                else:
                    $activeClass = "";
                endif;

                echo <<<HTML
                    <li class="{$activeClass}"><a href="{$siteUrl}/{$topmenuUrl}/">{$topmenuTitle}</a></li>
                HTML;
            endforeach;
        ?> 
    </ul>
    <div class="top-cta flex flex-col items-end mr-[30px] hidden-1117">
    <div id="info" class="infotopmenu flex flex-col w-[254px] h-[82px] bg-primary text-white p-4 relative items-end slanted-edge">
    <a href="tel:<?=$site_telnr;?>"><?=$site_telnr;?></a>
    <a href="mailto:<?=$site_email;?>"><?=$site_email;?></a>
</div>


     <div id="socialmediatopmenu" class="flex bg-secondary p-4 slanted-edge-socialmedia " style="width: 167px; justify-content: end;">
         <a href="https://www.facebook.com" target="_blank">
         <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 33px">
            <path d="M5.42857 0C2.43438 0 0 2.43438 0 5.42857V32.5714C0 35.5656 2.43438 38 5.42857 38H13.758V25.633H9.27946V19H13.758V16.1415C13.758 8.75357 17.1 5.32679 24.3607 5.32679C25.7348 5.32679 28.1098 5.59821 29.0853 5.86964V11.875C28.5763 11.8241 27.6857 11.7902 26.5746 11.7902C23.0121 11.7902 21.6379 13.1388 21.6379 16.642V19H28.729L27.5076 25.633H21.6295V38H32.5714C35.5656 38 38 35.5656 38 32.5714V5.42857C38 2.43438 35.5656 0 32.5714 0H5.42857Z" fill="#D03184"/>
         </svg>
        </a>
        <a href="https://twitter.com" target="_blank">
          <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 33px">
             <path d="M5.42857 0C2.43438 0 0 2.43438 0 5.42857V32.5714C0 35.5656 2.43438 38 5.42857 38H32.5714C35.5656 38 38 35.5656 38 32.5714V5.42857C38 2.43438 35.5656 0 32.5714 0H5.42857ZM30.629 7.125L21.8246 17.1848L32.1812 30.875H24.0723L17.7277 22.571L10.4585 30.875H6.42946L15.8446 20.1112L5.91205 7.125H14.2246L19.967 14.7165L26.6 7.125H30.629ZM27.4228 28.4661L13.0116 9.4067H10.6112L25.1835 28.4661H27.4143H27.4228Z" fill="#D03184"/>
         </svg>
        </a>
</div>



    </div>
    <?php if(get_meertaligheid() == true): ?>
        <ul id="taalswitch" class="relative">
            <li class="taalkeuze">
                <?php
                    $huidigePagina = getTranslation('item2', 'veld', get_taal(), get_id());
                    $sqltaal = use_query_loop('*', 'sitework_taal', 'actief = "1"', 'volgorde ASC');
                    // $sqltaal = $mysqli->query("SELECT * FROM sitework_taal WHERE taalkort != '".get_taal()."' AND actief = '1' ORDER BY volgorde ASC") or die($mysqli->error . __LINE__);
                    echo '<a class="chevron" aria-label="'.$huidigePagina.'" href="#"><img src="' . get_url() . '/flags/' . get_taal() . '.svg" alt="" class="vlag"></a>';
                ?>
                <ul class="nav-dropdown nav-submenu">
                    <?php
                        foreach( $sqltaal as $taal ):
                            $paginaurlTaal = getTranslation('paginaurl', 'veld', $taal['taalkort'], get_id());
                            $paginaurlTaalHome = getTranslation('paginaurl', 'veld', $taal['taalkort'], 1);

                            $sqlvertalingPaginas = $mysqli->query("SELECT * FROM sitework_vertaling WHERE cms_id = '" . get_id() . "' AND taal = '".$taal['taalkort']."'") or die($mysqli->error . __LINE__);
                            $numberOfRows = $sqlvertalingPaginas->num_rows;

                            if($paginaurlTaal == "") {
                                $paginaurlTaal = getTranslation('paginaurl', 'veld', $taal['taalkort'], '1');
                            }

                            if($numberOfRows > 0) {
                                echo '<li><a href="' . get_url() . '/' . $taal['taalkort'] . '/' . $paginaurlTaal . '/" aria-label="'.$taal['taallang'].'" class="vlaglink"><img src="' . get_url() . '/flags/' . $taal['taalkort'] . '.svg" alt="" class="vlag"/><span>'.$taal['taalkort'].'</span></a></li>';
                            } else {
                                if(get_taal() == 'nl') {
                                    echo '<li><a href="' . get_url() . '/' . $taal['taalkort'] . '/' . $paginaurlTaalHome . '/" aria-label="'.$taal['taallang'].'" class="vlaglink"><img src="' . get_url() . '/flags/' . $taal['taalkort'] . '.svg" alt="" class="vlag"/><span>'.$taal['taalkort'].'</span></a></li>';
                                } else {
                                    echo '<li><a href="' . get_url() . '/' . $taal['taalkort'] . '/' . $paginaurlTaal . '/" aria-label="'.$taal['taallang'].'" class="vlaglink"><img src="' . get_url() . '/flags/' . $taal['taalkort'] . '.svg" alt="" class="vlag"/><span>'.$taal['taalkort'].'</span></a></li>';
                                }
                            }
                        endforeach;
                    ?>
                </ul>
            </li>
        </ul>
    <?php endif; ?>
</div>