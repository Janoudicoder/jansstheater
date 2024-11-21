<? 

// checken of men wel is ingelogd
// =================================
login_check_v2(); ?>

<script> 
// categorie verwijderen melding
// =============================
    function ConfirmDeleteCat() {
    return confirm('Weet u zeker dat u deze block wilt verwijderen?'); 
    } 
</script>

<? // categorie toevoegen
// ======================
if($_POST['toevoegen'] == 1 && $_POST['naam']){
	$sql_ins = $mysqli->query("INSERT INTO sitework_block SET block_naam = '".$_POST['naam']."', bestandsnaam = '".$_POST['bestandsnaam']."'") or die($mysqli->error.__LINE__);
	echo "
        <div class=\"alert alert-success\">
            Block toegevoegd
        </div>
    ";
}

if($_POST['toevoegen'] == 1 && !$_POST['naam']){ 
echo "
    <div class=\"alert alert-error\">
        Voor een block naam in
    </div>
";
}

// categorie verwijderen
// =====================
if ($_GET['delid']) { 
	$mysqli->query("DELETE FROM sitework_block WHERE id = '".$_GET['delid']."' ") or die($mysqli->error.__LINE__);
	header('Location: '.$PHP_SELF.'');
}
?>
<div class="box-container">
    <div id="message"></div>
    <div class="box box-2-3 lg-box-full">
        <h3><span class="icon fas fa-boxes"></span>Blocks</h3>
        <?php if($rowuser['id'] == '1'): ?>
            <a href="" class="clickme btn fl-right nieuw">Nieuwe block</a>
        <?php endif; ?>
        <div class="toggle-box">
            <form action="<?=$PHP_SELF; ?>" method="post" enctype="multipart/form-data" name="form1">
                <input type="text" tabindex="1" name="naam"  class="inputveld mr-10" id="naam"  placeholder="Naam block" />
                <input type="text" tabindex="2" name="bestandsnaam"  class="inputveld mr-10" id="bestandsnaam"  placeholder="Naam bestandsnaam" />
                <input type="hidden" name="toevoegen" value="1">
                <input name="button3" type="submit" class="btn fl-left" id="button" value="toevoegen">
            </form>
        </div>

        <div class="content-container <?php echo ($rowuser['id'] == '1') ? "block-admin" : "block-user" ?>">    	
            <div class="row blocks type <?php echo ($rowuser['id'] == '1') ? "admin-verwijder" : "userdel" ?>">
                <?php
                    if($rowuser['id'] == '1') {
                        echo '<div class="col">Actief</div>';
                    }
                ?>
                <div class="col">naam block</div>
                <div class="col sm-mob-hide">bestandsnaam</div>
                <div class="col center">verwijderen</div>
            </div>
            <?php $sql = $mysqli->query("SELECT * FROM sitework_block ORDER BY actief DESC,block_naam ASC LIMIT 100") or die ($mysqli->error.__LINE__);		
            $rows = $sql->num_rows;
            while ($row = $sql->fetch_assoc()){?>
                <div class="row blocks <?php echo ($row['actief'] == '0') ? 'niet-actief' : 'actief' ?> <?php echo ($rowuser['id'] == '1') ? "admin-verwijder" : "userdel" ?>">
                    <?php
                        if($rowuser['id'] == '1') {
                            if($row['actief'] == '1') {
                                $checked = "checked";
                            } else {
                                $checked = "";
                            }

                            echo '<div class="col checkbox-block">';
                                echo '<input type="checkbox" id="'.$row['id'].'" name="'.$row['bestandsnaam'].'-'.$row['id'].'" value="'.$row['id'].'" '.$checked.'>';
                            echo '</div>';
                        }
                    ?>
                    <label class="col cursor" for="<?php echo $row['id']; ?>"><? echo $row['block_naam']; ?></label>
                    <div class="col sm-mob-hide"><? echo $row['bestandsnaam']; ?></div>
                    <div class="col center"><a class="delete" href="<?=$PHP_SELF; ?>?page=blocks&delid=<?=$row['id']; ?>" onclick='return ConfirmDeleteCat();' title="Verwijderen"><span class="fas fa-trash"></span></a></div>
                </div>
            <? } ?>     
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('.checkbox-block input').change(function() {
        var checked = "";
        if(this.checked) { checked = "true"; } else { checked = "false"; }
        var block_id = 'block=' + $(this).attr("id") + '&action=actiefBlock&checked='+checked;
        $.post("dragdrop/update_block_status.php", block_id, function(theResponse) {
            $('#message').html(theResponse);
        });
    });
});
</script>