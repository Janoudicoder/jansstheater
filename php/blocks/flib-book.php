<?php
$textGallery = the_field('tekst', 'block', $blockId);

// Check if there are uploaded documents with the same block_id
$sqldoc = $mysqli->prepare("
    SELECT url, naam, block_id 
    FROM sitework_doc 
    WHERE cms_id = ? AND doc_taal = ? AND block_id = ? 
    ORDER BY volgorde, id DESC
") or die("Error preparing query: " . $mysqli->error);

$sqldoc->bind_param('isi', get_id(), get_taal(), $blockId); // Bind block_id to the query
$sqldoc->execute();
$sqldoc->store_result();
$sqldoc->bind_result($urlDoc, $naamDoc, $blockId);

if ($sqldoc->num_rows > 0): ?>
    <ul class="documenten mt-16">
        <?php while ($sqldoc->fetch()):
            $sqlDocMedia = $mysqli->prepare("
                SELECT naam, ext, bijschrift 
                FROM sitework_mediabibliotheek 
                WHERE id = ?
            ") or die("Error preparing query: " . $mysqli->error);
            $sqlDocMedia->bind_param('s', $urlDoc);
            $sqlDocMedia->execute();
            $sqlDocMedia->store_result();
            $sqlDocMedia->bind_result($mediaName, $mediaExt, $mediaCaption);
            $sqlDocMedia->fetch();
            ?>
            <li id="block-<?=$blockId;?>" class="blocken container mx-auto galerij my-16">
                <div id="real3d-flipbook-container" class="pdf-flipbook relative galerij-blok"></div>
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const options = {
                            pdfUrl: "<?php echo get_url(); ?>/doc/<?php echo $mediaName . '.' . $mediaExt; ?>",
                            pdfScale: 1.5,
                            lightBox: false,
                            webgl: true,
                            backgroundColor: "transparent",
                            controls: ["pageNavigation", "zoom", "fullscreen"],
                        };

                        const container = document.getElementById('real3d-flipbook-container');
                        try {
                            if (container) {
                                new FlipBook(container, options);
                            } else {
                                console.error("Flipbook container not found.");
                            }
                        } catch (error) {
                            console.error("An error occurred while initializing the flipbook:", error);
                        }
                    });
                </script>
            </li>
        <?php endwhile; ?>
    </ul>
<?php endif; ?>

<?php if ($textGallery): ?>
    <div class="container mx-auto  galerij-nav mb-32 mt-8 px-4 sm:px-6 md:px-8 text-center sm:text-left">
    <?= $textGallery; ?>w
</div>
<?php endif; ?>

<style>
#real3d-flipbook-container {
    width: 100%;
    height: 50vh;
    background: transparent;
}
</style>
