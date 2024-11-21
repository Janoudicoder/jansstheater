<? ob_start();
// database connectie en inlogfuncties
// ======================================
include ("../login/config.php");
include ('../login/functions.php');
include ('./blocks/block_translate_functions.php');
session_start();

// checken of men wel is ingelogd
// ==============================
login_check_v2();

// gegevens ophalen
// ================
if($_GET['taal'] != 'nl') { 
  $vertaalID = $_GET['id'];
  $sqlVertaal = $mysqli->prepare("SELECT veld, waarde FROM sitework_vertaling WHERE cms_id = ?");
  $sqlVertaal->bind_param("i", $vertaalID);
  $sqlVertaal->execute();
  $resultVertaal = $sqlVertaal->get_result();

  $row = [];
  $row['id'] = $vertaalID;

  while ($rowVertaal = $resultVertaal->fetch_assoc()) {
      $row[$rowVertaal['veld']] = $rowVertaal['waarde'];
  }
} else {
  $sql = $mysqli->query("SELECT * FROM siteworkcms WHERE id = '".$_GET['id']."' ") or die($mysqli->error.__LINE__);
  $row = $sql->fetch_assoc();
}

// bijwerken
// =========
if($_POST['bijwerken'] == 1 && $_POST['seo_taal'] == 'nl'){

			$sql_update = $mysqli->query("UPDATE siteworkcms SET  meta_titel = '".$mysqli->real_escape_string($_POST['meta_titel'])."', 
												                                    meta_keywords = '".$mysqli->real_escape_string($_POST['meta_keywords'])."',
												                                    meta_beschrijving = '".$mysqli->real_escape_string($_POST['meta_beschrijving'])."' WHERE id = '".$_GET['id']."'") or die($mysqli->error.__LINE__);
									  
			$melding = "De wijzigingen zijn opgeslagen";
      $opgeslagen = "ja";	
      echo "
		<div class=\"alert alert-success fancybox\">
    De wijzigingen zijn opgeslagen
		</div>
	";
} elseif($_POST['bijwerken'] == 1 && $_POST['seo_taal'] != 'nl') {
  foreach ($_POST as $veld => $waarde) {
    if($veld != 'seo_taal' AND $veld != 'bijwerken' AND $veld != 'opslaan' AND $veld != 'afb'){
        $sqlInsertOrUpdate = $mysqli->query("SELECT id FROM sitework_vertaling WHERE veld = '" . $veld . "' AND cms_id = '".$row['id']."' AND taal = '".$_GET['taal']."'") or die($mysqli->error . __LINE__);
        $rowInsertOrUpdate = $sqlInsertOrUpdate->fetch_assoc();

        if($rowInsertOrUpdate['id']){
            $sql_insert = $mysqli->query("UPDATE sitework_vertaling 
                                                    SET 
                                                        cms_id = '" . $row['id'] . "',
                                                        veld = '" . $veld . "',
                                                        waarde = '" . $waarde . "',
                                                        taal = '" . $_GET['taal'] . "'
                                                        WHERE id = '" . $rowInsertOrUpdate['id'] . "'
                     
                     ") or die($mysqli->error . __LINE__);
            $rowid = $mysqli->insert_id;
            $melding = "Gegevens zijn opgeslagen";
        }else{
            $sql_insert = $mysqli->query("INSERT sitework_vertaling 
                                                    SET 
                                                        cms_id = '" . $row['id'] . "',
                                                        veld = '" . $veld . "',
                                                        waarde = '" . $waarde . "',
                                                        taal = '" . $_GET['taal'] . "'
                     
                     ") or die($mysqli->error . __LINE__);
            $rowid = $mysqli->insert_id;
            $melding = "Gegevens zijn opgeslagen";
        }
    }
  }
}

// Meta variabelen
$meta_titel = getTranslation('meta_titel', 'veld', $_GET['taal'], $row['id']);
$meta_keywords = getTranslation('meta_keywords', 'veld', $_GET['taal'], $row['id']);
$meta_beschrijving = getTranslation('meta_beschrijving', 'veld', $_GET['taal'], $row['id']);

$paginaTitel = getTranslation('item2', 'veld', $_GET['taal'], $row['id']);
$meta_url = getTranslation('paginaurl', 'veld', $_GET['taal'], $row['id']);
if($rowinstellingen['meertaligheid'] == 'ja') {
  $urlTaal = $_GET['taal'] . '/';
} else { $urlTaal = ''; }

?>
<TITLE>SiteWork CMS Metatags</TITLE>
<meta charset="UTF-8"/>
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/css/stylesheet.css">
<link rel='stylesheet' type='text/css' href='<? echo $url; ?>/cms/css/branding-stylesheet.php' />
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/font-awesome/css/all.min.css">
<link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/css/themify-icons.css">

<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery-ui-1-12-1.min.js"></script>
<script>
	// Alert voor opgeslagen
    // ======================
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove(); 
        });
    }, 4000);

    $(".alert").on("click", function () {
        $( this ).fadeTo( "slow", 0 );
	});
</script>
<script>
  // tellen van karakters t.b.v. metatags
    // ====================================
    function countChar(val){
        var len = val.value.length;
          if (len >= 170) {
            val.value = val.value.substring(0, 170);
            }else {
            $('#charNum').text(170 - len);
          }
    };

    // metatitel wijzigen onchange
    // ===========================
    $(function() {
        var myMetaTitle = document.getElementById("myMetaTitle");
        if (myMetaTitle) {
        myMetaTitle.onkeyup = function(){ 
          var output = document.getElementById("output");
           output.innerHTML = this.value + ' | <? echo $sitenaam; ?>';
        }}
    });

    // meta omschrijving wijzigen onchange
    // ===================================
    $(function() {
        var myMetaDesc = document.getElementById("myMetaDesc");
        if (myMetaDesc) {
        myMetaDesc.onkeyup = function(){
          var output2 = document.getElementById("output2");
           output2.innerHTML = this.value;
        }}
    });
</script>
<div class="fancybox-wrap">
	<div class="box-container">
  	<div class="box box-full">
      <h3>
        <?php echo ($_GET['taal'] != 'nl') ? '<img src="/flags/'.$_GET['taal'].'.svg" alt="'.$_GET['taal'].'" width="25px" height="20px" class="icon" />' : '<span class="icon fas fa-search"></span>'?>
        Metatags
      </h3>
      <form action="<?=$PHP_SELF; ?>?id=<? echo $_GET['id']; ?>&taal=<? echo $_GET['taal'] ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
        <label for="meta_titel">Metatitel</label><input name="meta_titel" value="<? echo $meta_titel;  ?>" type="text" id="myMetaTitle" class="inputveld invoer small-70" placeholder="Titel zichtbaar in zoekmachines" maxlength="<?=(65 - strlen($sitenaam));?>"  /><span class="fl-right form-label langer rounded" style="background-color: white;">Tussen de 20 & 60 tekens</span>
        </div>
        <div class="form-group">
          <label for="meta_keywords">Meta keywords</label><input name="meta_keywords" value="<? echo $meta_keywords;  ?>" type="text" id="meta_keywords"  class="inputveld invoer" placeholder="Let op! zet tussen de keywords een komma. (max 20 woorden)" maxlength="60"  />
        </div>
        <div class="form-group">
          <label for="meta_beschrijving">Meta omschrijving</label>
          <div id="site_chips">
            <span id="siteTitle" class="chip" data-text="<?=$sitenaam;?>">Site titel</span>
            <span id="pageTitle" class="chip" data-text="<?=$paginaTitel;?>">Pagina titel</span>
            <?php if($meta_titel): ?> <span id="metaTitle" class="chip" data-text="<?=$meta_titel;?>">Meta titel</span> <?php endif; ?>
          </div>
          <textarea style="resize:none;" name="meta_beschrijving" onkeyup="countChar(this)" cols="60" id="myMetaDesc" class="textveld invoer" placeholder="Korte omschrijving" rows="6" ><? echo $meta_beschrijving;  ?></textarea>
          <div id="progressBar">
              <div id="progress"></div>
          </div>
          <span id="maxtekens" class="maxtekens">Gebruik max 220 tekens!</span>
        </div> 

        <h3><span class="icon fab fa-google"></span>Voorbeeld in zoekmachines</h3>
        <div class="google-voorbeeld">
          <div class="metatitle" id="output"><?php if ($meta_titel) { echo  $meta_titel; } else echo $row['item1']; ?> | <? echo $sitenaam; ?></div>
          <div class="metaurl"><?php echo $url; ?>/<?=$urlTaal;?><?php if ($meta_url) { echo strtolower(str_replace(" ", "-", $meta_url)); } else echo strtolower(str_replace(" ", "-", $row['item1'])); ?></div>
          <div class="metadesc" id="output2">
            <? if ($meta_beschrijving) { echo $meta_beschrijving; } 
              else echo substr(strip_tags($row['tekst'],""),0,170);  
            ?>
          </div>
        </div>
        <input type="hidden" name="afb" value="<? echo $_GET['id'] ?>" >
        <input type="hidden" name="seo_taal" value="<? echo $_GET['taal'] ?>">
        <input type="hidden" name="bijwerken" value="1">
        <button name="opslaan" class="btn fl-left save" type="submit">Opslaan</button>
      </form>
    </div>
  </div>
</div>

<script>
    const metaDescription = document.getElementById('myMetaDesc');
    const progressBar = document.getElementById('progress');
    const feedback = document.getElementById('maxtekens');
    const chips = document.querySelectorAll('.chip');

    function updateProgress() {
      const length = metaDescription.value.length;
      let feedbackText = '';
      let progressWidth = 0;
      let progressColor = 'green';

      if (length <= 1) {
          feedbackText = "Gebruik max 220 tekens!";
      } else if (length < 50) {
          feedbackText = "Te kort (minder dan 50 karakters).";
          progressWidth = (length / 50) * 100;
          progressColor = 'red';
      } else if (length <= 150) {
          feedbackText = "Acceptabel, maar langer wordt aangeraden (50-150 karakters).";
          progressWidth = ((length - 50) / 100) * 100;  // Correctie van de berekening
          progressColor = 'orange';
      } else if (length <= 220) {
          feedbackText = "Goede beschrijving (150-220 karakters).";
          progressWidth = ((length - 150) / 70) * 100;  // Correctie van de berekening
          progressColor = 'green';
      } else if (length <= 300) {
          feedbackText = "Acceptabel, maar korter wordt aangeraden (220-300 karakters).";
          progressWidth = ((length - 220) / 80) * 100;  // Correctie van de berekening
          progressColor = 'orange';
      } else {
          feedbackText = "Te lang (meer dan 300 karakters).";
          progressWidth = 100;
          progressColor = 'red';
      }

      progressBar.style.width = progressWidth + '%';
      progressBar.style.backgroundColor = progressColor;
      feedback.textContent = feedbackText;
    }

    updateProgress();

    metaDescription.addEventListener('input', updateProgress);

    chips.forEach(function(chip) {
      chip.addEventListener('click', function() {
        const textToAdd = chip.getAttribute('data-text') || chip.innerText;
        metaDescription.value += textToAdd + ' ';
        updateProgress(); // Update progress after modifying the textarea content
      });
    });
</script>