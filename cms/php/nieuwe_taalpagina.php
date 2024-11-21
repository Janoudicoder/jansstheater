<?php
// database connectie en inlogfuncties
// ===================================
require ("../login/config.php");
include ('../login/functions.php');
session_start();

login_check_v2();
// schone url met toevoegen pagina
// ===============================
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

$sql = $mysqli->query("SELECT * FROM siteworkcms WHERE id = '" . $_GET['cmsid'] . "' ") or die($mysqli->error . __LINE__);
$row = $sql->fetch_assoc();

if($_POST['opslaan'] == 1){

    $sql_insert = $mysqli->query("INSERT sitework_vertaling SET 
                                                                cms_id 	= '" . $_GET['cmsid'] . "',
																veld 	= 'item2',
																waarde	= '".$mysqli->real_escape_string($_POST['item2'])."',
																taal = '".$_GET['taal']."'
																") or die($mysqli->error.__LINE__);
    $rowid = $mysqli->insert_id;

    $sql_insert2 = $mysqli->query("INSERT sitework_vertaling SET 
                                                                cms_id 	= '" . $_GET['cmsid'] . "',
																veld 	= 'paginaurl',
																waarde	= '".strtolower(slugify($_POST['item2']))."',
																taal = '".$_GET['taal']."'
																") or die($mysqli->error.__LINE__);

    $sql_insert3 = $mysqli->query("INSERT sitework_vertaling SET 
                                                                cms_id 	= '" . $_GET['cmsid'] . "',
																veld 	= 'keuze1',
																waarde	= '".$mysqli->real_escape_string($_POST['keuze1'])."',
																taal = '".$_GET['taal']."'
																") or die($mysqli->error.__LINE__);

    $sql_insert4 = $mysqli->query("INSERT sitework_vertaling SET 
                                                                cms_id 	= '" . $_GET['cmsid'] . "',
																veld 	= 'item1',
																waarde	= '".$mysqli->real_escape_string($_POST['item2'])."',
																taal = '".$_GET['taal']."'
																") or die($mysqli->error.__LINE__);
    
    echo "<script>parent.$.fancybox.close();</script>";
}
?>

<TITLE>SiteWork CMS afbeelding upload</TITLE>
<meta charset="UTF-8" />
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/css/stylesheet.css">
<link rel='stylesheet' type='text/css' href='<?php echo $url; ?>/cms/css/branding-stylesheet.php' />
<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/cms/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/font-awesome/css/all.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/cms/css/themify-icons.css">

<script type="text/javascript" src="<?php echo $url; ?>/cms/jquery/files/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="<?php echo $url; ?>/cms/jquery/files/jquery-ui-1-12-1.min.js"></script>

<div class="fancybox-wrap" style="width:100%; height:100%; display:block;">
    <div class="box-container" style="height: 100%;">
        <div class="box box-full">
            <form action="nieuwe_taalpagina.php?cmsid=<?php echo $_GET['cmsid'] ?>&taal=<?=$_GET['taal'];?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="keuze1">Categorie (<?=$_GET['taal'];?>)</label>
                    <select name="keuze1" class="inputveld invoer dropdown">
                        <?php //kenmerken ophalen
                        // ===================
                        $catTaal = $_GET['taal'];
                        $sqlkenmerk = $mysqli->query("SELECT * FROM sitework_categorie ORDER BY categorie ASC") or die($mysqli->error . __LINE__);
                        while ($rowkenmerk = $sqlkenmerk->fetch_assoc()) {
                            $categorie = $rowkenmerk['categorie_'.$catTaal.''];
                            if($row['keuze1'] == $rowkenmerk['categorie']){
                                $active = "selected";
                            }else{
                                $active = "";
                            }
                            ?>
                            <option value="<?php echo $rowkenmerk['categorie']; ?>" <?=$active;?>><?php echo $categorie; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="item2">Pagina titel (<?=$_GET['taal'];?>)</label>
                    <input type="text" name="item2" class="inputveld invoer" placeholder="Paginatitel" value="<? echo stripslashes($_POST['item2']); ?>" />
                </div>
                <input type="hidden" name="opslaan" value="1" >
                <button name="save" class="btn fl-left save" type="submit">Opslaan</button>
            </form>
        </div>
    </div>
</div>

