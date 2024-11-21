<? // checken of men wel is ingelogd
// =================================
login_check_v2(); ?>

<?php
$sqlCats = $mysqli-> prepare ("SELECT categorie FROM sitework_categorie") or die($mysqli->error.__LINE__);
$sqlCats -> execute();
$sqlCats -> store_result();
$sqlCats -> bind_result($cmsCats);

?>

<ul id="hoofdmenu">
    <span id="title">CMS | <? echo $sitenaam; ?></span>
    <li><a <?php if (!$_GET['page'] or $_GET['page'] == "dashboard") { echo "class=\"active\""; } ?> href="?page=dashboard"><i class="menu-icon fal fa-th-large"></i></span><span class="menu-item">Dashboard</span></a></li>
    <li>
        <a id="web" <?php if ($_GET['page'] == "webpaginas") { echo "class=\"active\""; } ?> href="?page=webpaginas"><i class="menu-icon fal fa-copy"></i><span class="menu-item">Webpagina`s</span></a><?php if($sqlCats->num_rows > 0):?> <span id="web-drop" class="menu-drop ti-angle-down <?php if($_GET['cat'] != ""){echo "active";} ?>"></span> <?php endif;?>
        <ul class="cat-dropdown <?php if($_GET['cat'] != ""){echo "menuopen";} ?>">
            <?php while($sqlCats -> fetch()) :?>
                <li>
                    <a <?php if ($_GET['page'] == "webpaginas" && $_GET['cat'] == $cmsCats) { echo "class=\"active\""; } ?> href="?page=webpaginas&cat=<?=$cmsCats;?>"><i class="menu-icon fal fa-tag"></i><span class="menu-item"><?=ucfirst($cmsCats);?></span></a>
                </li>
            <?php endwhile; ?>
        </ul>
    </li>
    <? /* if ($rowinstellingen['makelaar'] == 'ja') { ?>
        <li><a <?php if ($_GET['page'] == "woningen") { echo "class=\"active\""; } ?> href="?makelaar=ja&page=woningen"><i class="menu-icon fal fa-home"></i><span class="menu-item">Woningen</span></a></li>
    <? } ?>
    <? if ($rowinstellingen['makelaar'] == 'ja' && $rowinstellingen['bogaanbod'] == 'ja') { ?>    
        <li><a <?php if ($_GET['page'] == "bedrijfspanden") { echo "class=\"active\""; } ?> href="?makelaar=ja&page=bedrijfspanden"><i class="menu-icon fal fa-home"></i><span class="menu-item">Bedrijfspanden</span></a></li>
    <? } */ ?>
    <li><a <?php if ($_GET['page'] == "menustructuur") { echo "class=\"active\""; } ?> href="?page=menustructuur"><i class="menu-icon fal fa-th-list"></i><span class="menu-item">Menustructuur</span></a></li>
    <li><a <?php if ($_GET['page'] == "mediabibliotheek") { echo "class=\"active\""; } ?> href="?page=mediabibliotheek"><i class="menu-icon fal fa-photo-video"></i><span class="menu-item">Media</span></a></li>
    <? if($rowinstellingen['meertaligheid'] == 'ja' || $rowuser['id'] == '1') { ?>
        <li><a <?php if ($_GET['page'] == "categorie") { echo "class=\"active\""; } ?> href="?page=categorie"><i class="menu-icon fal fa-tags"></i><span class="menu-item">Categorie&euml;n</span></a></li>
        <li><a <?php if ($_GET['page'] == "kenmerken") { echo "class=\"active\""; } ?> href="?page=kenmerken"><i class="menu-icon fal fa-bookmark"></i><span class="menu-item">Kenmerken</span></a></li>
    <? } ?>
    <li><a <?php if ($_GET['page'] == "blocks") { echo "class=\"active\""; } ?> href="?page=blocks"><i class="menu-icon fal fa-boxes"></i><span class="menu-item">Blocks</span></a></li>
    <li><a <?php if ($_GET['page'] == "cookie") { echo "class=\"active\""; } ?> href="?page=cookie"><i class="menu-icon fal fa-cookie-bite"></i><span class="menu-item">Cookiemelding</span></a></li>
    <li><a <?php if ($_GET['page'] == "formulieren") { echo "class=\"active\""; } ?> href="?page=formulieren"><i class="menu-icon fal fa-envelope"></i><span class="menu-item">Formulieren</span></a></li>
    <li><a <?php if ($_GET['page'] == "website-instellingen") { echo "class=\"active\""; } ?> href="?page=website-instellingen"><i class="menu-icon fal fa-browser"></i><span class="menu-item">Website instellingen</span></a></li>
    <li><a <?php if ($_GET['page'] == "handleiding") { echo "class=\"active\""; } ?> href="?page=handleiding"><i class="menu-icon fal fa-book"></i><span class="menu-item">Handleiding</span></a></li>
    <li><a <?php if ($_GET['page'] == "instellingen") { echo "class=\"active\""; } ?> href="?page=instellingen"><i class="menu-icon fal fa-cog"></i><span class="menu-item">Instellingen</span></a></li>
    <li><a <?php if ($_GET['page'] == "gebruikers") { echo "class=\"active\""; } ?> href="?page=gebruikers"><i class="menu-icon fal fa-users ti-user"></i><span class="menu-item">Gebruikers</span></a></li>
    <li><a <?php if ($_GET['page'] == "inlogpogingen") { echo "class=\"active\""; } ?> href="?page=inlogpogingen"><i class="menu-icon fal fa-lock"></i><span class="menu-item">Inlogpogingen</span></a></li>
    <li><a <?php if ($_GET['page'] == "prullenbak") { echo "class=\"active\""; } ?> href="?page=prullenbak"><i class="menu-icon fal fa-trash"></i><span class="menu-item">Prullenbak</span></a></li>
</ul>