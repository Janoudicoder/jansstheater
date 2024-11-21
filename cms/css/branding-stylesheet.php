<?php

header("Content-type: text/css; charset: UTF-8");

include ("../login/config.php");

   if ($rowinstellingen['branding'] == 'sitework') { $brandColor = '#00adf2'; $brandColorHover = '#00A0E5'; }
   if ($rowinstellingen['branding'] == 'puremotion') { $brandColor = '#ec7f00'; $brandColorHover = '#DF7200';}
   if ($rowinstellingen['branding'] == 'reclamemakers') { $brandColor = '#0000FF'; $brandColorHover = '#0000F2';}
   if ($rowinstellingen['branding'] == 'oviiontwerp') { $brandColor = '#C3006B'; $brandColorHover = '#a9005d';}

?>

<? // brandcolors inlogscherm ?>

#logincontainer .inlogkop {
    color: <? echo $brandColor; ?>;
}

#logincontainer .login-button {
    background: <? echo $brandColor; ?>;
}

#logincontainer .logindiv .fa {
    color: <? echo $brandColor; ?>;
}

<? // brancolors cms ?>

.btn {
    background: <? echo $brandColor; ?>;
}

.btn:hover {
    background: <? echo $brandColorHover ?>;
}

.box .row .col .delete:hover, .box .row .col .edit:hover {
    color: <? echo $brandColor; ?>;
}

.box h3:after {
    background: <? echo $brandColor; ?>;
}

#files::before {
    background: <? echo $brandColor; ?>;
}

#nummeringwrap .nummering {
    background: <? echo $brandColor; ?>;
}

#nummeringwrap .nummering:hover {
    background: <? echo $brandColorHover ?>;
}

#nummeringwrap .nummering#activenum {
    background: <? echo $brandColorHover ?>;
}

#structuur li .hoofditemtitel .structure-edit:hover {
    color: <? echo $brandColor; ?>;
}

#structuur li #structuursub .subitemtitel .structure-edit:hover {
    color: <? echo $brandColor; ?>;
}

#contentLeft .sort-wrap, #content-doc-Left .sort-wrap, #content-doc-woning-Left .sort-wrap {
    color: <? echo $brandColor; ?>;
}

#contentLeft .sort-wrap .delete-image, #content-doc-Left .sort-wrap .delete-image, #content-doc-woning-Left .sort-wrap .delete-image {
    color: <? echo $brandColor; ?>;
}

input[type=radio]:checked + label:before {
    color: <? echo $brandColor; ?>;
}

.checkbox input[type=checkbox]:checked + label:before {
    background: <? echo $brandColor; ?>;
}