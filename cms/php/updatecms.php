<?php
// checken of men wel is ingelogd
// ==============================
include ('ftp/config.php');

login_check_v2();     

include_once 'php/update_functies.php';

ini_set("display_errors", 1);


if($_POST['update-versie']) {
    // External ZIP file URL
    $downloadURL = 'https://download.sitework.nl/releases/'.$rowinstellingen['cmspakket'].'/'.$_POST['update-versie'].'/Sitework_'.$_POST['update-versie'].'.zip';
    // Local path to save the downloaded ZIP file
    $localFileName = $url.'/downloaded/Sitework_'.$_POST['update-versie'].'.zip';
    // Path to extract the ZIP file
    $extractPath = $url;

    downloadFile($downloadURL, $localFileName);

    // extractZip($localFileName, $extractPath);

    // uploadToFTP($ftpstream, $ftpuser, $ftppass, $extractPath, $pad_cms_core_update);

    // unlink($localFileName);
    // array_map('unlink', glob("$extractPath/*.*"));

    // Redirect or complete
    // header('Location: ?page=updates');
    // exit();
}

?>

<div class="box-container">
    <div class="box box-full md-box-full sm-box-full gecentreerd">
        <span class="icon fad fa-spinner-third"></span>
        <h3>Updaten...</h3>
        <div class="update-messages">
            <div id="download-progress-container">
                <h4>Downloaden...</h4>
                <div id="download-progress-bar" style="width: 100%; background-color: #f3f3f3; border: 1px solid #ccc;">
                    <div id="download-progress" style="width: 0%; height: 30px; background-color: #4caf50;"></div>
                </div>
            </div>
            <div id="extract-progress-container" style="display: none;">
                <h4>Uitpakken...</h4>
                <div id="extract-progress-bar" style="width: 100%; background-color: #f3f3f3; border: 1px solid #ccc;">
                    <div id="extract-progress" style="width: 0%; height: 30px; background-color: #4caf50;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateProgress(progress) {
        document.getElementById('download-progress').style.width = progress + '%';
        if (progress >= 100) {
            document.getElementById('download-progress-container').style.display = 'none';
            document.getElementById('extract-progress-container').style.display = 'block';
        }
    }

    function updateExtractProgress(progress) {
        document.getElementById('extract-progress').style.width = progress + '%';
    }
</script>

<?php
// $directory = '../updates';

// if($_GET['update'] <> "") {
//     $zip = new ZipArchive; 

//     $uploadFile = $_GET['update'];
//     // Zip File Name 
//     if ($zip->open($directory . "/" . $uploadFile . '.zip') === TRUE) { 
//         // Unzip Path 
//         $zip->extractTo('../'); 
//         $zip->close();
        
//         // ftp_delete($ftpstream, $directory."/".$uploadFile.".zip"); //thumbnail verwijderen

//         ftp_site($ftpstream,$pad_cms_update_open);
//         unlink($directory."/".$sitepakket."/".$uploadFile.".zip"); //thumbnail verwijderen
//         ftp_site($ftpstream,$pad_cms_update_dicht);

//         header('Location: ?page=updates');
//     } else { 
//         header('Location: ?page=updates');
//     }
// } else {
//     header('Location: ?page=updates');
// } 

// // Set up authentication credentials
// $username = 'your_username';
// $password = 'your_password';

// // Create a stream context with the authentication headers
// $context = stream_context_create([
//     'http' => [
//         'header' => "Authorization: Basic " . base64_encode("$username:$password"),
//     ],
// ]);

// // Fetch the content using file_get_contents with the created context
// $content = file_get_contents("https://www.example.com/10.0.0/10.0.0.zip", false, $context);

// // Check if content was fetched successfully
// if ($content === false) {
//     // Handle error
//     echo "Failed to fetch content.";
// } else {
//     // Content fetched successfully, you can process it further
//     // For example, you can save it to a file
//     file_put_contents("10.0.0.zip", $content);
// }



/* 
 * 
 * Aan de andere kant waar ik het vandaan haal
 * 
 */

// $credentials = [
//     'SiteworkCMS_1.0.0_standaard' => ['update_cms_1.0.0_standaard', '/updates/standaard/1.0.0/1.0.0.zip', '/updates/standaard/1.0.0/changelog.md'],
//     'SiteworkCMS_1.0.0_deluxe' => ['update_cms_1.0.0_deluxe', '/updates/deluxe/1.0.0/1.0.0.zip', '/updates/deluxe/1.0.0/changelog.md'],
//     'SiteworkCMS_1.0.0_premium' => ['update_cms_1.0.0_premium', '/updates/premium/1.0.0/1.0.0.zip', '/updates/premium/1.0.0/changelog.md'],
//     // Add more entries as needed
// ];

// // Check if the Authorization header is set
// if (!isset($_SERVER['PHP_AUTH_USER'])) {
//     // Send authentication headers if not set
//     header('WWW-Authenticate: Basic realm="My Restricted Area"');
//     header('HTTP/1.0 401 Unauthorized');
//     echo 'Authentication required.';
//     exit;
// } else {
//     // Check if the provided credentials are correct
//     $username = $_SERVER['PHP_AUTH_USER'];
//     $password = $_SERVER['PHP_AUTH_PW'];

//     if (!array_key_exists($username, $credentials) || $credentials[$username][0] !== $password) {
//         // Invalid credentials, send 401 Unauthorized
//         header('HTTP/1.0 401 Unauthorized');
//         echo 'Invalid username or password.';
//         exit;
//     }
// }

// // If authentication passes, serve the zip file corresponding to the username
// if (array_key_exists($username, $credentials)) {
//     $zipFilePath = $credentials[$username][1];
//     $changelogFilePath = $credentials[$username][2];

//     if (file_exists($zipFilePath)) {
//         // Set appropriate headers for the zip file
//         header('Content-Type: application/zip');
//         header('Content-Disposition: attachment; filename="' . basename($zipFilePath) . '"');
//         header('Content-Length: ' . filesize($zipFilePath));

//         // Output the zip file contents
//         readfile($zipFilePath);

//         // Output a line break to separate the zip file from the changelog
//         echo "\n\n";

//         // Check if changelog file exists
//         if (file_exists($changelogFilePath)) {
//             // Set appropriate headers for the changelog file
//             header('Content-Type: text/markdown');
//             header('Content-Disposition: attachment; filename="' . basename($changelogFilePath) . '"');
//             header('Content-Length: ' . filesize($changelogFilePath));

//             // Output the changelog file contents
//             readfile($changelogFilePath);
//         } else {
//             // If the changelog file doesn't exist, send a 404 Not Found response
//             header('HTTP/1.0 404 Not Found');
//             echo 'Changelog file not found.';
//         }
//     } else {
//         // If the zip file doesn't exist, send a 404 Not Found response
//         header('HTTP/1.0 404 Not Found');
//         echo 'Zip file not found.';
//     }
// } else {
//     // If the username is not found in the credentials array, send a 404 Not Found response
//     header('HTTP/1.0 404 Not Found');
//     echo 'User not found.';
// }
?>