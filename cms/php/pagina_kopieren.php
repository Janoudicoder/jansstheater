<?php ob_start();
// database connectie en inlogfuncties
// ======================================
include ("../login/config.php");
include ('../login/functions.php');
include ('./blocks/block_translate_functions.php');
session_start();

// checken of men wel is ingelogd
// ==============================
login_check_v2();

function slugify($slug)
{
    $slug = utf8_encode($slug);
    $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $slug);
    $slug = preg_replace('/[^a-z0-9- ]/i', '', $slug);
    $slug = str_replace(' ', '-', $slug);
    $slug = trim($slug, '-');
    $slug = strtolower($slug);

    if (empty($slug)) {
        return 'n-a';
    }

    return $slug;
}

$PaginaGekopieerd = false;
$blokkenMee = false;

$sql = $mysqli->query("SELECT *,DATE_FORMAT(datum, '%d-%m-%Y') AS datum1,DATE_FORMAT(datum2, '%d-%m-%Y') AS datum2 FROM siteworkcms WHERE id = '" . $_GET['id'] . "' ") or die($mysqli->error . __LINE__);
$row = $sql->fetch_assoc();

if($_POST['aanmaken'] == '1') {
	if(isset($_POST['blokken-meenemen']) && $_POST['blokken-meenemen'] == 'ja') {
		$blokkenMee = true;
	}

	$linkje = strtolower(slugify($_POST['item2_new']));
	$paginaUrlCheck = $mysqli->query(" SELECT * FROM siteworkcms WHERE paginaurl = '".$linkje."'") or die ($mysqli->error.__LINE__);
	$rowpaginaurl = $paginaUrlCheck->num_rows;      

	if ($rowpaginaurl > 0) {
		$Paginalinkje = $linkje . '-copy';
	} else {
		$Paginalinkje = $linkje;
	}

	$duplicate = $mysqli->query("INSERT siteworkcms SET gebruikersid 	= '".$_SESSION['id']."',
						  item1 				= '".$mysqli->real_escape_string($_POST['item2_new'])."', 
						  item2 				= '".$mysqli->real_escape_string($_POST['item2_new'])."',
						  item3 				= '".$mysqli->real_escape_string($row['item3'])."',
						  item4 				= '".$mysqli->real_escape_string($row['item4'])."',
						  item5 				= '".$mysqli->real_escape_string($row['item5'])."',
						  keuze1				= '".$_POST['keuze1_new']."',
						  tekst      			= '".str_replace('\r\n', '',$mysqli->real_escape_string($row['tekst']))."',
						  paginaurl				= '".slugify($Paginalinkje)."',
						  externeurl  			= '".$row['externeurl']."',
						  targetlink  			= '".$row['targetlink']."',
						  kenmerken   			= '".$row['kenmerken']."',
						  hoofdid     			= '".$row['hoofdid']."',
						  meta_titel 			= '".$row['meta_titel']."',
						  meta_keywords 		= '".$row['meta_keywords']."',
						  meta_beschrijving 	= '".$row['meta_beschrijving']."',
						  taal        			= 'nl',
						  status 				= 'Niet actief',
						  datum 				= NOW() ") or die($mysqli->error.__LINE__);
															
	$rowid = $mysqli->insert_id;  
	$melding = "Gegevens zijn opgeslagen";
	$PaginaGekopieerd = true;
	$allBlocks = array();

	if($PaginaGekopieerd == true && $blokkenMee == true) {
		$sqlBlocks = $mysqli->query("SELECT * FROM sitework_blocks WHERE cms_id = '" . $_POST['duplicate_id'] . "' ") or die($mysqli->error . __LINE__);

		while($rowBlocks = $sqlBlocks->fetch_assoc()) {
			$allBlocks[] = $rowBlocks;
		}
	}

	if(!empty($allBlocks)) {
		foreach ($allBlocks as $block) {
			if($block['volgorde'] == null) {
				$block['volgorde'] = 0;
			}

			$duplicateBlocks = $mysqli->query("INSERT INTO sitework_blocks SET 
				block_id    = '" . $mysqli->real_escape_string($block['block_id']) . "',
				cms_id      = '" . $rowid . "', 
				titel       = '" . $mysqli->real_escape_string($block['titel']) . "',
				tekst       = '" . $mysqli->real_escape_string($block['tekst']) . "',
				categorie   = '" . $mysqli->real_escape_string($block['categorie']) . "',
				volgorde    = " . $block['volgorde'] . "") or die($mysqli->error . __LINE__);
	
			$rowBlockId = $mysqli->insert_id;
		}
	}

	echo "
		<div class=\"alert alert-info\">
			Pagina is aangemaakt, u word nu doorgestuurd.
		</div>
	";
	echo "<script>self.parent.location.href='".$url."/cms/maincms.php?page=pagina_bewerken&id=".$rowid."'</script>";
}

?>

<TITLE>SiteWork CMS Metatags</TITLE>
<meta charset="UTF-8"/>
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/css/stylesheet.css">
<link rel='stylesheet' type='text/css' href='<? echo $url; ?>/cms/css/branding-stylesheet.php' />
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/font-awesome/css/all.min.css">
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/css/themify-icons.css">

<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery-ui-1-12-1.min.js"></script>

<div class="fancybox-wrap">
	<div class="box-container">
		<div class="box box-2-3">
			<h3><i class="icon fas fa-copy"></i> Kopieer de pagina: <strong><?=$row['item1'];?></strong></h3>
			<form action="<?=$PHP_SELF; ?>" method="POST" enctype="multipart/form-data">
				<div class="form-group">
					<label for="keuze1_new">Categorie</label>
					<select name="keuze1_new" class="inputveld invoer dropdown<? if ($error_keuze1){ echo ' foutveld'; } ?>" placeholder="Selecteer een categorie" >	
					<? if (!$row['keuze1']) { ?><option value="">selecteer een categorie</option><? } ?>	
						<? //categorien ophalen
						foreach (getCategorie() as $categorien) {
							if($row['keuze1'] == htmlspecialchars($categorien['categorie'])) {
								$selected = "selected";
							} else { $selected = ""; }

							echo '<option value="'.htmlspecialchars($categorien['categorie']).'" '.$selected.'>'.htmlspecialchars($categorien['categorie']).'</option>';		
						} //functie categorien ?>
					</select>
				</div>
				<div class="form-group">
					<label for="item2_new">Pagina titel</label><input type="text" name="item2_new" class="inputveld invoer" placeholder="Paginatitel" value="<? echo stripslashes($_POST['item2']); ?>" required />
				</div>
				<div class="form-group">
					<label class="form-label">Blokken meenemen</label>
					<div class="inputveld invoer checkbox">
						<input type="checkbox" name="blokken-meenemen" id="blokken-meenemen" value="ja" />
						<label for="blokken-meenemen">Neem blokken mee</label>
					</div>
				</div>

				<input type="hidden" name="duplicate_id" value="<? echo $_GET['id'] ?>" >
				<input type="hidden" name="duplicate_taal" value="<? echo $_GET['taal'] ?>">
				<input type="hidden" name="aanmaken" value="1">
				<button name="opslaan" class="btn fl-left save" type="submit">Opslaan</button>
			</form>
		</div>
		<div class="box box-1-3">
			<div class="info full">
				<span class="far fa-info-circle"></span>
				U heeft als enige mogelijkheid Nederlandse pagina's te kopiëren. 
			</div>
			<?php if($PaginaGekopieerd == true): ?>
				<div class="form-group button-group">
					<a href="/cms/maincms.php?page=pagina_bewerken&id=<?=$rowid;?>&opgeslagen=ja" target="_parent" class="btn arrow">Bekijk pagina</a>
				</div>
			<?php endif; ?>
		</div>
  	</div>
</div>