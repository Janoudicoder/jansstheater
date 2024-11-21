<?php

// checken of men wel is ingelogd
// ==============================
login_check_v2();

$data = "";

$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $versie_directory);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$headers = [
    'Authorization: Basic ' . $rowinstellingen['api_key_verificatie'],
    'Domain: ' . $rowinstellingen['domeinnaam'],
    'Version: ' . $rowinstellingen['CMS_versie']
];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);

if(curl_errno($ch)){
    echo 'Curl error: ' . curl_error($ch);
    exit;
}

curl_close($ch);

$data = json_decode($response, true);

?>

<div class="box-container">
    <div class="box box-2-3 md-box-full sm-box-full">
        <h3 class="!full"><span class="icon far fa-wrench"></span>CMS update</h3>
        <?php if($data && !$data['error']): ?>
            <div class="row cms-update type">
                <div class="col">Versie</div>
                <div class="col">Pakket</div>
                <div class="col">Datum</div>
                <div class="col center">Update</div>
            </div>
            <?php
                foreach ($data as $key => $item) {
                    echo '<div class="row cms-update">';
                        echo '<div class="col">'.$item['version'].'</div>';
                        echo '<div class="col">'.$item['pakket'].'</div>';
                        echo '<div class="col">'.$item['date'].'</div>';
                        echo '  <div class="col center">
                                    <form action="'.$PHP_SELF.'?page=updatecms" method="POST">
                                        <input type="hidden" name="update-versie" value="'.$item['version'].'" />
                                        <button type="submit" class="clear-button downloadUpdate cursor-pointer fas fa-upload"></button>
                                    </form>
                                </div>';
                    echo '</div>';
                }
        else:
            echo "<p>Geen updates beschikbaar</p>";
        endif;
        ?>
    </div>
</div>

<script>
    // document.querySelectorAll('.downloadUpdate').forEach(button => {
    //     button.onclick = function() {
    //         // Get the data-version attribute from the button
    //         const version = this.getAttribute('data-version');
    //         const pakket = this.getAttribute('data-pakket');
    //         // Construct the URL with the version

    //         fetch('<?//=$versie_directory;?>')
    //             .then(response => {
    //                 if (!response.ok) {
    //                     throw new Error('Network response was not ok ' + response.statusText);
    //                 }
    //                 return response.json();
    //             })
    //             .then(data => {                    
    //                 if (data[version] && data[version].pakket === pakket) {
    //                     const downloadLink = data[version].download;
    //                     console.log(downloadLink);

    //                     // window.location.href = downloadLink;
    //                 } else {
    //                     console.log('No matching version and pakket found in the data.');
    //                 }
    //             })
    //             .catch(error => {
    //                 console.error('There was a problem with the fetch operation:', error);
    //             });
    //     };
    // });
</script>