<?php

namespace App\AuthNotify;

/**
 * Send a notification of user authorization changes to EDUC Person project
 */
class EducAuthNotify
{
    private $curlOptions = [
        CURLOPT_RETURNTRANSFER => true, // return result instead of echoing
        CURLOPT_SSL_VERIFYPEER => false, // stop cURL from verifying the peer's certificate
        CURLOPT_FOLLOWLOCATION => true, // follow redirects, Location: headers
        CURLOPT_MAXREDIRS      => 10, // but dont redirect more than 10 times
        CURLOPT_USERAGENT      => 'PhpRestClient:EducAuthNotify', // identify the user agent to the server
    ];

    private $name;
    private $url;
    private $token;

    public function __construct($name, $url, $token)
    {
        $this->name = $name;
        $this->token = $token;
        $this->url = $url;
    }

    public function notifyQuietly($uwnetid, $role, $actor_uwnetid, $person_data = [])
    {
        if (!$this->token || !$this->url) {
            return;
        }

        try {
            $this->notify($uwnetid, $role, $actor_uwnetid, $person_data);
        } catch (\Exception $e) {
            // non critical process, ok to fail silently
        }
    }

    public function notify($uwnetid, $role, $actor_uwnetid, $person_data = [])
    {
        $postData = array_merge($person_data, [
            'type' => 'authorization',
            'source' => $this->name,
            'uwnetid' => $uwnetid,
            'role' => $role,
            'actor_uwnetid' => $actor_uwnetid,
        ]);

        $curl = curl_init();
        foreach ($this->curlOptions as $curlopt => $value) {
            curl_setopt($curl, $curlopt, $value);
        }

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Uw-Educ-Token: ' . $this->token]);
        curl_setopt($curl, CURLOPT_URL, $this->url);

        curl_exec($curl);
    }

    public function debug($uwnetid, $role, $actor_uwnetid, $person_data = [])
    {
        $debugData = array_merge($person_data, [
            'url' => $this->url,
            'token' => $this->token,
            'type' => 'authorization',
            'source' => $this->name,
            'uwnetid' => $uwnetid,
            'role' => $role,
            'actor_uwnetid' => $actor_uwnetid,
        ]);

        if (function_exists('dd')) {
            dd($debugData);
        }

        echo '<pre>';
        print_r($debugData);
        echo '</pre>';
        exit;
    }
}
