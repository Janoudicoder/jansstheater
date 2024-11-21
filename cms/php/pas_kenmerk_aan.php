<?php
// database connectie en inlogfuncties
// ===================================
require ("../login/config.php");
include ('../login/functions.php');
session_start();
login_check_v2();

$kenmerk = $_GET['kenmerk'];
?>

<script>
// inline editen url van kanaal
// ===========================
	function showEdit(editableObj) {
		$(editableObj).css("background","#FFF");
	}

	function saveToDatabase(editableObj,catTaal,id) {
		$(editableObj).css("background","#FFF url(/cms/editinplace/loaderIcon.gif) no-repeat right");
		$.ajax({
			url: "/cms/editinplace/saveedit_kenmerken.php",
			type: "POST",
			data:'catTaal='+catTaal+'&editval='+editableObj.value+'&id='+id,
			success: function(data){
				$(editableObj).css("background","#FDFDFD");
			}
		});
	}

</script>

<?php
// if($_POST['opslaan'] == 1){

//     $sql_insert = $mysqli->query("INSERT sitework_website_settings SET 
//                                                                 tekst 	= '" . $_POST['footertekst'] . "',
// 																taal = '".$_POST['footertaal']."'
// 																") or die($mysqli->error.__LINE__);
//     $rowid = $mysqli->insert_id;
    
//     echo "opgeslagen";

//     echo "<script>parent.$.fancybox.close();</script>";
// }
?>

<TITLE>SiteWork CMS afbeelding upload</TITLE>
<meta charset="UTF-8" />
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/css/stylesheet.css">
<link rel='stylesheet' type='text/css' href='<? echo $url; ?>/cms/css/branding-stylesheet.php' />

<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/datepick/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/font-awesome/css/all.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,200i,300,300i,400,400i,600,600i,700,700i,900,900i">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Khand:300,400,500,600,700">

<script type="text/javascript" src="<?php echo $url; ?>/cms/jquery/files/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="<?php echo $url; ?>/cms/jquery/files/jquery-ui-1.10.2.js"></script>

<div class="fancybox-wrap" style="width:100%; height:100%; display:block;">
    <div class="box-container" style="height: 100%;">
        <div class="box box-full">
            <h3><i class="icon fas fa-language"></i>Vertaal kenmerk: <?=ucfirst($kenmerk);?></h3>
            <?php
            $sqlCategorie = $mysqli->query("SELECT * FROM sitework_kenmerken WHERE kenmerk = '" . $kenmerk . "'") or die($mysqli->error . __LINE__);
            $rowsCategorie = $sqlCategorie->num_rows;

            while ($rowCategorie = $sqlCategorie->fetch_assoc()) {
                $ignoredColumns = ['id', 'kenmerk', 'datum_aangemaakt']; // Array of columns to exclude
                foreach ($rowCategorie as $columnName => $columnValue) {
                    if (!in_array($columnName, $ignoredColumns)) {
                        $language = explode('_', $columnName)[1];
                        $actiefTaalCheck = $mysqli->query("SELECT * FROM sitework_taal WHERE taalkort = '" . $language . "'") or die($mysqli->error . __LINE__);
                        $rowactiefTaalCheck = $actiefTaalCheck->fetch_assoc();

                        if($rowactiefTaalCheck['actief'] == '1'):
                        ?>
                            <div class="form-group">
                                <label class="col"><img src="/flags/<?=$language;?>.svg" alt="" width="30px"></label>
                                <input class="col left inputveld invoer" contenteditable="true"
                                    onBlur="saveToDatabase(this, '<?php echo $columnName; ?>', '<?php echo $rowCategorie["id"]; ?>')"
                                    onClick="showEdit(this);"
                                    <?php echo ($columnValue != "") ? 'value="'.$columnValue . '"' : 'placeholder="Voer kenmerk vertaling in"'; ?>
                                    >
                            </div>
                        <?php
                        endif;
                    }
                }
            }
            ?>
        </div>
    </div>
</div>

