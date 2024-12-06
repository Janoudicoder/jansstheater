<script>
    function adjustIframe(obj) {
        function resize() {
            obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
        }
        resize(); // Adjust height initially
        setTimeout(resize, 1500); // Recheck height after 1 second
    }
</script>

<?php
$blokkenTeller = 1;

if($_GET['taal'] == 'nl' OR !isset($_GET['taal'])) {
    //alle gekoppelde blocken ophalen
    $blocks = $mysqli->query("SELECT * FROM sitework_blocks WHERE cms_id = '" . $_GET['id'] . "' ORDER BY volgorde ASC") or die($mysqli->error . __LINE__);
    while ($rowBlocks = $blocks->fetch_assoc()) {
        $block = $mysqli->query("SELECT * FROM sitework_block WHERE actief = '1' AND id = '" . $rowBlocks['block_id'] . "'") or die($mysqli->error . __LINE__);
        $rowBlock = $block->fetch_assoc();
        $rowblokId = $rowBlocks['block_id'];
        $blocksid = $rowBlocks['id'];
        $_GET['block_id'] = $rowBlocks['id'];

        //haal per block de instellingen van het block op
        $sqlBlock = $mysqli->prepare("SELECT id, block_naam, bestandsnaam FROM sitework_block WHERE actief = '1' AND id = ?") or die ($mysqli->error . __LINE__);
        $sqlBlock->bind_param('i', $rowblokId);
        $sqlBlock->execute();
        $sqlBlock->store_result();
        $sqlBlock->bind_result($idBlock, $blockNaam, $blockBestand);
        $sqlBlock->fetch();

        //deze twee blokken gebruiken dezelfde block opmaak "tekst-foto". Dus we passen de $blockBestand aan naar 'tekst-foto' ipv de bestandsnaam in de db
        if ($blockNaam == "Tekst - Afbeelding") {
            $blockBestand = "tekst-foto";
        }

        //array vullen met blocks waar een afbeelding upload moet komen (vullen met de waarde van $blockBestand)
        //Via de array gaan we bepalen of een block een afbeelding upload knop moet krijgen of niet
        $afbeelingUpload = array(
            'tekst-foto',
            'galerij',
            'logo-slider',
            'galerij-grid',
            'tekst-galerij'
        );

        ?>

        <script type="text/javascript">
            function ConfirmDelete() {
                return confirm('Weet u zeker dat u dit block wilt verwijderen?');
            }
        </script>
        <?php
        if ($_GET['delid'] <> "") {
            // gebruiker verwijderen
            // =====================
            $mysqli->query("DELETE FROM sitework_blocks WHERE id = '" . $_GET['delid'] . "' ") or die($mysqli->error . __LINE__);
            header('Location: ?page=pagina_bewerken&id=' . $_GET['id'] . '&opgeslagen=ja');
        } ?>

        <?php if($idBlock != ""): ?>
            <div class="pagina-block">
                <div class="block-nr-inner">
                    <span class="block-nr"><?=$blokkenTeller;?></span>
                </div>
                <div class="block-inner">
                    <h3 id="block-cms-<?=$blocksid;?>"><span class="icon fad fa-cube"></span><?= $blockNaam; ?>
                        <!--blok verwijderen knop-->
                        <a href="?page=pagina_bewerken&id=<?=$_GET['id']; ?>&delid=<?= $blocksid; ?>" onclick='return ConfirmDelete();'
                        class="btn delete-btn float-right ml-10 mb-10">Verwijder blok</a>
                        <!--optioneel per blok: afbeelding upload-->
                        <?php if (in_array($blockBestand, $afbeelingUpload)) { ?>
                            <a class="btn float-right mb-10 image" data-fancybox data-small-btn="true" data-type="iframe"
                            href="php/media_upload.php?id=<?= $_GET['id']; ?>&block_id=<?= $blocksid; ?>&taal=<?=$_GET['taal'];?>&media=afbeelding&upload_from=block"
                            href="javascript:;">Afbeeldingen</a>
                        <?php } ?>
                    </h3>
                    <!--Iframe inladen-->
                    <iframe src="php/blocks/iframe-<?= $blockBestand; ?>.php?blockid=<?= $blocksid; ?>&taal=<?=$_GET['taal'];?>&cmsid=<?=$_GET['id'];?>" frameborder="0"
                            class="block-iframe" onload="adjustIframe(this)"></iframe>
                </div>
            </div>
        <?php $blokkenTeller += 1;
        endif;
        $idBlock = "";
        $blockNaam = "";
        $blockBestand = "";
    }
} else {
    //alle gekoppelde blocken ophalen
    $blocks = $mysqli->query("SELECT * FROM sitework_vertaling_blocks WHERE cms_id = '" . $_GET['id'] . "' AND hoofdid = '0' AND taal = '".$_GET['taal']."' ORDER BY volgorde ASC") or die($mysqli->error . __LINE__);
    while ($rowBlocks = $blocks->fetch_assoc()) {
        $rowblokId = $rowBlocks['block_id'];
        $blocksid = $rowBlocks['id'];
        $_GET['block_id'] = $rowBlocks['id'];

        //haal per block de instellingen van het block op
        $sqlBlock = $mysqli->prepare("SELECT id, block_naam, bestandsnaam FROM sitework_block WHERE actief = '1' AND id = ?") or die ($mysqli->error . __LINE__);
        $sqlBlock->bind_param('i', $rowblokId);
        $sqlBlock->execute();
        $sqlBlock->store_result();
        $sqlBlock->bind_result($idBlock, $blockNaam, $blockBestand);
        $sqlBlock->fetch();

        //deze twee blokken gebruiken dezelfde block opmaak "tekst-foto". Dus we passen de $blockBestand aan naar 'tekst-foto' ipv de bestandsnaam in de db
        if ($blockNaam == "Tekst - Afbeelding") {
            $blockBestand = "tekst-foto";
        }

        //array vullen met blocks waar een afbeelding upload moet komen (vullen met de waarde van $blockBestand)
        //Via de array gaan we bepalen of een block een afbeelding upload knop moet krijgen of niet
        $afbeelingUpload = array(
            'tekst-foto',
            'galerij',
            'logo-slider',
            'galerij-grid',
            'tekst-galerij'
        );

        ?>

        <script type="text/javascript">
            function ConfirmDelete() {
                return confirm('Weet u zeker dat u dit block wilt verwijderen?');
            }
        </script>
        <?php
        if ($_GET['delid'] <> "" && $_GET['taal'] != 'nl') {
            // block verwijderen
            // =====================
            $mysqli->query("DELETE FROM sitework_vertaling_blocks WHERE id = '" . $_GET['delid'] . "' ") or die($mysqli->error . __LINE__);
            header('Location: ?page=pagina_bewerken&id=' . $_GET['id'] . '&taal='.$_GET['taal'].'&opgeslagen=ja');
        } ?>

        <?php if($idBlock != ""): ?>
            <div class="pagina-block">
                <div class="block-nr-inner">
                    <span class="block-nr"><?=$blokkenTeller;?></span>
                </div>
                <div class="block-inner">
                    <h3 id="block-cms-<?=$blocksid;?>"><span class="icon fad fa-cube"></span><?= $blockNaam; ?>
                        <!--blok verwijderen knop-->
                        <a href="?page=pagina_bewerken&id=<?=$_GET['id']; ?>&delid=<?= $blocksid; ?>&taal=<?=$_GET['taal'];?>" onclick='return ConfirmDelete();'
                        class="btn delete-btn float-right ml-10 mb-10">Verwijder blok</a>
                        <!--optioneel per blok: afbeelding upload-->
                        <?php if (in_array($blockBestand, $afbeelingUpload)) { ?>
                            <a class="btn float-right mb-10 image" data-fancybox data-small-btn="true" data-type="iframe"
                            href="php/media_upload.php?id=<?= $_GET['id']; ?>&block_id=<?= $blocksid; ?>&taal=<?=$_GET['taal'];?>&media=afbeelding&upload_from=block"
                            href="javascript:;">Afbeeldingen</a>
                        <?php } ?>
                    </h3>
                    <!--Iframe inladen-->
                    <iframe src="php/blocks/iframe-<?= $blockBestand; ?>.php?blockid=<?= $blocksid; ?>&taal=<?=$_GET['taal'];?>&cmsid=<?=$_GET['id'];?>" frameborder="0"
                            class="block-iframe" onload="adjustIframe(this)"></iframe>
                </div>
            </div>
        <?php $blokkenTeller += 1;
        endif;
        $idBlock = "";
        $blockNaam = "";
        $blockBestand = "";
    }
}
?>