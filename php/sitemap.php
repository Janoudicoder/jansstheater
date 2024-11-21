<ul id="sitemap">      
    <?php // eerst de hoofdpagina`s ophalen
    // ====================================
    $sitehoofd = $mysqli->query("SELECT * FROM siteworkcms WHERE keuze1 = 'pagina' or keuze1 = 'nieuws' or keuze1 = 'overige' $taalquery AND status = 'actief' ORDER BY item1 ASC") or die($mysqli->error.__LINE__);
    while($rowsitehoofd = $sitehoofd->fetch_assoc()) { 
        $sitehoofdTitel = the_field('item1', $rowsitehoofd['id']);
        $sitehoofdpagUrl = the_field('paginaurl', $rowsitehoofd['id']);
        $sitehoofdExt = the_field('externeurl', $rowsitehoofd['id']);
        $sitehoofdTarget = the_field('targetlink', $rowsitehoofd['id']);
        ?>
     
        <li>
            <a href="<?php if ($sitehoofdext) { echo $sitehoofdext; } else echo get_link($rowsitehoofd['id']); ?>" 
            <?php if ($sitehoofdext <> "" && $sitehoofdTarget == "ja") { echo 'target="_blank" rel="noopener" rel="noreferrer"'; } ?>><?php echo $sitehoofdTitel ?></a>
            
            <ul>
                <?php // koppelingen en subpagina`s ophalen
                // ========================================
                $sitesub = $mysqli->query("SELECT * FROM siteworkcms WHERE hoofdid = '".$rowsitehoofd['id']."' AND status = 'actief' ORDER BY volgorde ASC") or die($mysqli->error.__LINE__);
                while($rowsitesub= $sitesub->fetch_assoc()) { 
                    $subTitel = the_field('item1', $rowsitesub['id'])
                    ?>

                    <li>
                        <a href="<?php echo get_link($rowsitesub['id']);?>"><?php echo $subTitel; ?></a>                                                
                    </li>

                <? } ?>
            </ul>
        </li>
    <? } ?>
</ul>
