<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit159b47003aa3ebd2c9549811fa55c2a9
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Sitework\\GoogleAuthenticator\\' => 29,
            'Selective\\Base32\\' => 17,
        ),
        'P' => 
        array (
            'Psr\\SimpleCache\\' => 16,
            'Psr\\Log\\' => 8,
            'Psr\\Cache\\' => 10,
        ),
        'L' => 
        array (
            'League\\MimeTypeDetection\\' => 25,
            'League\\Flysystem\\' => 17,
        ),
        'E' => 
        array (
            'Endroid\\QrCode\\' => 15,
        ),
        'D' => 
        array (
            'DASPRiD\\Enum\\' => 13,
        ),
        'C' => 
        array (
            'Cache\\TagInterop\\' => 17,
            'Cache\\Adapter\\Filesystem\\' => 25,
            'Cache\\Adapter\\Common\\' => 21,
        ),
        'B' => 
        array (
            'BaconQrCode\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Sitework\\GoogleAuthenticator\\' => 
        array (
            0 => __DIR__ . '/../..' . '/google_authenticator/src',
        ),
        'Selective\\Base32\\' => 
        array (
            0 => __DIR__ . '/..' . '/selective/base32/src',
        ),
        'Psr\\SimpleCache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/simple-cache/src',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/src',
        ),
        'Psr\\Cache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/cache/src',
        ),
        'League\\MimeTypeDetection\\' => 
        array (
            0 => __DIR__ . '/..' . '/league/mime-type-detection/src',
        ),
        'League\\Flysystem\\' => 
        array (
            0 => __DIR__ . '/..' . '/league/flysystem/src',
        ),
        'Endroid\\QrCode\\' => 
        array (
            0 => __DIR__ . '/..' . '/endroid/qr-code/src',
        ),
        'DASPRiD\\Enum\\' => 
        array (
            0 => __DIR__ . '/..' . '/dasprid/enum/src',
        ),
        'Cache\\TagInterop\\' => 
        array (
            0 => __DIR__ . '/..' . '/cache/tag-interop',
        ),
        'Cache\\Adapter\\Filesystem\\' => 
        array (
            0 => __DIR__ . '/..' . '/cache/filesystem-adapter',
        ),
        'Cache\\Adapter\\Common\\' => 
        array (
            0 => __DIR__ . '/..' . '/cache/adapter-common',
        ),
        'BaconQrCode\\' => 
        array (
            0 => __DIR__ . '/..' . '/bacon/bacon-qr-code/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit159b47003aa3ebd2c9549811fa55c2a9::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit159b47003aa3ebd2c9549811fa55c2a9::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit159b47003aa3ebd2c9549811fa55c2a9::$classMap;

        }, null, ClassLoader::class);
    }
}