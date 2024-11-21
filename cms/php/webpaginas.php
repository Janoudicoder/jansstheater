<?php // checken of men wel is ingelogd
// ====================================
login_check_v2();

// resultaten bepalen aantal pagina`s
// ==================================
if(is_numeric($_GET['max'])) $max = $_GET['max']; 
if(is_numeric($_GET['start'])) $start = $_GET['start']; 

if (empty($max)) $max = 10;  // $max is max aantal per pagina 
if (empty($start)) $start = 0; // niet editen

// query berekenen
// ===============
$eind = $start + $max; // tot welk record moet ie? 
$vorige = $start - $max; // voor de var start voor 'vorige'-link 
$volgende = $eind; // voor de var start voor 'volgende'-link 

// zoekfunctie
// ===========
if($_POST['zoek']) { $zoek = $_POST['zoek']; }
else { $zoek = $_GET['zoek']; }

if($_POST['sortering']) { $sort = $_POST['sortering']; }
else { $sort = $_GET['sort']; }

if($_POST['cat']) { $cat = $_POST['cat']; }
else { $cat = $_GET['cat']; }

if($_POST['taal']) { $taal = $_POST['taal']; }
else { $taal = $_GET['taal']; }

if (!$sort) { $sortering = 'ORDER BY item1 ASC'; }
if ($sort == 'alfabetischaz'){ $sortering = 'ORDER BY item1 ASC'; }
if ($sort == 'alfabetischza'){ $sortering = 'ORDER BY item1 DESC'; }
if ($sort == 'laatstewijziging'){ $sortering = 'ORDER BY laatste_wijziging DESC'; }

// telquery voor paginanummering
// =============================
$sql_tellen = $mysqli->query("SELECT * FROM siteworkcms WHERE 
(item1 LIKE '%".$zoek."%' or 
item2 LIKE '%".$zoek."%' or
item3 LIKE '%".$zoek."%' or
item4 LIKE '%".$zoek."%' or
item5 LIKE '%".$zoek."%' or
paginaurl LIKE '%".str_replace(' ', '%', $zoek)."%' or  
tekst LIKE '%".$zoek."%')
AND keuze1 LIKE '%".$cat."%'
AND taal LIKE '%".$taal."%'
AND status LIKE  '".$_GET['status']."%'
AND status <> 'prullenbak'
") or die($mysqli->error.__LINE__);
$totaal = $sql_tellen->num_rows;

if($zoek != null && $zoek <> ""):
  $sql_tellenBlocks = $mysqli->query("SELECT * FROM sitework_blocks WHERE titel LIKE '%$zoek%' or tekst LIKE '%$zoek%'") or die($mysqli->error.__LINE__);
  $totaal_blocks = $sql_tellenBlocks->num_rows;
endif;

$aantalPaginas = ceil($totaal/$max);
$welkePagina = ceil($start/$max)+1;

// unset alle post of get voor de rest zoekopdracht
// ================================================
function doUnset()
{
  // $_POST
  unset($_POST['zoek']);
  unset($_POST['sortering']);
  unset($_POST['cat']);
  unset($_POST['taal']);
  // $_GET
  unset($_GET['zoek']);
  unset($_GET['sort']);
  unset($_GET['cat']);
  unset($_GET['taal']);

  header('?page=webpaginas');
}

if(isset($_POST['clear_search']) == '1') {
  doUnset();
}

// status wijzigen
// ===============
if (isset($_GET['statusid']) && isset($_GET['statusnieuw']) ) {
													  
  $sql_status = $mysqli->query("UPDATE siteworkcms SET status = '".$_GET['statusnieuw']."'  WHERE id = '".$_GET['statusid']."' ") or die($mysqli->error.__LINE__);
  $statusrowid = $mysqli->insert_id;  
          
  // pagina redirecten om deze te kunnen bewerken
  // ============================================
  header('Location: ?page=webpaginas&opgeslagen=ja');
}

// Categorie pagina & Nieuwe categorie
// ===================================
if(!$cat){ 
  $catTitel = "webpagina's";
  $catNieuw = "pagina";
  $catLink = "?page=nieuwe_pagina";
} else {
  if($cat == 'pagina') {
    $catNieuw = $cat;
  } else {
    $catNieuw = $cat . " pagina";
  }
  $catTitel = $cat;
  $catLink = "?page=nieuwe_pagina&cat=".$cat;
}

?>
<script type="text/javascript">
function MM_jumpMenu(targ,selObj,restore){
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
</script>

<div class="box-container">
  <div class="box box-full">
    <h3 class="!full"><span class="icon fas fa-copy"></span>Overzicht <?=$catTitel;?> (<?php echo $totaal; ?>) <?php echo ($totaal_blocks > 0) ? '- Tekst in blokken ('.$totaal_blocks.')' : '' ?></h3>
    <a class="btn fl-left nieuw" href="<?=$catLink;?>">Nieuwe <?=$catNieuw;?></a>
    <? // filteropties ?>
    <div class="filters">
      <?php if($zoek != null || $sort != null || $cat != null || $taal != null): ?>
        <form id="clear_filters" method="post" action="?page=webpaginas">
          <div class="inputveld">
            <input type="hidden" name="clear_search" value="1">
            <button type="submit">Reset zoekopdracht</button>
          </div>
        </form>
      <?php endif; ?>
      <form id="filters" method="post" action="?page=webpaginas">            
        <select class="inputveld mr-10 dropdown extra-pad full-mob" name="cat">
            <option value="">alle categorieën</option>
                <?php $sqlcate = $mysqli->query("SELECT * FROM sitework_categorie") or die($mysqli->error.__LINE__);
                while($rowcate = $sqlcate->fetch_assoc()){  ?>                                  
                  <option value="<? echo strtolower($rowcate['categorie']); ?>" <? if ($cat == strtolower($rowcate['categorie'])) { echo "selected"; } ?>><? echo strtolower($rowcate['categorie']); ?></option>
                <? } ?>
        </select>
        <select class="inputveld mr-10 dropdown extra-pad full-mob" name="sortering">
            <option value="">sortering</option>                                
            <option value="alfabetischaz" <? if ($sort == 'alfabetischaz') { echo "selected"; } ?>>alfabetisch A-Z</option>
            <option value="alfabetischza" <? if ($sort == 'alfabetischza') { echo "selected"; } ?>>alfabetisch Z-A</option>
            <option value="laatstewijziging" <? if ($sort == 'laatstewijziging') { echo "selected"; } ?>>laatste wijziging</option>
        </select>
        <? if ($rowinstellingen['meertaligheid'] == 'ja') { ?>
        <!-- <select class="inputveld mr-10 dropdown extra-pad full-mob" name="taal">
            <option value="">alle talen</option> -->
                <?php /* $resulttaal = $mysqli->query("SELECT * FROM sitework_taal WHERE actief = '1'") or die($mysqli->error.__LINE__);
                while($rowtaal = $resulttaal->fetch_assoc()){  */ ?>                                  
                <!-- <option value="<? //echo strtolower($rowtaal['taalkort']); ?>" <? //if ($taal == strtolower($rowtaal['taalkort'])) { echo "selected"; } ?>><? //echo strtolower($rowtaal['taallang']); ?></option> <? //} ?> -->
        <!-- </select> -->
        <? } ?>
        <input name="zoek" type="text" class="inputveld mr-10 full-mob" placeholder="Zoek op trefwoord" value="<? echo $_POST['zoek']; ?>">
        <button type="submit" class="btn fl-left search" name="Submit">Zoek</button>      
      </form>
    </div>
    
 
    <div class="content-container">
      <div class="row webpaginas type<? if ($rowinstellingen['meertaligheid'] == 'nee') { echo ' hidetaal'; }?>">
        <div class="col">menutitel</div>
        <div class="col sm-ipad-hide">auteur</div>
        <div class="col extra-sm-mob-hide">categorie</div>
        <? if ($rowinstellingen['meertaligheid'] == 'ja') { ?><div class="col center">taal</div><? } ?>
        <div class="col sm-ipad-hide md-hide-overzicht large-hide">laatste wijziging</div>
        <div class="col sm-ipad-hide center">actief ja/nee</div>
        <div class="col sm-ipad-hide center">bewerk</div>
      </div>
            
          <?php
          if(isset($_POST['zoek']) || isset($_GET['zoek'])) :
            $sqlzoekBlocks = "SELECT * FROM sitework_blocks WHERE titel LIKE '%$zoek%' or tekst LIKE '%$zoek%'";
            $resultszoekBlocks = $mysqli->query($sqlzoekBlocks) or die($mysqli->error.__LINE__);

            $addPageIDS = $mysqli->query($sqlzoekBlocks) or die($mysqli->error.__LINE__);

            $searchPageIDS = array();
            while($getPage = $addPageIDS->fetch_assoc()){
              if(!in_array($getPage['cms_id'], $searchPageIDS)){
                $searchPageIDS[] = $getPage['cms_id'];
              }
            }
            $filteredIDS = "";
            foreach ($searchPageIDS as $id) {
              $filteredIDS .= '"' . $id . '",';
            }
            // Remove the trailing comma (if there are elements in the array)
            $filteredIDS = rtrim($filteredIDS, ",");

            // Enclose the string in parentheses
            $final_string = "(" . $filteredIDS . ")";
          else:
            $final_string = "";
          endif;

          if($final_string != "" && $final_string != "()"): 
            $checkIDS = "or id IN ".$final_string;
          else:
            $checkIDS = "";
          endif;

          // items ophalen
          // =============
          $sql = $mysqli->query("SELECT *,DATE_FORMAT(laatste_wijziging, '%d-%m-%Y') AS datum_mut,DATE_FORMAT(laatste_wijziging, '%H:%i') AS tijd_mut FROM siteworkcms WHERE 
             (item1 LIKE '%".$zoek."%' or 
              item2 LIKE '%".$zoek."%' or
              item3 LIKE '%".$zoek."%' or
              item4 LIKE '%".$zoek."%' or
              item5 LIKE '%".$zoek."%' or
              paginaurl LIKE '%".str_replace(' ', '%', $zoek)."%' or  
              tekst LIKE '%".$zoek."%' 
              ".$checkIDS." )
              AND keuze1 LIKE '%".$cat."%'
              AND taal LIKE '%".$taal."%'
              AND status LIKE  '".$_GET['status']."%'
              AND status <> 'prullenbak'
              $sortering
              LIMIT ".$start.",".$max."") or die($mysqli->error.__LINE__);

          $rows = $sql->num_rows;
          $rowsBlocks = $resultszoekBlocks->num_rows;

          $pagesIDS = array();

        while ($row = $sql->fetch_assoc()){  
          $pagesIDS[] = $row['id'];

          // gebruiker erbij ophalen
          // =======================
          $resultusers = $mysqli->query("SELECT * FROM siteworkcms_gebruikers WHERE id = '".$row['gebruikersid']."' ") or die($mysqli->error.__LINE__);
          $rowusers = $resultusers->fetch_assoc();
          
          // afbeelding ophalen 
          // ==================
          $resultimg = $mysqli->query("SELECT * FROM sitework_img WHERE cms_id = '".$row['id']."'") or die($mysqli->error.__LINE__);
          $rowimg = $resultimg->fetch_assoc();

          $resultmedia = $mysqli->query("SELECT * FROM sitework_mediabibliotheek WHERE id = '".$rowimg['naam']."'") or die($mysqli->error.__LINE__);
          $rowmedia = $resultmedia->fetch_assoc();

            // gekoppelde pagina ophalen
            // =========================
            $resultkop = $mysqli->query("SELECT item1 FROM siteworkcms WHERE id = '".$row['hoofdid']."'") or die($mysqli->error.__LINE__);
            $rowkop = $resultkop->fetch_assoc(); ?>
            
              <div class="row webpaginas<? if ($rowinstellingen['meertaligheid'] == 'nee') { echo ' hidetaal'; }?>">
                <div class="col">
                <a href="?page=pagina_bewerken&id=<? echo $row['id']; ?>">
                  <? if (!$rowmedia['naam']) { ?><img class="preview-img" src="../cms/images/no-image.jpg" width="80" height="72" /> <? } else { ?>
                  <img class="preview-img" src="../img/<? echo $rowmedia['naam'] . "_tn." . $rowmedia['ext'] ?>" width="80" height="72" border="0" /><? } ?>
                  <span class="page-title"><? echo $row['item1']; ?></span>
                  <span class="page-text"><? echo strip_tags(substr($row['tekst'],0,100), '').'..'; ?></span>
                  <? if ($row['kenmerken']) { echo "<span class=\"page-kenmerken\"><span class=\"ti-tag\"></span>".$row['kenmerken']."</span>"; } ?>
                </a>
                </div>
                <div class="col sm-ipad-hide fat"><? echo $rowusers['username']; ?></div>
                <div class="col extra-sm-mob-hide fat">
                  <? echo $row['keuze1']; ?>
                  <? if ($rowkop['item1']) { ?><span class="page-text"><i class="fa fa-level-down"></i> <? echo $rowkop['item1']; ?></span><? } ?>
                </div>
                  <? if ($rowinstellingen['meertaligheid'] == 'ja') { ?>
                    <div class="col center flags">
                      <img src="../../flags/nl.svg" width="15px" title="Nederlands" />
                      <?php $sqlFlags = $mysqli->query("SELECT * FROM sitework_taal WHERE actief = '1'") or die($mysqli->error . __LINE__);
                      while($rowFlags = $sqlFlags->fetch_assoc()) {
                        $sqlFlagCheck = $mysqli->query("SELECT * FROM sitework_vertaling WHERE cms_id = '".$row['id']."' AND taal = '" . $rowFlags['taalkort'] . "'") or die($mysqli->error . __LINE__);
                        $numberOfRows = $sqlFlagCheck->num_rows;
                        if($numberOfRows > 0) {
                          echo '<img src="../../flags/'.$rowFlags['taalkort'].'.svg" width="15px" title="'.$rowFlags['taallang'].'" />';
                        }
                      } 
                      ?>
                    </div>
                  <? } ?>
                <div class="col fat sm-ipad-hide md-hide-overzicht large-hide"><? echo $row['datum_mut']; ?> <? echo $row['tijd_mut']; ?> uur</div>
              
                <div class="col sm-ipad-hide full center">
                  <form name="form" id="form">
                    <select class="inputveld full dropdown" name="jumpMenu" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)">
                      <option value="maincms.php?page=webpaginas&statusid=<?=$row['id']; ?>&statusnieuw=actief" <? if ($row['status'] == "Actief") {echo "selected"; } ?>>Actief</option>
                        <option value="maincms.php?page=webpaginas&statusid=<?=$row['id']; ?>&statusnieuw=Niet actief" <? if ($row['status'] == "Niet actief") {echo "selected"; } ?>>Niet actief</option>
                        <option value="maincms.php?page=webpaginas&statusid=<?=$row['id']; ?>&statusnieuw=Prullenbak" <? if ($row['status'] == "Prullenbak") {echo "selected"; } ?>>Prullenbak</option>   
                    </select>
                  </form>
                </div>
                
                <div class="col center sm-ipad-hide"><a class="edit" href="?page=pagina_bewerken&id=<? echo $row['id']; ?>"><span class="fas fa-edit"></span></a></div>
                <?php 
                  echo '<div>';
                    if($rowsBlocks && $zoek <> "") { 
                      $resultszoekBlocksByPage = $mysqli->query("SELECT * FROM sitework_blocks WHERE titel LIKE '%$zoek%' or tekst LIKE '%$zoek%'") or die($mysqli->error.__LINE__);
                      $titel = 0;
                      while($rowBlocks = $resultszoekBlocksByPage->fetch_assoc())
                      { 
                        if($titel == 0 && $resultszoekBlocksByPage->num_rows > 0 && in_array($row['id'], $searchPageIDS)):
                          echo '<h4 class="mt-20">Tekst gevonden in block(en):</h4>';
                        endif;
                        if($rowBlocks['cms_id'] == $row['id']) {
                          $rowszoek3 = "SELECT *,DATE_FORMAT(laatste_wijziging, '%d-%m-%Y') AS datum_mut,DATE_FORMAT(laatste_wijziging, '%H:%i') AS tijd_mut FROM siteworkcms WHERE id = '".$rowBlocks['cms_id']."' and status = 'actief'";
                          $resultzoek3 = $mysqli->query($rowszoek3) or die($mysqli->error.__LINE__);
                      
                          while($rowBlockWeb = $resultzoek3->fetch_assoc())
                          {
                            // if(!in_array($rowBlockWeb['id'], $pagesIDS)){
                              $resultBlockTekst = $mysqli->query("SELECT * FROM sitework_block WHERE id = '".$rowBlocks['block_id']."' ") or die($mysqli->error.__LINE__);
                              $rowBlockTekst = $resultBlockTekst->fetch_assoc();

                              echo "<a href=\"?page=pagina_bewerken&id=".$row['id']."#block-cms-".$rowBlocks['id']."\" class=\"page-tekst-gevonden-block\">".$rowBlockTekst['block_naam']."</a><br />"; 
                            // }
                          }
                        }
                        $titel++;
                      }
                    }
                  echo '</div>';
                ?>
              </div>
            
        <? } ?>   

        <div id="nummeringwrap">
          <? // paginanummering
          // ==================
          $paginaweergave = $aantalPaginas; 
          for($i=0; $i<$paginaweergave; $i++){ ?>
                              
            <a class="nummering" href="<?=$PHP_SELF ?>?page=webpaginas&zoek=<? echo $zoek; ?>&cat=<? echo $cat; ?>&sort=<? echo $sort; ?>&taal=<? echo $taal; ?>&start=<? echo $i*$max; ?>&max=<? echo $max; ?>"
            <? if($welkePagina == $i+1){ echo "id=\"activenum\""; }?>  ><? echo $i+1 ?></a>
                              
          <? }  ?>
        </div>       
      </div>
</div>
</div>