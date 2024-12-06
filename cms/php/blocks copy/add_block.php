<script>
    function adjustIframe(obj) {
        function resize() {
            obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
        }
        resize(); // Adjust height initially
        setTimeout(resize, 1500); // Recheck height after 1 second
    }
</script>

<?php // database connectie en inlogfuncties
// ===================================
include("../../login/config.php");

$blokkenTeller = 1;

if($_POST['paginaTaal'] == 'nl') {
    $query = $mysqli -> prepare("INSERT INTO sitework_blocks (cms_id, block_id) VALUES (?, ?)") or die ($mysqli->error.__LINE__);
    $query -> bind_param('ii',$_POST['add_id'],$_POST['blockId']);
    $query->store_result();
    $query->execute();
} else {
    $query = $mysqli -> prepare("INSERT INTO sitework_vertaling_blocks (cms_id, block_id, taal) VALUES (?, ?, ?)") or die ($mysqli->error.__LINE__);
    $query -> bind_param('iis',$_POST['add_id'],$_POST['blockId'],$_POST['paginaTaal']);
    $query->store_result();
    $query->execute();  
}

if($_POST['paginaTaal'] == 'nl') {
    $blocks = $mysqli->query("SELECT * FROM sitework_blocks WHERE cms_id = '" . $_POST['add_id'] . "' ORDER BY volgorde ASC") or die($mysqli->error . __LINE__);
    while ($rowBlocks = $blocks->fetch_assoc()) {
        $block = $mysqli->query("SELECT * FROM sitework_block WHERE id = '" . $rowBlocks['block_id'] . "'") or die($mysqli->error . __LINE__);
        $rowBlock = $block->fetch_assoc();
        $rowblokId = $rowBlocks['block_id'];
        $blocksid = $rowBlocks['id'];
        $_GET['block_id'] = $rowBlocks['id'];

        //haal per block de instellingen van het block op
        $sqlBlock = $mysqli->prepare("SELECT id, block_naam, bestandsnaam FROM sitework_block WHERE id = ?") or die ($mysqli->error . __LINE__);
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
        if($_GET['delid'] <> "") {
            // gebruiker verwijderen
            // =====================
            $mysqli->query("DELETE FROM sitework_blocks WHERE id = '" . $_GET['delid'] . "' ") or die($mysqli->error . __LINE__);
            header('Location: ?page=pagina_bewerken&id=' . $_GET['id'] . '&opgeslagen=ja');
        } ?>

        <div class="pagina-block">
            <div class="block-nr-inner">
                <span class="block-nr"><?=$blokkenTeller;?></span>
            </div>
            <div class="block-inner">
                <h3 id="block-cms-<?=$blocksid;?>"><span class="icon fad fa-cube"></span><?= $blockNaam; ?>
                    <!--blok verwijderen knop-->
                    <a href="?page=pagina_bewerken&id=<?= $_POST['add_id']; ?>&delid=<?= $blocksid; ?>" onclick='return ConfirmDelete();'
                    class="btn delete-btn float-right ml-10 mb-10">Verwijder blok</a>
                    <!--optioneel per blok: afbeelding upload-->
                    <?php if (in_array($blockBestand, $afbeelingUpload)) { ?>
                        <a class="btn float-right mb-10 image" data-fancybox data-small-btn="true" data-type="iframe"
                        href="php/media_upload.php?id=<?= $_POST['add_id']; ?>&block_id=<?= $blocksid; ?>&taal=<?=$_POST['paginaTaal'];?>&media=afbeelding&upload_from=block"
                        href="javascript:;">Afbeeldingen</a>
                    <?php } ?>
                </h3>
                <!--Iframe inladen-->
                <iframe src="php/blocks/iframe-<?= $blockBestand; ?>.php?blockid=<?= $blocksid; ?>&taal=<?=$_POST['paginaTaal'];?>&cmsid=<?=$_POST['add_id'];?>" frameborder="0"
                        class="block-iframe" onload="adjustIframe(this)"></iframe>
                </div>
            </div>
        <?php $blokkenTeller += 1;
    }
} else {
    //alle gekoppelde blocken ophalen in een andere taal
    $blocks = $mysqli->query("SELECT * FROM sitework_vertaling_blocks WHERE cms_id = '" . $_POST['add_id'] . "' AND hoofdid = '0' AND taal = '".$_POST['paginaTaal']."' ORDER BY volgorde ASC") or die($mysqli->error . __LINE__);
    while ($rowBlocks = $blocks->fetch_assoc()) {
        $rowblokId = $rowBlocks['block_id'];
        $blocksid = $rowBlocks['id'];
        $_GET['block_id'] = $rowBlocks['id'];

        //haal per block de instellingen van het block op
        $sqlBlock = $mysqli->prepare("SELECT id, block_naam, bestandsnaam FROM sitework_block WHERE id = ?") or die ($mysqli->error . __LINE__);
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
            // gebruiker verwijderen
            // =====================
            $mysqli->query("DELETE FROM sitework_vertaling_blocks WHERE id = '" . $_GET['delid'] . "' ") or die($mysqli->error . __LINE__);
            header('Location: ?page=pagina_bewerken&id=' . $_POST['add_id'] . '&taal='.$_POST['paginaTaal'].'&opgeslagen=ja');
        } ?>
        
        <div class="pagina-block">
            <div class="block-nr-inner">
                <span class="block-nr"><?=$blokkenTeller;?></span>
            </div>
            <div class="block-inner"></div>
                <h3 id="block-cms-<?=$blocksid;?>"><span class="icon fad fa-cube"></span><?= $blockNaam; ?>
                    <!--blok verwijderen knop-->
                    <a href="?page=pagina_bewerken&id=<?=$_POST['add_id']; ?>&delid=<?= $blocksid; ?>&taal=<?=$_POST['paginaTaal'];?>" onclick='return ConfirmDelete();'
                    class="btn delete-btn float-right ml-10 mb-10">Verwijder blok</a>
                    <!--optioneel per blok: afbeelding upload-->
                    <?php if (in_array($blockBestand, $afbeelingUpload)) { ?>
                        <a class="btn float-right mb-10 image" data-fancybox data-small-btn="true" data-type="iframe"
                        href="php/media_upload.php?id=<?= $_POST['add_id']; ?>&block_id=<?= $blocksid; ?>&taal=<?=$_POST['paginaTaal'];?>&media=afbeelding&upload_from=block"
                        href="javascript:;">Afbeeldingen</a>
                    <?php } ?>
                </h3>
                <!--Iframe inladen-->
                <iframe src="php/blocks/iframe-<?= $blockBestand; ?>.php?blockid=<?= $blocksid; ?>&taal=<?=$_POST['paginaTaal'];?>&cmsid=<?= $_POST['add_id']; ?>" frameborder="0"
                    class="block-iframe" onload="adjustIframe(this)"></iframe>
            </div>
        </div>
        <?php $blokkenTeller += 1;
    }
}

?>                