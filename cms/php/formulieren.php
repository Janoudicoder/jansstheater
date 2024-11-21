<? // checken of men wel is ingelogd
// =================================
login_check_v2(); ?>

<? // melding gebruiker verwijderen
// ================================ ?>
<script type="text/javascript">
  function ConfirmDelete() { 
     return confirm('Weet u zeker dat u dit formulier wilt verwijderen?'); 
 }
</script>

<?php

// formulier toevoegen
// ======================
if($_POST['toevoegen'] == 1 && $_POST['naam']){

	$sql_ins = $mysqli->query("INSERT INTO sitework_formulieren 
                                    SET naam = '".$_POST['naam']."',
                                        datum = NOW(),
                                        auteur = '".$_SESSION['id']."'
                            ") or die($mysqli->error.__LINE__);
	echo "
    <div class=\"alert alert-success\">
      Formulier toegevoegd
    </div>
  ";
}

if($_POST['toevoegen'] == 1 && !$_POST['naam']){ 
  echo "
    <div class=\"alert alert-error\">
      Voer een formulier naam in
    </div>
  ";
}

if($_GET['delid'] <> ""){	
  // formulier verwijderen
  // =====================
  $mysqli->query("DELETE FROM sitework_formulieren WHERE id = '".$_GET['delid']."' ") or die($mysqli->error.__LINE__);
  header('Location: maincms.php?page=formulieren');
} ?>

<div class="box-container">
  <div class="box box-2-3 lg-box-full">
    <h3><span class="icon fas fa-envelope"></span>Formulieren</h3>
      <?php 
      if($rowinstellingen['formuliermodule'] == "nee"){
        echo "<a class=\"btn fl-right nieuw disabled\">nieuw formulier</a>";
        echo "<div class=\"info full\">Wilt u zelf nieuwe formulieren kunnen aanmaken? Neem dan contact op met Sitework via support@sitework.nl of 0573 200 100</div>";

      }else{
        echo "<a class=\"clickme btn fl-right nieuw\" href=\"\">nieuw formulier</a>";
      }
      ?>
      <div class="toggle-box">
        <form action="<?=$PHP_SELF; ?>" method="post" enctype="multipart/form-data" name="form1">
            <input type="text" tabindex="1" name="naam"  class="inputveld mr-10" id="naam"  placeholder="Naam formulier" />
            <input type="hidden" name="toevoegen" value="1">
            <input name="button3" type="submit" class="btn fl-left" id="button" value="toevoegen">
        </form>
      </div>
    <div class="content-container">    
      <div class="row formulieren type">
        <div class="col md-hide">Formulier</div>
        <div class="col">Datum aanmaken</div>
        <div class="col sm-ipad-hide">Auteur</div>
        <div class="col extra-sm-mob-hide center">verwijderen</div>
        <div class="col extra-sm-mob-hide center">bewerken</div>
      </div>    
      <? // alle gebruikers ophalen
        // ========================
        $formulieren = $mysqli->query("SELECT * FROM sitework_formulieren ORDER BY id ASC") or die($mysqli->error.__LINE__);
        while ($rowformulieren = $formulieren->fetch_assoc()) { 
            $resultusers = $mysqli->query("SELECT * FROM siteworkcms_gebruikers WHERE id = '".$rowformulieren['auteur']."' ") or die($mysqli->error.__LINE__);
            $rowusers = $resultusers->fetch_assoc();
            ?>

          <div class="row formulieren">
            <div class="col md-hide"><? echo $rowformulieren['naam']; ?></div>
            <div class="col"><? echo $rowformulieren['datum']; ?></div>
            <div class="col sm-ipad-hide"><? echo $rowusers['username']; ?></div>
            <div class="col center">
              <?php if ($rowformulieren['id'] != 1) { // admin account mag niet worden verwijderd door gebruiker ?>
                <a class="delete" href="?page=formulieren&delid=<?php echo $rowformulieren['id']; ?>" onclick='return ConfirmDelete();' title="Verwijderen"><span class="ti-trash"></span></a>
              <?php } ?>
            </div> 
            <div class="col center">
              <?php if ($rowuser['niveau'] == "administrator") { // admin account mag niet aangepast worden door gebruiker ?>
                <a class="delete" href="?page=formulier_bewerken&id=<?php echo $rowformulieren['id']; ?>" title="Bewerken"><span class="ti-pencil-alt"></span></a>
              <?php } ?>
            </div>
          </div>
        
        <?php } ?>
        <? if ($rowuser['niveau'] == 'administrator') { ?></div><? } ?>
    </div>
    <?php if($rowuser['id'] == '1'): 
      $formLogs = $mysqli->query("SELECT form_id,datum_verzending,ipadres FROM sitework_formulieren_log ORDER BY datum_verzending DESC LIMIT 10") or die($mysqli->error.__LINE__);
      ?>
      <div class="box box-1-3 lg-box-full title">
        <h3><span class="icon fas fa-clipboard-list"></span>Formulieren logs</h3>
        <a data-fancybox data-small-btn="true" data-type="iframe" href="/cms/php/formlogs.php" href="javascript:;" class="btn fl-right">Bekijk logs</a>
        <div class="row formlogs-klein type">
          <div class="col">Formulier</div>
          <div class="col">Datum verzonden</div>
          <div class="col">IP adres</div>
        </div>
        <?php while($rowformLogs = $formLogs->fetch_assoc()): 
          $form = $mysqli->query("SELECT naam FROM sitework_formulieren WHERE id = '".$rowformLogs['form_id']."'") or die($mysqli->error.__LINE__);
          $rowFormNaam = $form->fetch_assoc();
          ?>
          <div class="row formlogs-klein">
            <div class="col"><?=$rowFormNaam['naam'];?></div>
            <div class="col"><?=$rowformLogs['datum_verzending'];?></div>
            <div class="col"><?=$rowformLogs['ipadres'];?></div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>
</div>
  