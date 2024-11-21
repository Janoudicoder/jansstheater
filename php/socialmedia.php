<?php 
	$sqlsoc = use_query_loop('*', 'sitework_socialmedia', '', '', 20);
?>
<div id="socialmedia">
	<?php 
		foreach ($sqlsoc as $social):
			echo '<a href="'.$social['url'].'" aria-label="'.$social['naam'].'" target="_blank" rel="noopener" rel="noreferrer"><i class="fab '.$social['icon'].'"></i></a>';
		endforeach; 
	?>	
</div>