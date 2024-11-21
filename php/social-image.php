<?php  
// woning pagina
// ================

    if($_GET['page'] == "bedrijfspand" && get_setting('makelaar') == 'ja' && get_setting('bogaanbod')){
            $pandsocialmedia = use_query('id', 'sitework_objecten', 'paginaurl = '.$_GET['title'].'');
            $sqlimgsocial = use_query('lokaalurl', 'sitework_mediabog', 'object_id = '.$pandsocialmedia['id'], 'volgorde ASC', 1);

            $socialimage =  get_url()."/realworks_bog/img/".$sqlimgsocial['lokaalurl'];

        // normale pagina
        // ==============
    } else {
        if(get_id()) {
            $slqSocialImage = use_query('naam', 'sitework_img', 'cms_id = ' . get_id(), 'volgorde ASC', 1);
        } else {
            $socialimage = '';
        }

        if($slqSocialImage == NULL OR empty($slqSocialImage)) {
            $slqSocialImage = use_query('naam', 'sitework_img', 'cms_id = 1', 'volgorde ASC', 1);
        }

        if($slqSocialImage == NULL OR empty($slqSocialImage)) {
            $socialimage = '';
        } else {
            $slqSocialImageMedia = use_query('naam, ext', 'sitework_mediabibliotheek', 'id = ' . $slqSocialImage['naam'], '', 1);

            if($slqSocialImageMedia == NULL OR empty($slqSocialImageMedia)) {
                $socialimage = '';
            } else {
                $socialimage =  get_url()."/img/".$slqSocialImageMedia['naam'].".".$slqSocialImageMedia['ext'];
            }
        }
    }
?>