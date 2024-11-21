<? // checken of men wel is ingelogd
// =================================
login_check_v2(); ?>

<script>
// categorie verwijderen melding
// =============================
    function ConfirmDeleteCat() {
    return confirm('Weet u zeker dat u deze categorie wilt verwijderen?'); 
    } 
</script>

<? // categorie toevoegen
// ======================
if($_POST['toevoegen'] == 1 && $_POST['naam']){

	$sql_ins = $mysqli->query("INSERT INTO sitework_categorie SET categorie = '".$_POST['naam']."'") or die($mysqli->error.__LINE__);
	echo "
    <div class=\"alert alert-success\">
      Categorie toegevoegd
    </div>
  ";
}

if($_POST['toevoegen'] == 1 && !$_POST['naam']){ 
  echo "
    <div class=\"alert alert-error\">
      Voor een categorie naam in
    </div>
  ";
}

// categorie verwijderen
// =====================
if ($_GET['delid']) { 
				$mysqli->query("DELETE FROM sitework_categorie WHERE id = '".$_GET['delid']."' ") or die($mysqli->error.__LINE__);
				header('Location: maincms.php?page=categorie');
}

if($rowinstellingen['meertaligheid'] == 'ja') {
  $talenEdit = 'taal_edit';
} else { $talenEdit = ''; }

if($rowuser['id'] != '1' && $rowuser['niveau'] <> 'agency') {
  $geenAdmin = 'user';
} else { $geenAdmin = ''; }
?>
<div class="box-container">
  <div class="box box-2-3 lg-box-full">
    <h3><span class="icon fas fa-tags"></span>Categorieën</h3>
      <?php if($rowuser['id'] == '1' || $rowuser['niveau'] == 'agency'): ?>
        <a href="" class="clickme btn fl-right nieuw">Nieuwe categorie</a>
      <?php endif; ?>
      <div class="toggle-box">
        <form action="<?=$PHP_SELF; ?>" method="post" enctype="multipart/form-data" name="form1">
            <input type="text" tabindex="1" name="naam"  class="inputveld mr-10" id="naam"  placeholder="Naam categorie" />
            <input type="hidden" name="toevoegen" value="1">
            <input name="button3" type="submit" class="btn fl-left" id="button" value="toevoegen">
        </form>
      </div>
    

  <div class="content-container">    	
    
    <div class="row categorieen type <?=$talenEdit;?> <?=$geenAdmin;?>">
      <div class="col">naam categorie</div>
      <div class="col sm-mob-hide">datum</div>
      <?php if($rowinstellingen['meertaligheid'] == 'ja'): ?>
        <div class="col center">Talen</div>
      <?php endif; ?>
      <?php if($rowuser['id'] == '1'): ?>
        <div class="col center">verwijderen</div>
      <?php endif; ?>
    </div>
          
    <?php $sql = $mysqli->query("SELECT *,DATE_FORMAT(datum_aangemaakt, '%d-%m-%Y') AS datum FROM sitework_categorie ORDER BY categorie LIMIT 100") or die ($mysqli->error.__LINE__);		
		$rows = $sql->num_rows;

    while ($row = $sql->fetch_assoc()){ ?>

        <div class="row categorieen <?=$talenEdit;?> <?=$geenAdmin;?>">
          <div class="col"><? echo $row['categorie']; ?></div>
          <div class="col sm-mob-hide"><? echo $row['datum']; ?></div>
          <?php if($rowinstellingen['meertaligheid'] == 'ja'): ?>
            <div class="col taal_edit center">
              <a data-fancybox data-small-btn="true" data-type="iframe" href="/cms/php/pas_categorie_aan.php?cat=<?=$row['categorie'];?>"><i class="fas fa-edit"></i></a>
            </div>
          <?php endif; ?>
          <?php if($rowuser['id'] == '1'): ?>
            <div class="col center"><a class="delete" href="<?=$PHP_SELF; ?>?page=categorie&delid=<?=$row['id']; ?>" onclick='return ConfirmDeleteCat();' title="Verwijderen"><span class="fas fa-trash"></span></a></div>
          <?php endif; ?>
        </div>

      <? } ?>     
    </div>

  </div>
</div>

  

