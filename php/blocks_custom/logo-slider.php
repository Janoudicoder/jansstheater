<?php
// Titel
$logoTitel = the_field('titel', 'block', $blockId);
?>

<div id="block-<?=$blockId;?>" class="blocken logos py-8 bg-primary my-16">
    <div class="container mx-auto logo-slider">
		<?php
			// Logo loop 
			getImg(get_url(), get_id(), $blockId, get_taal(), 'block', '','','', 0); 
		?>
    </div>
</div>