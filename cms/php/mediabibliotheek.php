<?php 
// checken of men wel is ingelogd
// =================================
login_check_v2(); ?>

<script>
    function handleImageError(img, id) {
        var fallbackImageUrl = '/img/noimg.jpg'; // Replace with your fallback image URL
        $(img).attr('src', fallbackImageUrl);
        
        // Update the href attribute of the parent a element
        $(img).closest('a').attr('href', '/cms/php/media_bewerken.php?media_id=' + id + '&imgUse=no&noimg=ja');
    }
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function() {
            $(this).remove();
        });
    }, 4000);

    $(".alert").on("click", function() {
        $(this).fadeTo("slow", 0);
    });

</script>

<?php
// img settings
$valid_formats = array("jpg", "JPG", "JPEG", "jpeg", "png", "PNG", "svg", "SVG", "gif"); // "jpg", "png", "gif", "zip", "bmp"

if(isset($_GET['max_bestanden'])) {
    $laadMeer = $_GET['max_bestanden'];
} else {
    $laadMeer = 30;
}

if(isset($_POST['afbsoort'])) {
    $afbsoort = $mysqli->real_escape_string($_POST['afbsoort']);
} else { $afbsoort = ''; }

if(isset($_POST['media-soort'])) {
    $mediaFormaat = $mysqli->real_escape_string($_POST['media-soort']);
} else { $mediaFormaat = ''; }

if(isset($_POST['actief'])) {
    $imgActief = $mysqli->real_escape_string($_POST['actief']);
} else { $imgActief = ''; }

if(isset($_POST['datum'])) {
    $date = $mysqli->real_escape_string($_POST['datum']);
} else { $date = ''; }

if(isset($_POST['s'])) {
    $search = $mysqli->real_escape_string($_POST['s']);
} else { $search = ''; }


if($afbsoort <> "") {
    $afbsoort_query = "afbsoort = '".$afbsoort."'";
} else { $afbsoort_query = ''; }

if($mediaFormaat <> "") {
    $mediaFormaat_query = "media = '".$mediaFormaat."'";
} else { $mediaFormaat_query = "media IN ('afbeelding', 'document')"; }

$mediaSQL = "SELECT * FROM sitework_mediabibliotheek WHERE ".$mediaFormaat_query." ORDER BY id DESC LIMIT ".$laadMeer."";

if($search != "") {
    $mediaSQL = "SELECT * FROM sitework_mediabibliotheek WHERE 
    (naam LIKE '%".$search."%' or 
    bijschrift LIKE '%".$search."%' or
    beschrijving LIKE '%".$search."%')
    AND ".$mediaFormaat_query." ORDER BY id DESC LIMIT ".$laadMeer."";
}

$sql = $mysqli->query($mediaSQL) or die ($mysqli->error.__LINE__);
$rows = $sql->num_rows;

$GetMonths = $mysqli->query("SELECT * FROM sitework_mediabibliotheek ORDER BY id ASC") or die ($mysqli->error.__LINE__);
$totaalPaginas = $GetMonths->num_rows;

$imagesQuery = [];

while ($rowGetMonth = $GetMonths->fetch_assoc()) {
    $imagesQuery[] = $rowGetMonth['naam'];
}

function getMonthsFromImageNames($images) {
    $months = [];
  
    if(is_array($images)) {
        foreach ($images as $image) {
            // Extract potential timestamp from filename
            $match = preg_match('/_(\d{10})/', $image, $matches);
            if ($match) {
                $timestamp = $matches[1];
                // Convert timestamp to datetime object
                $datetime = date("m-Y", $timestamp);
                if ($datetime) {
                    $months[] = $datetime;
                }
            }
        }
        // Remove duplicate entries
        $months = array_unique($months);
    } else {
        $image = $images;
        // Extract potential timestamp from filename
        $match = preg_match('/_(\d{10})/', $image, $matches);
        if ($match) {
            $timestamp = $matches[1];
            // Convert timestamp to datetime object
            $datetime = date("m-Y", $timestamp);
            if ($datetime) {
                $month = $datetime;
            }
        }

        $months = $month;
    }
    
    return $months;
}
  
$months = getMonthsFromImageNames($imagesQuery);
?>
<div class="box-container">
    <div class="box box-full lg-box-full">
        <h3><span class="icon fas fa-images"></span>Media bibliotheek</h3>
        <a href="" class="clickme btn fl-right nieuw">Upload mediabestand</a>
        <div class="content-container toggle-box">
            <div class="drag-and-drop">
                <div class="">
                    <button type="button" class="btn upload button-input" onclick="document.getElementById('inputFile').click()">Bestand uploaden</button>
                    <i>Of</i>
                    <input type="file" name="mediabiebFiles" class="form-control-file text-success font-weight-bold" id="inputFile" multiple accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf" onchange="readUrl(this)" data-title="Een bestand slepen en neerzetten">
                </div>
            </div>
        </div>
        <div class="content-container">
            <nav>
                <form id="bulk_media" method="post" action="?page=mediabibliotheek" class="mediatopbar fl-left mr-10">
                    <button type="submit" id="bulk-media-btn" class="btn fl-left mb-20"><?php echo ($_POST['bulk_selectie'] == '1') ? 'Sluit bulk selectie' : 'Bulk selectie'; ?></button>
                    <input type="hidden" name="bulk_selectie" value="<?php echo ($_POST['bulk_selectie'] == '1') ? '0' : '1'; ?>">
                </form>
                <?php if($_POST['bulk_selectie'] == '1'): ?>
                    <button id="remove_bulk" class="btn fl-left ml-10 disabled">Verwijder bulk selectie</button>
                <?php endif; ?>
                <form id="zoek_media" method="post" action="?page=mediabibliotheek" class="mediatopbar maxtekens mr-10">
                    <select class="inputveld dropdown extra-pad full-mob maxtekens zoek-media" name="datum">
                        <option value="">Datum</option>
                        <?php foreach ($months as $month) { 
                            if (strtolower($month) <> ""): ?>                                  
                                <option value="<?php echo strtolower($month); ?>" <?php if ($date == strtolower($month)) { echo "selected"; } ?>>
                                    <?php echo strtolower($month); ?>
                                </option>
                            <?php endif; 
                        } ?>     
                    </select>
                    <select class="inputveld mr-10 dropdown extra-pad full-mob maxtekens zoek-media" name="actief">
                        <option value="">Alle bestanden</option> 
                        <option value="1" <?php if($imgActief == '1') { echo 'selected'; } ?>>In gebruik</option>
                        <option value="0" <?php if($imgActief == '0') { echo 'selected'; } ?>>Niet in gebruik</option> 
                    </select>
                    <select class="inputveld mr-10 dropdown extra-pad full-mob maxtekens zoek-media" name="media-soort">
                        <option value="">Media soort</option> 
                        <option value="afbeelding" <?php if($mediaFormaat == 'afbeelding') { echo 'selected'; } ?>>Afbeeldingen</option>
                        <option value="document" <?php if($mediaFormaat == 'document') { echo 'selected'; } ?>>Documenten</option> 
                    </select>
                    <input type="search" placeholder="zoek op bestandnaam, -beschrijving of -bijschrift" value="<?=$search;?>" name="s" class="inputveld invoer search extra-pad full-mob small-50 round-full maxtekens zoek-media mr-10">
                </form>
            </nav>
            <div class="row mediabieb">
                <?php if($rows > 0):
                    if($_POST['bulk_selectie'] == '1') { ?>
                        <?php while ($row = $sql->fetch_assoc()) {
                            if(!in_array($row['ext'], $valid_formats)): ?>
                                <input class="media-keuze" data-media-id="<?=$row['id'];?>" type="checkbox" value="<?=$row['media'];?>-<?=$row['id'];?>" name="media-keuze" id="<?=$row['media'];?>-<?=$row['id'];?>">
                                <label for="<?=$row['media'];?>-<?=$row['id'];?>" class="doc ">
                                    <i class="fas fa-file"></i>  
                                    <p><?=$row['naam'];?>.<?=$row['ext'];?></p>                                      
                                </label>
                            <?php else: ?>
                                <input class="media-keuze" data-media-id="<?=$row['id'];?>" type="checkbox" value="<?=$row['media'];?>-<?=$row['id'];?>" name="media-keuze" id="<?=$row['media'];?>-<?=$row['id'];?>">
                                <label for="<?=$row['media'];?>-<?=$row['id'];?>" class="img ">
                                    <img src="/img/<?=$row['naam'];?>_tn.<?=$row['ext'];?>" alt="<?=$row['naam'];?>" onerror="handleImageError(this, <?=$row['id'];?>);">
                                </label>
                            <?php endif;
                        } ?>
                    <?php } else {
                        while ($row = $sql->fetch_assoc()){
                            if(!in_array($row['ext'], $valid_formats)) {
                                $sqlMediaUse = $mysqli->query("SELECT * FROM sitework_doc WHERE url = '".$row['id']."'") or die($mysqli->error.__LINE__);
                                $rowMediaUse = $sqlMediaUse->fetch_assoc();
                            } else {
                                $sqlMediaUse = $mysqli->query("SELECT * FROM sitework_img WHERE naam = '".$row['id']."'") or die($mysqli->error.__LINE__);
                                $rowMediaUse = $sqlMediaUse->fetch_assoc();
                            }
                            if($sqlMediaUse->num_rows <= 0) {
                                $noUse = '&imgUse=no';
                            } else { $noUse = ''; }
                            
                            ?>
                            <?php if(!in_array($row['ext'], $valid_formats)):
                                if($date <> ""):
                                    if(getMonthsFromImageNames($row['naam']) == $date): ?>
                                        <a class="doc <?php echo ($sqlMediaUse->num_rows <= 0) ? 'no-use' : '' ?> <?php echo (isset($imgActief) && $sqlMediaUse->num_rows > 0) ? 'in-gebruik-' . $imgActief : 'niet-gebruik-' . $imgActief ?>" data-fancybox data-small-btn="true" data-type="iframe" href="/cms/php/media_bewerken.php?media_id=<?=$row['id'];?><?=$noUse;?>" href="javascript:;">
                                            <i class="fas fa-file"></i>  
                                            <p><?=$row['naam'];?>.<?=$row['ext'];?></p>  
                                        </a>
                                    <?php endif;
                                else: ?>
                                    <a class="doc <?php echo ($sqlMediaUse->num_rows <= 0) ? 'no-use' : '' ?> <?php echo (isset($imgActief) && $sqlMediaUse->num_rows > 0) ? 'in-gebruik-' . $imgActief : 'niet-gebruik-' . $imgActief ?>" data-fancybox data-small-btn="true" data-type="iframe" href="/cms/php/media_bewerken.php?media_id=<?=$row['id'];?><?=$noUse;?>" href="javascript:;">
                                        <i class="fas fa-file"></i>  
                                        <p><?=$row['naam'];?>.<?=$row['ext'];?></p>                                      
                                    </a>
                                <?php endif;
                            else:
                                if($date <> ""):
                                    if(getMonthsFromImageNames($row['naam']) == $date): ?>
                                        <a class="img <?php echo ($sqlMediaUse->num_rows <= 0) ? 'no-use' : '' ?> <?php echo (isset($imgActief) && $sqlMediaUse->num_rows > 0) ? 'in-gebruik-' . $imgActief : 'niet-gebruik-' . $imgActief ?>" data-fancybox data-small-btn="true" data-type="iframe" href="/cms/php/media_bewerken.php?media_id=<?=$row['id'];?><?=$noUse;?>" href="javascript:;">
                                            <img src="/img/<?=$row['naam'];?>_tn.<?=$row['ext'];?>" alt="<?=$row['naam'];?>" onerror="handleImageError(this, <?=$row['id'];?>);">
                                        </a>
                                    <?php endif;
                                else: ?>
                                    <a class="img <?php echo ($sqlMediaUse->num_rows <= 0) ? 'no-use' : '' ?> <?php echo (isset($imgActief) && $sqlMediaUse->num_rows > 0) ? 'in-gebruik-' . $imgActief : 'niet-gebruik-' . $imgActief ?>" data-fancybox data-small-btn="true" data-type="iframe" href="/cms/php/media_bewerken.php?media_id=<?=$row['id'];?><?=$noUse;?>" href="javascript:;">
                                        <img src="/img/<?=$row['naam'];?>_tn.<?=$row['ext'];?>" alt="<?=$row['naam'];?>" onerror="handleImageError(this, <?=$row['id'];?>);">
                                    </a>
                                <?php endif;
                            endif;
                        }  
                    }
                else: ?>
                    <p id="geen-media"><strong>Geen afbeeldingen gevonden</strong></p>
                <?php endif; ?>
            </div>
            <?php if($totaalPaginas > $laadMeer): ?>
                <div id="laad_meer">
                    <p><?=$rows;?> van de <?=$totaalPaginas;?> bestanden weergeven</p>
                    <a class="btn" href="?page=mediabibliotheek&max_bestanden=<?php echo $laadMeer + 30;?>#laad_meer">Laad meer</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    $('.zoek-media').on('change', function () {
        $("#zoek_media").submit();
    });

    $('#bulk-media-btn').on('click', function () {
        $("#bulk_media").submit();
    });

    $('#remove_bulk').on('click', function (event) {
        event.preventDefault();

        var userConfirmed = confirm("Weet je zeker dat je de bulk selectie wilt verwijderen?");

        if (userConfirmed) {
            $('.box-container').append("<div class=\"alert alert-info\">Bulk selectie wordt verwijderd</div>");

            var checkedMediaIds = [];
            
            $('.media-keuze:checked').each(function() {
                checkedMediaIds.push($(this).data('media-id'));
            });

            var bulkDeleteIDS = new FormData();
        
            checkedMediaIds.forEach(function(id) {
                bulkDeleteIDS.append('media_ids[]', id); 
            });

            $.ajax({
                type: "POST",
                url: "/cms/php/media-bulk-delete.php", // Replace with your actual URL
                data: bulkDeleteIDS,
                contentType: false, // Set to avoid default form data processing
                processData: false, // Prevent jQuery from pre-processing data
                success: function (data) {
                    window.location.reload(true);
                    $('.box-container').append("<div class=\"alert alert-success\">Bulk selectie is verwijderd</div>");
                    $("#bulk_media").submit();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error("Upload failed:", textStatus, errorThrown);
                }
            });
        } else {
            $('.box-container').append("<div class=\"alert alert-info\">Bulk selectie verwijderen is geannuleerd</div>");
        }
    });

    $('.media-keuze').on('change', function (e) {
        var checkedMediaIds = [];
        
        $('.media-keuze:checked').each(function() {
            checkedMediaIds.push($(this).data('media-id'));
        });

        if(checkedMediaIds.length > 0) {
            $('#remove_bulk').removeClass('disabled');
        } else if(checkedMediaIds.length <= 0) {
            $('#remove_bulk').addClass('disabled');
        }
    });

    function readUrl(input) {
        if (input.files && input.files.length > 0) {
            let reader = new FileReader();
            reader.onload = (e) => {
                let imgData = e.target.result;
                let imgNames = [];
                for (let i = 0; i < input.files.length; i++) {
                    imgNames.push(input.files[i].name);
                }
                let filesCountText = input.files.length === 1 ? 'bestand' : 'bestanden';
                let displayText = `${input.files.length} ${filesCountText}`;
                input.setAttribute("data-title", displayText);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("input[name=mediabiebFiles]").on('change', function (event) {
        event.preventDefault();

        $('.box-container').append("<div class=\"alert alert-info\">Media word geupload</div>");

        // Get all selected files
        var files = event.target.files;

        if (files.length > 0) {
            // Create a FormData object
            var formData = new FormData();

            // Loop through all selected files
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                // Append each file to the FormData object
                formData.append('mediabiebFiles[]', file); 
            }

            // Configure the AJAX request
            $.ajax({
                type: "POST",
                url: "/cms/php/upload_mediabestand.php", // Replace with your actual URL
                data: formData,
                contentType: false, // Set to avoid default form data processing
                processData: false, // Prevent jQuery from pre-processing data
                success: function (data) {
                    $(".mediabieb").html(data);
                    $('#inputFile').val("");
                    $('#inputFile').attr("data-title", "Een bestand slepen en neerzetten");
                    $('.box-container').append("<div class=\"alert alert-success\">Media is successvol geupload</div>");
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('.box-container').append("<div class=\"alert alert-error\">Er is iets mis gegaan, probeer het nog eens</div>");
                    $('#inputFile').val("");
                    $('#inputFile').attr("data-title", "Een bestand slepen en neerzetten");
                    console.error("Upload failed:", textStatus, errorThrown);
                    // Handle upload errors gracefully (optional)
                }
            });
        }
    });
</script>