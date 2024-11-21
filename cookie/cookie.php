<?php
  $sqlCookie = $mysqli->query("SELECT * FROM sitework_cookies WHERE id = '1'") or die($mysqli->error.__LINE__);
  $rowcookie = $sqlCookie->fetch_assoc();

  $sqlCookieScripts = $mysqli->query("SELECT * FROM sitework_cookies_scripts WHERE cookie_id = '".$rowcookie['id']."'") or die($mysqli->error.__LINE__);

  if($_GET['taal']) { $cookieTaal = $_GET['taal']; } else { $cookieTaal = "nl"; }
?>

<style>
  .cc-nb-okagree, .cc-nb-reject, .cc-cp-foot-save {
    background-color: <?=$rowcookie['btn_background'];?> !important;
    color: <?=$rowcookie['btn_tekst'];?> !important;
  }
  .termsfeed-com---pc-dialog input[type=checkbox].cc-custom-checkbox:checked+label:before {
      background: <?=$rowcookie['btn_background'];?> !important;
  }
</style>

<!-- Cookie Consent by TermsFeed https://www.TermsFeed.com -->
<script type="text/javascript" src="https://www.termsfeed.com/public/cookie-consent/4.1.0/cookie-consent.js" charset="UTF-8"></script>
<script type="text/javascript" charset="UTF-8">
  document.addEventListener('DOMContentLoaded', function () {
    cookieconsent.run(
      {
        "notice_banner_type":"<?=$rowcookie['display_soort'];?>",
        "consent_type":"<?=$rowcookie['voorkeur_naleving'];?>",
        "website_name":"<?=$rowcookie['cookie_naam'];?>",
        "palette":"<?=$rowcookie['thema'];?>",
        "language":"<?=$cookieTaal;?>",
        "page_load_consent_levels":<?php echo ($rowcookie['voorkeur_naleving'] == 'express') ? '["strictly-necessary"]' : '["strictly-necessary", "functionality", "tracking", "targeting"]' ?>,
        "notice_banner_reject_button_hide":false,
        "preferences_center_close_button_hide":true,
        "page_refresh_confirmation_buttons":false,
        <?php 
          if($rowcookie['privacy_link'] != "" && $rowcookie['privacy_link'] != null) {
            echo '"website_privacy_policy_url":"'.$rowcookie['privacy_link'].'",';
          }
        ?>
      }
    );
  });
</script>

<?php while($rowCookieScripts = $sqlCookieScripts->fetch_assoc()): ?>
  <!-- <?=$rowCookieScripts['script_name'];?> -->
  <?php echo $rowCookieScripts['script_value'];?>
  <!-- end of <?=$rowCookieScripts['script_name'];?>-->
<?php endwhile; ?>

<noscript>Free cookie consent management tool by <a href="https://www.termsfeed.com/">TermsFeed</a></noscript>
<!-- End Cookie Consent by TermsFeed https://www.TermsFeed.com -->

<!-- Below is the link that users can use to open Preferences Center to change their preferences. Do not modify the ID parameter. Place it where appropriate, style it as needed. -->
<a href="#" class="cookie-btn" id="open_preferences_center" aria-label="Open het voorwaarden overzicht"><i class="fas text-white fa-cookie-bite"></i></a>