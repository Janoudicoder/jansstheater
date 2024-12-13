<?php
// Check of er documenten zijn geupload
// ==========================================

// Check of er documenten zijn geupload
$sqldoc = $mysqli->prepare("
    SELECT url, naam, block_id 
    FROM sitework_doc 
    WHERE cms_id = ? AND doc_taal = ? AND block_id = 0
    ORDER BY volgorde, id DESC
") or die($mysqli->error.__LINE__);
$sqldoc->bind_param('is', get_id(), get_taal());
$sqldoc->execute();
$sqldoc->store_result();
$sqldoc->bind_result($urlDoc, $naamDoc, $blockId);

if ($sqldoc->num_rows > 0) { ?>
    <ul class="documenten mt-16">
        <?php while ($sqldoc->fetch()) { 
            $sqlDocMedia = $mysqli->query("
                SELECT naam, ext, bijschrift 
                FROM sitework_mediabibliotheek 
                WHERE id = '$urlDoc'
            ") or die($mysqli->error.__LINE__);
            $rowDocMedia = $sqlDocMedia->fetch_assoc();
            ?>
            <li>
                <a href="<?php echo get_url(); ?>/doc/<?php echo $rowDocMedia['naam']; ?>.<?php echo $rowDocMedia['ext']; ?>" 
                   title="<?php echo $rowDocMedia['bijschrift']; ?>" 
                   target="_blank" rel="noopener noreferrer">
                    <?php echo $naamDoc; ?>
                </a>
            </li>
        <?php } ?>
    </ul>
<?php } ?>
