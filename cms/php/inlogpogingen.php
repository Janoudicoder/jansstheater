<? // checken of men wel is ingelogd
// =================================
login_check_v2(); ?>

<div class="box-container">
	<div class="box box-2-3 lg-box-full">
	<h3><span class="icon fas fa-lock"></span>Inlogpogingen</h3>
		<div class="content-container mt-0">    	
			<div class="row inlogpogingen type">
				<div class="col">gebruikersnaam</div>
				<div class="col sm-ipad-hide">ip-adres</div>
				<div class="col">datum & tijd</div>
			</div>
				
				<?php
				// inlogpogingen ophalen
				// =====================
				$sql = $mysqli->query("SELECT *,DATE_FORMAT(datum, '%d-%m-%Y om %H:%i uur') AS datum2 FROM sitework_login_log ORDER BY datum DESC LIMIT 50") or die($mysqli->error.__LINE__);			
				$rows = $sql->num_rows;
				while ($row = $sql->fetch_assoc()){  
					
					// gebruiker erbij ophalen
					// =======================
					$resultusers = $mysqli->query("SELECT * FROM siteworkcms_gebruikers WHERE id = '".$row['user_id']."' ") or die($mysqli->error.__LINE__);
					$rowusers = $resultusers->fetch_assoc();?>
				
					<div class="row inlogpogingen">
						<div class="col"><? echo $rowusers['username']; ?></div>
						<div class="col sm-ipad-hide"><? echo substr($row['ip'],0,100); ?></div>
						<div class="col"><? echo $row['datum2']; ?></div>
					</div>

			<? } ?>
		</div>
	</div>
</div>

