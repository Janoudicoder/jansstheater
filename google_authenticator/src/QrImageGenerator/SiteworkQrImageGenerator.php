<?php

namespace Sitework\GoogleAuthenticator\QrImageGenerator;

use Sitework\GoogleAuthenticator\Secret;

class SiteworkQrImageGenerator implements QrImageGeneratorInterface
{
    protected $width;
    protected $height;

    public function __construct($width = 200, $height = 200)
    {
        if (!is_numeric($width) || !is_numeric($height)) {
            throw new \InvalidArgumentException("Both width and height are required to be numeric");
        }

        $this->width = $width;
        $this->height = $height;
    }

    public function generateUri(Secret $secret)
    {
        $encodedUri = urlencode($secret->getUri());
        return "https://qrcode.tec-it.com/API/QRCode?data=".$encodedUri;
    }
}
