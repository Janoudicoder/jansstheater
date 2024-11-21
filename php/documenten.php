<?php // check of er documenten zijn geupload
// ==========================================

//check of er documenten zijn geupload
$sqldoc = $mysqli-> prepare ("SELECT url, naam FROM sitework_doc WHERE cms_id = ? AND doc_taal = ? ORDER BY volgorde,id DESC") or die($mysqli->error.__LINE__);
$sqldoc -> bind_param('is', get_id(), get_taal());
$sqldoc -> execute();
$sqldoc -> store_result();
$sqldoc -> bind_result($urlDoc, $naamDoc);

if ($sqldoc->num_rows > 0) { ?>
	<ul class="documenten mt-16">
		<?php while ($sqldoc -> fetch()) { 
				$sqlDocMedia = $mysqli->query("SELECT naam,ext,bijschrift FROM sitework_mediabibliotheek WHERE id = '$urlDoc'") or die($mysqli->error.__LINE__);
				$rowDocMedia = $sqlDocMedia->fetch_assoc();
			?>
			<li><a href="<? echo get_url();?>/doc/<? echo $rowDocMedia['naam'];?>.<? echo $rowDocMedia['ext'];?>" title="<? echo $rowDocMedia['bijschrift'];?>" target="_blank" rel="noopener" rel="noreferrer"><? echo $naamDoc;?></a></li>
		<? } ?>
	</ul>
<? } ?>


