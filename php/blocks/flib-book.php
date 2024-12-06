<?php
$textGallery = the_field('tekst', 'block', $blockId);
$pdfurl = 'https://jansstheater.sitework.link/doc/26752-13489-Flyer-voiceover-Elly-v1.pdf';

?>
<div id="block-<?=$blockId;?>" class="blocken container mx-auto galerij my-16">
    <div id="real3d-flipbook-container" class="pdf-flipbook relative galerij-blok"></div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const options = {
            pdfUrl: "<?= $pdfurl; ?>",
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
<div class="container mx-auto galerij-nav mb-32">
    <?php if ($textGallery): ?>
        <div class="mt-8"><?= $textGallery; ?></div>
    <?php endif; ?>
</div>
<style>
#real3d-flipbook-container {
    width: 100%;
    height: 50vh;
    background: transparent;
}
</style>
