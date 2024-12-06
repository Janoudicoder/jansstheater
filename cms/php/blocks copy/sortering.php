<?php 

ob_start();
// database connectie en inlogfuncties
// ===================================
require("../../login/config.php");
include '../../login/functions.php';
session_start();

// checken of men wel is ingelogd
// ==============================
login_check_v2();

if ($_GET['delete_id'] <> "") {
    $sql_del = $mysqli->query("DELETE FROM sitework_blocks WHERE id = '".$_GET['delete_id']."' ") or die($mysqli->error.__LINE__);

    header('Location: sortering.php?id='.$_GET['id'].'&taal='.$_GET['taal'].'');
}

if ($_GET['del_id'] <> "") {
    $sql_del = $mysqli->query("DELETE FROM sitework_vertaling_blocks WHERE id = '".$_GET['del_id']."' OR hoofdid = '".$_GET['del_id']."' AND taal = '".$_GET['taal']."' ") or die($mysqli->error.__LINE__);
    
    header('Location: sortering.php?id='.$_GET['id'].'&taal='.$_GET['taal'].'');
}

?>

<TITLE>SiteWork CMS afbeelding upload</TITLE>
<meta charset="UTF-8" />
<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/cms/css/stylesheet.css">
<link rel='stylesheet' type='text/css' href='<?php echo $url; ?>/cms/css/branding-stylesheet.php' />
<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/cms/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/cms/font-awesome/css/all.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/cms/css/themify-icons.css">

<script type="text/javascript" src="<?php echo $url; ?>/cms/jquery/files/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="<?php echo $url; ?>/cms/jquery/files/jquery-ui-1-12-1.min.js"></script>
<script>
// Alert voor opgeslagen
// ======================
window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function() {
        $(this).remove();
    });
}, 4000);

$(".alert").on("click", function() {
    $(this).fadeTo("slow", 0);
});
</script>
<script type="text/javascript">
function ConfirmDelete() {
    return confirm('Weet u zeker dat u dit block wilt verwijderen?');
}

// volgorde van afbeeldingen verslepen en opslaan
// ==============================================
$(function() {
    $("#structuur ul").sortable({
        opacity: 0.6,
        cursor: 'move',
        update: function() {
            var order = $(this).sortable("serialize") + '&action=updateRecordsListings&updateTaal=<?=$_GET['taal'];?>';
            $.post("../../dragdrop/update_blocks.php", order, function(theResponse) {
                $("#melding-blocks").html(theResponse);
            });
        }
    });
});
</script>
<div class="fancybox-wrap">
    <div class="box-container">
        <div class="box md-box-full">
            <h3><i class="icon fas fa-arrows-alt-v"></i>Volgorde blocks aanpassen</h3>
            <div id="melding-blocks"></div>
            <em class="info">
                <span class="far fa-info-circle"></span>&nbsp;&nbsp;U kunt de volgorde van de blocks zelf bepalen door ze
                te verslepen.
            </em>
            <div id="structuur" class="sorteer-blokken">
                <ul>
                    <?php 
                    $blokkenTeller = 1;
                    if($_GET['taal'] == 'nl') {
                        $querydrag = $mysqli->query("SELECT * FROM sitework_blocks WHERE cms_id = '".$_GET['id']."' ORDER BY volgorde,id ASC") or die($mysqli->error.__LINE__);
                        while($rowdrag = $querydrag->fetch_assoc()){ 
                            $querydragName = $mysqli->query("SELECT * FROM sitework_block WHERE id = '".$rowdrag['block_id']."'") or die($mysqli->error.__LINE__);
                            $rowdragName = $querydragName -> fetch_assoc();
                            ?>
                            
                            <li id="recordsArray_<?php echo $rowdrag['id']; ?>">
                                <div class="hoofditemlabel">Block: <?=$blokkenTeller;?></div>
                                <div class="hoofditemtitel">
                                    <?php echo $rowdragName['block_naam']; ?>
                                </div>
                                <a class="delete-block" href="?id=<?=$_GET['id']; ?>&taal=<?=$_GET['taal'];?>&delete_id=<?=$rowdrag['id']; ?>"
                                onclick='return ConfirmDelete();'>
                                    <span class="fas fa-trash"></span>
                                </a>
                            </li>
                        <?php $blokkenTeller += 1; }   
                    } else {
                        $querydrag = $mysqli->query("SELECT * FROM sitework_vertaling_blocks WHERE cms_id = '".$_GET['id']."' AND hoofdid = '0' AND taal like '%".$_GET['taal']."%' ORDER BY volgorde,id ASC") or die($mysqli->error.__LINE__);
                        while($rowdrag = $querydrag->fetch_assoc()){ 
                            $querydragName = $mysqli->query("SELECT * FROM sitework_block WHERE id = '".$rowdrag['block_id']."'") or die($mysqli->error.__LINE__);
                            $rowdragName = $querydragName -> fetch_assoc();
                            ?>
                            
                            <li id="recordsArray_<?php echo $rowdrag['id']; ?>">
                                <div class="hoofditemlabel">Block: <?=$blokkenTeller;?></div>
                                <div class="hoofditemtitel">
                                    <?php echo $rowdragName['block_naam']; ?>
                                </div>
                                <a class="delete-block" href="?id=<?=$_GET['id']; ?>&taal=<?=$_GET['taal'];?>&del_id=<?=$rowdrag['id']; ?>"
                                onclick='return ConfirmDelete();'>
                                    <span class="fas fa-trash"></span>
                                </a>
                            </li>
                        <?php $blokkenTeller += 1; }  
                    }?>
                </ul>
            </div>
        </div>
    </div>
</div>