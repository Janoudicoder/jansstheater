<section id="breadcrumbs" class="py-4">
    <div class="container mx-auto">
        <ul itemscope itemtype="https://schema.org/BreadcrumbList">
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a itemprop="item" href="
                <?php echo home_url(); ?>">        
                    <span itemprop="name"><?php echo the_field('item1', 1); ?></span>
                    <meta itemprop="position" content="1" />
                </a>
            </li>
        <?php  
            $nummerLaatste = '2';

            if ( the_field('hoofdid') ) // als het een subpagina is
            {
                //tellen hoeveelste item het is voor itemprop position
                $nummer = '2';
                $nummerLaatste = '3';

                //haal hoofd pagina op
                $sqlParrent = "SELECT id, item1, externeurl, paginaurl, hoofdid FROM siteworkcms WHERE id = '".the_field('hoofdid')."' and status = 'actief'"; 
                $resultParrent = $mysqli->query($sqlParrent) or die($mysqli->error.__LINE__);
                $rowParrent = $resultParrent->fetch_assoc();
  
                // eerst checken of er een hoofdpagina is 
                if ( the_field('hoofdid', $rowParrent['id']) )
                {
                    //tellen hoeveelste item het is voor itemprop position
                    $nummer = '3';
                    $nummerLaatste = '4';

                     //haal hoofd pagina op van de hoofdpagina
                    $sqlParrentHoofd = "SELECT id, item1, externeurl, paginaurl, hoofdid FROM siteworkcms WHERE id = '".the_field('hoofdid', $rowParrent['id'])."' and status = 'actief'"; 
                    $resultParrentHoofd = $mysqli->query($sqlParrentHoofd) or die($mysqli->error.__LINE__);
                    $rowParrentHoofd = $resultParrentHoofd->fetch_assoc();
                    // als externe url is ingevulf gebruik dan deze
                    if ( the_field('externeurl', $rowParrentHoofd['id']) )
                    {
                        $linkHoofd = the_field('externeurl', $rowParrentHoofd['id']);
                    }
                    else {
                        $linkHoofd = get_link($rowParrent['id']);
                    }
                    echo '
                        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <a itemprop="item" href="'.$linkHoofd.'">
                                <span itemprop="name">'.the_field('item1', $rowParrentHoofd['id']).'</span>
                                <meta itemprop="position" content="2" />
                            </a>
                        </li>';
                }

                if($rowParrent['id'] != '1'):
                    $titel = the_field('item1', $rowParrent['id']);
                    
                    if ( the_field('externeurl', $rowParrent['id']) )
                    {
                        $link = the_field('externeurl', $rowParrent['id']);
                    }
                    else 
                    {
                        $link = get_link($rowParrent['id']);
                    }
                    echo '
                        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <a itemprop="item" href="'.$link.'">
                                <span itemprop="name">'.$titel.'</span>
                                <meta itemprop="position" content="'.$nummer.'" />
                            </a>
                        </li>';
                endif;
            }
            if ( !the_field('hoofdid') && $_GET['page'] == 'product' ) {// product pagina
                $nummerLaatste = '3';
                echo '
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="javascript:history.back()">
                        <span itemprop="name">Producten</span>
                        <meta itemprop="position" content="2" />
                    </a>
                </li>';
            }
            if (!the_field('hoofdid') && $_GET['page'] == 'verhuren') {// product pagina
                $nummerLaatste = '3';
                echo '
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="javascript:history.back()">
                        <span itemprop="name">Verhuur</span>
                        <meta itemprop="position" content="2" />
                    </a>
                </li>';
            }

            if ( !the_field('hoofdid') && $_GET['page'] == 'nieuwsbericht' ) {
                $nummerLaatste = '3';
                echo '
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="'.get_url().''.(get_meertaligheid() == true ? '/'.get_taal() : '').'/nieuws/">
                        <span itemprop="name">Nieuws</span>
                        <meta itemprop="position" content="2" />
                    </a>
                </li>';
            }

            if ( !the_field('hoofdid') && $_GET['page'] == 'vacature' ) {
                $nummerLaatste = '3';
                echo '
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="'.get_url().''.(get_meertaligheid() == true ? '/'.get_taal() : '').'/vacatures/">
                        <span itemprop="name">Vacatures</span>
                        <meta itemprop="position" content="2" />
                    </a>
                </li>';
            }

            if ( !the_field('hoofdid') && $_GET['page'] == 'woning' ) {
                $nummerLaatste = '3';
                echo '
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="'.get_url().''.(get_meertaligheid() == true ? '/'.get_taal() : '').'/wonen/">
                        <span itemprop="name">Wonen</span>
                        <meta itemprop="position" content="2" />
                    </a>
                </li>';
            }
         
            
            // // ALS HET EEN AANBOD PAGINA IS LAAT DAN HOOFDPAGINA PORTEFUILE ZIEN
            // if ( $row['keuze1'] == 'project' ) {
            //     echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="https://julia.eu/nl/cases/">Cases</a></li>';
            // }
        ?>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name">
                <?php 
                    $beadItem2 = the_field('item2');
                    $beadItem1 = the_field('item1');
                    $beadKeuze1 = the_field('keuze1');

                    if ( $beadKeuze1 == 'project') // project detail pagina
                    {
                        echo $beadItem2;
                    }
                    else {
                        if ( $beadItem1 ) 
                        {
                            echo $beadItem1; 
                        } 
                        else 
                        { 
                            if($_GET['page'] == 'woning') {
                                $woningUrlDelen = explode('-', $_GET['title']);
                                $lastIndex = count($woningUrlDelen) - 1;

                                foreach ($woningUrlDelen as $index => $slug) {
                                    if ($index != $lastIndex) {  // Skip the last element
                                        echo ($index == 0 ? ucfirst($slug) . ', ' : ucfirst($slug) . ' ');
                                    }
                                }

                            } else {
                                echo $_GET['title']; 
                            }
                        } 
                    }
                ?>
                </span>
                <meta itemprop="position" content="<?php echo $nummerLaatste; ?>" />
            </li>
        </ul>
    </div>
</section>
