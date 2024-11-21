<? ob_start();
// database connectie en inlogfuncties
// ===================================
require("../login/config.php");
require("../ftp/config.php");
include '../login/functions.php';
session_start();

// checken of men wel is ingelogd
// ==============================
login_check_v2();

function img($url, $src, $ext, $alt, $width, $height, $maxdivwidth)
{
    $nosize = "";
    if (empty($width) and empty($height)) {
        $aspectratioDiv = number_format(0);
        $nosize = true;
    } else {
        $aspectratioCalc = $height / $width;
        $aspectratio = $aspectratioCalc * 100; 
        $aspectratioDiv = number_format($aspectratio, 2, '.', '');
    }
    if ($maxdivwidth < '700') {
        $defaultsrc = $url."/img/webp/".$src.".webp";
    } elseif ($maxdivwidth < '900') {
        $defaultsrc = $url."/img/webp/".$src.".webp";
    } else {
        $defaultsrc = $url."/img/webp/".$src.".webp";
    }
    if($ext == "png" OR $ext == "PNG"){
        $sourcemediathumb = "<source media=\"(max-width: 700px)\" data-srcset=\"{$url}/img/{$src}_tn.png\" type=\"image/png\">";
        $sourcemediamid = "<source media=\"(max-width: 900px)\" data-srcset=\"{$url}/img/{$src}_mid.png\" type=\"image/png\">";
        $sourcemedialarge = "<source media=\"(min-width: 900px)\" data-srcset=\"{$url}/img/{$src}.png\" type=\"image/png\">";
    }else if($ext == "JPG" OR $ext == "jpg"){
        $sourcemediathumb = "<source media=\"(max-width: 700px)\" data-srcset=\"{$url}/img/{$src}_tn.jpg\" type=\"image/jpeg\">";
        $sourcemediamid = "<source media=\"(max-width: 900px)\" data-srcset=\"{$url}/img/{$src}_mid.jpg\" type=\"image/jpeg\">";
        $sourcemedialarge = "<source media=\"(min-width: 900px)\" data-srcset=\"{$url}/img/{$src}.jpg\" type=\"image/jpeg\">";
    }else if($ext == "JPEG" OR $ext == "jpeg"){
        $sourcemediathumb = "<source media=\"(max-width: 700px)\" data-srcset=\"{$url}/img/{$src}_tn.jpeg\" type=\"image/jpeg\">";
        $sourcemediamid = "<source media=\"(max-width: 900px)\" data-srcset=\"{$url}/img/{$src}_mid.jpeg\" type=\"image/jpeg\">";
        $sourcemedialarge = "<source media=\"(min-width: 900px)\" data-srcset=\"{$url}/img/{$src}.jpeg\" type=\"image/jpeg\">";
    }
    if($nosize == true){
        echo "
            <picture>
                {$sourcemediathumb}
                {$sourcemediamid}
                {$sourcemedialarge}
                <img src=\"{$defaultsrc}\" alt=\"{$alt}\" class=\"lazy\" width=\"{$width}\" height=\"{$height}\">
            </picture>
    ";
    }else{
        echo "
        <div class=\"picture-container\" style=\"padding-top: {$aspectratioDiv}%\">
            <picture>
                {$sourcemediathumb}
                {$sourcemediamid}
                {$sourcemedialarge}
                <img src=\"{$defaultsrc}\" alt=\"{$alt}\" class=\"lazy\" width=\"{$width}\" height=\"{$height}\">
            </picture>
        </div>
    ";
    }
}

if(isset($_POST['media_bewerken'])) {
	$BG_ID = $_POST['media_bewerken'];
	$new_Status = $_POST['bewerk_status'];

	$sql_update_bg = $mysqli->query("UPDATE siteworkcms_background SET status = '" . $new_Status . "' WHERE id = '".$BG_ID."'")
	or die($mysqli->error . __LINE__);

	header('Location: cms_background.php');
}

if(isset($_POST) and isset($_POST['status'])) { 
	$media = $_POST['media-keuze'][0];
	$status = $_POST['status'];

	$mediawaardes = explode('-', $media);

	$mediaNaam = $mediawaardes[0];
	$mediaIdFile = $mediawaardes[1];

	$sql_insert_img = $mysqli->query("INSERT INTO siteworkcms_background SET 
		media_id = '" . $mediaIdFile . "',
		naam = '" . $mysqli->real_escape_string($mediaNaam) . "',
		status = '" . $status . "'")
	or die($mysqli->error . __LINE__);

	header('Location: cms_background.php');
}

$sqlMedia = $mysqli->query("SELECT * FROM sitework_mediabibliotheek WHERE media = 'afbeelding' ORDER BY id DESC LIMIT 100") or die ($mysqli->error.__LINE__);
$rowsMedia = $sqlMedia->num_rows;

// afbeelding verwijderen
// ======================
if($_GET['delete_id'] <> ""){
	$sql_del = $mysqli->query("DELETE FROM siteworkcms_background WHERE id = '".$_GET['delete_id']."' ") or die($mysqli->error.__LINE__);

	header('Location: cms_background.php');
}
?>

<TITLE>SiteWork CMS afbeelding upload</TITLE>
<meta charset="UTF-8"/>
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/css/stylesheet.css">
<link rel='stylesheet' type='text/css' href='<? echo $url; ?>/cms/css/branding-stylesheet.php' />

<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/datepick/jquery-ui-date.css">
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/fancybox/jquery.fancybox.min.css">
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/font-awesome/css/all.min.css">

<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery-ui-1-12-1.min.js"></script>  
<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery.fancybox.js"></script>
<script type="text/javascript" src="<? echo $url; ?>/cms/datepick/jquery-ui-date.js"></script>
<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery-nested-sortable.js"></script>  
<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/sitework.js"></script>
<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery.sticky-kit.min.js"></script>
<script type="text/javascript">
	function ConfirmDelete() { 
		return confirm('Weet u zeker dat u deze achtergrond wilt verwijderen?'); 
	}
	function initializeTooltips() {
		$('.showImgInfo').tooltip({
			content: function() {
				var infotekst = '<div class="afbeelding-info">';
						infotekst += '<div class="row">';
							infotekst += '<span class="fat"><b>Bestandsnaam:</b> '+$(this).attr('data-naam')+'</span>';
						infotekst += '</div>';
						infotekst += '<div class="row">';
								infotekst += '<span class="fat"><b>Afmetingen:</b> '+$(this).attr('data-afmetingen')+'</span>';
						infotekst += '</div>';
						infotekst += '<div class="row">';
							infotekst += '<span class="fat"><b>Bestandstype:</b> '+$(this).attr('data-type')+'</span>';
						infotekst += '</div>';
						infotekst += '<div class="row">';
							infotekst += '<span class="fat"><b>Bestandsgrootte:</b> '+$(this).attr('data-filesize')+'</span>';
						infotekst += '</div>';
						infotekst += '<div class="row">';
							infotekst += '<span class="fat" style="word-break: break-all;"><b>Url:</b> <span id="copy-url" data-url="<?=$url;?>/img/'+$(this).attr('data-naam')+'" style="text-decoration:underline;cursor:pointer;"><?=$url;?>/img/'+$(this).attr('data-naam')+'</span></span>';
						infotekst += '</div>';
						infotekst += '<span id="gekopieerd" style="position:absolute;bottom:10px;left:10px;color:green;"></span>';
					infotekst += '</div>';

				return $('<div class="tooltip-content">' + infotekst + '</div>');
			},
			open: function(event, ui) {
				ui.tooltip.on('click', '#copy-url', function() {
					var textToCopy = $(this).attr('data-url');
					navigator.clipboard.writeText(textToCopy)
						.then(function() {
							$('#gekopieerd').text('URL gekopieërd!'); // Show success message
						})
						.catch(function(err) {
							console.error("Failed to copy text:", err);
						});
				});

				ui.tooltip.hover(
					function () {
						$(this).stop(true).fadeTo(400, 1); // Keep tooltip visible on hover
					},
					function () {
						$(this).fadeOut('400', function () {
							$(this).remove();
						});
					}
				);
			},
			close: function(event, ui) {
				ui.tooltip.hover(
					function () {
						$(this).stop(true).fadeTo(400, 1); // Keep tooltip visible on hover
					},
					function () {
						$(this).fadeOut('400', function () {
							$(this).remove();
						});
					}
				);
			}
		});
	};

	function selectOptionByIdAndValue(selectId, valueToSelect) {
		var selectElement = document.getElementById(selectId);

		if (selectElement) {
			for (var i = 0; i < selectElement.options.length; i++) {
				var option = selectElement.options[i];

				if (option.value === valueToSelect) {
					option.selected = true;
					break;
				}
			}
		}
	}

	function removeAllSelectedOptions(selectId) {
		var selectElement = document.getElementById(selectId);

		if (selectElement) {
			for (var i = 0; i < selectElement.options.length; i++) {
				selectElement.options[i].selected = false;
			}
		}
	}

	$(document).ready(function () {
		initializeTooltips();
	});
</script>

<div class="fancybox-wrap">
	<div class="box-container">
		<div class="box box-1-2 md-box-full kaal">
			<div id="bg_edit" class="box box-full title hidden-box">
				<h3><span class="icon fas fa-edit"></span>Achtergrond bewerken</h3>
				<form id="bg_bewerken_form" action="" method="POST">
					<div class="form-group">
						<label for="bewerk_status">Status</label>
						<select name="bewerk_status" class="inputveld invoer dropdown" id="bewerk_status">
							<option value="">Selecteer status</option>
							<option value="actief">actief</option>
							<option value="inactief">inactief</option>
						</select>
					</div>

					<input type="hidden" name="bewerken" value="1">
					<button name="opslaan_bw" class="btn fl-left save" type="submit">Opslaan</button>
				</form>
			</div>
			<div class="box box-full title">
				<div class="info full fancy">
					<span class="far fa-info-circle"></span>&nbsp;&nbsp;De achtergronden met de status actief zullen 'random' gestoond worden. Klik op de afbeelding om de status te kunnen wijzigen.
				</div>
				<div id="error_area"></div>
				<div id="contentLeft">
					<ul id="update_bg">
					<? $querydrag = $mysqli->query("SELECT * FROM siteworkcms_background order by volgorde") or die($mysqli->error.__LINE__);
					if ($querydrag->num_rows == 0) { echo "<div class=\"middentekst\">Er zijn nog geen afbeeldingen geplaatst ...</div>";}
					
						while($rowdrag = $querydrag->fetch_assoc()){ 
							$mediaSoort = 'afbeelding';
							$_GET['media'] = 'afbeelding';

							if($_GET['media'] == 'afbeelding') {
								$mediaID_naam = $rowdrag['naam'];
								$mediaID_naamArr = explode('-', $mediaID_naam);
								$mediaID = $mediaID_naamArr[1];
							} else {
								$mediaID = $rowdrag['url'];
							}
							?>	
							
							<li id="recordsArray_<?php echo $rowdrag['id']; ?>">
									<input class="huidige-bg" type="checkbox" value="<?=$mediaSoort;?>-<?=$rowdrag['id'];?>" data-bg-id="<?php echo $rowdrag['id']; ?>" name="huidige-media" id="<?=$mediaSoort;?>-<?=$rowdrag['id'];?>">
									<label for="<?=$mediaSoort;?>-<?=$rowdrag['id'];?>" class="sort-wrap <?php echo ($mediaSoort == 'afbeelding') ? 'img' : 'doc';?>">
										<div>    
											<?php 
												$mediaBG_full = $mediaID_naamArr[0];
												$mediaBG_full_exp = explode('.', $mediaBG_full);

												$mediaBD_Naam = $mediaBG_full_exp[0];
												$mediaBD_Ext = $mediaBG_full_exp[1];
												
												img($url, $mediaBD_Naam, $mediaBD_Ext, '', '500', '500', '500');
											?>
										</div>
										<span class="soort-image">Achtergrond</span>
										<span 
											class="showImgInfo" 
											title=""
											data-afmetingen="
											<?php 
												list($width, $height, $type, $attr) = getimagesize("../../img/".$mediaBG_full); 
												echo $width." x ".$height." pixels<br>";  
											?>"
											data-naam="<?=$mediaBG_full;?>"
											data-type="
											<?php $finfo = finfo_open(FILEINFO_MIME_TYPE); 
												foreach (glob("../../img/".$mediaBG_full) as $filename) {
													echo finfo_file($finfo, $filename) . "\n";
												} finfo_close($finfo);
												?>"
											data-filesize="
											<?php 
												$filename = '../../img/'.$mediaBG_full; 
												echo round(filesize($filename)/1024, 2) . ' Kb'; 
											?>"
										>
										<i class="far fa-info-circle"></i></span>
										<a class="delete-image" href="?delete_id=<?php echo $rowdrag['id']; ?>"
											onclick='return ConfirmDelete();'>
											<span class="far fa-trash-alt"></span>
										</a>
									</label>
								</li>
							
						<?  } ?>		      
					</ul>
				</div>
			</div>
		</div>
	
		<form class="box box-1-2 md-box-full kaal" action="<? // echo $PHP_SELF ?>" method="POST">
			<div class="box box-full">
				<h3><span class="icon fas fa-images"></span>Achtergronden toevoegen</h3>
				<? if ($error){ ?><span class="error"><? echo $error; ?></span><? } ?>

				<div class="form-group">
					<label for="status">Status</label>
					<select name="status" class="inputveld invoer dropdown<? if ($error_afb){ echo ' foutveld'; } ?>" id="afbsoort">
						<option value="">Selecteer status</option>
						<option value="actief">actief</option>
						<option value="inactief">inactief</option>
					</select>
				</div>

				<input type="hidden" name="toevoegen" value="1">

				<button name="opslaan" class="btn fl-left save" type="submit">Opslaan</button>
			</div>
			<div class="box box-full">
				<h3><span class="icon fas fa-photo-video"></span>Mediabibliotheek</h3>
				<div class="content-container">    	
					<div class="row mediabieb kies-media">
						<?php if($rowsMedia > 0): ?>
							<?php while ($row = $sqlMedia->fetch_assoc()){ ?>
									
								<input class="media-keuze" type="checkbox" value="<?=$row['naam'];?>.<?=$row['ext'];?>-<?=$row['id'];?>" name="media-keuze[]" id="afbeelding-<?=$row['id'];?>">
								<label for="afbeelding-<?=$row['id'];?>" class="img">
									<img src="/img/<?=$row['naam'];?>_tn.<?=$row['ext'];?>" alt="<?=$row['naam'];?>" onerror="handleImageError(this, <?=$row['id'];?>);">
								</label>
							<? } ?>  
						<?php else: ?>
							<p id="geen-media"><strong>Geen afbeeldingen gevonden</strong></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
	$(".media-keuze").on('change', function (event) {
		if ($(this).is(':checked')) {
			$(".media-keuze").not(this).prop('checked', false);
		}
	});

	$("#update_bg").on('change', '.huidige-bg', function (event) {
		var checkedCount = $(".huidige-bg:checked").length;

		if(checkedCount > 0) {
			$('#bg_edit').removeClass('hidden-box');
			const bg_id = $(this).data('bg-id');

			if ($('#media_bewerken').length === 0) {
				$('#bg_bewerken_form').append('<input type="hidden" name="media_bewerken" id="media_bewerken" value="'+bg_id+'" />');
			}

			$.ajax({
			    url: "/cms/php/verkrijg_afbeelding.php",
			    type: "POST",
			    data: { 
			        bg_id: bg_id
			    },
			    dataType: 'json',
			    success: function (data) {
			        if(data.status == 'success') {
						var bgData = data.bg;
						var bgStatus = bgData.status;

						selectOptionByIdAndValue("bewerk_status", bgStatus);
			        } else {
			            $('#error_area').html('<span class="error">Er zijn geen gegevens gevonden, probeer het nogmaals</span>');
			        }
			    }
			});
		} else {
			$('#bg_edit').addClass('hidden-box');
			removeAllSelectedOptions("bewerk_status");
			$('#media_bewerken').remove();
		}
	});
</script>