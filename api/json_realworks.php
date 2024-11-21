<?php
//ini_set('memory_limit', '256M');
error_reporting(1);
ini_set('display_errors', 1);
require '../cms/login/config.php';
require_once '../vendor/autoload.php';
require 'realworks.php';
class App
{
    public function __construct($settings)
    {
        $this->settings = $settings;
        $this->realworks = new Realworks();
        $this->realworks->setToken($settings['token']);
        $this->secure_path = $settings['secure_path'];
        $this->salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
        $this->start();
        $this->next_object = null;
        $this->teller = 0;
        $this->start_time = false;
    }

    public function start() 
    {
        if (!$this->start_time)
        {
            $this->start_time = microtime(true);
        }

        $url = 'https://api.realworks.nl/wonen/v3/objecten?actief=true&aantal=50';
        if ($this->next_object) 
        {
            $url = $this->next_object;
        }

        $this->realworks->setUrl($url);
        $data = $this->realworks->getData();

        if ($data)
        {   
            $prettyJson = json_encode($data, JSON_PRETTY_PRINT);

            array_map('unlink', array_filter((array) glob($this->secure_path . '*')));

            $fp = fopen($this->secure_path . 'rw_' . $this->salt . '.json', 'a+');

            if (is_writable($this->secure_path)) {
                fwrite($fp, $prettyJson);
                fclose($fp);
                echo 'Schrijven voltooid';
            } else {
                echo 'Fout!';
            }
        }
    }
}

$app = new App([
    'token' => $rw_key,
    'secure_path' => '../../rw_secure/',
]);
