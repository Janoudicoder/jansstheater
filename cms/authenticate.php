<?php
include 'login/config.php';
include 'login/functions.php';

session_start();

require_once '../vendor/autoload.php';
// Change this to your connection info.

// Try and connect using the info above.
$con = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if ( !isset($_POST['username'], $_POST['password']) ) {
	$_SESSION['inlog_ban'] = true;

    header('Location: index.php');
    exit;
}

if ( isset($_SESSION['inlog_pogingen']) ) {
    $inlog_pogingen = $_SESSION['inlog_pogingen'];

    if (is_int($inlog_pogingen) && $inlog_pogingen >= 4) {
        $_SESSION['inlog_ban'] = true;

        header('Location: index.php');
        exit;
    }
}

$email_POST = $_POST['username'];
$password_POST = $_POST['password'];
$incorrectValues = false;

// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if ($stmt = $con->prepare('SELECT id, password FROM siteworkcms_gebruikers WHERE email = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	// Store the result so we can check if the account exists in the database.
	$stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        // Account exists, now we verify the password.
        // Note: remember to use password_hash in your registration file to store the hashed passwords.
        if (password_verify($_POST['password'], $password)) {
            // Verification success! User has logged-in!
            // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;

            $now = time();
            $ip_address = $_SERVER['REMOTE_ADDR'];

            $auth_gegevens = verificatie_check($id, $mysqli);
            $authenticated = 0;

            if($auth_gegevens['verificatie_actief'] == 1) {
                if($_POST['verstuurAuth'] == 'true') {
                    $googleAuthenticator = new \Sitework\GoogleAuthenticator\GoogleAuthenticator();

                    // Example use of the a PSR-6 cache adapter, in this case, the cache/filesystem adapter
                    // This extension is only installed as require-dev
                    $filesystemAdapter = new \League\Flysystem\Adapter\Local(sys_get_temp_dir()."/");
                    $filesystem = new \League\Flysystem\Filesystem($filesystemAdapter);
                    $pool = new \Cache\Adapter\Filesystem\FilesystemCachePool($filesystem);
                    $googleAuthenticator->setCache($pool);

                    $code = implode('', $_POST['auth-code']);

                    if ($googleAuthenticator->authenticate($auth_gegevens['verificatie_secretkey'], $code)) {
                        $authenticated = 1;
                    } else {
                        echo "<p class=\"auth-fout\">U heeft een foute code opgegeven (".$code."), probeer het opnieuw</p>";
                    }
                }

                if($authenticated == 1) {
                    unset($_SESSION['inlog_pogingen']);

                    $mysqli->query("INSERT INTO sitework_login_log (user_id, time, ip) VALUES ('$id', '$now', '$ip_address')");

                    header('Location: maincms.php');
                    exit;
                }	  
            } else {
                $authenticated = 1;

                if($authenticated == 1) {
                    unset($_SESSION['inlog_pogingen']);

                    $mysqli->query(query: "INSERT INTO sitework_login_log (user_id, time, ip) VALUES ('$id', '$now', '$ip_address')");

                    header('Location: maincms.php');
                    exit;
                }	
            } 
            // header('Location: maincms.php');
            //header('Location: home.php');
        } else {
            // Incorrect password
            $incorrectValues = true;

            $mysqli->query(query: "INSERT INTO sitework_login_fouten (user_id, time, ip) VALUES ('$id', '$now', '$ip_address')");
            
            if(!isset($_SESSION['inlog_pogingen'])) {
                $_SESSION['inlog_pogingen'] = 1;
            } else {
                $_SESSION['inlog_pogingen'] = $_SESSION['inlog_pogingen'] + 1;
            }
        }
    } else {
        // Incorrect username
        $incorrectValues = true;

        $mysqli->query(query: "INSERT INTO sitework_login_fouten (user_id, time, ip) VALUES ('$id', '$now', '$ip_address')");

        if(!isset($_SESSION['inlog_pogingen'])) {
            $_SESSION['inlog_pogingen'] = 1;
        } else {
            $_SESSION['inlog_pogingen'] = $_SESSION['inlog_pogingen'] + 1;
        }
    }

	$stmt->close();
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
    <div id="logincontainer" class="auth-code">
    <?php if(isset($_GET['error']) == '2') { echo 'Autorisatie is onjuist afgerond, probeer het opnieuw'; } ?>
            <form action="authenticate.php" method="post" id="auth_form" name="auth_form" autocomplete="off">
                <? if ($rowinstellingen['branding'] == "sitework") { ?><img src="images/sitework.svg" class="logologin" width="100%"><? } ?>
                <? if ($rowinstellingen['branding'] == "puremotion") { ?><img src="images/puremotion.svg" class="logologin" width="100%"><? } ?>
                <? if ($rowinstellingen['branding'] == "reclamemakers") { ?><img src="images/reclamemakers.svg" class="logologin" width="100%"><? } ?>
                <? if ($rowinstellingen['branding'] == "oviiontwerp") { ?><img src="images/oviiontwerp.svg" class="logologin" width="100%"><? } ?> 

                <?php if($incorrectValues == true){ echo '<span class="inlog-fout">De door u ingevoerde gegevens zijn incorrect. Probeer het nogmaals.</span>'; } ?>
                <?php if($incorrectValues == false): ?>
                    <div class="logindiv auth">
                        <i class="fa fa-key"></i> 
                        <div class="auth_inputs">
                            <input id="auth-code" name='auth-code[]' class='code-input' required/>
                            <input id="auth-code" name='auth-code[]' class='code-input' required/>
                            <input id="auth-code" name='auth-code[]' class='code-input' required/>
                            <input id="auth-code" name='auth-code[]' class='code-input' required/>
                            <input id="auth-code" name='auth-code[]' class='code-input' required/>
                            <input id="auth-code" name='auth-code[]' class='code-input' required/>
                        </div>
                        <button type="submit" class="login-button">Verstuur</button>
                    </div>
                    <input type="hidden" name="verstuurAuth" value="true">
                    <input type="hidden" name="username" value="<?=$email_POST;?>">
                    <input type="hidden" name="password" value="<?=$password_POST;?>">
                <?php endif; ?>
            </form>
        <a href="./index.php?back=ja" class="btn back"></a> 

        <script>
        const inputElements = [...document.querySelectorAll('input.code-input')]

        inputElements.forEach((ele,index)=>{
            ele.addEventListener('keydown',(e)=>{
            // if the keycode is backspace & the current field is empty
            // focus the input before the current. Then the event happens
            // which will clear the "before" input box.
            if(e.keyCode === 8 && e.target.value==='') inputElements[Math.max(0,index-1)].focus()
            })
            ele.addEventListener('input',(e)=>{
            // take the first character of the input
            // this actually breaks if you input an emoji like 👨‍👩‍👧‍👦....
            // but I'm willing to overlook insane security code practices.
            const value = e.target.value;

            if (value !== '' && !/^\d+$/.test(value)) {
                // Display error message or handle the error as needed
                alert('Alleen cijfers zijn toegestaan.');
                e.target.value = '';
                return;
            }

            const [first,...rest] = e.target.value
            e.target.value = first ?? '' // first will be undefined when backspace was entered, so set the input to ""
            const lastInputBox = index===inputElements.length-1
            const didInsertContent = first!==undefined
            if(didInsertContent && !lastInputBox) {
                // continue to input the rest of the string
                inputElements[index+1].focus()
                inputElements[index+1].value = rest.join('')
                inputElements[index+1].dispatchEvent(new Event('input'))
            }
            })
        })
        </script>
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