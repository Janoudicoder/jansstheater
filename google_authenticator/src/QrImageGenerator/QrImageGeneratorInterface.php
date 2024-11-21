<?php

namespace Sitework\GoogleAuthenticator\QrImageGenerator;

use Sitework\GoogleAuthenticator\Secret;

interface QrImageGeneratorInterface
{
    public function generateUri(Secret $secret);
}
