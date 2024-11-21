<? // checken of men wel is ingelogd
// =================================
login_check_v2(); ?>

<?php
if(isset($_POST['noMenuToevoegen']) == 1 && isset($_POST['opslaan1'])) {
	// Loop through the 'notmenu' array and update the database
	for ($i = 0; $i < count($_POST['notmenu']); $i++) {
		$item = $_POST['notmenu'][$i];
	
		// Update query (replace with your specific query)
		$addToMenu = "UPDATE siteworkcms SET in_menu = '1' WHERE id = '" . $item . "'";
		$resultaddToMenu = $mysqli->query($addToMenu) or die($mysqli->error.__LINE__);
	}
}
if(isset($_GET['delid']) <> "") {
	$itemDelId = $_GET['delid'];
	// Update query (replace with your specific query)
	if(isset($_GET['sub']) == "ja") {
		$delSubFromMenu = "UPDATE siteworkcms SET in_menu = '0', menu_volgorde = null, hoofdid = '0' WHERE id = '" . $itemDelId . "'";
		$resultDelSubMenu = $mysqli->query($delSubFromMenu) or die($mysqli->error.__LINE__);
	} else {
		$delFromMenu = "UPDATE siteworkcms SET in_menu = '0', menu_volgorde = null WHERE id = '" . $itemDelId . "'";
		$resultDelMenu = $mysqli->query($delFromMenu) or die($mysqli->error.__LINE__);
	}	
	header('Location: ?page=menustructuur');
}
?>

<script>
	function escapeString(input) {
		return input.replace(/[\0\x08\x09\x1a\n\r"'\\\%]/g, function (char) {
			switch (char) {
				case "\0": return "\\0";
				case "\x08": return "\\b";
				case "\x09": return "\\t";
				case "\x1a": return "\\z";
				case "\n": return "\\n";
				case "\r": return "\\r";
				case "\"": case "'": case "\\": case "%":
					return "\\" + char; // prepends a backslash to specific characters
			}
		});
	}

	function toggleAddMenuButton() {
		var isAnyCheckboxChecked = $('.menu-check-box').is(":checked");
		console.log(isAnyCheckboxChecked);

		if (isAnyCheckboxChecked) {
			$('#add_menu_item').removeClass('disabled');
		} else {
			$('#add_menu_item').addClass('disabled');
		}
	}

	function zoekPaginas(searchStr) {
		if (searchStr.length === 0) {
			// Don't send any data
			$.ajax({
				url: "php/getMenustructuur.php",
				type: "GET",
				success: function(data) {
					$('#liveSearch').html(data);
				}
			});
		} else {
			// Send search term as usual
			$.ajax({
				url: "php/getMenustructuur.php",
				type: "GET",
				data: 'q='+escapeString(searchStr),
				success: function(data){
					$('#liveSearch').html(data);
				}
			});
		}
	}
</script>

<div class="box-container">
	<div class="box box-1-3 lg-box-full">
		<h3><span class="icon fas fa-plus-circle"></span> Voeg toe aan menu</h3>
		<div class="content-container">
			<div class="form-group">
				<label for="">Zoek uw pagina</label>
				<input type="text" class="menupagina_zoeken inputveld invoer" size="30" onkeyup="zoekPaginas(this.value)">
			</div>
			<form id="add_menu_form" action="<?php echo $PHP_SELF ?>" method="post">
				<div id="liveSearch" class="not-menu scroll-area">
					<?php
						foreach (getPagesNotMenu() as $notMenu) { 
							echo '<div class="menu-check">';
								echo '<input type="checkbox" id="'.$notMenu['keuze1'].'-'.$notMenu['id'].'" class="menu-check-box" name="notmenu[]" value="'.$notMenu['id'].'">';
								echo '<label for="'.$notMenu['keuze1'].'-'.$notMenu['id'].'">'.$notMenu['item2'].'</label>';
							echo '</div>';
						}
					?>
				</div>	
				<input type="hidden" name="noMenuToevoegen" value="1">
				<button id="add_menu_item" name="opslaan1" class="btn fl-left mt-20 nieuw disabled" type="submit">Voeg aan menu toe</button>
			</form>
				
		</div>
	</div>

	<div class="box box-2-3 lg-box-full">
		<h3><span class="icon fas fa-th-list"></span> Site menu</h3>
		<div id="melding-menu"></div>
		<div class="content-container">
			<div id="inMenu" class="in-menu">
				<ul id="menu">
					<?php
					foreach (getPagesMenu() as $inMenu) { ?>
						<li id="recordsArray_<?php echo $inMenu['id']; ?>" data-menu="menu">
							<div class="menu-item">
								<div class="hoofditemlabel">Menu item</div>
								<div class="hoofditemtitel" title="<?php echo ucfirst($inMenu['keuze1']); ?>">
									<?php echo $inMenu['item1']; ?>
									<a class="structure-edit" href="maincms.php?page=pagina_bewerken&id=<?php echo $inMenu['id'];?>"><span class="fas fa-edit"></span></a>
									<span class="structure-delete" title="verwijder menu item: <?=$inMenu['item1'];?>" onclick="return confirmDialog('Weet u zeker dat u het dit item uit het menu wilt verwijderen?', '<?php echo $inMenu['id'];?>')">
										<span class="fas fa-trash"></span>
									</span>
								</div>
							</div>
							<ul class="submenu" id="inMenuSub_<?php echo $inMenu['id']; ?>">
								<?php foreach (getSubPagesMenu($inMenu['id']) as $inMenuSub) { ?>
									<?php if($inMenu['id'] != $inMenuSub['id']): ?>
										<li id="recordsArraySub_<?php echo $inMenuSub['id']; ?>" data-menu="submenu">
											<div class="menu-item">
												<div class="hoofditemlabel">Submenu item</div>
												<div class="hoofditemtitel" title="<?php echo ucfirst($inMenuSub['keuze1']); ?>">
													<?php echo $inMenuSub['item1']; ?>
													<a class="structure-edit" href="maincms.php?page=pagina_bewerken&id=<?php echo $inMenuSub['id'];?>"><span class="fas fa-edit"></span></a>
													<span class="structure-delete" title="verwijder menu item: <?=$inMenuSub['item1'];?>" onclick="return confirmDialogSub('Weet u zeker dat u het dit item uit het menu wilt verwijderen?', '<?php echo $inMenuSub['id'];?>')">
														<span class="fas fa-trash"></span>
													</span>
												</div>
											</div>
										</li>
									<?php endif; ?>
								<?php } ?>
							</ul>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>

    <div class="box box-2-3 lg-box-full">
		<h3><span class="icon fas fa-sort-amount-up-alt"></span> Categorie structuur</h3>
		<? // zoekoptie om te filteren op categorie
		// ======================================== ?>
		<form method="post" action="?page=menustructuur#structuur" class="fl-right">
			<select class="inputveld mr-10" name="cat">
				<option value="">Selecteer categorie</option>
				<? //categorien ophalen
				foreach (getCategorie() as $categorien) { 
					echo '<option value="'.htmlspecialchars($categorien['categorie']).'" >'.htmlspecialchars($categorien['categorie']).'</option>';		
				} ?>
			</select>
			<button type="submit" class="btn search" name="Submit">Zoek</button>
		</form>
	
		<? // structeer ophalen en tonen in lijstweergave
		// =============================================== ?>
		<div id="structuur">
			<ul id="structuurCat">
			<?php if (!$_POST['cat']) { $cat = "pagina"; } else { $cat = $_POST['cat']; }
				
				// hoofdmenu ophalen
				// ====================
				$querydrag = $mysqli->query("	SELECT * FROM siteworkcms WHERE keuze1 LIKE '%". $cat."%' AND status <> 'prullenbak' ORDER BY volgorde,id ASC") or die($mysqli->error.__LINE__);
				while($rowdrag = $querydrag->fetch_assoc()){ ?>
					
					<li id="recordsArray_<?php echo $rowdrag['id']; ?>">
						<div class="hoofditemlabel"><?=$rowdrag['keuze1'];  ?></div>
						<div class="hoofditemtitel">
							<?php echo strip_tags($rowdrag['item1'],allowed_tags: ""); ?>
							<a class="structure-edit" href="maincms.php?page=pagina_bewerken&id=<?=$rowdrag['id']; ?>"><span class="fas fa-edit"></span></a>
						</div>
					</li>

				<? } ?>
			</ul>
		</div>

	</div>
</div>
	
<script>
$('#add_menu_form').on('submit', function(event){ $('#add_menu_item').addClass('disabled') });
$('.menupagina_zoeken').on('keyup', function(event){ $('#add_menu_item').addClass('disabled') });
$("#liveSearch").on('change', '.menu-check-box', toggleAddMenuButton);

function confirmDialog(message, id) {
    if (confirm(message)) {
        window.location.href = 'maincms.php?page=menustructuur&delid=' + id;
    }
    return false;
}
function confirmDialogSub(message, id) {
    if (confirm(message)) {
        window.location.href = 'maincms.php?page=menustructuur&sub=ja&delid=' + id;
    }
    return false;
}
$( function() {
	$(document).tooltip();
} );
</script>