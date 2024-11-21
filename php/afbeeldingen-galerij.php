<? 
//Afbeeldingen galerij ophalen
$sqlimgGalerij = $mysqli-> query ("SELECT naam FROM sitework_img WHERE cms_id = '".get_id()."'  and afbsoort = 'galerij' order by volgorde ASC") or die($mysqli->error.__LINE__);
if ($sqlimgGalerij->num_rows > 0) { // check of er afbeeldingen zijn geupload
?>
	<section id="galerij-main" class="py-16 px-0">
		<div class="container mx-auto relative grid autoGridGallerij" id="">
			<?php getImg($url, get_id(), '', $_GET['taal'], 'galerij', '500','400','500', ''); ?>
		</div>
	</section>
<? } ?>