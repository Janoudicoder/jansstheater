<?php

	class Realworks {

		private $token;
		private $request_url;
		private $data = [];

		function setToken($token)
		{
			$this->token = $token;
		}

		function getToken()
		{
			return $this->token;
		}

		function setUrl($request_url)
		{
            $this->request_url = $request_url;
		}

		function getData()
		{

			$headers = [
				'Authorization: ' . $this->token,
				'accept: application/json;charset=UTF-8',
			];

			$ch = curl_init();
		 	curl_setopt($ch, CURLOPT_URL, $this->request_url);
		    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		    $content = curl_exec($ch);
		    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		    $data = json_decode($content);

            if (isset($data->resultaten)) {
                return $data;
            }

		   	echo "Er gaat iets fout!";
	   		die();
		}
	}
?>
