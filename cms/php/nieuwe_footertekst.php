<?php
// database connectie en inlogfuncties
// ===================================
require ("../login/config.php");
include ('../login/functions.php');
session_start();

if($_POST['opslaan'] == 1){

    $sql_insert = $mysqli->query("INSERT sitework_website_settings SET 
                                                                kolom_1     = '".str_replace('\r\n', '', $mysqli->real_escape_string($_POST['footer-kol-1'])) . "',
                                                                kolom_2     = '".str_replace('\r\n', '', $mysqli->real_escape_string($_POST['footer-kol-2'])) . "',
                                                                kolom_3     = '".str_replace('\r\n', '', $mysqli->real_escape_string($_POST['footer-kol-3'])) . "',
                                                                kolom_4     = '".str_replace('\r\n', '', $mysqli->real_escape_string($_POST['footer-kol-4'])) . "',
																taal = '".$_POST['footertaal']."'
																") or die($mysqli->error.__LINE__);
    $rowid = $mysqli->insert_id;
    
    echo "opgeslagen";

    echo "<script>parent.$.fancybox.close();</script>";
}
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

<link rel="stylesheet" href="<? echo $url; ?>/cms/richtexteditor/rte_theme_default.css" />
<script type="text/javascript" src="<? echo $url; ?>/cms/richtexteditor/rte.js"></script>
<script type="text/javascript" src='<? echo $url; ?>/cms/richtexteditor/plugins/all_plugins.js'></script>
<script type='text/javascript' src="<? echo $url; ?>/cms/richtexteditor/lang/rte-lang-nl.js"></script>

<script type="text/javascript" src="<?php echo $url; ?>/cms/jquery/files/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="<?php echo $url; ?>/cms/jquery/files/jquery-ui-1.10.2.js"></script>

<div class="fancybox-wrap" style="width:100%; height:100%; display:block;">
    <div class="box-container" style="height: 100%;">
        <div class="box box-full">
            <form id="new-footer-cols" action="nieuwe_footertekst.php?taal<?=$_GET['taal'];?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="footertekst" style="display: flex;"><img src="../../flags/<?=$_GET['taal'];?>.svg" alt="<?=$_GET['taal'];?>" style="margin-right: 1rem;width: 30px;">Footer tekst</label>
                    <div class="footer-kolommen">

                        <div id="footer-kol-editor-1" style="min-height:400px;max-height:800px" class="inputveld invoer sitework-editor">
						</div>

						<div id="footer-kol-editor-2" style="min-height:400px;max-height:800px" class="inputveld invoer sitework-editor">
						</div>

						<div id="footer-kol-editor-3" style="min-height:400px;max-height:800px" class="inputveld invoer sitework-editor">
						</div>

						<div id="footer-kol-editor-4" style="min-height:400px;max-height:800px" class="inputveld invoer sitework-editor">
						</div>

					</div>
                </div>

                <input type="hidden" id="footer-kol-1" name="footer-kol-1">
				<input type="hidden" id="footer-kol-2" name="footer-kol-2">
				<input type="hidden" id="footer-kol-3" name="footer-kol-3">
				<input type="hidden" id="footer-kol-4" name="footer-kol-4">

                <input type="hidden" name="opslaan" value="1" >
                <input type="hidden" name="footertaal" value="<?=$_GET['taal'];?>">
                <button name="save" class="btn fl-left save" type="submit">Opslaan</button>
            </form>
        </div>
    </div>
</div>

<?php 
    include '../richtexteditor/footer-editor.php';
?>
<script>
    document.getElementById('new-footer-cols').addEventListener('submit', function(event) {
        save_and_strip('footer-kol-1', editorFooter1);
        save_and_strip('footer-kol-2', editorFooter2);
        save_and_strip('footer-kol-3', editorFooter3);
        save_and_strip('footer-kol-4', editorFooter4);
    });
</script>