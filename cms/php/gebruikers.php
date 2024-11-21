<? // checken of men wel is ingelogd
// =================================
login_check_v2(); ?>

<? // melding gebruiker verwijderen
// ================================ ?>
<script type="text/javascript">
  function ConfirmDelete() { 
     return confirm('Weet u zeker dat u deze gebruiker wilt verwijderen?'); 
 }
</script>

<?php
if($_GET['delid'] <> ""){	
  // gebruiker verwijderen
  // =====================
  $mysqli->query("DELETE FROM siteworkcms_gebruikers WHERE id = '".$_GET['delid']."' ") or die($mysqli->error.__LINE__);
  header('Location: '.$PHP_SELF.'');
} ?>

<div class="box-container">
  <div class="box box-2-3 lg-box-full">
    <h3><span class="icon fas fa-users"></span>Gebruikers</h3>
<? if ($rowuser['niveau'] == 'administrator') { ?><a class="btn fl-right nieuw" href="?page=nieuwe_gebruiker">nieuwe gebruiker</a>
    <div class="content-container">    
<? } ?>
      <div class="row gebruikers type">
        <div class="col md-hide">gebruikersnaam</div>
        <div class="col">emailadres</div>
        <div class="col sm-ipad-hide">naam</div>
        <div class="col extra-sm-mob-hide">niveau</div>
        <div class="col extra-sm-mob-hide center">verwijderen</div>
        <div class="col extra-sm-mob-hide center">bewerken</div>
      </div>    
      <? // alle gebruikers ophalen
        // ========================
        if ($rowuser['id'] <> "1") {$hidesuperadmin = "WHERE id <> 1"; } // ons account verbergen voor administrator en gebruikers
        $gebruikers = $mysqli->query("SELECT * FROM siteworkcms_gebruikers $hidesuperadmin ORDER BY niveau,id ASC") or die($mysqli->error.__LINE__);
        while ($rowgebruikers = $gebruikers->fetch_assoc()) { ?>

          <div class="row gebruikers">
            <div class="col md-hide"><? echo $rowgebruikers['username']; ?></div>
            <div class="col"><? echo $rowgebruikers['email']; ?></div>
            <div class="col sm-ipad-hide"><? echo "".$rowgebruikers['voorletters']." ".$rowgebruikers['achternaam'].""; ?></div>
            <div class="col extra-sm-mob-hide"><? echo $rowgebruikers['niveau']; ?></div>
            <div class="col center">
              <?php if ($rowuser['id'] == $rowgebruikers['id'] or $rowuser['niveau'] == "administrator") { // admin account mag niet worden verwijderd door gebruiker ?>
                <a class="delete" href="?page=gebruikers&delid=<?php echo $rowgebruikers['id']; ?>" onclick='return ConfirmDelete();' title="Verwijderen"><span class="far fa-trash"></span></a>
              <?php } ?>
            </div> 
            <div class="col center">
              <?php if ($rowuser['id'] == $rowgebruikers['id'] or $rowuser['niveau'] == "administrator") { // admin account mag niet aangepast worden door gebruiker ?>
                <a class="delete" href="?page=gebruiker_bewerken&id=<?php echo $rowgebruikers['id']; ?>" title="Bewerken"><span class="far fa-edit"></span></a>
              <?php } ?>
            </div>
          </div>
        
        <?php } ?>
        <? if ($rowuser['niveau'] == 'administrator') { ?></div><? } ?>
    </div>
</div>
  