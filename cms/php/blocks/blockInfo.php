<? // database connectie en inlogfuncties
// ===================================
include ("../../login/config.php");
include ('../../login/functions.php');

//haal block gegevens op
$blockData = $mysqli->query("SELECT * FROM sitework_block WHERE id = '".$_GET['block_id']."'") or die($mysqli->error.__LINE__);
$rowBlockData = $blockData->fetch_assoc();

$blocknaam = $rowBlockData['bestandsnaam'];


//HIERONDER GAAN WE DE TEKSTEN VULLEN PER BLOCK

// tekst foto
if ($blocknaam == "tekst-foto") {
    $tekst = '
        <h1>Tekst Foto</h1>
        <p>Dit blok toont tekst en een foto</p>
        <hr>
        <em>
        Je kunt voor dit blok zelf de layout kiezen<br/>
        Dit kan tekst link - foto rechts of foto link - tekst rechts zijn <br><br>
        Ook kun je kiezen uit een verhouding van 30-70, 50-50 of 70-30.<br><br>
        Er kunnen meerdere afbeeldingen zijn. Als je er meerdere upload zal er automatisch een slider komen.<br/>
        De afbeeldingen kan je uploaden d.m.v. de "Afbeeldingen" knop rechts bovenin het block <br>
        </em>
    ';
}

//foto link tekst rechts
if($blocknaam == "foto-links-tekst-rechts"){
    $tekst = '
        <h1>Foto links, tekst rechts</h1>
        <p>Dit block toont een foto links en tekst rechts.</p>
        <hr>
        <em>
        De tekst is in te voeren in het tekst veld.<br/>
        De afbeelding(en) links kunnen meerdere afbeeldingen zijn. Als je er meerdere upload zal er automatisch een slider komen.<br/>
            De afbeeldingen kan je uploaden d.m.v. de "Afbeeldingen" knop rechts bovenin het block
        </em>
    ';
}

//tekst link foto rechts
if($blocknaam == "tekst-links-foto-rechts"){
    $tekst = '
        <h1>Tekst links, foto rechts</h1>
        <p>Dit block toont tekst links en een foto rechts.</p>
        <hr>
        <em>
        De tekst is in te voeren in het tekst veld.<br/>
        De afbeelding(en) links kunnen meerdere afbeeldingen zijn. Als je er meerdere upload zal er automatisch een slider komen.<br/>
            De afbeeldingen kan je uploaden d.m.v. de "Afbeeldingen" knop rechts bovenin het block
        </em>
    ';
}

//Tekst - twee kolommen
if($blocknaam == "tekst-twee-kolom"){
    $tekst = '
        <h1>Tekst over twee kolommen</h1>
        <p>Dit block toont een tekst blok verdeeld over twee kolommen</p>
        <hr>
        <em>
        De tekst is in te voeren in het tekst veld.<br/>
        De tekst zal automatisch de tekst verdelen over twee kolommen.<br/>
        Als je zelf controle wilt hebben over waar de tekst wordt opgebroken kan je dit doen d.m.v. het "pagina einde" icoon in de tekst editor: <br/>
        <img src="'.$url.'/cms/php/blocks/info/images/leesmeer.png" alt="leesmeer" />
  
        </em>
    ';
}

//Categorie overzicht - drie kolommen
if($blocknaam == "categorie-overzicht"){
    $tekst = '
        <h1>Categorie overzicht - drie kolommen</h1>
        <p>Dit block toont een overzicht van pagina\'s van een bepaalde categorie (bijvoorbeeld nieuws)</p>
        <hr>
        <em>
        De te tonen categorie kan je zelf kiezen d.m.v. het categorie selectie veld. Als je boven het overzicht nog een titel en/of een introductie tekst wilt plaatsen kan dit 
        door dit in het tekst veld te typen.
        </em>
    ';
}

//Categorie overzicht - drie kolommen laad meer
if($blocknaam == "categorie-overzicht-laadmeer"){
    $tekst = '
        <h1>Categorie overzicht - laad meer</h1>
        <p>Dit block toont een overzicht van pagina\'s van een bepaalde categorie (bijvoorbeeld nieuws) met een laad meer knop om steeds een aantal nieuwe berichten te tonen.</p>
        <hr>
        <em>
        De te tonen categorie kan je zelf kiezen d.m.v. het categorie selectie veld. Als je boven het overzicht nog een titel en/of een introductie tekst wilt plaatsen kan dit 
        door dit in het tekst veld te typen.
        </em>
    ';
}

//Galerij
if($blocknaam == "galerij"){
    $tekst = '
        <h1>Galerij</h1>
        <p>Dit block toont afbeeldingenin een galerij vorm</p>
        <hr>
        <em>
        De afbeeldingen kan je uploaden d.m.v. de "Afbeeldingen" knop rechts bovenin het block
        </em>
    ';
}

//Contact
if($blocknaam == "contact"){
    $tekst = '
        <h1>Contact</h1>
        <p>Dit block toont contact gegevens en een formulier</p>
        <hr>
        <em>
        De titel en tekst kan je aanpassen via het de aangegeven tekst velden. <br/>
        Via het selectie veld "contactformulier" kan je zelf kiezen wel formulier moet worden getoond. Het formulier kan je aanmaken via de formulier module.
        </em>
    ';
}

//Google maps
if($blocknaam == "contact-google-maps-full"){
    $tekst = '
        <h1>Google maps</h1>
        <p>Dit block toont een google maps kaart.</p>
        <hr>
        <em>
        In het tekstveld kan je het adres invullen, google maps zal dan automatisch de goede locatie tonen.<br/>
        Voorbeeld voor invoer: "Prins Bernhardweg 27, lochem".
        </em>
    ';
}

//logo-slider
if($blocknaam == "logo-slider"){
    $tekst = '
        <h1>Logo slider</h1>
        <p>Dit block toont een een slider met logo\'s</p>
        <hr>
        <em>
        De afbeeldingen kan je uploaden d.m.v. de "Afbeeldingen" knop rechts bovenin het block
        </em>
    ';
}

//Video
if($blocknaam == "video"){
    $tekst = '
        <h1>Video</h1>
        <p>Dit block toont een video op volledige grootte</p>
        <hr>
        <em>
        Je hebt 2 opties: YouTube en Vimeo.<br/>
        <strong>YouTube:</strong> Je zoekt je video op YouTube en klikt deze aan. Ga nu naar de adresbalk van je browser en selecteer ALLEEN de code ACHTER "https://www.youtube.com/watch?v=" <u>(bijvoorbeeld "RK1K2bCg4J8")</u><br/>
        <strong>Vimeo:</strong> Je zoekt je video op Vimeo en klikt deze aan. Ga nu naar de adresbalk van je browser en selecteer ALLEEN de code ACHTER "https://vimeo.com/" <u>(bijvoorbeeld "112836958")</u><br/>
        </em>
    ';
}

?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/css/stylesheet.css">
    <link rel='stylesheet' type='text/css' href='<? echo $url; ?>/cms/css/branding-stylesheet.php' />

    <link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/datepick/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,200i,300,300i,400,400i,600,600i,700,700i,900,900i">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Khand:300,400,500,600,700">

    <script type="text/javascript" src="<? echo $url; ?>/cms/jquery/files/jquery-3.5.1.min.js"></script>
    <style>
        body,html{
            background: white;
        }
        .no-border-top{
            border-top: 0;
            border-bottom: 1px dashed #dadada;
            width: 100%;
        }
    </style>
</head>
<body>
    <div id="blockinfo">
        <div class="links">
            <?=$tekst;?>
        </div>
        <div class="rechts">
            <?php if($blocknaam == "tekst-foto"): ?>
                <div>
                    <img src="info/images/<?=$blocknaam;?>.png" alt="">
                    <img src="info/images/<?=$blocknaam;?>-2.png" alt="">
                </div>
            <?php else: ?>
                <img src="info/images/<?=$blocknaam;?>.png" alt="">
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
