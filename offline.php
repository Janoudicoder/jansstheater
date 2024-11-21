<?php
require("cms/login/config.php");
session_start();
ob_start();

## eerst checken of zit online staat ##
$sitelive = $mysqli->query("SELECT * FROM sitework_settings WHERE id = '1'") or die($mysqli->error.__LINE__);
$live = $sitelive->fetch_assoc();
if ($live['offline'] == "nee"){ header('Location: ./'); }

//*********************************************************************************************************		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" lang="nl"  >
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Offline</title>
	<meta http-equiv="content-language" content="nl"/>
	<meta http-equiv="content-style-type" content="text/css"/>
	<meta http-equiv="content-script-type" content="text/javascript"/>
	<meta name="robots" content="all"/>
	<meta name="language" content="nederlands"/>
	<meta name="subject" content="">
	<meta name="author" content="SiteWork Lochem - 0573 200 100"/>
	<meta name="robots" content="ALL,INDEX,FOLLOW"/>
	<meta name="revisit-after" content="1"/>
	<meta http-equiv="Cache-Control" content="must-revalidate"/>
    <link rel="canonical" href="<?php echo $url; ?>/" />
	<link rel="shortcut icon" href="<?php echo $url; ?>/favicon.ico"/>
	<link rel="icon" type="image/vnd.microsoft.icon" href="<?php echo $url; ?>/favicon.ico"/>

	<!-- stylesheets -->
		<link href="<?php echo $url; ?>/css/settings.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $url; ?>/css/stylesheet.min.css" rel="stylesheet" type="text/css" />
	<!-- stylesheets -->

</head>

<body style="height:100vh; width:100%;background: linear-gradient(150deg, rgba(var(--rgb-primary), 0.5) 0%, rgba(var(--rgb-lightGray), 1) 50%, rgba(var(--rgb-secondary), 0.5) 100%);">
<div style="text-align:center; font-family:Arial, Helvetica, sans-serif; color:#999; top:50%; left:50%; margin-left:-250px; margin-top:-50px; width:500px; position:absolute; font-size:15px;">
	<strong><i><?php echo $live['offline_tekst']; ?> </i></strong><br><br />
    <a href="./" style="display:block;aspect-ratio:16/9;height:auto;width:100%;margin:0 auto;" target="_blank" rel="noopener" rel="noreferrer"><img src="<?php echo $url; ?>/images/logo.png" class="aspect-video" style="position: relative;width: 100%;height: auto;" border="0" /></a>
</div>

</body>
</html>

