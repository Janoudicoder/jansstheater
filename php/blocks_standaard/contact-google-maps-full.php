<?php
        $mapsAdres = the_field('titel', 'block', $blockId);
?>
<div id="block-<?=$blockId;?>" class="blocken GoogleMaps my-16">
        <iframe title="Google maps kaart voor de locatie: <?=$mapsAdres;?>" class="lazy" loading="lazy" width="100%" height="500" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=100%25&amp;height=450&amp;hl=nl&amp;q=<?=$mapsAdres;?>&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
</div>
