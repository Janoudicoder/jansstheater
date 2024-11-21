<?
$logoTitel = the_field('titel', 'block', $blockId);
?>
<div id="block-<?=$blockId;?>" class="blocken logos py-8 bg-primary my-16">
	<div class="container mx-auto">
		<h2><?=$logoTitel;?></h2>
	</div>
    <div class="container mx-auto logo-slider">
		<?php getImg(get_url(), get_id(), $blockId, get_taal(), 'block', '200','150','300', 0); ?>
    </div>
</div>