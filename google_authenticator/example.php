<?php

include "../vendor/autoload.php";

if (isset($argv[1])) {
    $secretKey = $argv[1];
    echo "Secret passed in as argument!\n";
    echo "Your secret is ".$secretKey."\n";
} else {
    $secretFactory = new \Sitework\GoogleAuthenticator\SecretFactory();
    $secret = $secretFactory->create("Sitework CMS - Test QR", "Sitework");
    $secretKey = $secret->getSecretKey();

    $qrImageGenerator = new \Sitework\GoogleAuthenticator\QrImageGenerator\SiteworkQrImageGenerator();
    // $qrImageGenerator = new \Sitework\GoogleAuthenticator\QrImageGenerator\EndroidQrImageGenerator();
    // $qrImageGenerator = new \Sitework\GoogleAuthenticator\QrImageGenerator\GoogleQrImageGenerator();
    // $qrImageGenerator = new \Sitework\GoogleAuthenticator\QrImageGenerator\QrImageGeneratorInterface();

    echo "Your secret is: ".$secretKey."\n";
    file_put_contents(__DIR__."/example.html", "<img height='200px' width='200px' src='".$qrImageGenerator->generateUri($secret)."'>'");
    echo "Visit this URL: 'file://".__DIR__."/example.html' to view an image of your secret, and add it to your google authenticator app\n";
}

// https://qrcode.tec-it.com/API/QRCode?data=

$googleAuthenticator = new \Sitework\GoogleAuthenticator\GoogleAuthenticator();

// Example use of the a PSR-6 cache adapter, in this case, the cache/filesystem adapter
// This extension is only installed as require-dev
$filesystemAdapter = new \League\Flysystem\Adapter\Local(sys_get_temp_dir()."/");
$filesystem = new \League\Flysystem\Filesystem($filesystemAdapter);
$pool = new \Cache\Adapter\Filesystem\FilesystemCachePool($filesystem);
$googleAuthenticator->setCache($pool);
?>

<style>
    body {
        background: linear-gradient(90deg, rgba(40,158,199,1) 0%, rgba(46,55,70,1) 100%);
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .code-pop-up {
        color: #222;
        position: absolute;
        inset: 50% 0 0 50%;
        transform: translate(-50%, -50%);
        background-color: #fff;
        padding: 1.5rem;
        border-radius: 0.375rem;
        max-width: 400px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-evenly;
    }   
    .input {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    input[type="number"] {
        border-radius: 0.275rem;
        padding: 8px;
        border: 1px solid #dedede;
        outline: none !important;
    }
    button[type="submit"] {
        border-radius: 0.275rem;
        background-color: #fff;
        padding: 8px;
        border: 1px solid #dedede;
        cursor: pointer;
        transition: 0.5s all;
    }
    button[type="submit"]:hover {
        background-color: rgba(40,158,199,1);
        border: 1px solid rgba(40,158,199,1);
        color: #fff;
    }
</style>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" class="code-pop-up">
    <iframe src="./example.html" frameborder="0" style="overflow:hidden;" width="225px" height="235px"></iframe>
    <div class="input">
        <input type="number" name="code" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==6) return false;" placeholder="Authenticator code" id="code" value="<?php echo ($_POST['code']) ? $_POST['code'] : '' ?>">
        <input type="hidden" name="secretKey" value="<?php echo $secretKey?>">
        <button type="submit">Verstuur</button>
    </div>    
</form>

<?php
if (isset($_POST['code']) && $_POST['code'] <> "") {
    // $handle = fopen("php://stdin", "r");
    // $code = trim(fgets($handle));

    $secretKey = $_POST['secretKey'];
    $code = $_POST['code'];

    if ($googleAuthenticator->authenticate($secretKey, $code)) {
        echo "This code was valid!\n";
    } else {
        echo "This code was invalid!\n";
    }
}
?>