<? 
    //Afbeeldingen zijkant ophalen
    $sqlimg = $mysqli-> prepare ("SELECT naam FROM sitework_img WHERE cms_id = ?  and afbsoort = 'zijkant' order by volgorde ASC LIMIT 0,5") or die($mysqli->error.__LINE__);
    $sqlimg -> bind_param('i', get_id());
    $sqlimg -> execute();
    $sqlimg -> store_result();
	
	if ($sqlimg->num_rows > 0) { // check of er afbeeldingen zijn geupload
?>

	<div id="afbeeldingen-zijkant" class="grid grid-cols-2 gap-4">
		<?php getImg($url, get_id(), '', $post_taal, 'zijkant', '600','400','300', ''); ?>
	</div>
	
<? } ?>