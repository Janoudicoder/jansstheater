<section id="content-vervolg-main">
	<div class="container mx-auto grid grid-cols-1 md:grid-cols-5 gap-x-32 pt-6">

		<article class="col-span-3 content pb-8 pl-12 text-white">
			<?php if(the_field('verberg_pagina_titel', 'cf') <> 'ja'): ?>
				<h1><? echo get_title(); ?></h1>
			<?php endif; ?>

			<p><?php echo str_replace("[leesmeer]", "", get_content()); ?></p>
			<?php include ('php/documenten.php'); ?>
			<?php
				if (the_field('formulieren')) {
					include('php/formulieren.php');
				}
				
				if(strpos(get_kenmerk(), 'zoekbalk') !== false){
					include('zoeken.php'); 
				}   
			?>
		</article>
		<aside class="col-span-3 md:col-span-2">
			<?php include ('php/afbeeldingen-zijkant.php'); ?>
		</aside>
	</div>

	<?php 
		if(strpos(get_kenmerk(), 'nieuwsoverzicht') !== false) {
			include('nieuws.php'); 
		}
		
		include ('php/afbeeldingen-galerij.php');
 
		if(strpos(get_kenmerk(), 'woningoverzicht') !== false && get_setting('makelaar') == 'ja' && get_setting('realworkswonen') == 'ja') {
			include('php/realworks/realworks_wonen.php'); 
		}

		if(strpos(get_kenmerk(), 'bedrijfspandenoverzicht') !== false && get_setting('makelaar') == 'ja' && get_setting('realworksbog') == 'ja' ) {
			include('php/realworks/realworks_bog.php'); 
		}

		if(strpos(get_kenmerk(), 'nieuwbouwoverzicht') !== false && get_setting('makelaar') == 'ja' && get_setting('realworksnieuwbouw') == 'ja') {
			include('php/realworks/realworks_nieuwbouw.php'); 
		}
	
    	include('php/blocks/blocks.php');  
		include ('php/categorieen-subs.php');
    ?>
</section>