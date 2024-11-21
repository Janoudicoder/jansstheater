<?php
// FTP gegevens
// ============
$ftpuser = 'janssthea'; // gebruikersnaam ftp
$ftppass = 'aRLYCVC5sfQS4wLJDkT7'; // wachtwoord ftp  

// FTP stream
// ==========
$ftpstream = @ftp_connect('localhost');

// login to the FTP server
// =======================
$login = @ftp_login($ftpstream, $ftpuser, $ftppass);

// PHPmailer host
// ==============
$host = gethostname();

// FTP paden naar de favicon
// =========================
$pad_favicon = "/public_html";
$pad_favicon_open = "CHMOD 0777 /domains/".$domein."/public_html";
$pad_favicon_dicht = "CHMOD 0755 /domains/".$domein."/public_html";

// FTP paden naar het Logo
// =========================
$pad_logo = "/public_html/images";
$pad_logo_open = "CHMOD 0777 /domains/".$domein."/public_html/images";
$pad_logo_dicht = "CHMOD 0755 /domains/".$domein."/public_html/images";

// FTP paden naar de CMS updates
// =========================
$pad_cms_core_update = "domains/".$domein."/public_html/";
$pad_cms_update = "domains/".$domein."/public_html/cms_updates";
$pad_cms_update_open = "CHMOD 0777 /domains/".$domein."/public_html/cms_updates";
$pad_cms_update_dicht = "CHMOD 0755 /domains/".$domein."/public_html/cms_updates";

// FTP paden naar de images mappen
// ===============================
$pad_img_open = "CHMOD 0777 /domains/".$domein."/public_html/img";
$pad_webp_open = "CHMOD 0777 /domains/".$domein."/public_html/img/webp";
$pad_temp_open = "CHMOD 0777 /domains/".$domein."/public_html/img/temp";
$pad_thumbs_open = "CHMOD 0777 /domains/".$domein."/public_html/img/thumbs";

$pad_img_dicht = "CHMOD 0755 /domains/".$domein."/public_html/img";
$pad_webp_dicht = "CHMOD 0755 /domains/".$domein."/public_html/img/webp";
$pad_temp_dicht = "CHMOD 0755 /domains/".$domein."/public_html/img/temp";
$pad_thumbs_dicht = "CHMOD 0755 /domains/".$domein."/public_html/img/thumbs";

// FTP paden naar de background mappen
// ===================================
$pad_img_open_cms = "CHMOD 0777 /domains/".$domein."/public_html/cms/images/backgrounds";
$pad_temp_open_cms = "CHMOD 0777 /domains/".$domein."/public_html/cms/images/backgrounds/temp";
$pad_thumbs_open_cms = "CHMOD 0777 /domains/".$domein."/public_html/cms/images/backgrounds/thumbs";

$pad_img_dicht_cms = "CHMOD 0755 /domains/".$domein."/public_html/cms/images/backgrounds/";
$pad_temp_dicht_cms = "CHMOD 0755 /domains/".$domein."/public_html/cms/images/backgrounds/temp";
$pad_thumbs_dicht_cms = "CHMOD 0755 /domains/".$domein."/public_html/cms/images/backgrounds/thumbs";

// FTP paden naar de doc mappen
// ============================
$pad_doc_open = "CHMOD 0777 /domains/".$domein."/public_html/doc";
$pad_doc_dicht = "CHMOD 0755 /domains/".$domein."/public_html/doc";

// tijdzone op nederland zetten
// ============================
setlocale(LC_ALL, 'nl_NL');
?>
