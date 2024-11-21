<? // checken of men wel is ingelogd
// =================================
login_check_v2(); ?>

<script type="text/javascript">
  // prullenbak legen melding
  // ========================
  function dropdown_validator()
  { return (true); }

  function ConfirmDelete() { 
  return confirm('Weet u zeker dat u alle items definitief wilt verwijderen?'); } 
</script>

<?php
// resultaten bepalen aantal pagina`s
// ==================================
if(is_numeric($_GET['max'])) $max = $_GET['max']; 
if(is_numeric($_GET['start'])) $start = $_GET['start']; 

if (empty($max)) $max = 15;  // $max is max aantal per pagina 
if (empty($start)) $start = 0; // niet editen!

// query berekenen
// ===============
$eind = $start + $max;   // Tot welk record moet ie? 
$vorige = $start - $max;   // Voor de var start voor 'vorige'-link 
$volgende = $eind;   // Voor de var start voor 'volgende'-link 

if($_POST['zoek']) { $zoek = $_POST['zoek']; }
else { $zoek = $_GET['zoek']; }

if($_POST['cat']) { $cat = $_POST['cat']; }
else { $cat = $_GET['cat']; }

$sql_tellen = $mysqli->query("SELECT * FROM siteworkcms WHERE (item1 LIKE '%".$zoek."%' or tekst LIKE '%".$zoek."%' ) AND status = 'prullenbak'") or die($mysqli->error.__LINE__);
$totaal = $sql_tellen->num_rows; 

$aantalPaginas = ceil($totaal/$max);
$welkePagina = ceil($start/$max)+1;
?>

<div class="box-container">
  <div class="box box-full">
  
  <h3><span class="icon fas fa-trash"></span>Prullenbak</h3>
  <a class="btn fl-right mb-10 arrow" href="?page=prullenbak_opschonen" onclick='return ConfirmDelete();'>prullenbak legen</a>
  <div class="info"><span class="far fa-info-circle"></span>&nbsp;&nbsp;Op deze pagina vindt u alle pagina's met de status "prullenbak". Om deze pagina('s) terug te zetten naar een actieve status kunt u op het item klikken en de status wijzigen. Met de knop "prullenbak legen" worden alle prullenbak items in 1x verwijderd.</div>
  <div class="content-container">   	
          
      <div class="row prullenbak type">
        <div class="col">titel</div>
        <div class="col sm-mob-hide">categorie</div>
        <div class="col extra-sm-mob-hide">datum</div>
        <div class="col md-hide">status</div>
        <div class="col center">bewerk</div>
      </div>
          
        <?php
        // items met status prullenbak ophalen
        // ===================================
        $sql = $mysqli->query("SELECT 	*,DATE_FORMAT(laatste_wijziging, '%d-%m-%Y %H:%i') AS datum FROM siteworkcms WHERE (item1 LIKE '%".$zoek."%' or tekst LIKE '%".$zoek."%' ) AND status = 'prullenbak' ORDER BY datum DESC LIMIT ".$start.",".$max."") or die($mysqli->error.__LINE__);
        $rows = $sql->num_rows;
        while ($row = $sql->fetch_assoc()){  

        // gebruiker erbij ophalen
        // =======================
        $resultusers = $mysqli->query("SELECT * FROM siteworkcms_gebruikers WHERE id = '".$row['gebruikersid']."' ") or die($mysqli->error.__LINE__);
        $rowusers = $resultusers->fetch_assoc();
        
        // afbeelding ophalen
        // ==================
        $resultimg = $mysqli->query("SELECT * FROM sitework_img WHERE cms_id = '".$row['id']."'") or die($mysqli->error.__LINE__);
        $rowimg = $resultimg->fetch_assoc(); ?>

        <a href="?page=pagina_bewerken&id=<? echo $row['id']; ?>">
          <div class="row prullenbak">
            <div class="col fat"><? echo $row['item1']; ?></div>
            <div class="col fat sm-mob-hide"><? echo $row['keuze1']; ?></div>
            <div class="col extra-sm-mob-hide"><? echo $row['datum']; ?></div>
            <div class="col md-hide"><? echo $row['status']; ?></div>
            <div class="col center"><span class="far fa-edit"></span></div>
          </div>
        </a>
        <? }
		   
		   if ($rows < 1) { echo '<span class="no-items">Geen items gevonden in de prullenbak</span>'; } ?>
           
        <div id="nummeringwrap">
          <? //paginanummering
          // =================
          $paginaweergave = $aantalPaginas; 
          for($i=0; $i<$paginaweergave; $i++){	?>
                              
            <a class="nummering" href="<?=$PHP_SELF ?>?page=prullenbak&zoek=<?=$zoek; ?>&cat=<?=$cat; ?>&start=<?=$i*$max; ?>&max=<?=$max; ?>"
            <? if($welkePagina == $i+1){ echo "id=\"activenum\""; }?>  ><? echo $i+1 ?></a>
                    
          <? }  ?>
        </div>       
  </div>  
</div>