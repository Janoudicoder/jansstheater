<?php
require("./cms/login/config.php");
require("./cms/ftp/config.php");

if($_GET['taal'] != "nl") {
    $beveiligingTaal = $_GET['taal'];
} else { $beveiligingTaal = "nl"; }

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
// Time token
$time_token = time();
?>

<?php if($ws_beveiliging == 'recaptcha'): ?>
    <script src="https://www.google.com/recaptcha/api.js?onload=myCallBack&render=explicit" async defer></script>
    <script>
        var recaptcha2;
        var myCallBack = function() {

        //Render the recaptcha2 on the element with ID "recaptcha2"
        recaptcha2 = grecaptcha.render("recaptcha2", {
            "sitekey" : "<?php echo $rc_client_key;?>", //Replace this with your Site key
            "theme" : "light"
        });
        };
    </script>
<?php
    require_once "./recaptcha/recaptchalib.php";

    //d($_POST);

    // reCAPTCHA supported 40+ languages listed here: https://developers.google.com/recaptcha/docs/language
    $lang = $beveiligingTaal;
    // The response from reCAPTCHA
    $resp = null;
    // The error code from reCAPTCHA, if any
    $error = null;

    $reCaptchaValidate = new ReCaptcha($rc_secret_key);

    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) 
    {
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $rc_secret_key . '&response=' . $_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);
    } 
endif;
?>
<?php if($ws_beveiliging == 'hcaptcha'): ?>
    <script src='https://js.hcaptcha.com/1/api.js?hl=<?=$beveiligingTaal;?>' async defer></script>
<?php
endif;

$addJsCheckFileUpload = false;

//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer; 	//vanaf PHP 5.3
use PHPMailer\PHPMailer\Exception; 	//vanaf PHP 5.3

require 'phpmailer/src/PHPMailer.php'; 	//locatie van PHPMailer script
require 'phpmailer/src/Exception.php'; 	//locatie van SMTP script
require 'phpmailer/src/SMTP.php'; 		//locatie van SMTP script

if($_GET['footerid']){
    $formidsId = '2';
}else if($_GET['formIdBlock']){
    $formidsId = $_GET['formIdBlock'];
}else{
    $formidsId = get_id();
}

/*gekoppelde formulieren ophalen*/
$sqlFormulieren = $mysqli -> prepare("SELECT formulieren FROM siteworkcms WHERE id = '".$formidsId."' ORDER BY id") or die ($mysqli->error.__LINE__);
$sqlFormulieren -> execute();
$sqlFormulieren->store_result();
$sqlFormulieren->bind_result($formulieren_ids);
$sqlFormulieren -> fetch();

/*als er meerdere formulieren zijn geselecteerd komt dit komma gescheiden in de DB te staan.
Eerst gaan we deze waarde exploden hierna door elk formulier te kunnen loopen*/
if($_GET['formIdBlock']){
    $formulierenIds = $_GET['formIdBlock'];
    $formulierenIds = array($formulierenIds);
}else{
    $formulierenIds = explode(",", $formulieren_ids);
}


/*loop door alle formulieren*/
$teller = 1;


foreach($formulierenIds as $formulierId){
    /*formulier gegevens ophalen*/
    $sqlFormulierInfo = $mysqli -> prepare("SELECT * FROM sitework_formulieren WHERE id = ? ") or die ($mysqli->error.__LINE__);
    $formid = $formulierId;
    $sqlFormulierInfo -> bind_param('i',$formid);
    $sqlFormulierInfo -> execute();
    $resultForm = $sqlFormulierInfo->get_result();
    while($rowFormInfo = $resultForm->fetch_assoc()) 
    {
        echo '<div id="formulieren-'.$teller.'" class="formulier">';
        echo "<form id=\"form-".get_id()."\" action=\"".get_url()."".$_SERVER['REQUEST_URI']."\" method=\"post\" enctype=\"multipart/form-data\" >";

        $form_token = $_POST['form_token'];
        $time_difference = time() - $form_token;

        /*POST GEGEVENS OPVANGEN EN VERWERKEN VOOR EMAIL*/
        if(
            $_POST['verzenden-'.$formulierId.''] == "1" && 
            empty($_POST['honeypot']) && 
            $time_difference > 5 && 
            hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ){

            if($ws_beveiliging == 'hcaptcha'){
                $data = array(
                    'secret' => $hc_secret_key,
                    'response' => $_POST['h-captcha-response']
                );
                    $verify = curl_init();
                    curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
                    curl_setopt($verify, CURLOPT_POST, true);
                    curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
                    curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($verify);
                    curl_close($verify);

                    $responseData = json_decode($response);
            }
            if($responseData->success) {
                $bericht = $rowFormInfo['bericht']."<br/><br/>";
                /*Loop door de post waardes heen.*/
                foreach($_POST as $name => $waarde){

                    if($waarde != "" AND $name != "verzenden-".$formulierId."" AND $name != "csrf_token" AND $name != "honeypot" AND $name != "form_token" AND $name != "h-captcha-response" AND $name != "g-recaptcha-response" /*lege value voor honeypot[sitework] recaptcha en verzenden niet meenemen in de loop*/){
                        $fieldId = strstr($name, '-'); /* voorbeeld: text-12 naar -12*/
                        $fieldId = substr($fieldId,1); /* voorbeeld: -12 naar 12*/
                        /*Label van veld ophalen dmv $fieldId*/
                        $postName = $mysqli -> prepare("SELECT label FROM sitework_formuliervelden WHERE id = ? ") or die ($mysqli->error.__LINE__);
                        $postName -> bind_param('i',$fieldId);
                        $postName -> execute();
                        $resultPost = $postName->get_result();
                        $postDBName = $resultPost->fetch_assoc();
                        /*waardes toevoegen aan $bericht*/
                        if (is_array($waarde))
                        {   
                            $waarde = implode(" en ", $waarde);
                        }

                        $bericht .= "<strong>".$postDBName['label']."</strong>:&nbsp;&nbsp;".$waarde."<br>";
                        //$checkemail = substr($name, 0, 5);
                        $checkemail = explode("-",$name)[0];

                        if($checkemail == "name"){
                            $username = $waarde;
                        }
                        if($checkemail == "email"){
                            $usermail = $waarde;
                        }
                        if($checkemail == "tel"){
                            $usertel = $waarde;
                        }
                    }
                }
                if($rowFormInfo['url_meesturen'] == "ja"){
                    $urlPagina = $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

                    $bericht .= "<br/>Vanaf pagina: ".$urlPagina."<br/><br/>";
                }
                ####################### VANAF HIER PHP mailer #######################

                $mail = new PHPMailer(true);

                try {
                    //Server settings
                    $mail->SMTPDebug = 2;									// Debug op 2 zetten voor logfile, standaard op 0
                    $mail->CharSet = 'UTF-8';								//sepcial characters
                    $mail->Encoding = 'base64';
                    $mail->isSMTP();                                      	// Mailer instellen voor het versturen via SMTP
                    $mail->Host = $host;  									// Specificeer mailserver
                    $mail->SMTPAuth = true;                               	// Versturen met smtp autenticatie
                    $mail->Username = $ftpuser;								// Gebruikersnaam mailbox óf ftp inloggegevens gebruiken
                    $mail->Password = $ftppass;
                    $mail->SMTPSecure = 'tls';                            	// TLS encryptie aanzetten, `ssl` is ook geaccepteerd
                    $mail->Port = 587;                                    	// TCP poort
                    $mail->IsHTML(false);

                    // Email verstuur naar instellingen
                        // Afzender instellen
                        $mail->setFrom($rowFormInfo['emailafzender'], $rowFormInfo['afzender']);				// Afzemder hetzelfde instellen als de username van de mailbox!

                        // Ontvangers instellen
                        $mail->addAddress($rowFormInfo['email'], $rowFormInfo['ontvanger']); 			// Standaard ontvanger website

                        // Als CC is ingesteld voegen we hem toe
                        if($rowFormInfo['emailcc']){
                            $mail->addCC($rowFormInfo['emailcc']);
                        }
                        
                        // Als kopie naar klant is ingesteld
                        if ($rowFormInfo['kopienaarklant']) {
                            $mail->addCC($usermail);
                        }
                    
                    // DKIM
                    /*$mail->DKIM_domain = get_url();
                    $mail->DKIM_private = '/path/to/private.key'; // Path to your private key file
                    $mail->DKIM_selector = 'default'; // Selector prefix
                    $mail->DKIM_passphrase = ''; // Leave empty if no passphrase
                    $mail->DKIM_identity = $mail->From; // The identity this email claims to be */
                    
                    // HTML opmaak selecteren.
                    $mail->SMTPOptions = array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        )
                    );

                    if (!empty($_FILES['attach_file']['name'])) {
                        // Loop through each uploaded file
                        for ($i = 0; $i < count($_FILES['attach_file']['name']); $i++) {
                            $tmpFilePath = $_FILES['attach_file']['tmp_name'][$i];
                            $fileName = $_FILES['attach_file']['name'][$i];
                    
                            if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                                // Attachments
                                $mail->AddAttachment($tmpFilePath, $fileName);
                            }
                        }
                    }

                    //Content
                    $mail->Subject = $rowFormInfo['onderwerp'];
                    $mail->Body    = '<font face="Arial, Helvetica, sans-serif" size="3"><br><br>'.$bericht.'</font>';
                    $mail->AltBody = 'Dit bericht is opgemaakt in HTML. U heeft een emailbrowser nodig die HTML ondersteund om het bericht te kunnen lezen.';

                    $mail->send();

                    $cookie_consent_level = json_decode($_COOKIE['cookie_consent_level'], true);

                    if(
                        isset($_COOKIE['cookie_consent_user_accepted']) && $_COOKIE['cookie_consent_user_accepted'] == true &&
                        isset($cookie_consent_level['strictly-necessary']) && $cookie_consent_level['strictly-necessary'] === true &&
                        isset($cookie_consent_level['functionality']) && $cookie_consent_level['functionality'] === true &&
                        isset($cookie_consent_level['tracking']) && $cookie_consent_level['tracking'] === true &&
                        isset($cookie_consent_level['targeting']) && $cookie_consent_level['targeting'] === true
                    ) {
                        $insertLog = $mysqli->query("INSERT sitework_formulieren_log set 
                                                                form_id = '".$formulierId."',
                                                                form_pagina = '".$_SERVER['REQUEST_URI']."',
                                                                naam = '".$username."',
                                                                email = '".$usermail."',
                                                                tel = '".$usertel."',
                                                                ipadres = '".$_SERVER['REMOTE_ADDR']."'
                                                            ") or die($mysqli->error.__LINE__);
                    } else {
                        $insertLog = $mysqli->query("INSERT sitework_formulieren_log set 
                                                                form_id = '".$formulierId."',
                                                                form_pagina = '".$_SERVER['REQUEST_URI']."',
                                                                ipadres = '".$_SERVER['REMOTE_ADDR']."'
                                                            ") or die($mysqli->error.__LINE__);
                    }

                    if ($rowFormInfo['bedanktURL']) {
                        if(get_meertaligheid() == true) {
                            $taalBedanktUrl = get_taal();
                        } else { $taalBedanktUrl = ''; }

                        header('Location: '.get_url()."/".$taalBedanktUrl.'/'.$rowFormInfo['bedanktURL'].''); 
                    } else if($rowFormInfo['bedankttekst']) {
                        echo $rowFormInfo['bedankttekst']."<br/><br/>";
                    } else {
                        echo 'Bericht is verzonden<br/><br/>';
                    }
                } catch (Exception $e) {
                    echo 'Bericht is niet verzonden.';
                    echo 'Mail foutmelding: ' . $mail->ErrorInfo;
                }

            ###################### EINDE EMAIL VERSTUREN #######################
            }

        }
            
        /*Titel van formulier tonen*/
        echo "<h2>".$rowFormInfo['naam']."</h2>";
        /*formulier velden ophalen*/
        $sqlFormulierVelden = $mysqli -> prepare("SELECT * FROM sitework_formuliervelden WHERE formid = ? ORDER BY volgorde") or die ($mysqli->error.__LINE__);
        $formid = $formulierId;
        $sqlFormulierVelden -> bind_param('i',$formid);
        $sqlFormulierVelden -> execute();
        $resultFormVelden = $sqlFormulierVelden->get_result();
        while($rowFormVeld = $resultFormVelden->fetch_assoc()) {
            /*Loop door elke veld heen. Op basis van het type tonen we het toepassingelijk soort veld*/
            $veldType = $rowFormVeld['type'];
            /*<---VELD AFHANKELIJKHEIDS CHECK--->*/
            if($rowFormVeld['afhankelijk_van'] != 0){
                $hidden = "hidden";
                $required = "";
                $sqlFormulierVeldenAHV = $mysqli -> prepare("SELECT * FROM sitework_formuliervelden WHERE id = ? ORDER BY volgorde") or die ($mysqli->error.__LINE__);
                $afhankelijk = $rowFormVeld['afhankelijk_van'];
                $sqlFormulierVeldenAHV -> bind_param('i',$afhankelijk);
                $sqlFormulierVeldenAHV -> execute();
                $resultFormAHV = $sqlFormulierVeldenAHV->get_result();
                $rowFormAHV = $resultFormAHV->fetch_assoc();
                $nameveld = $rowFormAHV['type']."-".$rowFormAHV['id'];?>
                <script>
                    $('input[name="<?php echo $nameveld;?>"]').on('change', function() {
                        if($(this).val().length) {
                            $(".<?php echo $veldType."-".$rowFormVeld['id'];?>").removeClass("hidden");
                            <?php if($rowFormVeld['verplicht'] == "ja"){ ?>
                                $("#<?php echo $veldType."-".$rowFormVeld['id'];?>").prop('required',true);
                                <?
                                if($veldType == "radio" OR $veldType == "checkbox"){
                                    $sqlFormulierOptiesReq = $mysqli -> prepare("SELECT * FROM sitework_formuliervelden_opties WHERE fieldid = ? ORDER BY volgorde") or die ($mysqli->error.__LINE__);
                                    $formidveldreq = $rowFormVeld['id'];
                                    $sqlFormulierOptiesReq -> bind_param('i',$formidveldreq);
                                    $sqlFormulierOptiesReq -> execute();
                                    $resultFormOptiesReq = $sqlFormulierOptiesReq->get_result();
                                    while($rowFormOptieReg = $resultFormOptiesReq->fetch_assoc()) {?>
                                        $("#<?php echo $veldType."-".$rowFormOptieReg['id'];?>").prop('required',true);
                                    <?php
                                    }
                                }
                                ?>
                            <?php } ?>
                        } else {
                            $(".<?php echo $veldType."-".$rowFormVeld['id'];?>").addClass("hidden");
                            <?php if($rowFormVeld['verplicht'] == "ja"){ ?>
                                $("#<?php echo $veldType."-".$rowFormVeld['id'];?>").prop('required',false);
                                <?
                                if($veldType == "radio" OR $veldType == "checkbox"){
                                    $sqlFormulierOptiesReq = $mysqli -> prepare("SELECT * FROM sitework_formuliervelden_opties WHERE fieldid = ? ORDER BY volgorde") or die ($mysqli->error.__LINE__);
                                    $formidveldreq = $rowFormVeld['id'];
                                    $sqlFormulierOptiesReq -> bind_param('i',$formidveldreq);
                                    $sqlFormulierOptiesReq -> execute();
                                    $resultFormOptiesReq = $sqlFormulierOptiesReq->get_result();
                                    while($rowFormOptieReg = $resultFormOptiesReq->fetch_assoc()) {?>
                                        $("#<?php echo $veldType."-".$rowFormOptieReg['id'];?>").prop('required',false);
                                    <?php
                                    }
                                }
                                ?>
                            <?php } ?>
                        }
                    });
                </script>
            <?php }else{
                $hidden = "";
                /*$required aanzetten als de 'verplicht' optie op 'ja' staat*/
                if($rowFormVeld['verplicht'] == "ja"){$required = "required";}else{$required = "";}
            }
            /*formulier veld opties ophalen*/
            $sqlFormulierOpties = $mysqli -> prepare("SELECT * FROM sitework_formuliervelden_opties WHERE fieldid = ? ORDER BY volgorde") or die ($mysqli->error.__LINE__);
            $formidveld = $rowFormVeld['id'];
            $sqlFormulierOpties -> bind_param('i',$formidveld);
            $sqlFormulierOpties -> execute();
            $resultFormOpties = $sqlFormulierOpties->get_result();
            /*---TEXT---*/
            if($veldType == "text" OR $veldType == "name"){
                echo "<div class=\"form-group ".$hidden." ".$veldType."-".$rowFormVeld['id']."\">";
                    echo "<input type=\"".$veldType."\" placeholder=\"\" value=\"".$_POST[$veldType."-".$rowFormVeld['id']]."\" name=\"".$veldType."-".$rowFormVeld['id']."\" id=\"".$veldType."-".$rowFormVeld['id']."\"  ".$required."/>";
                    echo "<label class=\"jumplabel\" for=\"".$veldType."-".$rowFormVeld['id']."\">".$rowFormVeld['label']."</label>";
                echo "</div>";
            }
            /*---DATE---*/
            if($veldType == "date"){
                echo "<div class=\"form-group ".$hidden." ".$veldType."-".$rowFormVeld['id']."\">";
                    echo "<input type=\"".$veldType."\" placeholder=\"\" value=\"".$_POST[$veldType."-".$rowFormVeld['id']]."\" name=\"".$veldType."-".$rowFormVeld['id']."\" id=\"".$veldType."-".$rowFormVeld['id']."\"  ".$required."/>";
                    echo "<label class=\"jumplabel\" for=\"".$veldType."-".$rowFormVeld['id']."\">".$rowFormVeld['label']."</label>";
                echo "</div>";
            }
            /*---EMAIL---*/
            if($veldType == "email"){
                echo "<div class=\"form-group ".$hidden." ".$veldType."-".$rowFormVeld['id']."\">";
                    echo "<input type=\"".$veldType."\" placeholder=\"\" value=\"".$_POST[$veldType."-".$rowFormVeld['id']]."\" name=\"".$veldType."-".$rowFormVeld['id']."\" id=\"".$veldType."-".$rowFormVeld['id']."\" ".$required."/>";
                    echo "<label class=\"jumplabel\" for=\"".$veldType."-".$rowFormVeld['id']."\">".$rowFormVeld['label']."</label>";
                echo "</div>";
            }
            /*---TEL---*/
            if($veldType == "tel"){
                echo "<div class=\"form-group ".$hidden." ".$veldType."-".$rowFormVeld['id']."\">";
                    echo "<input type=\"".$veldType."\" placeholder=\"".$rowFormVeld['label']."\" value=\"".$_POST[$veldType."-".$rowFormVeld['id']]."\" name=\"".$veldType."-".$rowFormVeld['id']."\" id=\"".$veldType."-".$rowFormVeld['id']."\" ".$required."/>";
                    //echo "<label class=\"jumplabel\" for=\"".$veldType."-".$rowFormVeld['id']."\">".$rowFormVeld['label']."</label>";
                echo "</div>";
            }
            /*---NUMBER---*/
            if($veldType == "number"){
                echo "<div class=\"form-group ".$hidden." ".$veldType."-".$rowFormVeld['id']."\">";
                    echo "<input type=\"".$veldType."\" placeholder=\"".$rowFormVeld['label']."\" value=\"".$_POST[$veldType."-".$rowFormVeld['id']]."\" name=\"".$veldType."-".$rowFormVeld['id']."\" id=\"".$veldType."-".$rowFormVeld['id']."\" ".$required."/>";
                    //echo "<label class=\"jumplabel\" for=\"".$veldType."-".$rowFormVeld['id']."\">".$rowFormVeld['label']."</label>";
                echo "</div>";
            }
            /*---SELECT---*/
            if($veldType == "select"){
                echo "<div class=\"form-group ".$hidden." ".$veldType."-".$rowFormVeld['id']."\">";
                    echo "<label>".$rowFormVeld['label']."</label>";
                    echo "<div class=\"styled-select\">";
                    echo "<select name=\"".$veldType."-".$rowFormVeld['id']."\" id=\"".$veldType."-".$rowFormVeld['id']."\" ".$required.">";
                        echo "<option value=\"\">Selecteer een optie</option>";
                        echo "<option value=\"\">---</option>";
                        while($rowFormOptie = $resultFormOpties->fetch_assoc()) {
                            echo "<option value=\"".$rowFormOptie['naam']."\">".$rowFormOptie['naam']."</option>";
                        }
                    echo "</select>";
                    echo "</div>";
                echo "</div>";
            }
            /*---RADIO---*/
            if($veldType == "radio"){
                echo "<div class=\"form-group ".$hidden." ".$veldType."-".$rowFormVeld['id']."\">";
                    echo "<label>".$rowFormVeld['label']."</label>";
                    echo "<div class=\"radio\">";
                    while($rowFormOptie = $resultFormOpties->fetch_assoc()) {
                        echo "<input type=\"".$veldType."\" value=\"".$rowFormOptie['naam']."\" name=\"".$veldType."-".$rowFormVeld['id']."\"  ".$required." id=\"".$veldType."-".$rowFormOptie['id']."\"/>";
                        echo "<label for=\"".$veldType."-".$rowFormOptie['id']."\">".$rowFormOptie['naam']."</label>";
                    }
                    echo "</div>";
                echo "</div>";
            }
            /*---CHECKBOX---*/
            if($veldType == "checkbox"){
                echo "<div class=\"form-group ".$hidden." ".$veldType."-".$rowFormVeld['id']."\">";
                    echo "<label>".$rowFormVeld['label']."</label>";
                    echo "<div class=\"checkbox\">";
                    while($rowFormOptie = $resultFormOpties->fetch_assoc()) {
                        echo "<input type=\"".$veldType."\" value=\"".$rowFormOptie['naam']."\" name=\"".$veldType."-".$rowFormVeld['id']."[]\"  ".$required." id=\"".$veldType."-".$rowFormOptie['id']."\"/>";
                        echo "<label for=\"".$veldType."-".$rowFormOptie['id']."\">".$rowFormOptie['naam']."</label>";
                    }
                    echo "</div>";
                echo "</div>";
            }
            /*---FILE---*/
            if($veldType == "file"){
                $addJsCheckFileUpload = true;
                echo "<div class=\"form-group ".$hidden." ".$veldType."-".$rowFormVeld['id']."\">";
                    echo "<label for=\"attach_file\">".$rowFormVeld['label']." ".$requiredStar."</label>";
                    echo "<input type=\"".$veldType."\" id=\"fileinput\" class=\"file\" multiple=\"multiple\" accept=\".pdf,.doc,.docx,image/*\" name=\"attach_file[]\" ".$required."/>";
                echo "</div>";
            }
            /*---TEXTAREA---*/
            if($veldType == "textarea"){
                echo "<div class=\"form-group area ".$hidden." ".$veldType."-".$rowFormVeld['id']."\">";
                    echo "<textarea placeholder=\" \" value=\"".$_POST[$veldType."-".$rowFormVeld['id']]."\" name=\"".$veldType."-".$rowFormVeld['id']."\" ".$required."></textarea>";
                    echo "<label class=\"jumplabel\" for=\"".$veldType."-".$rowFormVeld['id']."\">".$rowFormVeld['label']."</label>";
                echo "</div>";
            }
            /*---TEKST OPVULLING---*/
            if($veldType == "tekstopvulling"){
                echo "<div class=\"form-group form-text ".$hidden." ".$veldType."-".$rowFormVeld['id']."\">";
                    echo $rowFormVeld['label'];
                echo "</div>";
            }
        }
      
        // Privacy verklaring
        // ==================
     /*   if($rowFormInfo['privacyURL'] <> ""):
            echo "<div class=\"form-group privacy-verklaring\">";
                echo "<div class=\"checkbox\">";
                    echo "<input type=\"checkbox\" value=\"\" name=\"privacy-verklaring\" required id=\"privacy-verklaring\"/>";
                    echo "<label for=\"privacy-verklaring\">Hiermee gaat u akkoord met de <a href=\"".get_url()."/".$rowFormInfo['privacyURL']."\" target=\"_blank\">privacy verklaring</a></label>";
                echo "</div>";
            echo "</div>";
        endif; /*

            /* Honeypot field (Niet zichtbaar voor bezoekers) */
            echo "<input type=\"text\" name=\"honeypot\" style=\"display:none;\">";
            /* Time token */
            echo "<input type=\"hidden\" name=\"form_token\" value=\"".$time_token."\">";
            /* CSRF token */
            echo "<input type=\"hidden\" name=\"csrf_token\" value=\"".$_SESSION['csrf_token']."\">";

           
            echo "<input type=\"hidden\" name=\"verzenden-".$formulierId."\" value=\"1\"/>";
            echo "<button type=\"submit\" class=\"button submit-form\">".$verzenden."</button>";
        echo "</form>";
        echo "</div>";
        $teller ++;
    }

}
//javascript inladen als er een upload veld in het formulier staat
if($addJsCheckFileUpload == true) {
?>
    <script>
    	var uploadField = document.getElementById("fileinput");

    	uploadField.onchange = function() {
    		if(this.files[0].size > 4194304){
    		    alert("max. 4 MB");
    			this.value = "";
    		};
    	};
    </script>
<?php } ?>