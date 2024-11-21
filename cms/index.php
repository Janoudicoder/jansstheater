<? 
include 'login/config.php';
include 'login/functions.php';
session_start();

error_reporting(E_ALL ^ E_NOTICE);

$error = '';

// uitloggen
// =========
if ($_GET['back'] == 'ja') { 
  // session_destroy(); 
  unset($_SESSION['loggedin'], $_SESSION['name'], $_SESSION['id']);
  $error = "U bent succesvol uitgelogd!"; 

  header('Location: index.php');
}

if ($_GET['uitloggen'] == 'ja') { 
  session_destroy(); 
  header('Location: index.php');
}

// Ban Time
// ========
if (isset($_SESSION['ban_expiration'])) {
  $current_time = time();
  $ban_expiration = $_SESSION['ban_expiration'];
  
  // Calculate the remaining time in seconds
  $remaining_time = $ban_expiration - $current_time;
  
  if ($remaining_time > 0) {
    // Convert remaining time to minutes and seconds
    $minutes = floor($remaining_time / 60);
    $seconds = $remaining_time % 60;

    $formatted_time = sprintf("%02d:%02d", $minutes, $seconds);
  } else {      
    unset($_SESSION['ban_expiration'], $_SESSION['inlog_pogingen'], $_SESSION['inlog_ban']);
  }
}

// Inlog ban
// =========
if(isset($_SESSION['inlog_ban']) == true) {
  if(!isset($_COOKIE['login-ban'])) {
    $ckie_name = 'login-ban';
    $ckie_minval = '5 Minuten ban';

    setcookie($ckie_name, $ckie_minval, time() + 300);
    $_SESSION['ban_expiration'] = time() + 300;
  }
}
	
?>

<!DOCTYPE html>
<html xmlns="https://www.w3.org/1999/xhtml" xml:lang="nl" lang="nl">
  <head>
    <title><? if ($rowinstellingen['branding'] == "oviiontwerp") { echo 'Ovii Ontwerp CMS'; } 
    elseif ($rowinstellingen['branding'] == "puremotion") { echo 'Puremotion CMS'; }
    elseif ($rowinstellingen['branding'] == "reclamemakers") { echo 'Reclamemakers CMS'; }
    else { echo 'Sitework CMS'; } ?> | <? echo $sitenaam; ?></title>
    <meta charset="UTF-8"/>
    <meta name="author" content="SiteWork BV">
    <meta name="robots" content="noindex, nofollow" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="shortcut icon" href="https://www.sitework.nl/favicon.ico"/>
    <link rel="icon" type="image/vnd.microsoft.icon" href="https://www.sitework.nl/favicon.ico"/>
    <link rel="stylesheet" href="<? echo $url; ?>/cms/css/stylesheet.css"  type="text/css">
    <link rel='stylesheet' href='<? echo $url; ?>/cms/css/branding-stylesheet.php' type='text/css' />
    <link rel="stylesheet" href="<? echo $url; ?>/cms/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/font-awesome/css/all.min.css">
    
  </head>
  <body >
  
  <? $queryback = $mysqli->query("SELECT * FROM siteworkcms_background WHERE status = 'actief' order by RAND()") or die($mysqli->error.__LINE__);
    $rowbackground = $queryback->fetch_assoc(); ?>

    <? if ($rowbackground){ ?>
    <div id="page-background" style="background-image: url(../img/<? echo $rowbackground['naam'];?>);"></div>
    <? } else { 
        // standaard achtergrond sitework
        if ($rowinstellingen['branding'] == "sitework") { ?>
            <div id="page-background" style="background-image: url(../cms/images/backgrounds/sitework-default.jpg);"></div>
      <? } ?>
      <? // standaard achtergrond puremotion
      if ($rowinstellingen['branding'] == "puremotion") { ?>
            <div id="page-background" style="background-image: url(../cms/images/backgrounds/puremotion-default.jpg);"></div>
      <? } ?>
      <? // standaard achtergrond reclamemakers
      if ($rowinstellingen['branding'] == "reclamemakers") { ?>
            <div id="page-background" style="background-image: url(../cms/images/backgrounds/reclamemakers-default.jpg);"></div>
      <? } ?>
      <? // standaard achtergrond oviiontwerp
      if ($rowinstellingen['branding'] == "oviiontwerp") { ?>
            <div id="page-background" style="background-image: url(../cms/images/backgrounds/oviiontwerp-default.jpg);"></div>
      <? } ?>
    <? } ?>
    <div id="background-overlay"></div>
    <div id="logincontainer" <?php echo ($_SESSION['showAUTH'] == '1' || $_SESSION['showAUTH'] == 1) ? 'class="auth-code"' : '' ?>>
        <?php if(isset($_GET['error'])) { $error = 'Foutieve of onbekende inlogcombinatie!'; } ?>
        
        <form action="authenticate.php" method="post" name="login_form" autocomplete="off">
            <? if ($rowinstellingen['branding'] == "sitework") { ?><img src="images/sitework.svg" class="logologin" width="100%"><? } ?>
            <? if ($rowinstellingen['branding'] == "puremotion") { ?><img src="images/puremotion.svg" class="logologin" width="100%"><? } ?>
            <? if ($rowinstellingen['branding'] == "reclamemakers") { ?><img src="images/reclamemakers.svg" class="logologin" width="100%"><? } ?>
            <? if ($rowinstellingen['branding'] == "oviiontwerp") { ?><img src="images/oviiontwerp.svg" class="logologin" width="100%"><? } ?> 
            <div class="inlogkop">Inloggen CMS</div> <? if(isset($_GET['error']) OR $error <> '') { echo "<div class=\"foutlogin\">".$error."</div>"; } ?>
            
            <? if(isset($_SESSION['ban_expiration']) && $remaining_time > 0) { echo "<div class=\"foutlogin\">U bent gebanned van inloggen voor: " . $formatted_time . "</div>"; } ?>
            
            <div class="logindiv">
              <i class="fa fa-user"></i> <input type="text" id="username" name="username" class="tekstvak_medium" placeholder="E-mailadres" />
            </div>

            <div class="logindiv">
              <i class="fa fa-lock"></i> <input type="password" name="password" id="password" class="tekstvak_medium" placeholder="Wachtwoord" />
              <span id="checkPass" onclick="showPass()"><i id="eye" class="fa fa-eye"></i></span>
            </div>        

            <button type="submit" name="mySubmit" value="" class="login-button" onFocus="setFocus()">&raquo;</button>
        </form> 
  </div>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript">
    $(function() {
        $("input:text:visible:first").focus();
      });
    function setFocus(){
        document.login_form.mySubmit.focus();
    }
    function showPass() {
      var x = document.getElementById("password");
      var icon = document.getElementById("eye");
      if (x.type === "password") {
        icon.classList.add("fa-eye-slash");
        x.type = "text";
      } else {
        x.type = "password";
        icon.classList.remove("fa-eye-slash");
      }
    }
    </script>
  </body>
</html>

