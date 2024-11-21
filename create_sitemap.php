<?php 
    require("cms/login/config.php");
    include_once("php/functions.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maak sitemap | <?=$sitenaam;?></title>
    <link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/css/stylesheet.css">
    <link rel='stylesheet' type='text/css' href='<? echo $url; ?>/cms/css/branding-stylesheet.php' />
    <link rel="stylesheet" type="text/css" href="<? echo $url; ?>/cms/font-awesome/css/all.min.css">
</head>
<body>
    
</body>
</html>

<?php
$_GET['taal'] = 'nl';
$post_taal = $_GET['taal'];

$talen = use_query_loop('taalkort', 'sitework_taal', 'actief = 1', 'volgorde ASC');
$nlTaal = array('taalkort' => $post_taal);
array_unshift($talen , $nlTaal);

foreach($talen as $taalkort):
    $taal = $taalkort['taalkort'];
    $post_taal = $taal;
    $pages = array();

    $sitemap = $mysqli->query("SELECT id, item2, keuze1, hoofdid, laatste_wijziging, paginaurl, eigenXMLurl FROM siteworkcms WHERE inXML = 'ja' AND status = 'Actief' ORDER BY id ASC") or die($mysqli->error.__LINE__);

    if($post_taal == 'nl'):
        $fp = fopen('sitemap.xml', 'w');
    else:
        $fp = fopen('sitemap-'.$post_taal.'.xml', 'w');
    endif;

    while ($sitemapGegevens = $sitemap->fetch_assoc()) {
        $pages[] = $sitemapGegevens;
    }

    //header("Content-type: text/xml");

    fwrite($fp, '<?xml version="1.0" encoding="utf-8" ?>');
    fwrite($fp, '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:xhtml="http://www.w3.org/TR/xhtml11/xhtml11_schema.html">');
    ?>
        <?php
            // dump($sitemapGegevens);
            foreach($pages as $pagina) {
                $paginaurlSitemapSub = "";
                $sitemapUrl = "";

                $gekoppeldeAfbeeldingen = '';
                $bijschriftTekens = ["'", ":" , "," , "& ", "&" , "."];


                $paginaIMG = use_query_loop('naam', 'sitework_img', 'cms_id = '.$pagina['id'].'', 'volgorde');
                foreach($paginaIMG as $img) {
                    $paginaMEDIA = use_query('naam, bijschrift, ext', 'sitework_mediabibliotheek', 'id= "'.$img['naam'].'" AND media = "afbeelding"', 'datum_geupload');

                    $gekoppeldeAfbeeldingen .=
                    '<image:image>
                        <image:loc>'.$url.'/img/webp/'.$paginaMEDIA['naam'].'.webp </image:loc>
                        <image:caption>'.str_replace($bijschriftTekens, " " ,$paginaMEDIA['bijschrift']).'</image:caption>
                        <image:title> '.str_replace($bijschriftTekens, " " ,$pagina['item2']).' || '.$sitenaam.' </image:title>
                    </image:image>';
                }

                if($pagina['eigenXMLurl']){
                    $sitemapUrl = $pagina['eigenXMLurl'];
                } else {
                    if(get_meertaligheid() == true) {
                        $meertaligLink = $post_taal . "/";
                    } else { $meertaligLink = ''; }

                    $paginaurl = the_field('paginaurl', $pagina['id']);
                    $paginaHoofdurl = the_field('paginaurl', $pagina['hoofdid']);
                    $paginaCat = the_field('keuze1', $pagina['id']);
                    $paginaWijziging = the_field('laatste_wijziging', $pagina['id']);

                    if($pagina['hoofdid'] != "0"){
                        $paginaHOOFD = use_query('paginaurl, eigenXMLurl', 'siteworkcms', 'id = '.$pagina['hoofdid'].' AND status = "Actief"', 'volgorde ASC');
                        $sitemapUrl = $url."/".$meertaligLink.$paginaHoofdurl."/".$paginaurl.'/';
                        
                    } else if($pagina['keuze1'] == "pagina" OR $pagina['keuze1'] == "overige"){
                        $sitemapUrl = $url."/".$meertaligLink.$paginaurl.'/';
                    } else {
                        $keuze1url  = strtolower($paginaCat);
                        $keuze1url = str_replace(" ", "-", $keuze1url);
                        $sitemapUrl = $url."/".$meertaligLink.$keuze1url ."/".$paginaurl.'/';
                    }
                }

                $datum = new DateTime($paginaWijziging);
                $datumString = $datum->format('Y-m-d');

                if($paginaurl <> ""):
                    fwrite($fp, '<url>
                                    <loc>'.$sitemapUrl.'</loc>
                                    <lastmod>'.$datumString.'</lastmod>
                                    <changefreq>monthly</changefreq>
                                    <priority>0.5</priority>
                                    '.$gekoppeldeAfbeeldingen.'
                                </url>'); 
                endif;
            }
    fwrite($fp, '</urlset>');
    fclose($fp);
endforeach;

$bekijkTalenSitemaps = '';
$normaleKnop = '<a class="btn" href="'.get_url().'/sitemap.xml" target="_blank" rel="noopener" rel="noreferrer">Bekijk de sitemap</a>';

foreach($talen as $taalkort):
    $taal = $taalkort['taalkort'];

    $taalMap = $taal == 'nl' ? '' : '-' . $taal;
    $bekijkTalenSitemaps .= '<a class="btn vlag" href="'.get_url().'/sitemap'.$taalMap.'.xml" target="_blank" rel="noopener" rel="noreferrer"><img src="' . get_url() . '/flags/' . $taal . '.svg" width="30px" height="20px" alt="" class="vlag"/><span>Bekijk de sitemap</span></a>';
endforeach;

if(get_meertaligheid() == true) { $normaleKnop = ''; } else { $bekijkTalenSitemaps = ''; }

echo    '<div class="box in-het-midden">
            <a class="btn back" href="'.get_url().'/cms/maincms.php?page=instellingen">Terug naar instellingen</a>
            '.$bekijkTalenSitemaps.'
            '.$normaleKnop.'
        </div>';
// echo "XML site is aangemaakt en te vinden via <a href=\"{$rowinstellingen['weburl']}/sitemap.xml\">{$rowinstellingen['weburl']}/sitemap.xml</a>";

// $paginaVERTALINGEN_talen = $mysqli->prepare("SELECT waarde FROM sitework_vertaling WHERE cms_id = ".$pagina['id']." AND veld = 'paginaurl' AND taal <> 'nl'") or die($mysqli->error . __LINE__);
// $paginaVERTALINGEN_talen->execute();
// $result_paginaVERTALINGEN_talen = $paginaVERTALINGEN_talen->get_result();
// while ($row_vertaling = $result_paginaVERTALINGEN_talen->fetch_assoc()) {
//     $sqlTaalVertaling = $mysqli->query("SELECT taal FROM sitework_vertaling WHERE waarde = '" . $row_vertaling['waarde'] . "' LIMIT 1") or die($mysqli->error . __LINE__);
//     while($rowTaalVertaling = $sqlTaalVertaling->fetch_assoc()):
//         $vertaling_pagURL = getTranslation('paginaurl', 'veld', $rowTaalVertaling['taal'], $pagina['id']);
//         $vertaling_hoofdID = getTranslation('hoofdid', 'veld', 'nl', $pagina['id']);

//         if($vertaling_hoofdID != 0) {
//             $vertaling_pagURL_Hoofd = getTranslation('paginaurl', 'veld', $rowTaalVertaling['taal'], $vertaling_hoofdID);
//             $gekoppeldeVertalingen .= '<xhtml:link rel="alternate" hreflang="'.$rowTaalVertaling['taal'].'" href="'.$url.'/'.$rowTaalVertaling['taal'].'/'.$vertaling_pagURL_Hoofd.'/'.$vertaling_pagURL.'/" />';
//         } else {
//             $gekoppeldeVertalingen .= '<xhtml:link rel="alternate" hreflang="'.$rowTaalVertaling['taal'].'" href="'.$url.'/'.$rowTaalVertaling['taal'].'/'.$vertaling_pagURL.'/" />';
//         }
//     endwhile;
    
// }
?>